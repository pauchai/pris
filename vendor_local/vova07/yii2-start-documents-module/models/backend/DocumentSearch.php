<?php
namespace vova07\documents\models\backend;
use vova07\base\components\DateJuiBehavior;
use yii\validators\DateValidator;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class DocumentSearch extends \vova07\documents\models\Document
{
    public $issuedFrom;
    public $issuedTo;
    public $expiredFrom;
    public $expiredTo;
    public $metaStatusId;

    public function rules()
    {
        return [
            [['person_id','type_id'],'integer'],
            [['issuedFrom','issuedTo'],'integer'],
            [['issuedToJui','issuedFromJui'],'date'],
            [['expiredFrom','expiredTo'],'integer'],
            [['expiredToJui','expiredFromJui'],'date'],
            [['metaStatusId', 'status_id'], 'integer'],
    //        [['issuedToJui'],'date','format' => 'dd-mm-yyyy']

        ];
    }

    public function behaviors()
    {
        return [
            'issuedFromJui'=>[
                'class' => DateJuiBehavior::class,
                'attribute' => 'issuedFrom',
                'juiAttribute' => 'issuedFromJui'
            ],
            'issuedToJui'=>[
                'class' => DateJuiBehavior::class,
                'attribute' => 'issuedTo',
                'juiAttribute' => 'issuedToJui',
              //  'dateFormat' => 'dd-mm-yyyy'
            ],
            'expiredFromJui'=>[
                'class' => DateJuiBehavior::class,
                'attribute' => 'expiredFrom',
                'juiAttribute' => 'expiredFromJui'
            ],
            'expiredToJui'=>[
                'class' => DateJuiBehavior::class,
                'attribute' => 'expiredTo',
                'juiAttribute' => 'expiredToJui',
                //  'dateFormat' => 'dd-mm-yyyy'
            ],
            'dateIssueJui'=>[
                'class' => DateJuiBehavior::className(),
                'attribute' => 'date_issue',
                'juiAttribute' => 'dateIssueJui'
            ],
            'dateExpirationJui'=>[
                'class' => DateJuiBehavior::className(),
                'attribute' => 'date_expiration',
                'juiAttribute' => 'dateExpirationJui'
            ]
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(),['person.second_name']);
    }

    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find(),

        ]);

        $dataProvider->query->joinWith(['person' => function($query){
            $query->from(['person'=>'person']);
        }]);



        $dataProvider->sort->defaultOrder = [

                    'person.second_name' => SORT_ASC,
                    'type_id' => SORT_ASC,

        ];
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $dataProvider->query->andFilterWhere([
            'person_id' => $this->person_id,
            'type_id' => $this->type_id,
            'status_id' => $this->status_id,

        ]);
        if ($this->metaStatusId == self::META_STATUS_ABOUT_EXPIRATION)
                $dataProvider->query->aboutExpiration();
        elseif ($this->metaStatusId == self::META_STATUS_EXPIRATED)
                $dataProvider->query->expired();


        $dataProvider->query
            ->andFilterWhere(['>=','date_issue',$this->issuedFrom])
            ->andFilterWhere(['<=','date_issue',$this->issuedTo]);
        $dataProvider->query
            ->andFilterWhere(['>=','date_expiration',$this->expiredFrom])
            ->andFilterWhere(['<=','date_expiration',$this->expiredTo]);
        return $dataProvider;

    }

}
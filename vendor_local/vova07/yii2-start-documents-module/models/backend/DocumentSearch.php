<?php
namespace vova07\documents\models\backend;
use vova07\base\components\DateJuiBehavior;
use vova07\documents\models\DocumentQuery;
use vova07\documents\Module;
use vova07\prisons\models\Company;
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
    public $companyId;

    public function rules()
    {
        return [
            [['person_id','type_id'],'integer'],
            [['issuedFrom','issuedTo'],'integer'],
            [['issuedToJui','issuedFromJui'],'date'],
            [['expiredFrom','expiredTo'],'integer'],
            [['expiredToJui','expiredFromJui'],'date'],
            [['metaStatusId', 'status_id'], 'integer'],
            [['companyId'], 'integer'],
           // ['companyId','default','value' => \Yii::$app->base->company->primaryKey]
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




        //if (!($this->load($params) && $this->validate())) {
           // return $dataProvider;
        //}
        $this->load($params);
        $this->validate();
        $dataProvider->query->andFilterWhere([
            'person_id' => $this->person_id,
            'type_id' => $this->type_id,
            'status_id' => $this->status_id,

        ]);
        $query = $dataProvider->query;
        /**
         * @var $query DocumentQuery
         */
        if ($this->metaStatusId == self::META_STATUS_ABOUT_EXPIRATION)
            $query->aboutExpiration();
        elseif ($this->metaStatusId == self::META_STATUS_EXPIRATED)
                $query->expired();
        elseif ($this->metaStatusId == self::META_STATUS_NOT_EXPIRED)
                     $query->active()->notExpired();


            $dataProvider->query
            ->andFilterWhere(['>=','date_issue',$this->issuedFrom])
            ->andFilterWhere(['<=','date_issue',$this->issuedTo]);
            $dataProvider->query
            ->andFilterWhere(['>=','date_expiration',$this->expiredFrom])
            ->andFilterWhere(['<=','date_expiration',$this->expiredTo]);
        if ($this->companyId && ($company = Company::findOne($this->companyId))){


            $dataProvider->query->andWhere(
              [
                  'assigned_to' => $company->getOfficers()->select('__person_id')
              ]
            );
        }
        return $dataProvider;

    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),[
            'metaStatusId' => Module::t('labels', "DOCUMENT_META_STATUS_LABEL"),

        ]);
    }

}
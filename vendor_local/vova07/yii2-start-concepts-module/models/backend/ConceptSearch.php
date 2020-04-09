<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 1/31/20
 * Time: 11:23 AM
 */
namespace vova07\concepts\models\backend;


use vova07\base\components\DateJuiBehavior;
use vova07\concepts\models\Concept;

class ConceptSearch extends Concept
{
    public $dateStartFrom;
    public $dateStartTo;
    public function behaviors()
    {
        return [
            'dateIssueJui'=>[
                'class' => DateJuiBehavior::class,
                'attribute' => 'dateStartFrom',
                'juiAttribute' => 'dateStartFromJui'
            ],
            'dateExpirationJui'=>[
                'class' => DateJuiBehavior::class,
                'attribute' => 'dateStartTo',
                'juiAttribute' => 'dateStartToJui',
                //  'dateFormat' => 'dd-mm-yyyy'
            ]
        ];
    }
    public function rules()
    {
        return [
            [['dateStartFromJui', 'dateStartToJui'],'string'],
            [['title'],'string'],
            [['status_id'] , 'integer'],
            [['status_id'], 'default', 'value' => self::STATUS_ACTIVE]

        ];
    }
    public function search($params)
    {

        $query = self::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);


        $dataProvider->sort = [
            'defaultOrder' => [
                'date_start' => SORT_ASC,
            ]
        ];
        $this->load($params) ;
        $this->validate();

            $dataProvider->query->andFilterWhere([

                'status_id' => $this->status_id,

            ]);
            $dataProvider->query->andFilterWhere(
                ['like', 'title', $this->title  ]
            );
            $dataProvider->query->andFilterWhere(
                ['>=', 'date_start', $this->dateStartFrom  ]
            );
            $dataProvider->query->andFilterWhere(
                ['<=', 'date_start', $this->dateStartTo  ]
            );



        return $dataProvider;

    }

}
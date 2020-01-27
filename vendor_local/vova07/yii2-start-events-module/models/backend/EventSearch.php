<?php
namespace vova07\events\models\backend;
use vova07\base\components\DateJuiBehavior;
use vova07\events\models\Event;


/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:40 PM
 */

class EventSearch extends Event
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
            [['category_id'] , 'integer'],
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
        if ($this->load($params) && $this->validate()){
            $dataProvider->query->andFilterWhere([
                'category_id' => $this->category_id,

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

        }

        return $dataProvider;

    }
}
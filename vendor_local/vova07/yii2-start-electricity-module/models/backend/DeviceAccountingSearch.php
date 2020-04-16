<?php
namespace vova07\electricity\models\backend;
use vova07\electricity\models\DeviceAccounting;
use vova07\jobs\helpers\Calendar;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class DeviceAccountingSearch extends \vova07\electricity\models\DeviceAccounting
{
    public function rules()
    {
        return [

            [['from_date'],'default', 'value' => Calendar::getRangeForDate(time())[0]->getTimeStamp()],
            [['to_date'],'default', 'value' => Calendar::getRangeForDate(time())[1]->getTimeStamp()],
            [['dateRange'],'string'],

            [['prisoner.sector_id', 'prisoner.cell_id'], 'safe']
            //DefaultValueValidator::


        ];
    }


    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => DeviceAccounting::find()->joinWith(
                [
                    'prisoner' => function ($query){
                        $query->from(['prisoner' => 'prisoner']);
                        $query->joinWith([
                           'person' => function($query){
                                return $query->from(['person' => 'person']);
                           }
                        ]);
                        return $query;

                }
                ])
        ]);
        $dataProvider->query->orderBy([
            'person.second_name' => SORT_ASC,
        ]);



        if ($this->load($params    ) && $this->validate()   ) {

            $dataProvider->query->andFilterWhere([
               'prisoner.sector_id' => $this->getAttribute('prisoner.sector_id'),
                'prisoner.cell_id' => $this->getAttribute('prisoner.cell_id'),
            ]);
            $dataProvider->query->andFilterWhere(['>=','from_date',$this->from_date ]);
            $dataProvider->query->andFilterWhere(['<=','to_date',$this->to_date ]);
        }
        return $dataProvider;

    }

    public function dateTime($date)
    {
        return (new \DateTime())->setTimestamp($date);
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'prisoner.sector_id',
            'prisoner.cell_id'
        ]);
    }



}
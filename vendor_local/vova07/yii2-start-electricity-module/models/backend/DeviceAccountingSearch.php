<?php
namespace vova07\electricity\models\backend;
use vova07\electricity\models\DeviceAccounting;
use vova07\jobs\helpers\Calendar;

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
            [['dateRange'],'string']
            //DefaultValueValidator::


        ];
    }


    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => DeviceAccounting::find()
        ]);
        $tf = $this->dateTime($this->from_date);
        $tt = $this->dateTime($this->to_date);
        if ($this->load($params    ) && $this->validate()   ) {
            $tf = $this->dateTime($this->from_date);
            $tt = $this->dateTime($this->to_date);
            //$t = $this->dateRange;
            $dataProvider->query->andFilterWhere(['>=','from_date',$this->from_date ]);
            $dataProvider->query->andFilterWhere(['<=','to_date',$this->to_date ]);
        }
        return $dataProvider;

    }

    public function dateTime($date)
    {
        return (new \DateTime())->setTimestamp($date);
    }



}
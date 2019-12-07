<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 22.10.2019
 * Time: 15:18
 */

namespace vova07\jobs\models\backend;


use vova07\prisons\models\Company;




trait JobSearchTrait
{
    // public $yearMonth;
    public $yearMonthDateFormat = 'Y-m-d';
    public function rules()
    {
        $rules = [
            [['prison_id', 'yearMonth','month_no','year'],'safe'],
            [['yearMonth'],'default','value'=> $this->getDateTime()->format('Y-m-01') ],
            [['year'],'default','value'=> $this->getDateTime()->format('Y') ],
            [['month_no'],'default','value'=> $this->getDateTime()->format('m') ],
            [['prison_id'],'default','value'=> (Company::findOne(['alias'=>'pu-1']))->primaryKey ],
        ];

        $dayFieldsArray = [];
        for ($i=1;$i<=31;$i++){
            $dayFieldsArray[] =$i.'d';
        }
        $rules[] = [$dayFieldsArray,'number'];
        return $rules;
    }



    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()
        ]);
        $this->load($params);
        $this->validate();


        $dataProvider->query->andWhere([
            'prison_id' => $this->prison_id,
            'month_no' => $this->month_no,
            'year' => $this->year,
        ]);
        return $dataProvider;
    }



    public function getYearMonth()
    {
        if ($this->year && $this->month_no)
        {
            return $this->getDateTime()->format('Y-m-01');
        } else {
            return  (new \DateTime())->format('Y-m-01');
        }
    }

    public function setYearMonth($value)
    {
        if ($value)
        {
            $dateTime = \DateTime::createFromFormat('Y-m-d',$value);
            $this->year = $dateTime->format('Y');
            $this->month_no = $dateTime->format('m');
        }
    }
}
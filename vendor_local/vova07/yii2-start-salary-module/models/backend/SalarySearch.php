<?php
namespace vova07\salary\models\backend;

use vova07\finances\models\backend\BalanceByPrisonerView;
use vova07\finances\models\Balance;
use vova07\salary\models\Salary;


/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:40 PM
 */

class SalarySearch extends Salary
{

    public $atFormat = 'Y-m-01';
    public function rules()
    {
        return [
            [['at'],'safe'],
            [['at'], 'default', 'value' => (new \DateTime())->format($this->atFormat)]
        ];
    }
    public function search($params)
    {


        $query = parent::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);


        if (!$this->load($params)){
            $this->at = (new \DateTime())->format($this->atFormat);
        };
        $this->validate();


        return $dataProvider;

    }

    public function getAt($format = true)
    {
        if ($this->year && $this->month_no) {
            if ($format)
                return (new \DateTime())->setDate($this->year, $this->month_no, 1)->format($this->atFormat);
            else
                return (new \DateTime())->setDate($this->year, $this->month_no, 1);
        } else {
            if ($format)
                return (new \DateTime())->format($this->atFormat);
            else
                return (new \DateTime());
        }
    }


    public function setAt($value)
    {
        if ($value)
        {
            $dateTime = \DateTime::createFromFormat('Y-m-d',$value);
            $this->year = $dateTime->format('Y');
            $this->month_no = $dateTime->format('m');
        }
    }


}
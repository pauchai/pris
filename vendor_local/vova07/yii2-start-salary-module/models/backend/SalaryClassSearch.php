<?php
namespace vova07\salary\models\backend;

use vova07\finances\models\backend\BalanceByPrisonerView;
use vova07\finances\models\Balance;
use vova07\salary\models\Salary;
use vova07\salary\models\SalaryClass;


/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:40 PM
 */

class SalaryClassSearch extends SalaryClass
{


    public function search($params)
    {


        $query = parent::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);


        $this->load($params);

        $this->validate();

        return $dataProvider;

    }




}
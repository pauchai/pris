<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\salary\models;


use yii\db\ActiveQuery;
use yii\db\Expression;

class SalaryQuery extends ActiveQuery
{

    public function totalAmount()
    {
        return $this->sum('total');
    }


}
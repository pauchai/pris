<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\finances\models;


use yii\db\ActiveQuery;

class BalanceQuery extends ActiveQuery
{


    public function debit()
    {
        return $this->andWhere(['type_id' => Balance::TYPE_DEBIT]);
    }
    public function credit()
    {
        return $this->andWhere(['type_id' => Balance::TYPE_CREDIT]);
    }
}
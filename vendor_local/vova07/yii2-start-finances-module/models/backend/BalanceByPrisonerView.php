<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 25.10.2019
 * Time: 10:59
 */

namespace vova07\finances\models\backend;


use vova07\finances\models\backend\BalanceByPrisonerViewQuery;
use vova07\users\models\Prisoner;
use yii\db\ActiveRecord;

class BalanceByPrisonerView extends ActiveRecord
{
    public static function tableName()
    {
        return 'vw_balance_by_prisoner';
    }

    public static function find()
    {
        return new BalanceByPrisonerViewQuery(get_called_class());

    }

    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class,['__person_id' => 'prisoner_id']);
    }
}
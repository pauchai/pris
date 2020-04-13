<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\finances\models\backend;


use yii\db\ActiveQuery;

class BalanceByPrisonerViewQuery extends ActiveQuery
{

    public function byPrisonerId($prisoner_id)
    {
        return $this->andWhere([
           'prisoner_id' => $prisoner_id
        ]);
    }
}
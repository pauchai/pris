<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\salary\models\backend;


use yii\db\ActiveQuery;

class BalanceByOfficerViewQuery extends ActiveQuery
{

    public function byOfficerId($officer_id)
    {
        return $this->andWhere([
           'officer_id' => $officer_id
        ]);
    }
}
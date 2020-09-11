<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\plans\models;


use yii\db\ActiveQuery;
use yii\db\Expression;

class PrisonerPlanViewQuery extends PrisonerPlanQuery
{
    public function notExists()
    {
        return $this->andWhere(new Expression('ISNULL('.PrisonerPlanView::tableName().'.status_id)'));
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\plans\models;


use yii\db\ActiveQuery;

class PrisonerPlanQuery extends ActiveQuery
{

    public function ownedBy()
    {
        return $this->joinWith('ownableitem')->andWhere('ownableitem.created_by='. \Yii::$app->user->id);
    }

}
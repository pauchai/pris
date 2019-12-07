<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\events\models;


use yii\db\ActiveQuery;

class EventQuery extends ActiveQuery
{

    public function assigned()
    {
        return $this->andWhere(['assigned_to' => \Yii::$app->user->id]);
    }

    public function active()
    {
        return $this->andWhere(['status_id' => Event::STATUS_ACTIVE]);
    }
    public function planing()
    {
        return $this->andWhere(['status_id' => Event::STATUS_PLANING]);
    }

}
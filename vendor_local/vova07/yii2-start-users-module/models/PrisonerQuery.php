<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\users\models;


use yii\db\ActiveQuery;

class PrisonerQuery extends ActiveQuery
{

    public function active()
    {
        return $this->andWhere(['status_id' => Prisoner::STATUS_ACTIVE]);
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\plans\models;


use yii\db\ActiveQuery;

class ProgramQuery extends ActiveQuery
{

    public function active()
    {
        return $this->andWhere(['status_id' => Program::STATUS_ACTIVE]);
    }

}
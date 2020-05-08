<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\jobs\models;


use yii\db\ActiveQuery;

class JobPaidQuery extends ActiveQuery
{

    public function active()
    {

                return $this->andWhere(['in', 'status_id', [JobPaid::STATUS_PROCESSED, JobPaid::STATUS_INIT, JobPaid::STATUS_READY_PROCESSING]]);
    }
}
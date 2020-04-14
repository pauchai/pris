<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\electricity\models;


use yii\db\ActiveQuery;

class DeviceAccountingQuery extends ActiveQuery
{

/*
    public function active()
    {
        return $this->andWhere(['status_id' => Event::STATUS_ACTIVE]);
    }
*/
    public function readyForProcessing()
    {
        return $this->andWhere(['device_accountings.status_id' => DeviceAccounting::STATUS_READY_FOR_PROCESSING]);
    }
}
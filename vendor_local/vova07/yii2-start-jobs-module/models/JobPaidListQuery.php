<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\jobs\models;


use yii\db\ActiveQuery;

class JobPaidListQuery extends ActiveQuery
{

    public function active()
    {
        return $this->andWhere(['status_id' => JobPaidList::STATUS_ID_ACTIVE]);
    }
}
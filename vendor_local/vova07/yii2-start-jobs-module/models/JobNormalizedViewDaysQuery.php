<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\jobs\models;


use yii\db\ActiveQuery;
use yii\db\Expression;

class JobNormalizedViewDaysQuery extends ActiveQuery
{

    public function hasHours()
    {
        return $this->andWhere(new Expression('isNull(hours)'));
    }
    public function paid(){
        return $this->andWhere(['category_id' => JobNormalizedViewDays::CATEGORY_PAID]);
    }
    public function notPaid(){
        return $this->andWhere(['category_id' => JobNormalizedViewDays::CATEGORY_NOT_PAID]);
    }
}
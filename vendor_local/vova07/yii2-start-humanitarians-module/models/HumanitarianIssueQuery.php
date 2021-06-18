<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\humanitarians\models;


use yii\db\ActiveQuery;
use vova07\humanitarians\models\HumanitarianIssue;

class HumanitarianIssueQuery extends ActiveQuery
{

    public function processed()
    {
        return $this->andWhere(['status_id' => HumanitarianIssue::STATUS_PROCESSED]);
    }

    public function processing()
    {
        return $this->andWhere(['status_id' => HumanitarianIssue::STATUS_PROCESSING]);
    }


}
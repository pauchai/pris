<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\users\models;


use vova07\countries\models\Country;
use yii\db\ActiveQuery;
use yii\db\Expression;

class PrisonerViewQuery extends PrisonerQuery
{

    public function active()
    {
        return $this->andWhere(['vw_prisoner.status_id' => Prisoner::STATUS_ACTIVE]);
    }

}
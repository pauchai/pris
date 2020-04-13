<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\concepts\models;


use yii\db\ActiveQuery;
use yii\db\Expression;

class ConceptQuery extends ActiveQuery
{

    public function active()
    {
        return $this->andWhere(['status_id' => Concept::STATUS_ACTIVE]);
    }
}
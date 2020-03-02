<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\prisons\models;


use vova07\users\models\Prisoner;
use yii\db\ActiveQuery;

class PrisonerSecurityQuery extends ActiveQuery
{


        public function prisonerActive()
        {
            $this-> joinWith(['prisoner' => function($query){ return $query->from('prisoner');}]);
            $this->andWhere(['prisoner.status_id' => Prisoner::STATUS_ACTIVE]);
            return $this;
        }
}
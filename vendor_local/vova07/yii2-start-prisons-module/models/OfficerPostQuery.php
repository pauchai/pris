<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\prisons\models;


use yii\db\ActiveQuery;

class OfficerPostQuery extends ActiveQuery
{


    public function sumRates( $db = null)
    {
        if ($this->emulateExecution) {
            return 0;
        }

        return $this->queryScalar("ifnull(0,sum(if(full_time,1,0.5)))", $db);
    }

}
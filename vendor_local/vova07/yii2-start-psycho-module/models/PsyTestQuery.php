<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\psycho\models;


use yii\db\ActiveQuery;

class PsyTestQuery extends ActiveQuery
{
    public function fromTo($from, $to)
    {
        $this->andFilterWhere(
            ['>=', 'at',$from]
        );
        $this->andFilterWhere(
            ['<=', 'at', $to]
        );
        return $this;
    }
}
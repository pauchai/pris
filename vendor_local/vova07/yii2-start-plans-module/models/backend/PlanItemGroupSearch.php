<?php
namespace vova07\plans\models\backend;
use vova07\plans\models\PlanItemGroup;
use vova07\plans\models\Program;
use vova07\plans\models\ProgramDict;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:40 PM
 */

class PlanItemGroupSearch extends PlanItemGroup
{

    public function search($params)
    {

        $query = self::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);




        return $dataProvider;

    }
}
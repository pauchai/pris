<?php
namespace vova07\users\models\backend;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class OfficerSearch extends \vova07\users\models\Officer
{

    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()->with('person')
        ]);
        return $dataProvider;

    }

}
<?php
namespace vova07\prisons\models\backend;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class PostDictSearch extends \vova07\prisons\models\PostDict
{

    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()
        ]);
        return $dataProvider;

    }

}
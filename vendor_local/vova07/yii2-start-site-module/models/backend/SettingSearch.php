<?php
namespace vova07\site\models\backend;

use vova07\site\models\Setting;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class SettingSearch extends Setting
{

    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()
        ]);
        if ($this->load($params) ){
            $dataProvider->query->andFilterWhere(
                [

//
                ]);
        }
        return $dataProvider;

    }

}
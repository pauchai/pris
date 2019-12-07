<?php
namespace vova07\electricity\models\backend;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class DeviceSearch extends \vova07\electricity\models\Device
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
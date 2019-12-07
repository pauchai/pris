<?php
namespace vova07\jobs\models\backend;




class JobPaidListSearch extends \vova07\jobs\models\JobPaidList
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
<?php
namespace vova07\jobs\models\backend;




class JobPaidListSearch extends \vova07\jobs\models\JobPaidList
{

    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()->joinWith(['person' => function($query){$query->from('person');}])
        ]);
        $dataProvider->query->orderBy('person.second_name, person.first_name');
        $dataProvider->sort = false;


        if ($this->load($params) ){
            $dataProvider->query->andFilterWhere(
                [

//
                ]);
        }
        return $dataProvider;

    }



}
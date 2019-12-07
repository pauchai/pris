<?php
namespace vova07\jobs\models\backend;



class JobPaidTypeSearch extends \vova07\jobs\models\JobPaidType
{

    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()
        ]);

        return $dataProvider;

    }

}
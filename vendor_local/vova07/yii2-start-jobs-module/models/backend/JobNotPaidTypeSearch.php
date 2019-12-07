<?php
namespace vova07\jobs\models\backend;



class JobNotPaidTypeSearch extends \vova07\jobs\models\JobNotPaidType
{

    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()
        ]);

        return $dataProvider;

    }

}
<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\socio\models\backend;




use vova07\socio\models\RelationMaritalView;

class RelationMaritalViewSearch   extends  RelationMaritalView
{
    public function search($params)
    {

        $query = self::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);


        $dataProvider->sort = [
            'defaultOrder' => [
                'fio' => SORT_ASC,
                '__person_id' => SORT_ASC
            ]
        ];




        return $dataProvider;

    }



}

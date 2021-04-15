<?php
namespace vova07\users\models\backend;



/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class PersonSearch extends \vova07\users\models\Person
{

    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()
        ]);
      //  $dataProvider->sort->defaultOrder = ['second_name' => SORT_ASC];

        return $dataProvider;

    }

}
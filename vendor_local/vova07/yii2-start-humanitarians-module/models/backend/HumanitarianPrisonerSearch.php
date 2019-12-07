<?php
namespace vova07\humanitarians\models\backend;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class HumanitarianPrisonerSearch extends \vova07\humanitarians\models\HumanitarianPrisoner
{

    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()
        ]);
        return $dataProvider;

    }

}
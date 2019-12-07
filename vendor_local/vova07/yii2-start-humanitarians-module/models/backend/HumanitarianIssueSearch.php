<?php
namespace vova07\humanitarians\models\backend;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class HumanitarianIssueSearch extends \vova07\humanitarians\models\HumanitarianIssue
{

    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()
        ]);

        return $dataProvider;

    }

}
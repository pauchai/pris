<?php
namespace vova07\finances\models\backend;

use vova07\finances\models\backend\BalanceByPrisonerView;


/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:40 PM
 */

class BalanceByPrisonerViewSearch extends BalanceByPrisonerView
{

    public function init()
    {

    }
    public function rules()
    {
        return [
            [['prisoner_id','debit','credit'],'safe']
        ];
    }
    public function search($params)
    {

        $query = self::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);




        return $dataProvider;

    }


}
<?php
namespace vova07\salary\models\backend;

use vova07\finances\models\backend\BalanceByPrisonerView;
use vova07\finances\models\Balance;


/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:40 PM
 */

class BalanceSearch extends Balance
{


    public function rules()
    {
        return [
            [['category_id','officer_id','amount','reason'],'safe'],
           // [['at'],'date']
        ];
    }
    public function search($params)
    {

        $query = parent::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);
        $query->orderBy('at DESC');


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(
            [
                'category_id' => $this->category_id,
                'officer_id' => $this->officer_id,
                'at' => $this->at,
            ]
        );
        $query->andFilterWhere(
            ['like', 'reason' , $this->reason]
        );

        return $dataProvider;

    }


}
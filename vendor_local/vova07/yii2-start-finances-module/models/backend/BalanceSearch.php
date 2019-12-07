<?php
namespace vova07\finances\models\backend;

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
            [['type_id','category_id','prisoner_id','amount','reason','atJui'],'safe'],
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
                'type_id' => $this->type_id,
                'category_id' => $this->category_id,
                'prisoner_id' => $this->prisoner_id,
                'reason' => $this->reason,
                'at' => $this->at,



            ]
        );

        return $dataProvider;

    }


}
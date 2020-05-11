<?php
namespace vova07\prisons\models\backend;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class PenaltySearch extends \vova07\prisons\models\Penalty
{
    public function rules()
    {
        return [
            [['prisoner_id'], 'integer'],

        ];

    }

    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()
        ]);

        if ($this->load($params) && $this->validate()){
            $dataProvider->query->andFilterWhere([
                'prisoner_id' => $this->prisoner_id
            ])  ;
        }

        return $dataProvider;

    }

}
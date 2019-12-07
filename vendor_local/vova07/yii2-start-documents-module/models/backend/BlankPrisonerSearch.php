<?php
namespace vova07\documents\models\backend;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class BlankPrisonerSearch extends \vova07\documents\models\BlankPrisoner
{
    public function rules()
    {
        return [
            ['prisoner_id','integer'],
            ['blank_id','integer']
        ];

    }

    public function search($params)
    {

        $query = self::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(
            [
                'blank_id' => $this->blank_id,
                'prisoner_id' => $this->prisoner_id,

            ]
        );




        return $dataProvider;

    }

}
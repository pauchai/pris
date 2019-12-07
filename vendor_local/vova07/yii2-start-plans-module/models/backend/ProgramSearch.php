<?php
namespace vova07\plans\models\backend;
use vova07\plans\models\Program;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:40 PM
 */

class ProgramSearch extends Program
{

    public function init()
    {

    }
    public function rules()
    {
        return [
            [['programdict_id','prison_id','prisoner_id'],'integer']
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
                'programdict_id' => $this->programdict_id,
                'prison_id' => $this->prison_id,
                'prisoner_id' => $this->prisoner_id,

            ]
        );




        return $dataProvider;

    }
}
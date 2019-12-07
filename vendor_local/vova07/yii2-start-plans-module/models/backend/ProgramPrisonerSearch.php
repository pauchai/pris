<?php

namespace vova07\plans\models\backend;

use vova07\plans\models\Program;
use vova07\plans\models\ProgramPrisoner;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:40 PM
 */
class ProgramPrisonerSearch extends ProgramPrisoner
{

    public function init()
    {

    }

    public function rules()
    {
        return [
            [['programdict_id', 'prison_id', 'prisoner_id', 'program_id', 'date_plan'], 'integer']

        ];
    }

    public function search($params, $formName = null)
    {

        $query = self::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);
        if (!($this->load($params, $formName) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(
            [
                'programdict_id' => $this->programdict_id,
                'prison_id' => $this->prison_id,
                'prisoner_id' => $this->prisoner_id,
                'date_plan' => $this->date_plan,
                'program_id' => $this->program_id,

            ]
        );
        //$query->andWhere('program_id is NULL');


        return $dataProvider;

    }
}
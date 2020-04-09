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
            [['programdict_id', 'prison_id', 'planned_by', 'prisoner_id', 'program_id', 'date_plan'], 'integer'],
            [['ownableitem.created_by'],'safe'],
            [['date_plan'], 'default', 'value' => date('Y')],

        ];
    }

    public function search($params, $formName = null)
    {

        $query = self::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);
        $dataProvider->sort = false;
        $dataProvider->query->joinWith(['person' => function($query){$query->from('person');}])->joinWith(['ownableitem' => function($query){$query->from('ownableitem');}])
            ->orderBy('prison_id, programdict_id,person.second_name, person.first_name');

        $this->prison_id = \Yii::$app->base->company->primaryKey;
        $query->andFilterWhere(
            [
                'prison_id' => $this->prison_id,
            ]);


        $this->load($params, $formName);
        $this->validate();
       // if (!($this->load($params, $formName) && $this->validate())) {
       //     return $dataProvider;
       // }
        $query->andFilterWhere(
            [
                'programdict_id' => $this->programdict_id,
                'planned_by' => $this->planned_by,
                'prisoner_id' => $this->prisoner_id,
                'date_plan' => $this->date_plan,
                'program_id' => $this->program_id,
                'ownableitem.created_by' => $this->getAttribute('ownableitem.created_by')

            ]
        );

        //$query->andWhere('program_id is NULL');


        return $dataProvider;

    }

    public function attributes()
    {
// делаем поле зависимости доступным для поиска
        return array_merge(parent::attributes(), ['ownableitem.created_by']);
    }
}
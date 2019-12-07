<?php
namespace vova07\plans\models\backend;
use vova07\plans\models\Program;
use vova07\plans\models\ProgramPlan;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:40 PM
 */

class ProgramPlanSearch extends ProgramPlan
{

    public function init()
    {

    }
    public function rules()
    {
        return [
            [['programdict_id','prisoner_id'],'integer'],
            [['year'],'integer']
        ];
    }
    public function search($params)
    {

        $query = self::find()->joinWith('programDict pd')->orderBy('pd.title,prisoner_id,year');
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'sort' => [
              'attributes' => [
                    'tt'
              ]
            ],
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(
            [
                'programdict_id' => $this->programdict_id,
                'prisoner_id' => $this->prisoner_id,
                'year' => $this->year,

            ]
        );




        return $dataProvider;

    }
}
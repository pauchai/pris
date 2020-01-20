<?php
namespace vova07\plans\models\backend;
use vova07\plans\models\Program;
use vova07\plans\models\ProgramVisit;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:40 PM
 */

class ProgramVisitSearch extends ProgramVisit
{

    public function init()
    {

    }
    public function rules()
    {
        return [
            [['program_prisoner_id','date_visit','status_id'],'integer']
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
                'program_prisoner_id' => $this->program_prisoner_id,
                'date_visit' => $this->date_visit,
                'status_id' => $this->status_id,

            ]
        );




        return $dataProvider;

    }
}
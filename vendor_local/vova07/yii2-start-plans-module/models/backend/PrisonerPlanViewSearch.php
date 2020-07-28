<?php
namespace vova07\plans\models\backend;
use yii\db\Expression;
use vova07\plans\models\PrisonerPlanView;
use vova07\plans\models\Program;
use vova07\users\models\Prisoner;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:40 PM
 */

class PrisonerPlanViewSearch extends PrisonerPlanView
{


    public function rules()
    {
        return [
            [['prisoner_status_id', 'status_id'],'integer']
        ];
    }
    public function search($params)
    {

        $query = self::find();
        $query->andWhere([  'prisoner_status_id' => Prisoner::STATUS_ACTIVE]);
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);
        if ($this->load($params) && $this->validate()){
            if ($this->status_id == self::STATUS_ABSENT)
                $query->andWhere(new Expression('ISNULL(status_id)'));
            else
            $query->andFilterWhere(
                [
                    'status_id' => $this->status_id,

                ]
            );
        };






        return $dataProvider;

    }
}
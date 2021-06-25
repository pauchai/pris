<?php
namespace vova07\plans\models\backend;
use vova07\plans\models\Program;
use yii\helpers\ArrayHelper;

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
            [['programdict_id','prison_id', 'status_id' ],'integer'],
            [['status_id'], 'default', 'value' => self::STATUS_ACTIVE]
        ];
    }
    public function search($params)
    {

        $query = self::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query->joinWith('programDict')
        ]);
        $this->load($params);
        $this->validate();

        if (\Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_PRISONER_PLAN_PROGRAMS_PLANING) == false)
            $query->where(['program_dicts.group_id' => ArrayHelper::getValue(\Yii::$app->user->identity->getPlanGroup(),'id')]);


        $query->andFilterWhere(
            [
                'programdict_id' => $this->programdict_id,
                'prison_id' => $this->prison_id,
                'status_id'  => $this->status_id,

            ]
        );




        return $dataProvider;

    }
}
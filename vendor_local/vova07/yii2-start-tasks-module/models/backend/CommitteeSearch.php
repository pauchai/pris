<?php
namespace vova07\tasks\models\backend;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class CommitteeSearch extends \vova07\tasks\models\Committee
{
    public function rules()
    {
        return [
            [['subject_id', 'prisoner_id', 'assigned_to','status_id'], 'safe'],

            [['mark_id'],'integer'],


        ];
    }

    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()
        ]);
        if ($this->load($params) ){
            $dataProvider->query->andFilterWhere(
                [
                    'subject_id' => $this->subject_id,
                    'prisoner_id' => $this->prisoner_id,
                    'assigned_to' => $this->assigned_to,
                    'status_id' => $this->status_id,
                ]);
        }
        return $dataProvider;

    }

}
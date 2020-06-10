<?php
namespace vova07\prisons\models\backend;
use vova07\prisons\models\OfficerPost;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class OfficerPostSearch extends \vova07\prisons\models\OfficerPost
{
    public function rules()
    {
        return [
          [['officer_id','division_id', 'postdict_id'],'integer']
        ];
    }

    public function search($params)
    {
        $query = OfficerPost::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);

        if ($this->load($params) && $this->validate()){
            $query->andFilterWhere([
                'officer_id' => $this->officer_id,
                'company_id' => $this->company_id,
                'division_id' => $this->division_id,
                'postdict_id' => $this->postdict_id,
            ]);
        }
        return $dataProvider;

    }

}
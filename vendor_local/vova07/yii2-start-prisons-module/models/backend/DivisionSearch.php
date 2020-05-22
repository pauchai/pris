<?php
namespace vova07\prisons\models\backend;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class DivisionSearch extends \vova07\prisons\models\Division
{
    public function rules()
    {
        return [
          ['company_id','integer']
        ];
    }

    public function search($params)
    {
        $query = self::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);

        if ($this->load($params) && $this->validate()){
            $query->andFilterWhere(['company_id' => $this->company_id]);
        }
        return $dataProvider;

    }

}
<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 1/31/20
 * Time: 11:23 AM
 */
namespace vova07\concepts\models\backend;


use vova07\base\components\DateJuiBehavior;
use vova07\concepts\models\Concept;

class ClassSearch extends Concept
{

    public function rules()
    {
        return [
            ['concept_id','required']
        ];
    }
    public function search($params)
    {

        $query = self::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);


        $dataProvider->sort = [
            'defaultOrder' => [
                'date_start' => SORT_ASC,
            ]
        ];
        if ($this->load($params) && $this->validate()){
            $dataProvider->query->andFilterWhere([

                'concept_id' => $this->concept_id,

            ]);

        }

        return $dataProvider;

    }

}
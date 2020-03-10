<?php
namespace vova07\jobs\models\backend;




class JobsGeneralListViewSearch extends \vova07\jobs\models\JobsGeneralListView
{
    public function rules()
    {
        $rules = [
            [['prisoner_id', 'year'],'safe'],

        ];

        return $rules;
    }

    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()->joinWith(['person' => function($query){$query->from('person');}])
        ]);
        $dataProvider->sort = false;
        $dataProvider->query->orderBy('year DESC, person.second_name, person.first_name');
        if ($this->load($params) &&   $this->validate()){
            $dataProvider->query->andFilterWhere([
                'prisoner_id' => $this->prisoner_id,
                'year' => $this->year,
            ]);
        }



        return $dataProvider;
    }



}
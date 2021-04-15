<?php
namespace vova07\users\models\backend;



/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class PersonViewSearch extends \vova07\users\models\PersonView
{

    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()
        ]);
        $dataProvider->sort->defaultOrder = ['second_name' => SORT_ASC];

        $this->load($params);
        $this->validate();
        $dataProvider->query->andFilterWhere(['like','fio', $this->fio ]);
        $dataProvider->query->andFilterWhere(['birth_year' => $this->birth_year ]);


        return $dataProvider;

    }




}
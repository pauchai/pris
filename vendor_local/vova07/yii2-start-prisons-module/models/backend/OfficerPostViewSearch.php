<?php
namespace vova07\prisons\models\backend;
use vova07\prisons\models\OfficerPost;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class OfficerPostViewSearch extends \vova07\prisons\models\OfficerPostView
{
    public function rules()
    {
        return [
            ['category_id', 'integer']
        ];
    }

    public function search($params)
    {
        $query = OfficerPostViewSearch::find()
            ->joinWith(['officer' => function($query) { $query->from(['officer']);}])
            ->orderBy([ 'category_id' => SORT_ASC, 'second_name' => SORT_ASC, 'first_name' => SORT_ASC, 'patronymic' => SORT_ASC] );
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);

        if ($this->load($params) && $this->validate()){
            $query->andFilterWhere([
                'category_id' => $this->category_id
            ]);
        }
        return $dataProvider;

    }

}
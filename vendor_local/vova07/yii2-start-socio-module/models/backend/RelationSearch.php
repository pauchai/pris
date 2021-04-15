<?php
namespace vova07\socio\models\backend;
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 4/14/21
 * Time: 11:05 AM
 */

class RelationSearch extends \vova07\socio\models\Relation
{

    public function rules()
    {
        return [
          [['person.second_name'], 'safe']
        ];
    }
    public function search($params)
    {

        $query = self::find()->joinWith(['person']);
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);


        $dataProvider->sort = [
            'defaultOrder' => [
                'person.second_name' => SORT_ASC,
            ]
        ];




        return $dataProvider;

    }

    public function attributes()
    {
// делаем поле зависимости доступным для поиска
        return array_merge(parent::attributes(), ['person.second_name']);
    }
}
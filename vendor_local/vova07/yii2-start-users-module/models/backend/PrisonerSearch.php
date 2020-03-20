<?php
namespace vova07\users\models\backend;



/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class PrisonerSearch extends \vova07\users\models\Prisoner
{
    public function rules()
    {
        return [
            [['prison_id','sector_id','cell_id'], 'safe'],

            //DateValidator::
        ];
    }

    /**
     * @param $params
     * @return \yii\data\ActiveDataProvider
     */
    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        };
        $dataProvider->query->andFilterWhere(
            [
                'sector_id' => $this->sector_id,
                'cell_id' => $this->cell_id,
            ]
        );
        return $dataProvider;

    }

}
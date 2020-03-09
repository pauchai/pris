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
use vova07\concepts\models\ConceptParticipant;

class ConceptParticipantSearch extends ConceptParticipant
{

    public function rules()
    {
        return [

            [['concept_id', 'prisoner_id'] , 'integer'],
        ];
    }
    public function search($params)
    {

        $query = self::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);



        if ($this->load($params) && $this->validate()){

            $dataProvider->query->andFilterWhere([

                'concept_id' => $this->concept_id,
                'prisoner_id' => $this->prisoner_id,

            ]);

        }

        return $dataProvider;

    }

}
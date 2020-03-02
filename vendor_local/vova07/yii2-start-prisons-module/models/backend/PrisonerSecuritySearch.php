<?php
namespace vova07\prisons\models\backend;
use Faker\Provider\DateTime;
use vova07\base\components\DateJuiBehavior;
use vova07\prisons\models\PrisonerSecurity;
use vova07\users\models\Prisoner;
use yii\db\Expression;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class PrisonerSecuritySearch extends \vova07\prisons\models\PrisonerSecurity
{
    public $availableTypes;

    public function rules()
    {
        return [
            [['prisoner_id'], 'integer'],

            [['dateEndJui'], 'safe']
        ];

    }





    /**
     * @param $params
     * @return \yii\data\ActiveDataProvider
     */

    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()->
                joinWith(['person' => function($query){ return $query->from('person');}])
                //->joinWith(['prisoner' => function($query){ return $query->from('prisoner');}])
        ]);
        $dataProvider->query->orderBy('person.second_name, person.first_name');
       // $dataProvider->query->andWhere(['prisoner.status_id' => Prisoner::STATUS_ACTIVE]);
        $dataProvider->query->prisonerActive();

        $dataProvider->sort = false;

        if (!$this->type_id && $this->availableTypes){
            $dataProvider->query->andWhere(

                    ['in','type_id', $this->availableTypes]


            );
        }


        if ($this->load($params) && $this->validate()){
            $dataProvider->query->andFilterWhere(
                [
                    'prisoner_id' => $this->prisoner_id,

                ]
            );
            if ($this->date_end)
            {
                $condition = "FROM_UNIXTIME(`date_end`,'%Y') =" . date('Y',$this->date_end);
                $conditions = $condition;
                $dataProvider->query->andWhere(

                    $condition

                );
                $condition = "FROM_UNIXTIME(`date_end`,'%m') =" . date('m',$this->date_end);
                $conditions .= $condition;
                $dataProvider->query->andWhere(

                    $condition

                );
                $condition = "FROM_UNIXTIME(`date_end`,'%d') =" . date('d',$this->date_end);
                $conditions .= $condition;
                $dataProvider->query->andWhere(

                    $condition
                );
                /*
                $dataProvider->query->andFilterWhere(
                    [
                        new Expression("FROM_UNIXTIME(`date_end`,'%M) =:month ",[':month' => date('m',$this->date_end)]),
                    ]
                );
                $dataProvider->query->andFilterWhere(
                    [
                        new Expression("FROM_UNIXTIME(date_end,'%D) =:day ",[':day' => date('d',$this->date_end)]),
                    ]
                );
                */

            }




        }
        return $dataProvider;

    }

}
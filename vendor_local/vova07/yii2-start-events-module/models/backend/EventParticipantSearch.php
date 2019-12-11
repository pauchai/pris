<?php
namespace vova07\events\models\backend;
use vova07\events\models\Event;
use vova07\events\models\EventParticipant;


/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:40 PM
 */

class EventParticipantSearch extends EventParticipant
{


    public function search($params)
    {

        $query = self::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);
        $query->andWhere([
            'prisoner_id' => $this->prisoner_id
        ]);



        return $dataProvider;

    }
}
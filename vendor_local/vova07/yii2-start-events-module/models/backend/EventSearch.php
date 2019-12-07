<?php
namespace vova07\events\models\backend;
use vova07\events\models\Event;


/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:40 PM
 */

class EventSearch extends Event
{

    public function init()
    {

    }
    public function rules()
    {
        return [
            [['title'],'string']
        ];
    }
    public function search($params)
    {

        $query = self::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);




        return $dataProvider;

    }
}
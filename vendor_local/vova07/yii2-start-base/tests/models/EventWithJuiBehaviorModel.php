<?php


namespace tests\models;
use vova07\base\components\DateJuiBehavior;


/**
 * DummyModel class
 *
 * @author albanjubert
 **/
class EventWithJuiBehaviorModel extends EventModel
{


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'dateStartJui' => [
                'class'     => DateJuiBehavior::class,
                'juiAttribute' => 'dateStartJui',
                'attribute' => 'date_start',
                'dateFormat' => 'd-m-Y',
            ],
        ];
    }


}
<?php


namespace tests\models;
use vova07\base\components\DateJuiBehavior;
use vova07\base\components\DateRangeBehavior;


/**
 * DummyModel class
 *
 * @author albanjubert
 **/
class EventWithDateRangeBehaviorModel extends EventModel
{


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'dateStartJui' => [
                'class'     => DateRangeBehavior::class,
                'dateStartAttribute' => 'date_start',
                'dateEndAttribute' => 'date_end',
                'dateStartFormat' => 'Y-m-d',
                'dateEndFormat' => 'Y-m-d',
                'displayFormat' => 'd-m-Y',


            ],
        ];
    }


}
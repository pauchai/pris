<?php


namespace tests\models;
use vova07\base\components\DateConvertJuiBehavior;
use vova07\base\components\DateJuiBehavior;


/**
 * DummyModel class
 *
 * @author albanjubert
 **/
class EventWithJuiConvertBehaviorModel extends EventModel
{


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'dateStartJui' => [
                'class'     => DateConvertJuiBehavior::class,
                'juiAttribute' => 'dateStartJui',
                'attribute' => 'date_start',
                'dateFormat' => 'd-m-Y',
                'dateConvertFormat' => 'Y-M-d',
            ],
        ];
    }


}
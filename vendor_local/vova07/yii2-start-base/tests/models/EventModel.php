<?php


namespace tests\models;
use vova07\base\components\DateJuiBehavior;


/**
 * DummyModel class
 *
 * @author albanjubert
 **/
class EventModel extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_event';
    }




}
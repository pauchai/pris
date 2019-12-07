<?php

namespace tests;





use tests\models\EventWithDateRangeBehaviorModel;
use tests\models\EventWithJuiBehaviorModel;
use vova07\base\components\DateRangeBehavior;
use vova07\electricity\models\Device;
use vova07\electricity\models\DeviceAccounting;
use vova07\events\models\Event;
use Yii;
use yii\db\Migration;


class DateRangeBehaviorTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp()
    {
        parent::setUp();
        $this->setupDbData();

    }

    protected function tearDown()
    {
        $db = Yii::$app->getDb();
        $db->createCommand()->dropTable(EventWithDateRangeBehaviorModel::tableName())->execute();
        parent::tearDown();
    }

    protected function setupDbData()
    {
        /** @var \yii\db\Connection $db */
        $db = Yii::$app->getDb();
        $migration = new Migration();

        // Dummy
        $db->createCommand()->createTable(EventWithDateRangeBehaviorModel::tableName(), [
            'id'        => $migration->primaryKey(),
            'title' => $migration->string(),
            'date_start' => $migration->date(),
            'date_end' => $migration->date()
        ])->execute();

        /**
         * Insert some data
         */

        $db->createCommand()->batchInsert(EventWithDateRangeBehaviorModel::tableName(), [
            'id', 'title','date_start','date_end'],
            [
            [
                1,
                'event1',
                Yii::$app->formatter->asTimestamp((new \DateTime())->setDate('2019','1','1')),
                Yii::$app->formatter->asTimestamp((new \DateTime())->setDate('2019','1','10'))
            ],
        ])->execute();


    }


    public function testA()
    {

        $event = new EventWithDateRangeBehaviorModel;
        $event->title = 'device1';
        $this->assertEquals($event->date_range,'01-11-2019 - 30-11-2019');
        //$this->assertTrue($event->save());


        $event = new EventWithDateRangeBehaviorModel;
        $event->title = 'device1';
        $event->date_range = '1-10-2019 - 30-10-2019';
        $this->assertTrue($event->save());
        $this->assertEquals($event->date_start,'2019-10-01');

        $event = new EventWithDateRangeBehaviorModel;
        $event->title = 'device1';
        $event->date_range = '2-10-2019 - 30-10-2019';
        $this->assertTrue($event->save());
        $this->assertNotEquals($event->date_start,'2019-10-01');






//        $this->assertTrue($event->save());
 //       unset($event);
  //      $event = EventWithJuiBehaviorModel::findOne('1');
   //     $this->assertEquals($event->dateStartJui,'10-10-2019');

    }
}

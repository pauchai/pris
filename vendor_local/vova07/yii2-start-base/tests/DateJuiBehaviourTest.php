<?php

namespace tests;




use tests\models\EventWithJuiBehaviorModel;
use vova07\base\components\DateJuiBehavior;
use Yii;
use yii\db\Migration;


class DateJuiBehaviourTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp()
    {
        parent::setUp();
        $this->setupDbData();
        Yii::$container->set(EventWithJuiBehaviorModel::class,[
            'behaviors' => [
                'dateStartJui' => [
                    'class'     => DateJuiBehavior::class,
                    'juiAttribute' => 'dateStartJui',
                    'attribute' => 'date_start',
                ],
            ]
        ]);
    }

    protected function tearDown()
    {
        $db = Yii::$app->getDb();
        $db->createCommand()->dropTable('test_event')->execute();
        parent::tearDown();
    }

    protected function setupDbData()
    {
        /** @var \yii\db\Connection $db */
        $db = Yii::$app->getDb();
        $migration = new Migration();

        // Dummy
        $db->createCommand()->createTable('test_event', [
            'id'        => $migration->primaryKey(),
            'title' => $migration->string(),
            'date_start' => $migration->integer()
        ])->execute();

        /**
         * Insert some data
         */

        $db->createCommand()->batchInsert('test_event', ['id', 'title','date_start'], [
            [1, 'event1', Yii::$app->formatter->asTimestamp((new \DateTime())->setDate('2019','10','10'))],
        ])->execute();


    }


    public function testA()
    {
        $event = EventWithJuiBehaviorModel::findOne('1');
        $event->title = 'ttt';
        $event->dateStartJui = '10-10-2019';
        $this->assertTrue($event->save());
        unset($event);
        $event = EventWithJuiBehaviorModel::findOne('1');
        $this->assertEquals($event->dateStartJui,'10-10-2019');


    }
}

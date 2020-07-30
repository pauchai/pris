<?php

use yii\db\Migration;
use vova07\psycho\models\PsyTest;

/**
 * Class m200730_114918_dbPsyTestAddStatus
 */
class m200730_114918_dbPsyTestAddStatus extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn(PsyTest::tableName(),  'status_id', PsyTest::getMetadata()['fields']['status_id']);
        $this->update(PsyTest::tableName(), ['status_id' => PsyTest::STATUS_ID_SUCCESS] );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(PsyTest::tableName(), 'status_id');
        return true;
    }
}

<?php

use yii\db\Migration;
use vova07\electricity\models\DeviceAccounting;
use vova07\finances\models\Balance;

/**
 * Class m200423_071354_deviceAccountingBalance
 */
class m200423_071354_deviceAccountingBalance extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $migration = new Migration();
        $this->alterColumn(DeviceAccounting::tableName(),
            'prisoner_id',
            $migration->integer()
        );
        $this->dropForeignKey('fk_device_accounting3163613496',DeviceAccounting::tableName());
        $this->dropColumn(DeviceAccounting::tableName(),'balance_id');


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $migration = new Migration();
        $this->alterColumn(DeviceAccounting::tableName(),
            'prisoner_id',
            $migration->integer()->notNull()
        );
        $this->addColumn(DeviceAccounting::tableName(),'balance_id',
            $migration->integer()
        );
        $this->addForeignKey('fk_device_accounting3163613496',
            DeviceAccounting::tableName(),
            ['balance_id'],
            Balance::tableName(),
            Balance::primaryKey()

            );


        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200423_071354_deviceAccountingBalance cannot be reverted.\n";

        return false;
    }
    */
}

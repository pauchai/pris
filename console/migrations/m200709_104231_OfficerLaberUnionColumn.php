<?php

use yii\db\Migration;
use \vova07\users\models\Officer;

/**
 * Class m200709_104231_OfficerLaberUnionColumn
 */
class m200709_104231_OfficerLaberUnionColumn extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn(Officer::tableName(),  'member_labor_union',$this->boolean()->defaultValue(false));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Officer::tableName(), 'member_labor_union');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200709_104231_OfficerLaberUnionColumn cannot be reverted.\n";

        return false;
    }
    */
}

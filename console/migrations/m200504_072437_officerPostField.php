<?php

use yii\db\Migration;
use vova07\users\models\Officer;

/**
 * Class m200504_072437_officerPostField
 */
class m200504_072437_officerPostField extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn(Officer::tableName(),'post', $this->string());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Officer::tableName(),'post');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200504_072437_officerPostField cannot be reverted.\n";

        return false;
    }
    */
}

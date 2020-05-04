<?php

use yii\db\Migration;
use vova07\users\models\Officer;

/**
 * Class m200504_070858_officerRankIdField
 */
class m200504_070858_officerRankIdField extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn(Officer::tableName(),'rank_id', $this->tinyInteger());
        $this->createIndex('idx_' . Officer::tableName() .'rank_id', Officer::tableName(), 'rank_id' );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Officer::tableName(),'rank_id');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200504_070858_officerRankIdField cannot be reverted.\n";

        return false;
    }
    */
}

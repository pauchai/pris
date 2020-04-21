<?php

use yii\db\Migration;
use vova07\prisons\models\Cell;
use yii\db\Schema;

/**
 * Class m200421_052114_cellSquare
 */
class m200421_052114_cellSquare extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $migration = new Migration();
        $this->addColumn(Cell::tableName(),'square', $migration->double());
        $this->createIndex('idx_cell_square',Cell::tableName(),'square');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropColumn(Cell::tableName(), 'square');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200421_052114_cellSquare cannot be reverted.\n";

        return false;
    }
    */
}

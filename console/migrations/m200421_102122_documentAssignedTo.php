<?php

use yii\db\Migration;
use vova07\documents\models\Document;

/**
 * Class m200421_102122_documentAssignedTo
 */
class m200421_102122_documentAssignedTo extends Migration
{
    const FK_DOCUMENT_NAME_ASSIGNED = 'fx_document_assigned_to';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $migration = new Migration();
        $this->addColumn(Document::tableName(),'assigned_to', $migration->integer());
        $this->createIndex('idx_document_assigned_to',Document::tableName(),'assigned_to');
        $this->addColumn(Document::tableName(),'assigned_at', $migration->bigInteger());
        $this->createIndex('idx_document_assigned_at',Document::tableName(),'assigned_at');

        $this->addForeignKey(self::FK_DOCUMENT_NAME_ASSIGNED, Document::tableName(), 'assigned_to', \vova07\users\models\Officer::tableName(), '__person_id');


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(self::FK_DOCUMENT_NAME_ASSIGNED,Document::tableName());
        $this->dropColumn(Document::tableName(),'assigned_to');
        $this->dropColumn(Document::tableName(),'assigned_at');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200421_102122_documentAssignedTo cannot be reverted.\n";

        return false;
    }
    */
}

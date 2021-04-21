<?php

use yii\db\Migration;
use vova07\socio\models\Relation;
use vova07\documents\models\Document;

/**
 * Class m210419_143350_dbRelationAddDocumentColumn
 */
class m210419_143350_dbRelationAddDocumentColumn extends Migration
{
    const FK_RELATION_DOCUMENT = 'fk_relation_document';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Relation::tableName(),  'document_id',Relation::getMetadata()['fields']['document_id']);
        $this->addForeignKey(self::FK_RELATION_DOCUMENT, Relation::tableName(), 'document_id', Document::tableName(), Document::primaryKey()[0]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(self::FK_RELATION_DOCUMENT, Relation::tableName());
        $this->dropColumn(Relation::tableName(),  'document_id');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210419_143350_dbRelationAddDocumentColumn cannot be reverted.\n";

        return false;
    }
    */
}

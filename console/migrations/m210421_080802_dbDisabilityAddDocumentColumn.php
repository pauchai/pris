<?php

use yii\db\Migration;
use vova07\socio\models\Disability;
use vova07\documents\models\Document;

/**
 * Class m210421_080802_dbDisabilityAddDocumentColumn
 */
class m210421_080802_dbDisabilityAddDocumentColumn extends Migration
{
    const FK_RELATION_DOCUMENT = 'fk_disability_document';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Disability::tableName(),  'document_id',Disability::getMetadata()['fields']['document_id']);
        $this->addForeignKey(self::FK_RELATION_DOCUMENT, Disability::tableName(), 'document_id', Document::tableName(), Document::primaryKey()[0]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(self::FK_RELATION_DOCUMENT, Disability::tableName());
        $this->dropColumn(Disability::tableName(),  'document_id');

        return true;
    }
}

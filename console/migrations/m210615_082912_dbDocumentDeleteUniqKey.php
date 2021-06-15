<?php

use yii\db\Migration;

/**
 * Class m210615_082912_dbDocumentDeleteUniqKey
 */
class m210615_082912_dbDocumentDeleteUniqKey extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropIndex("idx_document_type_id_country_id_person_id", \vova07\documents\models\Document::tableName());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210615_082912_dbDocumentDeleteUniqKey cannot be reverted.\n";

        return false;
    }
    */
}

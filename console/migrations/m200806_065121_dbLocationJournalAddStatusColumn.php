<?php

use yii\db\Migration;
use vova07\users\models\PrisonerLocationJournal;

/**
 * Class m200806_065121_dbLocationJournalAddStatusColumn
 */
class m200806_065121_dbLocationJournalAddStatusColumn extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $this->addColumn(PrisonerLocationJournal::tableName(),  'status_id',PrisonerLocationJournal::getMetadata()['fields']['status_id']);


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(PrisonerLocationJournal::tableName(), 'status_id');

        return true;
    }
}

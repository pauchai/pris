<?php

use yii\db\Migration;
use vova07\users\models\PrisonerLocationJournalWithNextView;
use vova07\users\models\PrisonerLocationJournal;

/**
 * Class m200915_073741_dbCreateViewLocationJournalWithNextLocation
 */
class m200915_073741_dbCreateViewLocationJournalWithNextLocation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createOfficerView();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return $this->db->createCommand()->dropView(PrisonerLocationJournalWithNextView::tableName())->execute();
    }


    public function createOfficerView()
    {
        $time = $this->beginCommand("create view " . $this->db->quoteTableName(PrisonerLocationJournalWithNextView::tableName()));

        $this->db->createCommand()->createView(PrisonerLocationJournalWithNextView::tableName(),
            (PrisonerLocationJournal::find())->select(
                [

                    'location_journal.*',
                    'next_id' => new \yii\db\Expression(<<<SQL
(SELECT id FROM location_journal l2
  WHERE (l2.id > location_journal.id) AND l2.prisoner_id = location_journal.prisoner_id
    ORDER BY id DESC LIMIT 1)
SQL
                    )


                ]
            )
        )->execute();
        $this->endCommand($time);
    }
}

<?php

use yii\db\Migration;
use vova07\users\models\PrisonerLocationJournalWithNextView;
use vova07\users\models\PrisonerLocationJournal;

/**
 * Class m210113_130551_dbModifyViewLocationJournal
 */
class m210113_130551_dbModifyViewLocationJournal extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createView();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return $this->db->createCommand()->dropView(PrisonerLocationJournalWithNextView::tableName())->execute();
    }


    public function createView()
    {
        $this->db->createCommand()->dropView(PrisonerLocationJournalWithNextView::tableName())->execute();
        $time = $this->beginCommand("create view " . $this->db->quoteTableName(PrisonerLocationJournalWithNextView::tableName()));

        $this->db->createCommand()->createView(PrisonerLocationJournalWithNextView::tableName(),
            (PrisonerLocationJournal::find())->select(
                [

                    'location_journal.*',
                    'next_id' => new \yii\db\Expression(<<<SQL
(SELECT id FROM location_journal l2
  WHERE (l2.at > location_journal.at) AND l2.prisoner_id = location_journal.prisoner_id
    ORDER BY at ASC LIMIT 1)
SQL
                    )


                ]
            )
        )->execute();
        $this->endCommand($time);
    }
}

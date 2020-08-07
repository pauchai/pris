<?php

use yii\db\Migration;
use vova07\users\models\PrisonerLocationJournal;
use vova07\users\models\PrisonerLocationJournalView;

/**
 * Class m200806_082305_dbcreateViewLocationJournalWithPreviewLocation
 */
class m200806_082305_dbcreateViewLocationJournalWithPreviewLocation extends Migration
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
        return $this->db->createCommand()->dropView(PrisonerLocationJournalView::tableName())->execute();
    }


    public function createOfficerView()
    {
        $time = $this->beginCommand("create view " . $this->db->quoteTableName(PrisonerLocationJournalView::tableName()));

        $this->db->createCommand()->createView(PrisonerLocationJournalView::tableName(),
            (PrisonerLocationJournal::find())->select(
                [

                    'location_journal.*',
                    'prev_id' => new \yii\db\Expression(<<<SQL
(SELECT id FROM location_journal l2
  WHERE (l2.id < location_journal.id) AND l2.prisoner_id = location_journal.prisoner_id
    ORDER BY id DESC LIMIT 1)
SQL
)


                ]
            )
        )->execute();
        $this->endCommand($time);
    }
}

<?php

use yii\db\Migration;
use vova07\users\models\PrisonerLocationJournalUnionView;
use vova07\users\models\PrisonerLocationJournal;
use vova07\users\models\Prisoner;
use yii\db\Expression;

/**
 * Class m210112_070630_dbCreateViewLocationJournalUnit
 */
class m210112_070630_dbCreateViewLocationJournalUnit extends Migration
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
        return $this->db->createCommand()->dropView(PrisonerLocationJournalUnionView::tableName())->execute();
    }


    public function createOfficerView()
    {
        $time = $this->beginCommand("create view " . $this->db->quoteTableName(PrisonerLocationJournalUnionView::tableName()));

        $locationJournalTable = \vova07\users\models\PrisonerLocationJournal::tableName();


        $this->db->createCommand()->createView(PrisonerLocationJournalUnionView::tableName(),
            (Prisoner::find())->select(
                [

                      'id' => 'j.id',
                    'prisoner_id' => new Expression("CASE WHEN j.prisoner_id is null  THEN prisoner.__person_id ELSE j.prisoner_id END"),
                    'prison_id' => new Expression("CASE WHEN j.prison_id is null  THEN prisoner.prison_id ELSE j.prison_id END"),
                    'sector_id' => new Expression("CASE WHEN j.sector_id is null  THEN prisoner.sector_id ELSE j.sector_id END"),
                    'cell_id' => new Expression("CASE WHEN j.cell_id is null  THEN prisoner.cell_id ELSE j.cell_id END"),
                    'at' => 'j.at',
                    'status_id' => new Expression("CASE WHEN j.status_id is null  THEN prisoner.status_id ELSE j.status_id END"),

                ]
            )->notDeleted()->leftJoin(
                ['j' => PrisonerLocationJournal::find()->orderBy('at DESC')->limit(1) ],
                ['__person_id' => 'j.prisoner_id']

            )
                ->union(PrisonerLocationJournal::find()->orderBy('at'))
        )->execute();
        $this->endCommand($time);
    }
}

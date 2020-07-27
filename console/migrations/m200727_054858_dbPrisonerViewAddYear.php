<?php

use yii\db\Migration;
use vova07\users\models\PrisonerView;
use vova07\users\models\Prisoner;
use  vova07\psycho\models\PsyCharacteristic;

/**
 * Class m200727_054858_dbPrisonerViewAddYear
 */
class m200727_054858_dbPrisonerViewAddYear extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->db->createCommand()->dropView(PrisonerView::tableName())->execute();
        $this->createView();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }


    public function createView()
    {

        $time = $this->beginCommand("create view " . $this->db->quoteTableName(PrisonerView::tableName()));
        $this->db->createCommand()->createView(PrisonerView::tableName(),
            (new \yii\db\Query())->select(
                [
                    'prisoner.__person_id',
                    'prison_id',
                    'sector_id',
                    'cell_id',
                    'origin_id',
                    'profession',
                    'article',
                    'term_start',
                    'term_finish',
                    'term_udo',
                    'status_id',
                    'birth_year',
                    'prisoner.__ownableitem_id',
                    'fio' => new \yii\db\Expression("concat(person.second_name,' ', person.first_name,' ', person.patronymic)"),


                ]
            )->leftJoin('person','person.__ident_id = prisoner.__person_id')
            ->from(['prisoner'=>Prisoner::tableName()])

        )->execute();
        $this->endCommand($time);
    }
}

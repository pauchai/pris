<?php

use yii\db\Migration;
use vova07\users\models\PrisonerView;
use \vova07\users\models\Prisoner;

/**
 * Class m191209_130304_create_prisoner_view
 */
class m191209_130304_create_prisoner_view extends Migration
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
        return $this->db->createCommand()->dropView(PrisonerView::tableName())->execute();
    }


    public function createView()
    {
        $time = $this->beginCommand("create view " . $this->db->quoteTableName(PrisonerView::tableName()));
        $this->db->createCommand()->createView(PrisonerView::tableName(),
            (new \yii\db\Query())->select(
                [
                    '__person_id',
                    'prison_id',
                    'sector_id',
                    'origin_id',
                    'profession',
                    'article',
                    'term_start',
                    'term_finish',
                    'term_udo',
                    'status_id',
                    'prisoner.__ownableitem_id',
                    'fio' => new \yii\db\Expression("concat(person.second_name,' ', person.first_name,' ', person.patronymic)")

                ]
            )->leftJoin('person','person.__ident_id = prisoner.__person_id')->from(['prisoner'=>Prisoner::tableName()])
        )->execute();
        $this->endCommand($time);
    }
}

<?php

use yii\db\Migration;
use vova07\users\models\PrisonerView;
use vova07\users\models\Prisoner;

/**
 * Class m210407_162126_dbPrisonerViewModify
 */
class m210407_162126_dbPrisonerViewModify extends Migration
{
    /*
     * create view vw_prisoner as
  select
    `prisons`.`prisoner`.`__person_id`        AS `__person_id`,
    `prisons`.`prisoner`.`prison_id`          AS `prison_id`,
    `prisons`.`prisoner`.`sector_id`          AS `sector_id`,
    `prisons`.`prisoner`.`cell_id`            AS `cell_id`,
    `prisons`.`prisoner`.`origin_id`          AS `origin_id`,
    `prisons`.`prisoner`.`profession`         AS `profession`,
    `prisons`.`prisoner`.`article`            AS `article`,
    `prisons`.`prisoner`.`term_start`         AS `term_start`,
    `prisons`.`prisoner`.`term_finish`        AS `term_finish`,
    `prisons`.`prisoner`.`term_finish_origin` AS `term_finish_origin`,
    `prisons`.`prisoner`.`term_udo`           AS `term_udo`,
    `prisons`.`prisoner`.`status_id`          AS `status_id`,
    `prisons`.`person`.`birth_year`           AS `birth_year`,
    `prisons`.`prisoner`.`__ownableitem_id`   AS `__ownableitem_id`,
    concat(`prisons`.`person`.`second_name`, ' ', `prisons`.`person`.`first_name`, ' ',
           `prisons`.`person`.`patronymic`)   AS `fio`,
    `prisons`.`prisoner`.`criminal_records`   AS `criminal_records`
  from (`prisons`.`prisoner`
    left join `prisons`.`person` on ((`prisons`.`person`.`__ident_id` = `prisons`.`prisoner`.`__person_id`)));


     */




    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $time = $this->beginCommand("create view " . $this->db->quoteTableName(PrisonerView::tableName()));

        $this->db->createCommand()->dropView(PrisonerView::tableName())->execute();
        $this->db->createCommand()->createView(PrisonerView::tableName(),
            (new \yii\db\Query())->select(
                [
                    '__person_id',
                    'prison_id',
                    'sector_id',
                    'cell_id',
                    'origin_id',
                    'profession',
                    'article',
                    'term_start',
                    'term_finish',
                     'term_finish_origin',
                    'term_udo',
                    'status_id',
                    'birth_year',
                    'prisoner.__ownableitem_id',
                    'fio' => new \yii\db\Expression("concat(person.second_name,' ', person.first_name,' ', person.patronymic)")

                ]
            )->leftJoin('person','person.__ownableitem_id = prisoner.__person_id')->from(['prisoner'=>Prisoner::tableName()])
        )->execute();
        $this->endCommand($time);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {



        return true;
    }



}

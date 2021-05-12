<?php

use yii\db\Migration;
use vova07\users\models\Person;

/**
 * Class m210407_115130_personDecoupleIdentReassignRelations
 */
class m210407_115130_personDecoupleIdentReassignRelations extends Migration
{

    public $foreignKeys = [


        'fk_salaryofficer_id_officer___person_id' => [
            'table' => 'salary', 'column' => 'officer_id', 'ref_table' => 'officer', 'ref_column' => '__person_id'
        ],
        'fk_program_prisoner1347739984' => [
            'table' => 'program_prisoners', 'column' => 'planned_by', 'ref_table' => 'officer', 'ref_column' => '__person_id'
        ],
        'fk_officer_postsofficer_id_officer___person_id' => [
            'table' => 'officer_posts', 'column' => 'officer_id', 'ref_table' => 'officer', 'ref_column' => '__person_id'
        ],
        'fk_event1720187373' => [
            'table' => 'events', 'column' => 'assigned_to', 'ref_table' => 'officer', 'ref_column' => '__person_id'
        ],
        'fk_concept1720187373' => [
            'table' => 'concepts', 'column' => 'assigned_to', 'ref_table' => 'officer', 'ref_column' => '__person_id'
        ],
        'fk_salary_withholdofficer_id_officer___person_id' => [
            'table' => 'salary_withhold', 'column' => 'officer_id', 'ref_table' => 'officer', 'ref_column' => '__person_id'
        ],
        'fk_program_assigned' => [
            'table' => 'programs', 'column' => 'assigned_to', 'ref_table' => 'officer', 'ref_column' => '__person_id'
        ],
        'fk_prisoner_plan_assigned_at' => [
            'table' => 'prisoner_plans', 'column' => 'assigned_to', 'ref_table' => 'officer', 'ref_column' => '__person_id'
        ],
        'fk_officer_balancesofficer_id_officer___person_id' => [
            'table' => 'officer_balances', 'column' => 'officer_id', 'ref_table' => 'officer', 'ref_column' => '__person_id'
        ],
        'fx_document_assigned_to' => [
            'table' => 'document', 'column' => 'assigned_to', 'ref_table' => 'officer', 'ref_column' => '__person_id'
        ],
        //'fk_biblio_journalperson_id_person___ident_id' => [
        //    'table' => 'biblio_journal', 'column' => 'person_id', 'ref_table' => 'person', 'ref_column' => '__ident_id'
       // ],
        'fk_prisoner1424250747' => [
            'table' => 'prisoner', 'column' => '__person_id', 'ref_table' => 'person', 'ref_column' => '__ident_id'
        ],
        'fk_officer1424250747' => [
            'table' => 'officer', 'column' => '__person_id', 'ref_table' => 'person', 'ref_column' => '__ident_id'
        ],
        'fk_document2525966856' => [
            'table' => 'document', 'column' => 'person_id', 'ref_table' => 'person', 'ref_column' => '__ident_id'
        ],
        'fk_setting2559708119' => [
            'table' => 'settings', 'column' => 'director', 'ref_table' => 'person', 'ref_column' => '__ident_id'
        ],
        'fk_psy_characteristic1424250747' => [
            'table' => 'psy_characteristics', 'column' => '__person_id', 'ref_table' => 'person', 'ref_column' => '__ident_id'
        ],

        'fk_balance2041663612' => ['table'=>'balances','column'=>'prisoner_id','ref_table'=>'prisoner','ref_column'=>'__person_id',],
        'fk_blank_prisoner2041663612' => ['table'=>'blank_prisoner','column'=>'prisoner_id','ref_table'=>'prisoner','ref_column'=>'__person_id',],
        'fk_committee2041663612' => ['table'=>'committee','column'=>'prisoner_id','ref_table'=>'prisoner','ref_column'=>'__person_id',],
        'fk_concept_participant2041663612' => ['table'=>'concept_participants','column'=>'prisoner_id','ref_table'=>'prisoner','ref_column'=>'__person_id',],
        'fk_device_accounting2041663612' => ['table'=>'device_accountings','column'=>'prisoner_id','ref_table'=>'prisoner','ref_column'=>'__person_id',],
        'fk_device2041663612' => ['table'=>'devices','column'=>'prisoner_id','ref_table'=>'prisoner','ref_column'=>'__person_id',],
        'fk_event_participant2041663612' => ['table'=>'event_participants','column'=>'prisoner_id','ref_table'=>'prisoner','ref_column'=>'__person_id',],
        'fk_humanitarian_prisoner2041663612' => ['table'=>'humanitarians','column'=>'prisoner_id','ref_table'=>'prisoner','ref_column'=>'__person_id',],
        'fk_job_not_paid2041663612' => ['table'=>'jobs_not_paid','column'=>'prisoner_id','ref_table'=>'prisoner','ref_column'=>'__person_id',],
        'fk_job_paid2041663612' => ['table'=>'jobs_paid','column'=>'prisoner_id','ref_table'=>'prisoner','ref_column'=>'__person_id',],
        'fk_job_paid_list1630104415' => ['table'=>'jobs_paid_list','column'=>'assigned_to','ref_table'=>'prisoner','ref_column'=>'__person_id',],
        'fk_prisoner_location_journal2041663612' => ['table'=>'location_journal','column'=>'prisoner_id','ref_table'=>'prisoner','ref_column'=>'__person_id',],
        'fk_penalty2041663612' => ['table'=>'penalties','column'=>'prisoner_id','ref_table'=>'prisoner','ref_column'=>'__person_id',],
        'fk_prisoner_plan1987402847' => ['table'=>'prisoner_plans','column'=>'__prisoner_id','ref_table'=>'prisoner','ref_column'=>'__person_id',],
        'fk_prisoner_security2041663612' => ['table'=>'prisoner_security','column'=>'prisoner_id','ref_table'=>'prisoner','ref_column'=>'__person_id',],
        'fk_program_plan2041663612' => ['table'=>'program_plans','column'=>'prisoner_id','ref_table'=>'prisoner','ref_column'=>'__person_id',],
        'fk_program_prisoner2041663612' => ['table'=>'program_prisoners','column'=>'prisoner_id','ref_table'=>'prisoner','ref_column'=>'__person_id',],
        'fk_psy_test2041663612' => ['table'=>'psy_tests','column'=>'prisoner_id','ref_table'=>'prisoner','ref_column'=>'__person_id',],


    ];
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->db->createCommand()->checkIntegrity(false)->execute();

        foreach ($this->foreignKeys as $fKeyName => $fKeyInfo) {

           // if ($fKeyInfo['ref_table'] == 'person'){
                $this->db->createCommand()->dropForeignKey($fKeyName,$fKeyInfo['table'] )->execute();
                foreach(Person::find()->all() as $person){
                //    $person = Person::findOne(['__ident_id' => $fKeyInfo['column'] ]);
                    $this->db->createCommand()->update($fKeyInfo['table'],[$fKeyInfo['column'] => $person->primaryKey],[$fKeyInfo['column'] => $person->__ident_id])->execute();
                }

                $this->db->createCommand()->addForeignKey($fKeyName, $fKeyInfo['table'], $fKeyInfo['column'],Person::tableName(), Person::primaryKey() )->execute();
            //}
        }
        $this->db->createCommand()->checkIntegrity()->execute();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->db->createCommand()->checkIntegrity(false)->execute();

        foreach ($this->foreignKeys as $fKeyName => $fKeyInfo) {

           //    $this->db->createCommand()->dropForeignKey($fKeyName,$fKeyInfo['table'] )->execute();
                $this->db->createCommand()->addForeignKey($fKeyName, $fKeyInfo['table'], $fKeyInfo['column'],$fKeyInfo['ref_table'], $fKeyInfo['ref_column'] )->execute();

        }
        $this->db->createCommand()->checkIntegrity()->execute();

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210407_115130_personDecoupleIdentReassignRelations cannot be reverted.\n";

        return false;
    }
    */
}

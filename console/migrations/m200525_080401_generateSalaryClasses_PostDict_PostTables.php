<?php

use yii\db\Migration;
use vova07\users\models\Officer;
use vova07\prisons\models\Post;
use vova07\salary\models\SalaryClass;
use vova07\prisons\models\PostDict;
use vova07\base\ModelGenerator\ModelTableGenerator;
use vova07\base\components\CsvFile;
/**
 * Class m200525_080431_officerPostId
 */
class m200525_080401_generateSalaryClasses_PostDict_PostTables extends \vova07\base\components\MigrationWithModelGenerator
{

    const FK_OFFICER_COMPANY_DIVISION_POST_ID = 'fk_officer_company_division_post_id';
    const FK_OFFICER_POST_ID = 'fk_officer_post_id';
    public $models = [
        SalaryClass::class,
    ];
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->generateModelTables();


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropModelTables();

        return true;
    }




}

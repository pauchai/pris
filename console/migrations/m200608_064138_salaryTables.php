<?php

use \vova07\base\components\MigrationWithModelGenerator;
use vova07\salary\models\Salary;
use vova07\salary\models\SalaryWithHold;
use \vova07\salary\models\Balance;

/**
 * Class m200606_064138_salaryTables
 */
class m200608_064138_salaryTables extends MigrationWithModelGenerator
{
    public $models = [
        Balance::class,
        Salary::class,
        SalaryWithHold::class,
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

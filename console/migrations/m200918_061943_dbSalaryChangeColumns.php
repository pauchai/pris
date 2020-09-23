<?php

use yii\db\Migration;
use vova07\salary\models\Balance;
use vova07\salary\models\SalaryIssue;
use vova07\salary\models\SalaryWithHold;
use vova07\salary\models\Salary;
use vova07\base\components\MigrationWithModelGenerator;

/**
 * Class m200918_061943_dbSalaryChangeColumns
 */
class m200918_061943_dbSalaryChangeColumns extends MigrationWithModelGenerator
{
    public $models = [
        Balance::class,
        SalaryIssue::class,
        Salary::class,
        SalaryWithHold::class,
    ];
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropModelTables();

        $this->generateModelTables();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      //  $this->dropModelTables();

        return true;
    }
}

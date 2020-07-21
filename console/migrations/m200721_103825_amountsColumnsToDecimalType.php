<?php

use yii\db\Migration;
use vova07\salary\models\Salary;
use vova07\salary\models\SalaryWithHold;
use vova07\salary\models\Balance as OfficerBalance;
use vova07\finances\models\Balance;

/**
 * Class m200721_103825_amountsColumnsToDecimalType
 */
class m200721_103825_amountsColumnsToDecimalType extends Migration
{
    const SALARY_COLUMNS = [
        'base_rate',
        'amount_rate',
        'amount_rank_rate' ,
        'amount_conditions',
        'amount_advance',
        'amount_optional',
        'amount_diff_sallary',
        'amount_additional',
        'amount_maleficence',
        'amount_vacation' ,
        'amount_sick_list',
        'amount_bonus',
    ];
    const SALARY_WITHHOLD_COLUMNS = [
        'amount_income_tax',
        'amount_execution_list',
        'amount_labor_union',
        'amount_sick_list',
        'amount_card',
    ];
    const BALANCE_COLUMNS = [
        'amount',

    ];
    const OFFICER_BALANCE_COLUMNS = [
        'amount',

    ];
    public function safeUp()
    {
        foreach (Salary::getMetadata()['fields'] as $fieldName => $schema){
            if (in_array($fieldName,self::SALARY_COLUMNS)){
                $this->alterColumn(Salary::tableName(), $fieldName, $schema);
            }
        };
        foreach (SalaryWithHold::getMetadata()['fields'] as $fieldName => $schema){
            if (in_array($fieldName,self::SALARY_WITHHOLD_COLUMNS)){
                $this->alterColumn(SalaryWithHold::tableName(), $fieldName, $schema);
            }
        };
        foreach (Balance::getMetadata()['fields'] as $fieldName => $schema){
            if (in_array($fieldName,self::BALANCE_COLUMNS)){
                $this->alterColumn(Balance::tableName(), $fieldName, $schema);
            }
        };
        foreach (OfficerBalance::getMetadata()['fields'] as $fieldName => $schema){
            if (in_array($fieldName,self::OFFICER_BALANCE_COLUMNS)){
                $this->alterColumn(OfficerBalance::tableName(), $fieldName, $schema);
            }
        };

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        return true;
    }
}

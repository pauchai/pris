<?php

use yii\db\Migration;
use vova07\salary\models\SalaryWithHold;

/**
 * Class m201001_121925_dbAddSalaryWithholdTotalColumn
 */
class m201001_121925_dbAddSalaryWithholdTotalColumn extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn(SalaryWithHold::tableName(),  'total',SalaryWithHold::getMetadata()['fields']['total']);


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(SalaryWithHold::tableName(),  'total');
        return true;
    }

}

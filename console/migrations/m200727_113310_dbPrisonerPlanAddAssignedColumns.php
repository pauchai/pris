<?php

use yii\db\Migration;
use vova07\plans\models\PrisonerPlan;
use vova07\users\models\Officer;

/**
 * Class m200727_113310_dbPrisonerPlanAddDateCreated
 */
class m200727_113310_dbPrisonerPlanAddAssignedColumns extends Migration
{
    const FK_ASSIGNED_TO = "fk_prisoner_plan_assigned_at";
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn(PrisonerPlan::tableName(),  'assigned_at',PrisonerPlan::getMetadata()['fields']['assigned_at']);
        $this->addColumn(PrisonerPlan::tableName(),  'assigned_to',PrisonerPlan::getMetadata()['fields']['assigned_to']);
        $this->addForeignKey(self::FK_ASSIGNED_TO,PrisonerPlan::tableName(),'assigned_to', Officer::tableName(),Officer::primaryKey());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(self::FK_ASSIGNED_TO, PrisonerPlan::tableName());
        $this->dropColumn(PrisonerPlan::tableName(), 'assigned_at');
        $this->dropColumn(PrisonerPlan::tableName(), 'assigned_to');
        return true;
    }
}

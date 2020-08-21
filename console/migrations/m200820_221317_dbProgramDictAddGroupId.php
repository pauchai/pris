<?php

use yii\db\Migration;
use vova07\plans\models\ProgramDict;
use vova07\plans\models\PlanItemGroup;
/**
 * Class m200820_221317_dbRequirementsProgramDictAddGroupId
 */
class m200820_221317_dbProgramDictAddGroupId extends Migration
{
    const FK_GROUP_ID = 'fk_programdict_group_id';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $this->addColumn(ProgramDict::tableName(),  'group_id',$this->tinyInteger());
        $this->addForeignKey(self::FK_GROUP_ID,ProgramDict::tableName(),'group_id', PlanItemGroup::tableName(),PlanItemGroup::primaryKey());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(self::FK_GROUP_ID, ProgramDict::tableName());
        $this->dropColumn(ProgramDict::tableName(), 'group_id');



        return true;
    }

}

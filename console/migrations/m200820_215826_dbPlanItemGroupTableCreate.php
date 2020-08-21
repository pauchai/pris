<?php

use yii\db\Migration;
use vova07\plans\models\PlanItemGroup;
use vova07\base\components\MigrationWithModelGenerator;
/**
 * Class m200820_215826_dbPlanItemGroupTableCreate
 */
class m200820_215826_dbPlanItemGroupTableCreate extends MigrationWithModelGenerator
{
    public $models = [
        PlanItemGroup::class,
    ];
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->generateModelTables();
        self::generateGroups();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropModelTables();


        return true;
    }


    private static function generateGroups()
    {
        $items = [
            PlanItemGroup::GROUP_SOCIOLOGIST_ID => \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_SOCIOLOGIST,
            PlanItemGroup::GROUP_PSYCHOLOGIST_ID =>  \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_PSYCHOLOGIST,
            PlanItemGroup::GROUP_EDUCATOR_ID =>  \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR,
        ];
        foreach ($items as $itemId=>$itemRole){
            $group = new PlanItemGroup([
                'id' => $itemId,
                'title' => $itemRole,
                'role' => $itemRole,
            ]);
            $group->save();
        }
    }

}

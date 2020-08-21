<?php

use yii\db\Migration;
use vova07\plans\models\Requirement;
use vova07\plans\models\PlanItemGroup;

/**
 * Class m200821_000708_dbRequirementsAddGroupId
 */
class m200821_000708_dbRequirementsAddGroupId extends Migration
{
    const FK_GROUP_ID = 'fk_requirements_group_id';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $this->addColumn(Requirement::tableName(),  'group_id',$this->tinyInteger());
        $this->addForeignKey(self::FK_GROUP_ID,Requirement::tableName(),'group_id', PlanItemGroup::tableName(),PlanItemGroup::primaryKey());
        $this->resolveGroupIdColumnValueByCreateByUserRole();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(self::FK_GROUP_ID, Requirement::tableName());
        $this->dropColumn(Requirement::tableName(), 'group_id');



        return true;
    }


    private function resolveGroupIdColumnValueByCreateByUserRole()
    {
        $requirements = Requirement::find()->all();
        foreach ($requirements as $requirement)
        {
            /**
             * @var $requirement Requirement
             */
            $role  = \yii\helpers\ArrayHelper::getValue($requirement,'ownableitem.createdBy.user.role');
            $group = PlanItemGroup::findOneByRole($role);
            if ($group)
                $requirement->group_id = $group->id;
            else
                $requirement->group_id = PlanItemGroup::GROUP_EDUCATOR_ID;

            $requirement->save();
        }
    }
}

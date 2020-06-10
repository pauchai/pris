<?php

use yii\db\Migration;
use vova07\users\models\Officer;
use vova07\prisons\models\OfficerPost;

/**
 * Class m200608_053938_OfficerAddConstraintToOfficerPost
 */
class m200608_053938_OfficerAddConstraintToOfficerPost extends Migration
{
    const FK_OFFICER_OFFICER_POST = 'fk_officer_officer_post';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(self::FK_OFFICER_OFFICER_POST,Officer::tableName(),['__person_id','company_id','division_id','postdict_id'], OfficerPost::tableName(),OfficerPost::primaryKey());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(self::FK_OFFICER_OFFICER_POST,Officer::tableName());

        return true;
    }
}

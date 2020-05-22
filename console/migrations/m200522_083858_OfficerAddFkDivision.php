<?php

use yii\db\Migration;
use vova07\users\models\Officer;
use vova07\prisons\models\Division;
/**
 * Class m200522_083858_OfficerAddFkDivision
 */
class m200522_083858_OfficerAddFkDivision extends Migration
{
    const FK_OFFICER_DIVISION_ID = 'fk_officer625626358_DIVISION';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(self::FK_OFFICER_DIVISION_ID,
            Officer::tableName(),['company_id', 'division_id'],
            Division::tableName(),['company_id', 'division_id']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(self::FK_OFFICER_DIVISION_ID, Officer::tableName());
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200522_083858_OfficerAddFkDivision cannot be reverted.\n";

        return false;
    }
    */
}

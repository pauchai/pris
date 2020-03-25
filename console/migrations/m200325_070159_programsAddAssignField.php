<?php

use yii\db\Migration;

/**
 * Class m200325_070159_programsAddAssignField
 */
class m200325_070159_programsAddAssignField extends Migration
{
    const FK_NAME_PROGRAM_ASSIGNED = 'fk_program_assigned';
    const FK_NAME_PROGRAM_PRISONER_ASSIGNED = 'fk_program_prisoner_assigned';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(\vova07\plans\models\Program::tableName(),'assigned_to', \yii\db\Schema::TYPE_INTEGER);


        foreach (\vova07\plans\models\Program::find()->all() as $program)
        {
            /**
             * @var $program \vova07\plans\models\Program
             */
            $officer = self::resolveOfficer($program->ownableitem->created_by);
            $program->assigned_to = $officer->primaryKey;
            $program->save();
        }
        $this->addForeignKey(self::FK_NAME_PROGRAM_ASSIGNED, \vova07\plans\models\Program::tableName(), 'assigned_to', \vova07\users\models\Officer::tableName(), '__person_id');
        $this->alterColumn(\vova07\plans\models\Program::tableName(),'assigned_to', \yii\db\Schema::TYPE_INTEGER . ' not null');

        /*$this->addColumn(\vova07\plans\models\ProgramPrisoner::tableName(),'assigned_to', \yii\db\Schema::TYPE_INTEGER);
        foreach (\vova07\plans\models\ProgramPrisoner::find()->all() as $programPrisoner)
        {
            $officer = self::resolveOfficer($programPrisoner->ownableitem->created_by);
            $programPrisoner->assigned_to = $officer->primaryKey;
            $programPrisoner->save();
        }
        $this->addForeignKey(self::FK_NAME_PROGRAM_PRISONER_ASSIGNED, \vova07\plans\models\ProgramPrisoner::tableName(), 'assigned_to', \vova07\users\models\Officer::tableName(), '__person_id');
        $this->alterColumn(\vova07\plans\models\ProgramPrisoner::tableName(),'assigned_to', \yii\db\Schema::TYPE_INTEGER . ' not null');*/


    }

    /**
     * @return null|\vova07\users\models\Officer
     */
    public static function resolveOfficer($createdBy)
    {
        if (!($officer = \vova07\users\models\Officer::findOne($createdBy))) {
            /**
             * @var $user \vova07\users\models\User
             */
            $user = \vova07\users\models\User::findOne(['username' => 'yus']);
            $officer = $user->ident->person->officer;
        }
        return $officer;
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        \Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();

        $this->dropForeignKey(self::FK_NAME_PROGRAM_ASSIGNED, \vova07\plans\models\Program::tableName());
        $this->dropColumn(\vova07\plans\models\Program::tableName(),'assigned_to');

   /*     $this->dropForeignKey(self::FK_NAME_PROGRAM_PRISONER_ASSIGNED, \vova07\plans\models\ProgramPrisoner::tableName());
        $this->dropColumn(\vova07\plans\models\ProgramPrisoner::tableName(),'assigned_to');
        \Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=1')->execute();*/


        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200325_070159_programsAddAssignField cannot be reverted.\n";

        return false;
    }
    */
}

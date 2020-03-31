<?php

use yii\db\Migration;

/**
 * Class m200325_130052_prisonerPlan
 */
class m200325_130052_prisonerPlan extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $user = \vova07\users\models\User::findOne(['username' => 'admin']);
        $ident = $user->ident;
        \Yii::$app->user->setIdentity($ident);

        foreach (\vova07\plans\models\ProgramPrisoner::find()->all() as $programPrisoner)
        {
            /**
             * @var $program \vova07\plans\models\ProgramPrisoner
             */

            if (!($prisonerPlan = \vova07\plans\models\PrisonerPlan::findOne($programPrisoner->prisoner_id))){
                $prisonerPlan = new \vova07\plans\models\PrisonerPlan();
                $prisonerPlan->__prisoner_id = $programPrisoner->prisoner_id;
                $prisonerPlan->status_id = \vova07\plans\models\PrisonerPlan::STATUS_ACTIVE;
                $prisonerPlan->save();

            };

        }
        $this->dropForeignKey('fk_program_prisoner2041663612', \vova07\plans\models\ProgramPrisoner::tableName());
        $this->addForeignKey('fk_program_prisoner_prisoner_id',\vova07\plans\models\ProgramPrisoner::tableName(), 'prisoner_id', \vova07\plans\models\PrisonerPlan::tableName(), '__prisoner_id'  );

        foreach (\vova07\plans\models\Requirement::find()->all() as $requirements)
        {
            /**
             * @var $program \vova07\plans\models\ProgramPrisoner
             */

            if (!($prisonerPlan = \vova07\plans\models\PrisonerPlan::findOne($requirements->prisoner_id))){
                $prisonerPlan = new \vova07\plans\models\PrisonerPlan();
                $prisonerPlan->__prisoner_id = $requirements->prisoner_id;
                $prisonerPlan->status_id = \vova07\plans\models\PrisonerPlan::STATUS_ACTIVE;
                $prisonerPlan->save();

            };

        }
        $this->dropForeignKey('fk_requirement2041663612', \vova07\plans\models\Requirement::tableName());
        $this->addForeignKey('fk_requirement_prisoner_id',\vova07\plans\models\Requirement::tableName(), 'prisoner_id', \vova07\plans\models\PrisonerPlan::tableName(), '__prisoner_id'  );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk_program_prisoner_prisoner_id', \vova07\plans\models\ProgramPrisoner::tableName());
        $this->addForeignKey('fk_program_prisoner2041663612',\vova07\plans\models\ProgramPrisoner::tableName(), 'prisoner_id', \vova07\users\models\Prisoner::tableName(), '__prisoner_id'  );

        $this->dropForeignKey('fk_requirement_prisoner_id', \vova07\plans\models\Requirement::tableName());
        $this->addForeignKey('fk_requirement2041663612',\vova07\plans\models\Requirement::tableName(), 'prisoner_id', \vova07\users\models\Prisoner::tableName(), '__prisoner_id'  );

        $this->truncateTable(\vova07\plans\models\PrisonerPlan::tableName());



        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200325_130052_prisonerPlan cannot be reverted.\n";

        return false;
    }
    */
}

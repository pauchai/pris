<?php

use yii\db\Migration;
use vova07\jobs\models\JobPaidList;

/**
 * Class m200428_094439_jobPaidListStatusDeletedAt
 */
class m200429_122701_jobPaidListStatusDeletedAt extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn(JobPaidList::tableName(),'status_id', $this->tinyInteger(3)->notNull());
        $this->addColumn(JobPaidList::tableName(),'deleted_at', $this->bigInteger());
        $this->createIndex('idx_' . JobPaidList::tableName() .'status_id', JobPaidList::tableName(), 'status_id' );

        foreach (JobPaidList::find()->all() as $job)
        {
            /**
             * @var $program \vova07\plans\models\Program
             */
            $officer = self::resolveOfficer($job->ownableitem->created_by);
            $job->status_id = JobPaidList::STATUS_ID_ACTIVE;
            $job->save();
        }

    }

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
        $this->dropColumn(JobPaidList::tableName(),'status_id');
        $this->dropColumn(JobPaidList::tableName(),'deleted_at');


        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200428_094439_jobPaidListStatusDeletedAt cannot be reverted.\n";

        return false;
    }
    */
}

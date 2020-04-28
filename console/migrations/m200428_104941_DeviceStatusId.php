<?php

use yii\db\Migration;
use vova07\electricity\models\Device;

/**
 * Class m200428_104941_DeviceStatusId
 */
class m200428_104941_DeviceStatusId extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Device::tableName(),'status_id', $this->tinyInteger()->notNull());
        $this->alterColumn(Device::tableName(),'assigned_at', $this->bigInteger());
        $this->alterColumn(Device::tableName(),'unassigned_at', $this->bigInteger());
        $this->createIndex('idx_' . Device::tableName() .'status_id', Device::tableName(), 'status_id' );

        foreach (Device::find()->all() as $job)
        {
            /**
             * @var $program \vova07\plans\models\Program
             */
            $officer = self::resolveOfficer($job->ownableitem->created_by);
            $job->status_id = Device::STATUS_ID_ACTIVE;
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
        $this->dropColumn(Device::tableName(),'status_id');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200428_104941_DeviceStatusId cannot be reverted.\n";

        return false;
    }
    */
}

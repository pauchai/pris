<?php

use yii\db\Migration;
use vova07\jobs\models\JobPaidList;

/**
 * Class m200429_122701_JobPaidListDropUniqAndAddComment
 */
class m200429_122701_JobPaidListDropUniqAndAddComment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx_job_paid_list_assigned_to', JobPaidList::tableName(), ['assigned_to']);
        $this->dropIndex('idx_job_paid_list_assigned_prison_type_half',JobPaidList::tableName());

        $this->addColumn(JobPaidList::tableName(),'comment', $this->string());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(JobPaidList::tableName(),'comment');

        $this->createIndex('idx_job_paid_list_assigned_prison_type_half',JobPaidList::tableName(),
            [
                'assigned_to',
                'prison_id',
                'type_id',
                'half_time',
            ],'true');

        $this->dropIndex('idx_job_paid_list_assigned_to', JobPaidList::tableName());

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200429_122701_JobPaidListDropUniqAndAddComment cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;
use vova07\humanitarians\models\HumanitarianIssue;

/**
 * Class m210616_063008_dbHumanitaqianIssueAddStatusColumn
 */
class m210616_063008_dbHumanitaqianIssueAddStatusColumn extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(HumanitarianIssue::tableName(), 'status_id', $this->tinyInteger()->notNull());
        $this->update(HumanitarianIssue::tableName(), ['status_id' => HumanitarianIssue::STATUS_PROCESSED]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(HumanitarianIssue::tableName(), 'status_id');

        return true;
    }



    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210616_063008_dbHumanitaqianIssueAddStatusColumn cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;
use vova07\tasks\models\Committee;

/**
 * Class m210601_075320_dbCommiteeFixPrimaryKeyValueAfterDecoupling_IdentColumn
 */
class m210601_075320_dbCommiteeFixPrimaryKeyValueAfterDecoupling_IdentColumn extends \vova07\base\components\MigrationWithModelGenerator
{
    public function safeUp()
    {
        $this->db->createCommand()->checkIntegrity(false)->execute();

        $this->db->createCommand()->alterColumn(Committee::tableName(),'assigned_to', $this->integer())->execute();
        $this->db->createCommand()->checkIntegrity()->execute();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {


        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210601_075320_dbCommiteeFixPrimaryKeyValueAfterDecoupling_IdentColumn cannot be reverted.\n";

        return false;
    }
    */
}

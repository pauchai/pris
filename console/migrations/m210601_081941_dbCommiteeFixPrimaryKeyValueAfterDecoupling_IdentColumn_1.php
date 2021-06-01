<?php

use yii\db\Migration;

/**
 * Class m210601_081941_dbCommiteeFixPrimaryKeyValueAfterDecoupling_IdentColumn_1
 */
class m210601_081941_dbCommiteeFixPrimaryKeyValueAfterDecoupling_IdentColumn_1 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->db->createCommand()->checkIntegrity(false)->execute();
        foreach(\vova07\tasks\models\Committee::find()->all() as $commitee){
            if ($ident = \vova07\users\models\Ident::findOne($commitee->assigned_to))
            {
                $commitee->assigned_to = $ident->person_id;
                $commitee->save();
            }

        }
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
        echo "m210601_081941_dbCommiteeFixPrimaryKeyValueAfterDecoupling_IdentColumn_1 cannot be reverted.\n";

        return false;
    }
    */
}

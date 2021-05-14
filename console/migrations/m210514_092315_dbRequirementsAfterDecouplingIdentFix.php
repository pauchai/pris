<?php

use yii\db\Migration;
use vova07\users\models\Ident;
use vova07\plans\models\Requirement;


/**
 * Class m210514_092315_dbRequirementsAfterDecouplingIdentFix
 */
class m210514_092315_dbRequirementsAfterDecouplingIdentFix extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->db->createCommand()->checkIntegrity(false)->execute();

        foreach (Requirement::find()->all() as $requirement) {

                if ($ident = Ident::findOne($requirement->prisoner_id)){
                    $command = $this->db->createCommand()->update(Requirement::tableName(),['prisoner_id' => $ident->person_id],['prisoner_id' => $ident->__item_id]);
                    $command->execute();
                    $this->beginCommand($command->getRawSql());
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

}

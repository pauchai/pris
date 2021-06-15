<?php

use yii\db\Migration;
use \vova07\tasks\models\Committee;

/**
 * Class m210614_004758_dbCommiteeMakrBehaviourColumn
 */
class m210614_004758_dbCommiteeMakrBehaviourColumn extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $this->addColumn(Committee::tableName(),  'mark_behaviour_id',Committee::getMetadata()['fields']['mark_behaviour_id']);




    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Committee::tableName(), 'mark_behaviour_id');

        return true;
    }
}

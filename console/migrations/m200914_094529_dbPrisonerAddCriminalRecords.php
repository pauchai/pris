<?php

use yii\db\Migration;
use vova07\users\models\Prisoner;

/**
 * Class m200914_094529_dbPrisonerAddCriminalRecords
 */
class m200914_094529_dbPrisonerAddCriminalRecords extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $this->addColumn(Prisoner::tableName(),  'criminal_records',Prisoner::getMetadata()['fields']['criminal_records']);



    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Prisoner::tableName(), 'criminal_records');


        return true;
    }
}

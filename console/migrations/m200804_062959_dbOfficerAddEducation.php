<?php

use yii\db\Migration;
use vova07\users\models\Officer;

/**
 * Class m200804_062959_dbOfficerAddEducation
 */
class m200804_062959_dbOfficerAddEducation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn(Officer::tableName(),  'has_education',$this->boolean()->defaultValue(false));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Officer::tableName(), 'has_education');
        return true;
    }
}

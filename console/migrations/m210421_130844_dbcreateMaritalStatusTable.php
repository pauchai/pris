<?php

use yii\db\Migration;
use vova07\socio\models\MaritalStatus;
use \vova07\base\components\MigrationWithModelGenerator;
/**
 * Class m210421_130844_dbcreateMaritalStatusTable
 */
class m210421_130844_dbcreateMaritalStatusTable extends MigrationWithModelGenerator
{
    public $models = [
        MaritalStatus::class,

    ];
    public function safeUp(){
        $this->generateModelTables();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropModelTables();
        return true;
    }

}

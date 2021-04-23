<?php


use vova07\socio\models\MaritalState;
use vova07\base\components\MigrationWithModelGenerator;

/**
 * Class m210414_101435_dbMaritalStatus
 */
class m210414_101435_dbMaritalStatus extends MigrationWithModelGenerator
{
    public $models = [
        MaritalState::class,

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

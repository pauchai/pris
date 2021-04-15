<?php

use yii\db\Migration;
use vova07\socio\models\Disability;

/**
 * Class m210415_065441_dbCreateDisabilityTable
 */
class m210415_065441_dbCreateDisabilityTable extends Migration
{
    public $models = [
        Disability::class,

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
    private function generateModelTables()
    {
        foreach ($this->models as $modelClassName)
            $modelsGenerated = (new \vova07\base\ModelGenerator\ModelTableGenerator())->generateTable($modelClassName);
    }
    private function dropModelTables()
    {
        \vova07\base\ModelGenerator\Helper::dropTablesForModels($this->models);
    }
}

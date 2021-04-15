<?php

use yii\db\Migration;
use vova07\socio\models\Relation;

/**
 * Class m210413_081257_dbCreateRelationTable
 */
class m210413_081257_dbCreateRelationTable extends Migration
{

    public $models = [
        Relation::class,

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

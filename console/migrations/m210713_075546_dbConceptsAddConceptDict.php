<?php


use vova07\concepts\models\ConceptDict;
use vova07\base\components\MigrationWithModelGenerator;

/**
 * Class m210713_075546_dbConceptsAddConceptDict
 */
class m210713_075546_dbConceptsAddConceptDict extends \vova07\base\components\MigrationWithModelGenerator
{
    public $models = [
        ConceptDict::class,

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

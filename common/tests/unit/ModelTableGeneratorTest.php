<?php namespace common\tests\unit;


use vova07\base\ModelGenerator\ModelsFactory;
use vova07\base\ModelGenerator\ModelTableGenerator;
use vova07\prisons\models\Company;
use vova07\prisons\models\Prison;
use vova07\users\models\Item;
use vova07\users\models\Person;
use vova07\users\models\User;



class ModelTableGeneratorTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testCreateTable()
    {

        codecept_debug(var_export(class_parents(User::class), true));
        $modelsGenerated = (new ModelTableGenerator())->generateTable(User::class);
        $modelsGenerated = (new ModelTableGenerator())->generateTable(Person::class);
        $modelsGenerated = (new ModelTableGenerator())->generateTable(Prison::class);



    }


    private function testGenerate()
    {

        ModelsFactory::dropTablesForModels([
            User::class ]);
        ModelsFactory::createTablesForModels([
            Person::class,

        ]);
        ModelsFactory::createTablesForModels([
            Prison::class,

        ]);

    }






}
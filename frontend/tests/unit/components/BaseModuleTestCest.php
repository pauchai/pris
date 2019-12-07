<?php namespace frontend\tests\unit\components;
use frontend\tests\UnitTester;
use vova07\base\components\Module;

class BaseModuleTestCest
{
    public function _before(UnitTester $I)
    {
    }

    // tests
    public function tryToTest(UnitTester $I)
    {

        $I->assertTrue(Module::className() == 'vova07\base\components\Module');
        $I->assertTrue(\vova07\users\Module::className() == 'vova07\users\Module');
        $I->assertTrue(\vova07\users\Module::getModuleName() == 'users');
        $I->assertTrue(\vova07\users\Module::getAuthorName() == 'vova07');

    }

}

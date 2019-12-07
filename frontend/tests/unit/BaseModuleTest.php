<?php
namespace frontend\tests\unit;

use frontend\models\ContactForm;
use vova07\base\components\Module;
use yii\mail\MessageInterface;

class BaseModuleTest extends \Codeception\Test\Unit
{
    public function testCommon()
    {
        expect('Имя класса модуля должно быть vova07\base\components\Module ', Module::className() == 'vova07\base\components\Module')->true();
        expect('Имя класса модуля должно быть vova07\users\Module',  \vova07\users\Module::className() == 'vova07\users\Module')->true();

        expect('Имя модуля должно быть users',  \vova07\users\Module::getModuleName() == 'users')->true();
        expect('Имя автора модуля должно быть vova07',  \vova07\users\Module::getAuthorName() == 'vova07')->true();


        //codecept_debug(Module::getAuthorName());
        //codecept_debug(\vova07\users\Module::getAuthorName());
    }
}

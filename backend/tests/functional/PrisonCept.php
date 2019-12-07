<?php use backend\tests\FunctionalTester;
$faker =   \Faker\Factory::create('ro_RO');
$I = new FunctionalTester($scenario);
$I->wantTo('perform actions and see result');
$I->amOnRoute('prisons/default/create');
$I->see(\vova07\prisons\Module::t('default','CREATE_NEW_PRISON'));

$title = $faker->company;
$I->fillField(['id'=>'company-title'], $title);
$I->click(\vova07\prisons\Module::t('default','CREATE'));
$I->see($title);
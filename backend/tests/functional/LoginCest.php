<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;

/**
 * Class LoginCest
 */
class LoginCest
{
    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @see \Codeception\Module\Yii2::_before()
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @return array
     */
    public function _fixtures()
    {

        return [
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'login_data.php'
            ]
        ];
    }
    
    /**
     * @param FunctionalTester $I
     */
    public function loginUser(FunctionalTester $I)
    {
        $I->amOnRoute('site/default/login');
        $I->see('SIGN_IN_TO_START_SESSION');

        $I->fillField(['id'=>'loginform-username'], 'admin');
        $I->fillField(['id'=>'loginform-password'], 'admin12345');


        $I->click('LOGIN');
        $I->dontSee("ERROR_MSG_INVALID_USERNAME_OR_PASSWORD");
        $I->canSeeInCurrentUrl('/index-test.php?r=site%2Fdefault%2Flogin-info');

    }

    public function wrongPage(FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
       // $I->see('#404');
        $I->canSeePageNotFound();
    }

    public function testRedirectPage(FunctionalTester $I)
    {
        $I->amOnRoute('site/default/test-redirect-login');
        $I->canSeeInCurrentUrl('/index-test.php?r=site%2Fdefault%2Flogin');
    }
}

<?php namespace backend\tests\unit;

use common\fixtures\UserFixture;
use vova07\base\ModelsFactory;
use vova07\users\models\LoginForm;
use vova07\users\models\User;




class LoginTest extends  \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;


    public function _fixtures()
    {
       return [
            'users' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'login_data.php'
            ]
        ];
    }
    protected function _before()
    {

    }

    protected function _after()
    {
    }

    // tests
    public function testLogin()
    {

        $loginForm = new LoginForm;
        $loginForm->attributes = [
            'username' => 'admin',
            'password' => 'admin12345',
        ];

        $this->assertTrue($loginForm->validate());
        $this->assertTrue($loginForm->login());

    }






}
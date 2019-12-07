<?php namespace common\tests\modules\users\models;

use vova07\users\models\Ident;
use vova07\users\models\User;

class UserTest extends \Codeception\Test\Unit
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


    public function testActiveRecordAttributes()
    {
        $ident = new Ident();
        $ident->create_at = (new \DateTime)->getTimestamp();
        $ident->save(false);
    }
    // tests

    public function testItemIdent()
    {
        $ident = new Ident();
        $ident->save();
        $this->assertNotNull($ident->item_id);
        $item = $ident->getItem();
        $this->assertTrue($item->created_at>0);
        $this->assertTrue(is_null($item->update_at));
    }


    public function testUserNew()
    {
        $user = new \vova07\users\models\backend\User();
        $user->scenario = \vova07\users\models\backend\User::SCENARIO_BACKEND_CREATE;
        $user->username = 'admin';
        $user->email = 'rv@email.com';
        $plaintext_password = 'admin12345';
        $user->password = $plaintext_password;
        $user->repassword = $plaintext_password;
        $this->assertTrue($user->save());

        $saved_user = \vova07\users\models\backend\User::findOne($user->id);
        $security = new \yii\base\Security();
        $this->assertinstanceOf(get_class($user), $saved_user);
        $this->assertTrue(
            $security->validatePassword(
                $plaintext_password,
                $saved_user->password_hash
            )
        );
        $ident = $user->getIdent();
        $item = $ident->getItem();

    }






}
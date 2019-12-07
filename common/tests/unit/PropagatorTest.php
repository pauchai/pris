<?php namespace common\tests\unit;

use common\fixtures\IdentFixture;
use common\fixtures\ItemFixture;
use common\fixtures\UserFixture;
use Faker\Factory;
use PHPUnit\Framework\TestResult;
use vova07\base\ModelGenerator\Facade;
use vova07\base\models\ActiveRecordMetaModel;
use vova07\base\models\Ownableitem;
use vova07\base\ModelsFactory;
use vova07\base\ModelTableGenerator;
use vova07\prisons\models\Company;
use vova07\prisons\models\Prison;
use vova07\users\models\Ident;
use vova07\base\models\Item;
use vova07\users\models\Person;
use vova07\users\models\User;
use yii\base\Event;
use yii\db\BaseActiveRecord;
use yii\db\Query;


class PropagatorTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    protected $adminUser;

    /**
     * @var \Faker\Generator
     */
    protected $faker;

    public function _before()
    {
        $this->tester->haveFixtures([
            'users' => [
                'class' => UserFixture::className(),
            ],
            'items' => [
                'class' => ItemFixture::className(),
            ],
            'ident' => [
                'class' => IdentFixture::className(),
            ]


        ]);

        $this->adminUser = $this->tester->grabFixture('users','admin');
        \Yii::$app->user->login($this->adminUser);
        $this->faker =   \Faker\Factory::create('ro_RO');


    }

    protected function _after()
    {
    }



    public function testUserSave()
    {

        $countItems = \Yii::$app->db->createCommand("SELECT count(*) FROM item")->queryScalar();
        expect("1 item",$countItems)->equals(1);
        $countOwnableItems = \Yii::$app->db->createCommand("SELECT count(*) FROM ownableitem")->queryScalar();
        expect("0 ownableitem",$countOwnableItems)->equals(0);
        $countIdents = \Yii::$app->db->createCommand("SELECT count(*) FROM ident")->queryScalar();
        expect("1 ident",$countIdents)->equals(1);
        $countUsers = \Yii::$app->db->createCommand("SELECT count(*) FROM user")->queryScalar();
        expect("1 users",$countIdents)->equals(1);

        $user = new User();
        $user->username = $this->faker->userName;
        $user->password_hash = md5($this->faker->password);
        $user->email = $this->faker->email;
        Facade::dispense($user);
        expect("save user true ",$user->save())->true();

        expect($countItems+1 . " item",\Yii::$app->db->createCommand("SELECT count(*) FROM item")->queryScalar())->equals($countItems+1);
        expect($countOwnableItems+1 ." ownableitem",\Yii::$app->db->createCommand("SELECT count(*) FROM ownableitem")->queryScalar())->equals($countOwnableItems+1);
        expect($countIdents+1 . " ident",\Yii::$app->db->createCommand("SELECT count(*) FROM ident")->queryScalar())->equals($countIdents+1);
        expect($countUsers+1 . " users",\Yii::$app->db->createCommand("SELECT count(*) FROM user")->queryScalar())->equals($countUsers+1);



    }

    private function testPrisonВРазработке()
    {
        $adminUser = $this->tester->grabFixture('users','admin');
        \Yii::$app->user->login($adminUser);
        $faker =   \Faker\Factory::create('ro_RO');

        $prison = new Prison;
        $company = new Company;
        $company->title = $faker->company;
        $ownableitem = new Ownableitem;
        $item = new Item;

        $prison->on(BaseActiveRecord::EVENT_BEFORE_INSERT, function($event){
            /**
             * @var ActiveRecordMetaModel $parent
             * @var Event $event
             */
            $parent = $event->data;
             $parent->save();

        }, $company);

        $company->on(BaseActiveRecord::EVENT_AFTER_INSERT, function($event){
            /**
             * @var Company $company
             */
            $model = $event->sender;
            $childModel = $event->data;
            $childModel->__company_id = $model->primaryKey;

        },$prison);

        $company->on(BaseActiveRecord::EVENT_BEFORE_INSERT, function($event){
            /**
             * @var ActiveRecordMetaModel $parent
             * @var Event $event
             */
            $parent = $event->data;
             $parent->save();

        },$ownableitem);


        $ownableitem->on(BaseActiveRecord::EVENT_BEFORE_INSERT, function($event){
            /**
             * @var ActiveRecordMetaModel $parent
             * @var Event $event
             */
            $parent = $event->data;
             $parent->save();

        },$item);

        $ownableitem->on(BaseActiveRecord::EVENT_AFTER_INSERT, function($event){
            /**
             * @var Company $company
             */
            $model = $event->sender;
            $childModel = $event->data;
            $childModel->__ownableitem_id = $model->primaryKey;

        },$company);




        $item->on(BaseActiveRecord::EVENT_AFTER_INSERT, function($event){
            /**
             * @var Company $company
             */
            $model = $event->sender;
            $childModel = $event->data;
            $childModel->__item_id = $model->primaryKey;

        },$ownableitem);


        $prison->save();


        /**
        $company->on('afterSave',function ($event){
            $company = $event->sender;
            $prison = $event->data;
            $_k = true;
        }, $prison);
        $ownableItem = new Ownableitem;
        $item = new Item;
         */

    }








}
<?php namespace common\tests\unit;

use common\fixtures\CompanyDepartmentFixture;
use common\fixtures\CompanyFixture;
use common\fixtures\DepartmentFixture;
use common\fixtures\IdentFixture;
use common\fixtures\ItemFixture;
use common\fixtures\OfficerFixture;
use common\fixtures\OwnableitemFixture;
use common\fixtures\PersonFixture;
use common\fixtures\PrisonerFixture;
use common\fixtures\PrisonFixture;
use common\fixtures\ProgramDictFixture;
use common\fixtures\ProgramFixture;
use common\fixtures\ProgramPrisonerFixture;
use common\fixtures\ProgramVisitFixture;
use common\fixtures\SectorFixture;
use common\fixtures\UserFixture;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestResult;
use vova07\base\ModelGenerator\Facade;
use vova07\base\models\Ownableitem;
use vova07\base\ModelsFactory;
use vova07\base\ModelTableGenerator;
use vova07\countries\models\Country;
use vova07\plans\models\Program;
use vova07\plans\models\ProgramDict;
use vova07\plans\models\ProgramPrisoner;
use vova07\plans\models\ProgramVisit;
use vova07\prisons\models\Company;
use vova07\prisons\models\Department;
use vova07\prisons\models\Officer;
use vova07\prisons\models\Prison;
use vova07\prisons\models\Prisoner;
use vova07\prisons\models\Sector;
use vova07\rbac\models\Permission;
use vova07\rbac\rules\AuthorRule;
use vova07\rbac\rules\OwnRule;
use vova07\users\models\Ident;
use vova07\base\models\Item;
use vova07\users\models\Person;
use vova07\users\models\User;
use yii\db\IntegrityException;


class RbacTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;
    /* @var  \Faker\Generator $faker */
    public $faker;


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
            ],
            'ownableitem' => [
                'class' => OwnableitemFixture::className(),
            ],

            'company' => [
                'class' => CompanyFixture::className(),
            ],
            'prison' => [
                'class' => PrisonFixture::className(),
            ],
            'person' => [
                'class' => PersonFixture::className(),
            ],
            'prisoner' => [
                'class' => PrisonerFixture::className(),
            ],
            'department' => [
                'class' => DepartmentFixture::className(),
            ],
            'company_department' => [
                'class' => CompanyDepartmentFixture::class
            ],
            'officer' => [
                'class' => OfficerFixture::class
            ],
            'program_dicts' => [
                'class' => ProgramDictFixture::class
            ],
            'programs' => [
                'class' => ProgramFixture::class
            ],
            'program_prisoners' => [
                'class' => ProgramPrisonerFixture::class
            ],

            'program_visits' => [
                'class' => ProgramVisitFixture::class
            ],

            'sector' => [
                'class' => SectorFixture::class,
            ]
        ]);

        $this->faker = \Faker\Factory::create('ro_RO');
        /**
         * @var $adminUser User
         */
        $adminUser = $this->tester->grabFixture('users', 'admin');
        \Yii::$app->user->login($adminUser);



        $auth = \Yii::$app->authManager;
        $auth->removeAll();

        $testPermission = $auth->createPermission('test');

        $ruleOwn = new OwnRule();
        $permissionEditPerson = $auth->createPermission('editPerson');
        $permissionEditOwnPerson = $auth->createPermission('editOwnPerson');
        $permissionEditOwnPerson->ruleName = $ruleOwn->name;


        $auth->add($testPermission);
        $auth->add($ruleOwn);
        $auth->add($permissionEditPerson);
        $auth->add($permissionEditOwnPerson);

        $auth->addChild($permissionEditOwnPerson, $permissionEditPerson);
        $auth->assign($testPermission,$adminUser->primaryKey);
        $auth->assign($permissionEditOwnPerson,$adminUser->primaryKey);
        $auth->assign($permissionEditPerson, $adminUser->primaryKey);



    }

    protected function _after()
    {
    }

    public function testCheck()
    {



        expect(\Yii::$app->user->can('test'))->true();
        expect(\Yii::$app->user->can('editOwnPerson',['model'=>$adminPerson]))->true();
        //expect(\Yii::$app->user->can('accessPrison',['prison_id'=>$prison->primary]))->true();

    }

    private  function createPerson($attr = [])
    {
        $person = new Person();
        //  $adminPerson->ident = [];
        $person->first_name = $this->faker->firstNameMale;
        $person->second_name = $this->faker->lastName;
        $person->citizen_id = Country::findOne(['iso'=>'md'])->id;
        $person->save();
        return $person;
    }
    private function createOfficer($attr = [])
    {
        $officer = new \vova07\users\models\Officer();
        $officer->person = $this->createPerson;


    }

    private  function createPrison($attr = [])
    {
        $prison = new Prison();
        //  $adminPerson->ident = [];
        $prison->company = $this->createCompany();
        $prison->save();
        return $prison;
    }

    private function createCompany($attr=[])
    {
        $company = new Company();
        $company->title = $this->faker->company;
        $company->save();
        return $company;
    }



}
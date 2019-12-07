<?php namespace common\tests\unit;

use common\fixtures\CompanyDepartmentFixture;
use common\fixtures\CompanyFixture;
use common\fixtures\DepartmentFixture;
use common\fixtures\DeviceAccountingFixture;
use common\fixtures\DeviceFixture;
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
use common\fixtures\SettingFixture;
use common\fixtures\UserFixture;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestResult;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Facade;
use vova07\base\models\Ownableitem;
use vova07\base\ModelsFactory;
use vova07\base\ModelTableGenerator;
use vova07\countries\models\Country;
use vova07\electricity\models\Device;
use vova07\electricity\models\DeviceAccounting;
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
use vova07\rbac\Module;
use vova07\site\models\Setting;
use vova07\users\models\Ident;
use vova07\base\models\Item;
use vova07\users\models\Person;
use vova07\users\models\User;
use yii\db\IntegrityException;


class DevicesTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;
    /* @var  \Faker\Generator $faker */
    public $faker;
    private $fixtures;


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
            ],
            'devices' => [
                'class' => DeviceFixture::class,
            ],
            'device_accountings' => [
                'class' => DeviceAccountingFixture::class,
            ],
            'settings' => [
                'class' => SettingFixture::class,
            ]
        ]);


        $adminUser = $this->tester->grabFixture('users', 'admin');
        \Yii::$app->user->login($adminUser);
        $this->faker = \Faker\Factory::create('ro_RO');
        $countItems = \Yii::$app->db->createCommand("SELECT count(*) FROM item")->queryScalar();
        expect( $countItems)->equals(1);
        $countOwnableItems = \Yii::$app->db->createCommand("SELECT count(*) FROM ownableitem")->queryScalar();
        expect( $countOwnableItems)->equals(0);
        $countIdents = \Yii::$app->db->createCommand("SELECT count(*) FROM ident")->queryScalar();
        expect( $countIdents)->equals(1);
        $countUsers = \Yii::$app->db->createCommand("SELECT count(*) FROM user")->queryScalar();
        expect( $countIdents)->equals(1);

        $this->generateFixtures();
    }

    protected function _after()
    {
       // $this->generateFixtures();
    }

    public function testA()
    {
       // $dateTime = \DateTime::createFromFormat('Y-m-d','2019-11-25');
       // $wday  = $dateTime->format('w');

        $device = new Device();
        $device->title =  $this->faker->title;
        $device->power = 111;
        $device->enable_auto_calculation = true;
        expect($device->save())->true();

        $deviceAccounting = new DeviceAccounting();
  //      $deviceAccounting->detachBehavior('dateRange');
        $deviceAccounting->attachBehavior('fromDateJui',[
           'class' => DateJuiBehavior::class,
            'attribute' => 'from_date',
            'juiAttribute' => 'fromDateJui',
        ]);
        $deviceAccounting->attachBehavior('toDateJui',[
            'class' => DateJuiBehavior::class,
            'attribute' => 'to_date',
            'juiAttribute' => 'toDateJui',
        ]);

        $deviceAccounting->prisoner_id = $this->fixtures['prisoner1']->primaryKey;
        $deviceAccounting->device_id = $device->primaryKey;
        $deviceAccounting->fromDateJui = '01-01-2019';
        $deviceAccounting->toDateJui = '31-12-2019';
       // $deviceAccounting->value = '111';
        expect($deviceAccounting->save())->true();
        expect($deviceAccounting->value)->equals('222');


       // expect($this->fixtures['prison1']->company->title)->equals('prison1');
       // expect($this->fixtures['prisoner1']->person->first_name)->equals('first_name1');
        //
    }

    private function generateFixtures()
    {

      $prison1 = new Prison;
      $prison1->company = ['title' => 'prison1'];
      expect($prison1->save())->true();
      $this->fixtures['prison1'] = $prison1;

        $setting1  = new Setting();
        $setting1->prison_id = $prison1->primaryKey;
        $setting1->kilo_watt_price = 1.4;
        expect($setting1->save())->true();

      $sector1 = new Sector;
      $sector1->title = 'sector1';
      $sector1->prison = $prison1;
      expect($sector1->save())->true();
      $this->fixtures['sector1']=$sector1;

      $prisoner1 = new \vova07\users\models\Prisoner();
      $prisoner1->prison = $prison1;
      $prisoner1->person = [
          'first_name' => 'first_name1',
          'second_name'=>'second_name1',
          'patronymic' => 'patronymic1',
          'citizen_id' => Country::findOne(['iso'=>'MD'])->id,

      ];


      $prisoner1->sector = $sector1;
      $prisoner1->status_id = \vova07\users\models\Prisoner::STATUS_ACTIVE;
        $prisoner1->save(true);
      expect($prisoner1->save(true))->true();
      $this->fixtures['prisoner1'] = $prisoner1;


//      $prisoner
    }


}
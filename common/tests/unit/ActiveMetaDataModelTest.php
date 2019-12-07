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
use vova07\users\models\Ident;
use vova07\base\models\Item;
use vova07\users\models\Person;
use vova07\users\models\User;
use yii\db\IntegrityException;


class ActiveMetaDataModelTest extends \Codeception\Test\Unit
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
    }

    protected function _after()
    {
    }

    private function createPrison()
    {
        $prison = new Prison;
        $company = new Company;
        $company->title = $this->faker->company;

        $prison->company = $company;
        $prison->save();
        return $prison;
    }

    private function createPrisonSector($prison)
    {
        $sector = new Sector();
        $sector->title = $this->faker->jobTitle;
        $sector->prison = $prison;
        $sector->save();
        return $sector;
    }


    private function createPrisoner($prison,$sector)
    {
        $prisoner = new \vova07\users\models\Prisoner();
        $person = new Person;
        $person->first_name = $this->faker->firstNameMale();
        $person->second_name = $this->faker->lastName();
        $person->patronymic = $this->faker->firstNameMale();
        $person->photo_url = $this->faker->imageUrl(640,480,'people');;
        $person->ident = new Ident();
        $country = Country::findOne(['iso'=>'md']);
        $person->citizen_id = $country->id;


        $prisoner->person = $person;
        $prisoner->prison = $prison;
        $prisoner->sector = $sector;
        $prisoner->save();
        return $prisoner;



    }
/*
    public function testCompany()
    {
        $company = new Company;
        $company->title = $this->faker->company();
        expect($company->save())->true();

        $countItems = \Yii::$app->db->createCommand("SELECT count(*) FROM item")->queryScalar();
        expect($countItems)->equals(1 + 1);
        $countOwnableItems = \Yii::$app->db->createCommand("SELECT count(*) FROM ownableitem")->queryScalar();
        expect($countOwnableItems)->equals(0 + 1);
        $countIdents = \Yii::$app->db->createCommand("SELECT count(*) FROM ident")->queryScalar();
        expect($countIdents)->equals(1);
        $countUsers = \Yii::$app->db->createCommand("SELECT count(*) FROM user")->queryScalar();
        expect($countIdents)->equals(1);

        expect($company->delete())->equals(true);
    }
    public function testPrison()
    {
        $prison = new Prison;
        $company = new Company;
        $company->title = 'old Title';

        $prison->company = $company;
        expect( $prison->save())->true();

        $countItems = \Yii::$app->db->createCommand("SELECT count(*) FROM item")->queryScalar();
        expect( $countItems)->equals(1+2);
        $countOwnableItems = \Yii::$app->db->createCommand("SELECT count(*) FROM ownableitem")->queryScalar();
        expect( $countOwnableItems)->equals(0+2);
        $countIdents = \Yii::$app->db->createCommand("SELECT count(*) FROM ident")->queryScalar();
        expect( $countIdents)->equals(1);
        $countUsers = \Yii::$app->db->createCommand("SELECT count(*) FROM user")->queryScalar();
        expect( $countIdents)->equals(1);

        $oldTitle = $prison->company->title;


        $companyId = $prison->__company_id;
        unset($company, $prison);

        $prison = Prison::findOne($companyId);

        //  $company = $prison->company;
        //  $company->title = "new title";
        //  $prison->company = $company;
        // $prison->getOldRelations();
        // $prison->company->title = "new title";
        //$prison->company = ;
        $data = [
            'Prison'=>[],
            'Company' => ['__ownableitem_id' => $companyId,'title' => 'new title']
        ];

        $prison->load($data);
        expect( $prison->save())->true();
        expect( $prison->company->title)->notEquals($oldTitle);

        $countItems = \Yii::$app->db->createCommand("SELECT count(*) FROM item")->queryScalar();
        expect( $countItems)->equals(1+2);
        $countOwnableItems = \Yii::$app->db->createCommand("SELECT count(*) FROM ownableitem")->queryScalar();
        expect( $countOwnableItems)->equals(0+2);
        $countIdents = \Yii::$app->db->createCommand("SELECT count(*) FROM ident")->queryScalar();
        expect( $countIdents)->equals(1);
        $countUsers = \Yii::$app->db->createCommand("SELECT count(*) FROM user")->queryScalar();
        expect( $countIdents)->equals(1);

        expect($prison->delete())->equals(true);

    }

*/
        /*
            public function testProgram()
            {
                $prison = $this->createPrison();
                $sector = $this->createPrisonSector($prison);
                $prisoner = $this->createPrisoner($prison,$sector);

                $programDict = new ProgramDict();
                $programDict->title = $this->faker->title;
                 expect($programDict->save())->true();


                $program = new Program();
                $program->programDict = $programDict;
                $program->prison = $prison;
                $program->date_start = '1974/03/12';
                $program->status_id = Program::STATUS_ACTIVE;
                $program->order_no = $this->faker->bankAccountNumber;
                expect($program->save())->true();


                $programPrisoner = new ProgramPrisoner();
                $programPrisoner->program = $program;
                $programPrisoner->prisoner = $prisoner;

                $programPrisoner->status_id = ProgramPrisoner::STATUS_ACTIVE;

                expect($programPrisoner->save())->true();



                $programVisit = new ProgramVisit();
                $programVisit->program = $program;
                $programVisit->prisoner = $prisoner;
                $programVisit->date_visit = '2019/08/22';
                $programVisit->status_id = ProgramVisit::STATUS_PRESENT;
                $programVisit->save();
                // expect($programVisit->save())->true();

                 $string =  join(',', $program->getFirstErrors());
                // codecept_debug($string);

            }

            public function testOfficerLoad()
            {
                $company = new Company;
                $company->title = $this->faker->company();
                expect( $company->save())->true();

                $department1 = new Department();
                $department1->title = $this->faker->company;
                expect($department1->save())->true();

                $company->departments = [$department1];
                expect($company->save())->true();
                $country = Country::findOne(['iso'=>'md']);


                $officer = new \vova07\users\models\Officer();
               // $officer->person = new Person;
               // $person = new Person;
             //   $officer->person->ident = new Ident();

               // $officer->person  = $person;
                $params = array(
                    'Officer' =>
                        array(
                            'company_id' => $company->__ownableitem_id,
                            'department_id' => $department1->__ownableitem_id,
                        ),
                    'Person' =>
                        array(
                            'first_name' => 'test',
                            'second_name' => 'test',
                            'patronymic' => 'test',
                            'birth_year' => '',
                            'photo_url' => '',
                            'citizen_id' => $country->id,
                            'address' => '',
                            'IDNP' => '',
                        ),
                );
                expect($officer->load($params))->true();
                $resultSave = $officer->save();
                expect ($resultSave)->true();
            }

        */
    /*
    public function testPerson()
    {

        $country = Country::findOne(['iso' => 'md']);
        $person = new Person;
        $person->first_name = $this->faker->firstNameMale();
        $person->second_name = $this->faker->lastName();
        $person->patronymic = $this->faker->firstNameMale();
        $person->photo_url = $this->faker->imageUrl(640, 480, 'people');;
        $person->country = $country;
        $person->ident = new Ident();
        expect($person->save())->true();

        $countItems = \Yii::$app->db->createCommand("SELECT count(*) FROM item")->queryScalar();
        expect( $countItems)->equals(1+1+1);
        $countOwnableItems = \Yii::$app->db->createCommand("SELECT count(*) FROM ownableitem")->queryScalar();
        expect( $countOwnableItems)->equals(0+1);
        $countIdents = \Yii::$app->db->createCommand("SELECT count(*) FROM ident")->queryScalar();
        expect( $countIdents)->equals(1+1);
        $countUsers = \Yii::$app->db->createCommand("SELECT count(*) FROM user")->queryScalar();
        expect( $countUsers)->equals(1);

    }
    public function testPersonWithIdentExists()
    {
        $country = Country::findOne(['iso' => 'md']);
        $person = new Person;
        $person->first_name = $this->faker->firstNameMale();
        $person->second_name = $this->faker->lastName();
        $person->patronymic = $this->faker->firstNameMale();
        $person->photo_url = $this->faker->imageUrl(640,480,'people');;
        $person->ident = Ident::findOne(1);
        $person->country = $country;
        expect( $person->save())->true();
    }





    public function testPrison()
    {
        $prison = new Prison;
        $company = new Company;
        $company->title = 'old Title';

        $prison->company = $company;
        expect( $prison->save())->true();

        $countItems = \Yii::$app->db->createCommand("SELECT count(*) FROM item")->queryScalar();
        expect( $countItems)->equals(1+2);
        $countOwnableItems = \Yii::$app->db->createCommand("SELECT count(*) FROM ownableitem")->queryScalar();
        expect( $countOwnableItems)->equals(0+2);
        $countIdents = \Yii::$app->db->createCommand("SELECT count(*) FROM ident")->queryScalar();
        expect( $countIdents)->equals(1);
        $countUsers = \Yii::$app->db->createCommand("SELECT count(*) FROM user")->queryScalar();
        expect( $countIdents)->equals(1);

        $oldTitle = $prison->company->title;


        $companyId = $prison->__company_id;
        unset($company, $prison);

        $prison = Prison::findOne($companyId);

      //  $company = $prison->company;
      //  $company->title = "new title";
      //  $prison->company = $company;
       // $prison->getOldRelations();
       // $prison->company->title = "new title";
        //$prison->company = ;
        $data = [
            'Prison'=>[],
            'Company' => ['__ownableitem_id' => $companyId,'title' => 'new title']
        ];

        $prison->load($data);
        expect( $prison->save())->true();
        expect( $prison->company->title)->notEquals($oldTitle);

        $countItems = \Yii::$app->db->createCommand("SELECT count(*) FROM item")->queryScalar();
        expect( $countItems)->equals(1+2);
        $countOwnableItems = \Yii::$app->db->createCommand("SELECT count(*) FROM ownableitem")->queryScalar();
        expect( $countOwnableItems)->equals(0+2);
        $countIdents = \Yii::$app->db->createCommand("SELECT count(*) FROM ident")->queryScalar();
        expect( $countIdents)->equals(1);
        $countUsers = \Yii::$app->db->createCommand("SELECT count(*) FROM user")->queryScalar();
        expect( $countIdents)->equals(1);

    }
    public function testIdent()
    {
        $ident = new Ident;
        //    $ident->item = new Item();;


        expect($ident->save())->true();
        $countItems = \Yii::$app->db->createCommand("SELECT count(*) FROM item")->queryScalar();
        expect( $countItems)->equals(2);
        $countOwnableItems = \Yii::$app->db->createCommand("SELECT count(*) FROM ownableitem")->queryScalar();
        expect( $countOwnableItems)->equals(0);
        $countIdents = \Yii::$app->db->createCommand("SELECT count(*) FROM ident")->queryScalar();
        expect( $countIdents)->equals(2);


    }






        public function testItem()
        {


            $item = new Item;
            expect("item save true", $item->save())->true();
            $countItems = \Yii::$app->db->createCommand("SELECT count(*) FROM item")->queryScalar();
            expect("1 item", $countItems)->equals(2);
            $countOwnableItems = \Yii::$app->db->createCommand("SELECT count(*) FROM ownableitem")->queryScalar();
            expect("0 ownableitem", $countOwnableItems)->equals(0);


        }

        public function testOwnableItem()
        {
            $ownableItem = new Ownableitem;
            $ownableItem->item = new Item();;

            expect($ownableItem->save())->true();
            $countItems = \Yii::$app->db->createCommand("SELECT count(*) FROM item")->queryScalar();
            expect($countItems)->equals(2);
            $countOwnableItems = \Yii::$app->db->createCommand("SELECT count(*) FROM ownableitem")->queryScalar();
            expect("0 ownableitem", $countOwnableItems)->equals(1);


        }



        public function testUser()
        {
            $user = new User;
            $user->username = $this->faker->username();
            $user->email = $this->faker->email();
            $user->password_hash = md5($this->faker->password());
            //$user->ident = Ident::findOne(1);
            $user->ident = new Ident;
            expect($user->save())->true();

            $countItems = \Yii::$app->db->createCommand("SELECT count(*) FROM item")->queryScalar();
            expect( $countItems)->equals(3);
            $countOwnableItems = \Yii::$app->db->createCommand("SELECT count(*) FROM ownableitem")->queryScalar();
            expect( $countOwnableItems)->equals(1);
            $countIdents = \Yii::$app->db->createCommand("SELECT count(*) FROM ident")->queryScalar();
            expect( $countIdents)->equals(2);
            $countUsers = \Yii::$app->db->createCommand("SELECT count(*) FROM user")->queryScalar();
            expect( $countIdents)->equals(2);
        }


        public function testUserFail()
        {
            $this->expectException(IntegrityException::class);
            $user = new User;
            $user->username = $this->faker->username();
            $user->email = $this->faker->email();
            $user->password_hash = md5($this->faker->password());

            $user->ident = Ident::findOne(1);
            //$user->ident = new Ident;

            $user->save();
        }




*/

        public function testPrisoner()
        {
            $prisoner = new \vova07\users\models\Prisoner();
            $person = new Person;
            $prison = $this->createPrison();
            $sector = $this->createPrisonSector($prison);

            $person->first_name = $this->faker->firstNameMale();
            $person->second_name = $this->faker->lastName();
            $person->patronymic = $this->faker->firstNameMale();
            $person->country = Country::findOne(['iso'=>'MD']);
            $person->photo_url = $this->faker->imageUrl(640,480,'people');
            //$person->ident = new Ident();


            $prisoner->person = $person;
            $prisoner->prison = $prison;
            $prisoner->sector = $sector;
            expect($prisoner->validate())->true();
            expect ($prisoner->save())->true();


        }
/*
        public function testDepartment()
        {


            $department1 = new Department();
            $department1->title = $this->faker->company;
            expect($department1->save())->true();

            $company = new Company;
            $company->title = $this->faker->company;
            expect($company->save())->true();


            $department2 = new Department();
            $department2->title = $this->faker->company;

            $company->departments = [$department1, $department2];
            expect($company->save())->true();

        }
        public function testDepartmentPreSavedSuccess()
        {


            $department1 = new Department();
            $department1->title = $this->faker->company;
            expect($department1->save())->true();

            $company = new Company;
            $company->title = $this->faker->company;
            expect($company->save())->true();


            $department2 = new Department();
            $department2->title = $this->faker->company;

            $company->departments = [$department1, $department2];
            expect($company->save())->true();

        }
        public function testDepartmentNewCreatedSuccess()
        {

            $company = new Company;
            $company->title = $this->faker->company;


            $department1 = new Department();
            $department1->title = $this->faker->company;


            $department2 = new Department();
            $department2->title = $this->faker->company;

            $company->departments = [$department1, $department2];
            expect($company->save())->true();

        }

        public function testLoadPrisonShouldSuccess()
        {
            $prison = new Prison;
            $prison->company = new Company;
            $data = [
                'Prison' => [],
                'Company' => [
                    'title' => $this->faker->company
                ]

            ];
            $prison->loadRelations($data);

            expect($prison->save());

        }
        public function testLoadPrisonWithUnsetPrisonDataShouldSuccess()
        {
            $prison = new Prison;
            $prison->company = new Company;
            $data = [

                'Company' => [
                    'title' => $this->faker->company
                ]

            ];
            $prison->loadRelations($data);

            expect($prison->save());

        }
        public function testLoadPrisonLoadTraitShouldSuccess()
        {
            $prison = new Prison;
            $prison->company = new Company;
            $data = [
                'Prison' => [],
                'Company' => [
                    'title' => $this->faker->company
                ]

            ];
            expect($prison->load($data))->true();

            expect($prison->save());


            $data = [
                'Prison' => [],
                'Company' => [
                    'title' => $this->faker->company
                ]

            ];
            expect($prison->load($data))->true();

            expect($prison->save());


        }
        public function testLoadPrisonLoadTraitWithUnsetPrisonShouldTrue()
        {
            $prison = new Prison;
            $prison->company = new Company;
            $data = [
                'Company' => [
                    'title' => $this->faker->company
                ]

            ];
            expect($prison->load($data))->false();

            expect($prison->save())->true();

        }
    */

}
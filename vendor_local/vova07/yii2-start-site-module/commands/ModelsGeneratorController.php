<?php
namespace vova07\site\commands;
use Faker\Factory;
use vova07\base\ModelGenerator\Facade;
use vova07\base\ModelGenerator\Helper;
use vova07\base\ModelGenerator\ModelTableGenerator;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\comments\models\Comment;
use vova07\concepts\models\Concept;
use vova07\concepts\models\ConceptClass;
use vova07\concepts\models\ConceptParticipant;
use vova07\concepts\models\ConceptVisit;
use vova07\countries\models\Country;
use vova07\electricity\models\Device;
use vova07\electricity\models\DeviceAccounting;
use vova07\electricity\models\DeviceAccountingBalance;
use vova07\events\models\Event;
use vova07\events\models\EventParticipant;
use vova07\finances\models\Balance;
use vova07\finances\models\BalanceCategory;
use vova07\humanitarians\models\HumanitarianIssue;
use vova07\humanitarians\models\HumanitarianPrisoner;
use vova07\humanitarians\models\HumanitarianItem;
use vova07\jobs\models\Holiday;
use vova07\jobs\models\JobNotPaid;
use vova07\jobs\models\JobNotPaidType;
use vova07\jobs\models\JobPaid;
use vova07\jobs\models\JobPaidList;
use vova07\jobs\models\JobPaidType;
use vova07\jobs\models\WorkDay;
use vova07\plans\models\PrisonerPlan;
use vova07\prisons\models\Division;
use vova07\prisons\models\Penalty;
use vova07\prisons\models\Post;
use vova07\prisons\models\PostDict;
use vova07\prisons\models\PostDictQuery;
use vova07\prisons\models\Rank;
use vova07\psycho\models\PsyCharacteristic;
use vova07\psycho\models\PsyRisk;
use vova07\psycho\models\PsyRiskQuery;
use vova07\psycho\models\PsyTest;
use vova07\rbac\helpers\Rbac;
use vova07\rbac\Module;
use vova07\salary\models\SalaryCharge;
use vova07\salary\models\SalaryClass;
use vova07\salary\models\SalaryWithHold;
use vova07\users\models\PrisonerLocationJournal;
use vova07\prisons\models\PrisonerSecurity;
use vova07\site\models\Setting;
use vova07\tasks\models\Committee;

use vova07\plans\models\ProgramDict;
use vova07\plans\models\Program;
use vova07\plans\models\ProgramPlan;
use vova07\plans\models\ProgramPrisoner;
use vova07\plans\models\ProgramVisit;
use vova07\plans\models\Requirement;
use vova07\documents\models\Blank;
use vova07\prisons\models\Company;
use vova07\prisons\models\CompanyDepartment;
use vova07\prisons\models\Department;
use vova07\documents\models\Document;
use vova07\documents\models\BlankPrisoner;

use vova07\prisons\models\Prison;

use vova07\prisons\models\Sector;
use vova07\prisons\models\Cell;
use vova07\rbac\rules\OwnRule;
use vova07\users\models\Ident;
use vova07\users\models\Officer;
use vova07\users\models\Person;
use vova07\users\models\Prisoner;
use vova07\users\models\User;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/30/19
 * Time: 5:34 PM
 */

class ModelsGeneratorController extends \yii\console\Controller
{

    public static function getModelClasses()
    {
        return [

/*
            Item::class,
            Ident::class,


            Ownableitem::class,
            Person::class,

            User::class,
            Company::class,
            Department::class,
            CompanyDepartment::class,
            Officer::class,
            Prison::class,
            Sector::class,
            Cell::class,
            Prisoner::class,

            Blank::class,
            BlankPrisoner::class,
            //            Country::class,
            Document::class,


            Event::class,
            EventParticipant::class,
            Committee::class,

            Holiday::class,
            WorkDay::class,



            BalanceCategory::class,
            Balance::class,

            JobPaidType::class,
            JobPaid::class,
            JobPaidList::class,
            JobNotPaidType::class,
            JobNotPaid::class,


            Device::class,
            DeviceAccounting::class,
            Setting::class,

            PrisonerLocationJournal::class,

            HumanitarianIssue::class,
            HumanitarianItem::class,
            HumanitarianPrisoner::class,

            PrisonerSecurity::class,

            ProgramDict::class,
            ProgramPlan::class,
            ProgramPrisoner::class,
            ProgramVisit::class,
            Program::class,
            Requirement::class,

            PsyCharacteristic::class,

            Comment::class,

            Concept::class,
            ConceptParticipant::class,
            ConceptClass::class,
            ConceptVisit::class,
//

            PsyTest::class,


            PrisonerPlan::class,

            DeviceAccountingBalance::class

            Penalty::class
//
           // Division::class ,// move to migration
            \vova07\salary\models\Balance::class,



            Rank::class,
*/


/////// this models are generated in migration
                    //    SalaryClass::class,
                    //   PostDict::class,
                    //   Post::class,
                /*



                     \vova07\salary\models\Rate::class,

                     SalaryCharge::class,
                     SalaryWithHold::class,
            */

        ];
    }

    public function actionGenerate()
    {


        foreach (self::getModelClasses() as $className){
            $modelsGenerated = (new ModelTableGenerator())->generateTable($className);
        }
    }

    public function actionGenerateMakroTemp()
    {
        $this->actionGenerateFirstIdent();
        $this->actionGeneratePrisons();
        $this->actionGenerateSectors();
    }

    private function createUser($attributes,$ident)
    {

            $user = new \vova07\users\models\backend\User();
            $user->scenario = \vova07\users\models\backend\User::SCENARIO_BACKEND_CREATE;
            $user->ident = $ident;
            $user->attributes = $attributes;
            $user->status_id = User::STATUS_ACTIVE;
            $user->save();

    return $user;
    }

    private function createPerson($attributes,$ident)
    {

            $person = new Person;

            $person->country = Country::findOne(['iso'=>'md']);
            $person->ident =  $ident;
            $person->attributes = $attributes;
            $person->save();

        return $person;
    }
    private function createIdent()
    {
        $ident = new Ident;
        $ident->save();
        return $ident;

    }
    public function doLogin($ident){
        \Yii::$app->user->setIdentity($ident);
    }
    public function actionGenerateFirstIdent()
    {
        $ident =  $this->createIdent();
        $this->doLogin($ident);
        $this->createUser(
            [
                    'username' => 'admin',
                    'email' => 'pu1@prison.md',
                    'password' => 'admin12345',
                    'repassword' => 'admin12345',
                    'role' => 'superadmin',
            ],$ident);



    }


    public function actionGenerateFirstIdentOfficer()
    {
        $user = User::findOne(['username' => 'admin']);
        $this->doLogin($user->ident);

        $companyPu1 = Company::findOne(['alias'=>"pu-1"]);
        $departmentSocialReintegration = Department::findOne(['title' => Department::SPECIAL_SOCIAL_REINTEGRATION]);

       /* $this->createOfficer(
            [
                'department_id' => $departmentSocialReintegration->primaryKey,
                'company_id' => $companyPu1->primaryKey
            ], $user->person
        );*/

    }

    public function actionGenerateOfficers()
    {
        $user = User::findOne(['username' => 'admin']);
        $this->doLogin($user->ident);


        $companyPu1 = Company::findOne(['alias'=>"pu-1"]);
        $departmentSocialReintegration = Department::findOne(['title' => Department::SPECIAL_SOCIAL_REINTEGRATION]);
        $departmentAdministration = Department::findOne(['title' => Department::SPECIAL_ADMINISTRATION]);
        $departmentFinance = Department::findOne(['title' => Department::SPECIAL_FINANCE]);
        $departmentLogistic = Department::findOne(['title' => Department::SPECIAL_LOGISTIC]);

        $ident = $this->createIdent();
        $user = $this->createUser([
            'username'=>'chiu',
            'email' => 'chiu@prison.md',
            'password' => 'chiu12345',
            'repassword' => 'chiu12345',
            'role' => 'SocReintegrationDepartmentHead',
        ],$ident);
        $person = $this->createPerson([
            'first_name' => 'Andrey',
            'second_name' => 'Chiulafli',
            'patronymic' => 'Petr',

        ],$ident);
        $officer = $this->createOfficer([
            'department_id' => $departmentSocialReintegration->primaryKey,
            'company_id' => $companyPu1->primaryKey
        ],$person);


        $ident = $this->createIdent();
        $user = $this->createUser([
            'username'=>'iurov',
            'email' => 'iurov@prison.md',
            'password' => 'iurov12345',
            'repassword' => 'iurov12345',
            'role' => 'SocReintegrationDepartmentExpert',
        ],$ident);
        $person = $this->createPerson([
            'first_name' => 'Ivan',
            'second_name' => 'Iurovschi',
            'patronymic' => 'Valeri',

        ],$ident);
        $officer = $this->createOfficer([
            'department_id' => $departmentSocialReintegration->primaryKey,
            'company_id' => $companyPu1->primaryKey
        ],$person);

        $ident = $this->createIdent();
        $user = $this->createUser([
            'username'=>'doicova',
            'email' => 'doicova@prison.md',
            'password' => 'doicova12345',
            'repassword' => 'doicova12345',
            'role' => 'SocReintegrationDepartmentSociologist',
        ],$ident);
        $person = $this->createPerson([
            'first_name' => 'Alina',
            'second_name' => 'Doicova',
            'patronymic' => 'Dmitri',

        ],$ident);
        $officer = $this->createOfficer([
            'department_id' => $departmentSocialReintegration->primaryKey,
            'company_id' => $companyPu1->primaryKey
        ],$person);


        $ident = $this->createIdent();
        $user = $this->createUser([
            'username'=>'cem',
            'email' => 'cem@prison.md',
            'password' => 'cem12345',
            'repassword' => 'cem12345',
            'role' => 'SocReintegrationDepartmentPsychologist',
        ],$ident);
        $person = $this->createPerson([
            'first_name' => 'Angela',
            'second_name' => 'Cemîrtan',
            'patronymic' => 'Iurii',

        ],$ident);
        $officer = $this->createOfficer([
            'department_id' => $departmentSocialReintegration->primaryKey,
            'company_id' => $companyPu1->primaryKey
        ],$person);

        $ident = $this->createIdent();
        $user = $this->createUser([
            'username'=>'yus',
            'email' => 'yus@prison.md',
            'password' => 'yus12345',
            'repassword' => 'yus12345',
            'role' => Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR,
        ],$ident);
        $person = $this->createPerson([
            'first_name' => 'Eugen',
            'second_name' => 'Yusin',
            'patronymic' => 'Anatol',

        ],$ident);
        $officer = $this->createOfficer([
            'department_id' => $departmentSocialReintegration->primaryKey,
            'company_id' => $companyPu1->primaryKey
        ],$person);

        $ident = $this->createIdent();
        $user = $this->createUser([
            'username'=>'bush',
            'email' => 'bush@prison.md',
            'password' => 'bush12345',
            'repassword' => 'bush12345',
            'role' => Module::ROLE_COMPANY_HEAD,
        ],$ident);
        $person = $this->createPerson([
            'first_name' => 'Andrei',
            'second_name' => 'Buşmachiu',
            'patronymic' => 'Ilia',

        ],$ident);
        $officer = $this->createOfficer([
            'department_id' => $departmentAdministration->primaryKey,
            'company_id' => $companyPu1->primaryKey
        ],$person);



        ////
        $ident = $this->createIdent();
        $user = $this->createUser([
            'username'=>'nechit',
            'email' => 'neckit@prison.md',
            'password' => 'nechit12345',
            'repassword' => 'nechit12345',
            'role' => Module::ROLE_FINANCE_DEPARTMENT_EXPERT,
        ],$ident);
        $person = $this->createPerson([
            'first_name' => 'Tatiana',
            'second_name' => 'Nechit',
            'patronymic' => 'Piotr',

        ],$ident);
        $officer = $this->createOfficer([
            'department_id' => $departmentFinance->primaryKey,
            'company_id' => $companyPu1->primaryKey
        ],$person);

        ////
        $ident = $this->createIdent();
        $user = $this->createUser([
            'username'=>'nichif',
            'email' => 'nichif@prison.md',
            'password' => 'nichif12345',
            'repassword' => 'nichif12345',
            'role' => Module::ROLE_LOGISTIC_AND_ADMINISTRATION_DEPARTMENT_EXPERT,
        ],$ident);
        $person = $this->createPerson([
            'first_name' => 'Liudmila',
            'second_name' => 'Nichiforova',
            'patronymic' => 'L',

        ],$ident);
        $officer = $this->createOfficer([
            'department_id' => $departmentLogistic->primaryKey,
            'company_id' => $companyPu1->primaryKey
        ],$person);

        ////
        $ident = $this->createIdent();
        $user = $this->createUser([
            'username'=>'dilgher',
            'email' => 'dilgher@prison.md',
            'password' => 'dilgher12345',
            'repassword' => 'dilgher12345',
            'role' => Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT,
        ],$ident);
        $person = $this->createPerson([
            'first_name' => 'Vitalii',
            'second_name' => 'Dilgher',
            'patronymic' => 'Jacovlevici',

        ],$ident);
        $officer = $this->createOfficer([
            'department_id' => $departmentSocialReintegration->primaryKey,
            'company_id' => $companyPu1->primaryKey
        ],$person);




    }

    private function createOfficer($attributes, $person)
    {
        $officer = new Officer;
        $officer->person = $person;
        $officer->attributes = $attributes;
        $officer->save();
        return $officer;
    }


    public function actionGenerateData()
    {
        $this->actionGenerateFirstIdent();
        $this->actionGeneratePrisons();
        $this->actionGenerateSectors();
      //  $this->actionGeneratePrisoners();
      //  $this->actionGenerateProgramDicts();
        $this->actionGenerateFirstIdentOfficer();
        $this->actionGenerateOfficers();
        $this->actionGenerateSettings();

    }
    public function actionGenerateSettings()
    {
        $user = User::findOne(['username' => 'admin']);
        $ident = $user->ident;
        \Yii::$app->user->setIdentity($ident);
        $setting = new Setting();
        $setting->prison_id = Company::findOne(Company::ID_DEPARTMENT)->primaryKey;
        $setting->{Setting::SETTING_FIELD_ELECTRICITY_KILO_WATT_PRICE} = 1.95;
        $setting->save();

        $setting = new Setting();
        $setting->prison_id = Company::findOne(Company::ID_PRISON_PU1)->primaryKey;
        $setting->{Setting::SETTING_FIELD_COMPANY_DIRECTOR} = User::findOne(['role' => Module::ROLE_COMPANY_HEAD])->primaryKey;
        $setting->{Setting::SETTING_FIELD_ELECTRICITY_KILO_WATT_PRICE} = 1.95;
        $setting->save();
    }

    public function actionGeneratePrisons()
    {
        $user = User::findOne(['username' => 'admin']);
        $ident = $user->ident;
        \Yii::$app->user->setIdentity($ident);

        $department1 = new Department(['title' => Department::SPECIAL_SOCIAL_REINTEGRATION]);
        $department1->save();
        $department2 = new Department(['title' => Department::SPECIAL_FINANCE]);
        $department2->save();
        $department3 = new Department(['title' => Department::SPECIAL_LOGISTIC]);
        $department3->save();
        $department4 = new Department(['title' => Department::SPECIAL_ADMINISTRATION]);
        $department4->save();


        foreach (Company::getList() as $companySlug) {
            $prison = new Prison();
            $prison->company = ['title' => $companySlug,'slug' => $companySlug];

            $prison->save();
            $company = $prison->company;
            $company->departments = [$department1, $department2, $department3, $department4];
            $company->save();
        }


    }

    public function actionGenerateSectors()
    {
        $user = User::findOne(['username' => 'admin']);
        $ident = $user->ident;
        \Yii::$app->user->setIdentity($ident);

        $prison = Prison::findOne(Company::ID_PRISON_PU1);
        $prison->sectors = [
            new Sector(['title' => 'sector 1']),
            new Sector(['title' => 'sector 2']),
            new Sector(['title' => 'sector 3']),
            new Sector(['title' => 'sector 4']),
            new Sector(['title' => 'hoz']),
            new Sector(['title' => 'bloc 1']),
        ];
        $prison->save();





        echo join(',',$prison->getFirstErrors());
        //$sector = new Sector(['title'=>'sector 1']);
        //$sector->prison_id = $prison->__company_id;
        //$sector->save(true);
        //echo join(',',$sector->getFirstErrors());
        //$sector = new Sector(['title'=>'sector 2']);
        //$sector->prison_id = $prison->__company_id;
        //$sector->save(true);
        //echo join(',',$sector->getFirstErrors());

    }
    public function actionGeneratePrisoners()
    {
        $user = User::findOne(['username' => 'admin']);
        $ident = $user->ident;
        \Yii::$app->user->setIdentity($ident);
        $faker = Factory::create('ro_RO');

        for ($i=1;$i<=10;$i++){
        $prisoner = new Prisoner();
        $prisoner->person = new Person;
        $prisoner->person->first_name = $faker->firstNameMale();
        $prisoner->person->second_name = $faker->lastName();
        $prisoner->person->patronymic = $faker->firstNameMale();
      //  $prisoner->person->photo_url = $faker->imageUrl(640,480,'people');;
        $prisoner->person->country = Country::findOne(['iso'=>'md']);
        $prisoner->person->ident = new Ident();

        $prisoner->prison = Prison::findOne(Company::ID_PRISON_PU1);
        $sector = Sector::findOne(['title'=>'sector 1']);
        $prisoner->sector = $sector;
        $prisoner->save();
        }

        echo join(',',$prisoner->getFirstErrors());

    }

    public function actionGenerateProgramDicts()
    {
        $user = User::findOne(['username' => 'admin']);
        $ident = $user->ident;
        \Yii::$app->user->setIdentity($ident);

        $faker = Factory::create('ro_RO');

        for ($i=1;$i<=10;$i++){
            $program = new ProgramDict();
            $program->title = $faker->jobTitle;
            $program->save();
        }
        echo join(',',$program->getFirstErrors());
    }

    public function actionGenerateHumanitarianItems()
    {
        $user = User::findOne(['username' => 'admin']);
        $this->doLogin($user->ident);
        $humanitarianItems = [
            'мыло','хозяыйственное мыло','зубная паста','зубной порошок','туалетная бумага'
        ];
        foreach ($humanitarianItems as $itemTitle){
            $item =  new HumanitarianItem(['title' => $itemTitle]);
            $item->save();
        }

    }
    public function actionTruncateData()
    {

        \Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();
       foreach (self::getModelClasses() as $modelClass){
           \Yii::$app->db->createCommand('DELETE FROM ' . $modelClass::tableName())->execute();

       }

        \Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=1')->execute();
    }
    public function actionDropData()
    {

        Helper::dropTablesForModels(self::getModelClasses());
    }
}
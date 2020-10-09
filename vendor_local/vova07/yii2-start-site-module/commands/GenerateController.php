<?php
namespace vova07\site\commands;
use Faker\Factory;
use vova07\base\ModelGenerator\Facade;
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
use vova07\events\models\Event;
use vova07\events\models\EventParticipant;
use vova07\humanitarians\models\HumanitarianIssue;
use vova07\humanitarians\models\HumanitarianItem;
use vova07\humanitarians\models\HumanitarianPrisoner;
use vova07\jobs\models\Holiday;
use vova07\jobs\models\JobNotPaid;
use vova07\jobs\models\JobNotPaidType;
use vova07\jobs\models\JobPaid;
use vova07\jobs\models\JobPaidList;
use vova07\jobs\models\JobPaidType;
use vova07\jobs\models\WorkDay;
use vova07\plans\models\ProgramDict;
use vova07\plans\models\Program;
use vova07\plans\models\ProgramPlan;
use vova07\plans\models\ProgramPrisoner;
use vova07\plans\models\ProgramVisit;
use vova07\plans\models\Requirement;
use vova07\documents\models\Blank;
use vova07\prisons\models\Cell;
use vova07\prisons\models\Company;
use vova07\prisons\models\CompanyDepartment;
use vova07\prisons\models\Department;
use vova07\documents\models\Document;
use vova07\documents\models\BlankPrisoner;

use vova07\prisons\models\Division;
use vova07\prisons\models\Prison;

use vova07\prisons\models\PrisonerSecurity;
use vova07\prisons\models\Sector;
use vova07\psycho\models\PsyCharacteristic;
use vova07\salary\models\Balance;
use vova07\salary\models\BalanceCategory;
use vova07\site\helpers\GenerateHelper;
use vova07\site\helpers\ImportHelper;
use vova07\site\models\Setting;
use vova07\tasks\models\Committee;
use vova07\users\models\Ident;
use vova07\users\models\Officer;
use vova07\users\models\Person;
use vova07\users\models\Prisoner;
use vova07\users\models\PrisonerLocationJournal;
use vova07\users\models\User;
use yii\console\ExitCode;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/30/19
 * Time: 5:34 PM
 */

class GenerateController extends \yii\console\Controller
{
    public static function getModelClasses()
    {
        return [
            Item::class,
            Ident::class,


            Ownableitem::class,
            Person::class,

            User::class,
            Company::class,
            Division::class,
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
          //  ProgramPlan::class,
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
        ];

    }

    public function actionPrisoners()
    {
        $this->doLogin();
        GenerateHelper::importUsers();

        return ExitCode::OK;

    }


    private function doLogin()
    {

        $user = User::findOne(['username' => 'admin']);
        $ident = $user->ident;
        \Yii::$app->user->setIdentity($ident);
    }

    public function actionGenerateAll()
    {
        $this->actionGenerateFirstIdent();
        $this->actionGeneratePrisons();
        $this->actionGeneratePrisoners();
        return ExitCode::OK;

    }

    public function actionGenerateFirstIdent()
    {
        $ident =  GenerateHelper::createIdent();
        \Yii::$app->user->setIdentity($ident);
        GenerateHelper::createUser(
            [
                'username' => 'admin',
                'email' => 'pu1@prison.md',
                'password' => 'admin12345',
                'repassword' => 'admin12345',
                'role' => 'superadmin',
            ],$ident);

    }

    public function actionGeneratePrisons()
    {
        $this->doLogin();

        $prisonPU1 = new Prison();
        $prisonPU1->company = ['title' => Company::getList()[0],'slug' => Company::getList()[0]];
        $prisonPU1->save();
    }

    public function actionGeneratePrisoners()
    {
       $this->doLogin();
        $faker = Factory::create('ro_RO');

        for ($i=1;$i<=10;$i++){
            $prisoner = new Prisoner();
            $prisoner->person = new Person;
            $prisoner->person->first_name = $faker->firstNameMale();
            $prisoner->person->second_name = $faker->lastName();
            $prisoner->person->patronymic = $faker->firstNameMale();
            //  $prisoner->person->photo_url = $faker->imageUrl(640,480,'people');;
          //  $prisoner->person->country = Country::findOne(['iso'=>'md']);
            $prisoner->person->ident = new Ident();

            $prisoner->prison = Company::find()->andWhere(['alias' => Company::PRISON_PU1])->one()->prison;
            //$sector = Sector::findOne(['title'=>'sector 1']);
            //$prisoner->sector = $sector;
            $prisoner->status_id = Prisoner::STATUS_ACTIVE;
            $prisoner->save();
            echo $prisoner->person->fio;

        }

        echo join(',',$prisoner->getFirstErrors());

    }
    public function actionTruncateData()
    {

        \Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();
        foreach (self::getModelClasses() as $modelClass){
            \Yii::$app->db->createCommand('DELETE FROM ' . $modelClass::tableName())->execute();

        }
        \Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=1')->execute();
    }


}
<?php
namespace vova07\site\commands;
use Faker\Factory;
use vova07\base\ModelGenerator\Facade;
use vova07\base\ModelGenerator\ModelTableGenerator;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\plans\models\Event;
use vova07\plans\models\EventParticipant;
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
use vova07\site\helpers\ImportHelper;
use vova07\users\models\Ident;
use vova07\users\models\Person;
use vova07\users\models\Prisoner;
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

class ImportController extends \yii\console\Controller
{
    public function actionPrisoners($limit=null,$prison_slug = 'pu-1' )
    {
        $user = User::findOne(['username' => 'admin']);
        $ident = $user->ident;
        \Yii::$app->user->setIdentity($ident);

        ImportHelper::ImportPrisoners($prison_slug, true, $limit );

        return ExitCode::OK;

    }

    public function actionPrograms()
    {
        $user = User::findOne(['username' => 'admin']);
        $ident = $user->ident;
        \Yii::$app->user->setIdentity($ident);

        ImportHelper::ImportPrograms();

        return ExitCode::OK;

    }
    public function actionProgramPrisoner($prison_slug = 'pu-1')
    {
        $user = User::findOne(['username' => 'admin']);
        $ident = $user->ident;
        \Yii::$app->user->setIdentity($ident);

        ImportHelper::ImportProgramPrisoner($prison_slug = 'pu-1');

        return ExitCode::OK;

    }
    public function actionDocuments()
    {
        $user = User::findOne(['username' => 'admin']);
        $ident = $user->ident;
        \Yii::$app->user->setIdentity($ident);

        ImportHelper::ImportDocuments();

        return ExitCode::OK;
    }
    public function actionBalance()
    {
        $this->doLogin();
        ImportHelper::ImportBalance();
        return ExitCode::OK;
    }
    public function actionBalanceCategory()
    {
        $this->doLogin();
        ImportHelper::ImportBalanceCategories();
        return ExitCode::OK;
    }
    public function actionJobs()
    {
        $this->doLogin();
        ImportHelper::ImportJobs();
        return ExitCode::OK;
    }

    public function actionNotPaidJobsTypes()
    {
        $this->doLogin();
        ImportHelper::ImportNotPaidJobsTypes();
        return ExitCode::OK;
    }


    public function actionUserJobs()
    {
        $this->doLogin();
        ImportHelper::ImportUserJobs();
        return ExitCode::OK;
    }


    public function actionTest()
    {
        //https://media1.prime.md/hls/202002/162259/360p199.ts
        $str = '';
        $baseUrl = "https://media1.prime.md/hls/202002/162259/";
        for ($i = 0; $i<360; $i++){
            $url = $baseUrl . '360p' . $i . '.ts';
            $str .= $url . "\n";
        }

        echo $str;

    }

    private function doLogin()
    {

        $user = User::findOne(['username' => 'admin']);
        $ident = $user->ident;
        \Yii::$app->user->setIdentity($ident);
    }



}
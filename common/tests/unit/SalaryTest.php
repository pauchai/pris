<?php namespace common\tests\unit;

use common\fixtures\CompanyDepartmentFixture;
use common\fixtures\CompanyFixture;
use common\fixtures\CountryFixture;
use common\fixtures\DepartmentFixture;
use common\fixtures\DeviceAccountingFixture;
use common\fixtures\DeviceFixture;
use common\fixtures\DivisionFixture;
use common\fixtures\IdentFixture;
use common\fixtures\ItemFixture;
use common\fixtures\OfficerFixture;
use common\fixtures\OfficerPostFixture;
use common\fixtures\OwnableitemFixture;
use common\fixtures\PersonFixture;
use common\fixtures\PostDictFixture;
use common\fixtures\PostFixture;
use common\fixtures\PostIsoFixture;
use common\fixtures\PrisonerFixture;
use common\fixtures\PrisonerLocationJournalFixture;
use common\fixtures\PrisonFixture;
use common\fixtures\ProgramDictFixture;
use common\fixtures\ProgramFixture;
use common\fixtures\ProgramPrisonerFixture;
use common\fixtures\ProgramVisitFixture;
use common\fixtures\RankFixture;
use common\fixtures\SalaryClassFixture;
use common\fixtures\SalaryFixture;
use common\fixtures\SalaryIssueFixture;
use common\fixtures\SalaryWithHoldFixture;
use common\fixtures\SectorFixture;
use common\fixtures\SettingFixture;
use common\fixtures\UserFixture;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Count;
use PHPUnit\Framework\TestResult;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Facade;
use vova07\base\models\Ownableitem;

use vova07\countries\models\Country;
use vova07\electricity\models\Device;
use vova07\electricity\models\DeviceAccounting;
use vova07\jobs\helpers\Calendar;
use vova07\plans\models\Program;
use vova07\plans\models\ProgramDict;
use vova07\plans\models\ProgramPrisoner;
use vova07\plans\models\ProgramVisit;
use vova07\prisons\models\Company;
use vova07\prisons\models\Department;
use vova07\prisons\models\Division;
use vova07\prisons\models\DivisionDict;
use vova07\prisons\models\OfficerPost;
use vova07\prisons\models\Rank;
use vova07\salary\models\SalaryIssue;
use vova07\users\models\Officer;
use vova07\prisons\models\Post;
use vova07\prisons\models\PostDict;
use vova07\prisons\models\PostIso;
use vova07\prisons\models\Prison;

use vova07\salary\models\Salary;
use vova07\users\models\PrisonerLocationJournal;
use vova07\prisons\models\Sector;
use vova07\rbac\Module;
use vova07\site\models\Setting;
use vova07\users\models\Ident;
use vova07\base\models\Item;
use vova07\users\models\Person;
use vova07\users\models\User;
use yii\db\IntegrityException;


class SalaryTest extends \Codeception\Test\Unit
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
            'country' => [
                'class' => CountryFixture::class,
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
            'ranks' => [
                'class' => RankFixture::class,
            ],
            'salary_class' => [
                'class' => SalaryClassFixture::class,
            ],
            'post_iso' => [
                'class' => PostIsoFixture::class
            ],
            'post_dicts' => [
                'class' => PostDictFixture::class
            ],
            'divisions' => [
                'class' => DivisionFixture::class,
            ],
            'posts' => [
                'class' => PostFixture::class,
            ],
            'officer' => [
                'class' => OfficerFixture::class
            ],
            'officer_posts' => [
                'class' => OfficerPostFixture::class
            ],
            'salary_issue' => [
                'class' => SalaryIssueFixture::class
            ],
            'salary' => [
                'class' => SalaryFixture::class
            ],
            'salary_withhold' => [
                'class' => SalaryWithHoldFixture::class
            ]


        ]);


        $adminUser = $this->tester->grabFixture('users', 'admin');
        \Yii::$app->user->login($adminUser);
        $this->faker = \Faker\Factory::create('ro_RO');


        $this->generateFixtures();
    }

    protected function _after()
    {
       // $this->generateFixtures();
    }

    public function testSalary()
    {
         $year = 2020;
         $month_no = 3;
         $firstDayDate  = (new \DateTime())->setDate($year, $month_no, 1);
         $officer = $this->fixtures['officerYusin'];
        /**
         * @var $officer Officer
         */
         expect($officer->getOfficerPosts()->count())->equals(1);
         $salaryIssue = new SalaryIssue([
             'year' => $year,
             'month_no' => $month_no,
         ]);
        expect($salaryIssue->save())->true();

        $salary = $salaryIssue->createSalaryForOfficerPost($this->fixtures['officerPost_YusinOfficerPrincipal'],
            [
                'work_days' => Calendar::getMonthDaysNumber($firstDayDate)
            ]);

        $salary->reCalculate();

        $salary->setAttributes([
            'amount_optional' => 0,
            'amount_diff_sallary' => 0,
            'amount_additional' => 0,
            'amount_maleficence' => 0,
            'amount_vacation' => 0 ,
            'amount_sick_list' => 0,
            'amount_bonus' => 0
        ]);
        expect($salary->calculateTotal())->equals(9474);



        $salary = $salaryIssue->createSalaryForOfficer( $officer,[
            'work_days' => Calendar::getMonthDaysNumber($firstDayDate),
              'base_rate' => 6980,
             'rank_rate' => 400,
            'full_time' => true,


        ]);
        $salary->reCalculate();

        $salary->setAttributes([
            'amount_optional' => 0,
            'amount_diff_sallary' => 0,
            'amount_additional' => 0,
            'amount_maleficence' => 0,
            'amount_vacation' => 0 ,
            'amount_sick_list' => 0,
            'amount_bonus' => 0
        ]);

        expect($salary->calculateTotal())->equals(9474);

         expect($salaryIssue->save())->true();
            $salary = $salaryIssue->createSalaryForOfficer( $officer,[
                'amount_rate' => 6980,
                'amount_rank_rate' => 400 ,
                'amount_conditions' => 1396,
                'amount_advance' => 698,

                'amount_optional' => 0,
                'amount_diff_sallary' => 0,
                'amount_additional' => 0,
                'amount_maleficence' => 0,
                'amount_vacation' => 0 ,
                'amount_sick_list' => 0,
                'amount_bonus' => 0
            ]);

            expect($salary->calculateTotal())->equals(9474);
            codecept_debug($salary);

            $withHold = $salaryIssue->createWithHoldForOfficer($officer,[
                'is_pension' => true,
                'amount_income_tax' => 0,
                'amount_execution_list' => 0,
                'amount_sick_list' => 0,
            ]);



       // $salary = $officer->createSalary($year, $monthNo);
       // $salary->recalculateTotal();


        //$withHold = $officer->createWithHold($year, $monthNo);
        //$withHold->recalculateTotal();


    }

    public function testSalaryWithHold()
    {
        $year = 2020;
        $month_no = 3;
        $firstDayDate  = (new \DateTime())->setDate($year, $month_no, 1);
        $officer = $this->fixtures['officerYusin'];
        /**
         * @var $officer Officer
         */
        expect($officer->getOfficerPosts()->count())->equals(1);
        $salaryIssue = new SalaryIssue([
            'year' => $year,
            'month_no' => $month_no,
        ]);
        expect($salaryIssue->save())->true();

        $salary = $salaryIssue->createSalaryForOfficerPost($this->fixtures['officerPost_YusinOfficerPrincipal'],
            [
                'work_days' => Calendar::getMonthDaysNumber($firstDayDate)
            ]);

        $salary->reCalculate();

        $salary->setAttributes([
            'amount_optional' => 0,
            'amount_diff_sallary' => 0,
            'amount_additional' => 0,
            'amount_maleficence' => 0,
            'amount_vacation' => 0 ,
            'amount_sick_list' => 0,
            'amount_bonus' => 0
        ]);
        expect($salary->calculateTotal())->equals(9474);
        expect($salary->save())->true();


        $salary = $salaryIssue->createSalaryForOfficer( $officer,[
            'amount_rate' => 6980,
            'amount_rank_rate' => 400 ,
            'amount_conditions' => 1396,
            'amount_advance' => 698,

            'amount_optional' => 0,
            'amount_diff_sallary' => 0,
            'amount_additional' => 0,
            'amount_maleficence' => 0,
            'amount_vacation' => 0 ,
            'amount_sick_list' => 0,
            'amount_bonus' => 0
        ]);
        expect($salary->calculateTotal())->equals(9474);
        expect($salary->save())->true();


        $withHold = $salaryIssue->createWithHoldForOfficer($officer,[
            'is_pension' => true,
            'amount_income_tax' => 0,
            'amount_execution_list' => 0,
            'amount_sick_list' => 0,
        ]);
        $withHold->reCalculate();
        expect($withHold->getTotal())->equals(568.44);
        expect($withHold->calculateAmountCard())->equals(8905.56);


        // $salary = $officer->createSalary($year, $monthNo);
        // $salary->recalculateTotal();


        //$withHold = $officer->createWithHold($year, $monthNo);
        //$withHold->recalculateTotal();


    }

    private function generateFixtures()
    {

      $prison1 = new Prison;
      $prison1->company = ['title' => 'prison1'];
      expect($prison1->save())->true();
      $this->fixtures['prison1'] = $prison1;

      $divisionDict = new DivisionDict(['id' => DivisionDict::ID_DIVISION_SOCIAL_REINTEGRATION ]);
      $division = new Division([
         'company_id' => $prison1->primaryKey,
         'division_id' => $divisionDict->id,
          'title' => $divisionDict->getTitle(),
      ]);
      expect($division->save())->true();
        $this->fixtures['division_reintegration'] = $division;

        $postDictOfficerPrincipal = $this->tester->grabFixture('post_dicts', 2);

        $post = new Post();
        $post->company_id = $division->company_id;
        $post->division_id = $division->division_id;
        $post->postdict_id = $postDictOfficerPrincipal->primaryKey;
        $post->title = 'officer principal';
        expect($post->save())->true();

        $rankCommisarJustic = $this->tester->grabFixture('ranks', 4);

        $officer = new Officer();
        $data = [

            'Person' => [
                'first_name' => 'eugen',
                'second_name' => 'yusin',
                'patronymic' => 'anatol'

            ],
            'Officer' => [
                'company_id' => $division->company_id,
                'rank_id' => $rankCommisarJustic->primaryKey,
                'member_labor_union' => true,
                'has_education' => true,

            ]
        ];
        $officer->load($data);
        $officer->person->ident = new Ident();
        expect($officer->save())->true();
        $this->fixtures['officerYusin'] = $officer;

        $officerPost = new OfficerPost([
            'officer_id' => $officer->primaryKey
        ]);
        $officerPost->link('post',$post);
        $this->fixtures['officerPost_YusinOfficerPrincipal'] = $officerPost;



    }


}
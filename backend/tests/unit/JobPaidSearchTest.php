<?php namespace backend\tests\unit;

use common\fixtures\UserFixture;
use vova07\base\ModelsFactory;
use vova07\jobs\models\backend\JobPaidSearch;
use vova07\users\models\LoginForm;
use vova07\users\models\User;




class JobPaidSearchTest extends  \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;


    public function _fixtures()
    {
       return [
      //      'users' => [
       //         'class' => UserFixture::className(),
      //          'dataFile' => codecept_data_dir() . 'login_data.php'
      //      ]
        ];
    }
    protected function _before()
    {

    }

    protected function _after()
    {
    }

    // tests
    public function testDefaultValues()
    {

        $jobPaidSearch = new JobPaidSearch();
        $jobPaidSearch->validate();

        expect($jobPaidSearch->yearMonth)->equals(\Yii::$app->formatter->asDate(time(),'Y-M-01'));
        expect($jobPaidSearch->month_no)->equals(\Yii::$app->formatter->asDate(time(),'M'));
        expect($jobPaidSearch->year)->equals(\Yii::$app->formatter->asDate(time(),'Y'));

        $jobPaidSearch = new JobPaidSearch();


        $jobPaidSearch->yearMonth = "2019-05-01";
        $jobPaidSearch->validate();
        expect($jobPaidSearch->month_no)->equals('5');
        expect($jobPaidSearch->year)->equals('2019');

        $jobPaidSearch->yearMonth = "2019-05-01";
        $jobPaidSearch->month_no = 6;
        $jobPaidSearch->year = 2019;
        $jobPaidSearch->validate();
        expect($jobPaidSearch->month_no)->equals('6');
        expect($jobPaidSearch->year)->equals('2019');


    }






}
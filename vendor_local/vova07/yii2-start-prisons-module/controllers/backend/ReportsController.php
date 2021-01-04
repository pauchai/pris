<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\prisons\controllers\backend;


use lajax\translatemanager\models\Language;
use vova07\base\components\BackendController;
use vova07\plans\models\ProgramPrisoner;
use vova07\prisons\models\backend\CompanyDepartmentSearch;
use vova07\prisons\models\backend\CompanySearch;
use vova07\prisons\models\backend\DivisionSearch;
use vova07\prisons\models\backend\PostSearch;
use vova07\prisons\models\backend\PrisonSearch;
use vova07\prisons\models\backend\RankSearch;
use vova07\prisons\models\backend\ReportPrisonerProgramSearch;
use vova07\prisons\models\backend\ReportSummarizedSearch;
use vova07\prisons\models\Company;
use vova07\prisons\models\CompanyDepartment;
use vova07\prisons\models\Division;
use vova07\prisons\models\OfficerPost;
use vova07\prisons\models\Post;
use vova07\prisons\models\Prison;
use vova07\prisons\models\Rank;
use vova07\prisons\Module;
use vova07\users\models\Prisoner;
use vova07\users\models\PrisonerLocationJournalView;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ReportsController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index', 'participants', 'programs-prisoners'],
                'roles' => ['@']
            ]
        ];
        return $behaviors;
    }


    public function actionIndex()
    {
        $searchModel = new ReportSummarizedSearch();
        $searchModel->applyFilter();

        $dataProvider = new ActiveDataProvider(
            ['query' => (new Query())
                ->select([
                    'prev_prison_id' => 'prev.prison_id',
                    'prev_sector_id' =>'prev.sector_id',


                    'cnt_prev_prison_id' => 'count(prev.prison_id)' ,
                    'cnt_prison_id' => 'count(c.prison_id)',
                    'cnt_prev_sector_id' => 'count(prev.sector_id)',
                    'cnt_sector_id' => 'count(c.sector_id)'
                ])
                ->from(PrisonerLocationJournalView::tableName() .' c')
                ->leftJoin(PrisonerLocationJournalView::tableName() . ' prev', 'prev.id = c.id')
                ->groupBy(['prev.prison_id',  'prev.sector_id' , 'c.prison_id', 'c.sector_id'])
            ]
        );


        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'locationProvider' => $searchModel->getFromToLocationProvider(),
            'terminateProvider' => $searchModel->getTerminateProvider(),
            'programProvider' => $searchModel->getProgramsProvider(),
            'conceptsProvider' => $searchModel->getConceptsProvider(),
            'eventsProvider' => $searchModel->getEventsProvider(),
            'jobsProvider' => $searchModel->getJobsProvider(),
            'committeeProvider' => $searchModel->getCommitteeProvider(),
            'searchModel' => $searchModel
        ]);
    }

    public function actionParticipants()
    {
        $selectMethods = [
            'getFromSectorQuery' =>['j.prisoner_id'],
            'getToSectorQuery' => ['j.prisoner_id'],
            'getToPrisonQuery' => ['j.prisoner_id'],
            'getFromPrisonQuery' => ['j.prisoner_id'],
            'getTerminateQuery' => ['j.prisoner_id'],
            'getProgramsQuery' => ['pp.prisoner_id'],
            'getConceptsQuery' => ['cp.prisoner_id'],
            'getEventsQuery' => ['ep.prisoner_id'],
            'getJobsQuery' => ['j.prisoner_id']



        ];

        $method = \Yii::$app->request->get('method');
        if (!key_exists($method, $selectMethods))
            throw new \LogicException("method not allowed");

        $arg =  \Yii::$app->request->get('arg',[]);

        $searchModel = new ReportSummarizedSearch();
        $searchModel->applyFilter();
        $dataProvider = new ActiveDataProvider([
            'query' => Prisoner::find()->where([
                        '__person_id' => $searchModel->$method(...$arg)->select($selectMethods[$method])
                    ])
        ]);


        return $this->render('participants', ['dataProvider' => $dataProvider]);

    }

    public function actionPrisonersPrograms()
    {
        $searchModel = new ReportPrisonerProgramSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        return $this->view('prisoners-programs',['dataProvider' => $dataProvider, 'searchModel' => $searchModel]);
    }





}
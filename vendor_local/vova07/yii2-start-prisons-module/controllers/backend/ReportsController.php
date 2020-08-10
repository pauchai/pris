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
use vova07\prisons\models\backend\ReportSummarizedSearch;
use vova07\prisons\models\Company;
use vova07\prisons\models\CompanyDepartment;
use vova07\prisons\models\Division;
use vova07\prisons\models\OfficerPost;
use vova07\prisons\models\Post;
use vova07\prisons\models\Prison;
use vova07\prisons\models\Rank;
use vova07\prisons\Module;
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
                'actions' => ['index'],
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





}
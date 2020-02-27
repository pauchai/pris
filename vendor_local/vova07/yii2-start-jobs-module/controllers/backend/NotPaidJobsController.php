<?php
namespace vova07\jobs\controllers\backend;
use http\Env\Url;
use vova07\base\components\BackendController;
use vova07\events\models\Event;
use vova07\events\Module;
use vova07\humanitarians\models\backend\HumanitarianPrisonerSearch;
use vova07\humanitarians\models\HumanitarianPrisoner;
use vova07\jobs\models\backend\JobNotPaidSearch;
use vova07\jobs\models\backend\JobPaidSearch;
use vova07\jobs\models\Holiday;
use vova07\jobs\models\JobNotPaid;
use vova07\jobs\models\JobNotPaidForm;
use vova07\jobs\models\JobPaid;
use vova07\jobs\models\JobPaidForm;
use vova07\jobs\models\WorkDay;
use vova07\tasks\models\backend\CommitteeSearch;
use vova07\tasks\models\Committee;
use vova07\users\models\backend\PrisonerViewSearch;
use vova07\users\models\backend\User;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class NotPaidJobsController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index', 'index-print','table-individual-report'],
                'roles' => [\vova07\rbac\Module::PERMISSION_NOT_PAID_JOBS_LIST]
            ],
            [
                'allow' => true,
                'actions' => ['create'],
                'roles' => [\vova07\rbac\Module::PERMISSION_NOT_PAID_JOB_CREATE]
            ],
            [
                'allow' => true,
                'actions' => ['update'],
                'roles' => [\vova07\rbac\Module::PERMISSION_NOT_PAID_JOB_UPDATE]
            ],
            [
                'allow' => true,
                'actions' => ['delete'],
                'roles' => [\vova07\rbac\Module::PERMISSION_NOT_PAID_JOB_DELETE]
            ],
            [
                'allow' => true,
                'actions' => ['view'],
                'roles' => [\vova07\rbac\Module::PERMISSION_NOT_PAID_JOB_VIEW]
            ],
            [
                'allow' => true,
                'actions' => ['toggle-date'],
                'roles' => ['@']//[Module::PERMISSION_PROGRAM_PLANING_VIEW]
            ],

            [
                'allow' => true,
                'actions' => ['copy-from-last-month'],
                'roles' => ['@']//[Module::PERMISSION_PROGRAM_PLANING_VIEW]
            ],


        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        \Yii::$app->user->returnUrl = \yii\helpers\Url::current();

        $searchModel = new JobNotPaidSearch();
        //$year = $year ?? (new \DateTime())->format('Y');
        //$month_no = $month_no ?? (new \DateTime())->format('n');
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        if (!\Yii::$app->user->can(\vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR))
            $dataProvider->query->ownedByCurrentUser();

        $dataProvider->pagination->pageSize = 0;
        //$dataProvider->query->andFilterWhere(compact(['year', 'month_no']));

        if ($post = \Yii::$app->request->post()) {
            $jobs = $dataProvider->query->indexBy('__ownableitem_id')->all();
            if (JobNotPaid::loadMultiple($jobs, $post) && JobNotPaid::validateMultiple($jobs)) {
                foreach ($jobs as $job) {
                    if (!$job->save(false)){
                        throw new \LogicException();
                    };
                }
            }


        };

        return $this->render("index", ['dataProvider' => $dataProvider, 'searchModel'=>$searchModel]);
    }

    public function actionIndexPrint()
    {
        $this->isPrintVersion = true;
        $this->layout = '@vova07/themes/adminlte2/views/layouts/print.php';


        $searchModel = new JobNotPaidSearch();

        //$year = $year ?? (new \DateTime())->format('Y');
        //$month_no = $month_no ?? (new \DateTime())->format('n');
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        if (!\Yii::$app->user->can(\vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR))
            $dataProvider->query->ownedByCurrentUser();

        $dataProvider->pagination->pageSize = 0;
        //$dataProvider->query->andFilterWhere(compact(['year', 'month_no']));



        return $this->render("index-print", ['dataProvider' => $dataProvider, 'searchModel'=>$searchModel]);
    }
    public function actionTableIndividualReport()
    {
        $this->isPrintVersion = true;
        $this->layout = '@vova07/themes/adminlte2/views/layouts/print.php';


        $searchModel = new JobNotPaidSearch();
        //$year = $year ?? (new \DateTime())->format('Y');
        //$month_no = $month_no ?? (new \DateTime())->format('n');
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        $dataProvider->query->ownedByCurrentUser();

        $dataProvider->pagination->pageSize = 0;
        //$dataProvider->query->andFilterWhere(compact(['year', 'month_no']));



        return $this->render("table-individual-report", ['dataProvider' => $dataProvider, 'searchModel'=>$searchModel]);
    }

    public function actionCloneList()
    {

    }

    public function actionCreate($prison_id, $year, $month_no)
    {

        $model = new JobNotPaidForm();
        $model->prison_id = $prison_id;
        $model->year = $year;
        $model->month_no = $month_no;


        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){
                return $this->goBack();
            } else {
                \Yii::$app->session->setFlash('error',join("<br/>" ,$model->getFirstErrors()));
            }
        }
        return $this->render("create", ['model' => $model]);
    }

    public function actionView($id)
    {
        if (is_null($model = JobNotPaid::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t("ITEM_NOT_FOUND"));
        };

        return $this->render('view', ['model'=>$model]);
    }
    public function actionDelete($id)
    {
        if (is_null($model = JobNotPaid::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        if ($model->delete()){
            return $this->goBack();
        };
        throw new \LogicException(Module::t('default',"CANT_DELETE"));
    }

    public function actionUpdate($id)
    {
        if (is_null($model = JobNotPaid::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()){
                if ($model->save()){
                    return $this->goBack();
                };
            };
        }

        return $this->render("update", ['model' => $model,'cancelUrl' => ['index']]);
    }

    public function actionToggleDate()
    {
        $date = \Yii::$app->request->post('day_date');

        $workDay = WorkDay::findOne($date);
        if ($workDay) {
            $workDay->delete();
        } else {

            $holiday = Holiday::findOne($date);

            if (!$holiday) {
                $holiday = new Holiday();
                $holiday->day_date = $date;
                $holiday->save();
            } else {
                $workDay = new WorkDay();
                $workDay->day_date = $date;
                $workDay->save();
            }


        }
        return $this->redirect(\Yii::$app->request->referrer);
    }
    public function actionCopyFromLastMonth($prison_id, $year, $month_no)
    {
        $dateTime = (new \DateTime)->setDate($year,$month_no,1)->modify('first day of previous month');
        $prevYear = $dateTime->format('Y');
        $prevMonthNo = $dateTime->format('m');
        $models = JobNotPaid::findAll(['prison_id'=>$prison_id,'year'=>$prevYear,'month_no'=>$prevMonthNo]);
        if ($models){
            foreach ($models as $model)
            {
              $newModel = new JobNotPaid();
              $newModel->attributes = $model->getAttributes(['prison_id','prisoner_id','type_id']);
              $newModel->year = $year;
              $newModel->month_no = $month_no;
              $newModel->save(true);
            }

        }
        $searchModel = new JobNotPaidSearch();
        $searchModel->attributes = compact(["prison_id","year","month_no"]);


        $redirectUrl[$searchModel->formName()] = compact(["prison_id","year","month_no"]);
        $redirectUrl[0] = 'index';
       return $this->redirect($redirectUrl);

    }

}
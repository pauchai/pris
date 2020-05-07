<?php
namespace vova07\jobs\controllers\backend;
use http\Env\Url;
use vova07\base\components\BackendController;
use vova07\events\models\Event;
use vova07\events\Module;
use vova07\humanitarians\models\backend\HumanitarianPrisonerSearch;
use vova07\humanitarians\models\HumanitarianPrisoner;
use vova07\jobs\models\backend\JobPaidSearch;
use vova07\jobs\models\Holiday;
use vova07\jobs\models\JobPaid;
use vova07\jobs\models\JobPaidForm;
use vova07\jobs\models\JobPaidList;
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

class DefaultController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index' ,'index-print'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PAID_JOBS_LIST]
            ],
            [
                'allow' => true,
                'actions' => ['create'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PAID_JOB_CREATE]
            ],
            [
                'allow' => true,
                'actions' => ['update'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PAID_JOB_UPDATE]
            ],
            [
                'allow' => true,
                'actions' => ['delete'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PAID_JOB_DELETE]
            ],
            [
                'allow' => true,
                'actions' => ['view'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PAID_JOB_VIEW]
            ],
            [
                'allow' => true,
                'actions' => ['toggle-date'],
                'roles' => ['@']//[Module::PERMISSION_PROGRAM_PLANING_VIEW]
            ],

            [
                'allow' => true,
                'actions' => ['create-tabular',],
                'roles' => ['@']//[Module::PERMISSION_PROGRAM_PLANING_VIEW]
            ],
            [
                'allow' => true,
                'actions' => ['index-orders-report', 'index-certificats-report'],
                'roles' => ['@']//[Module::PERMISSION_PROGRAM_PLANING_VIEW]
            ],



        ];
        return $behaviors;
    }

    public function actionIndex()
    {

        $searchModel = new JobPaidSearch();
        //$year = $year ?? (new \DateTime())->format('Y');
        //$month_no = $month_no ?? (new \DateTime())->format('n');
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        $dataProvider->pagination->pageSize = 30;
        //$dataProvider->query->andFilterWhere(compact(['year', 'month_no']));
        \Yii::$app->user->returnUrl = \yii\helpers\Url::current();
        if ($post = \Yii::$app->request->post()) {
            $jobs = $dataProvider->query->indexBy('__ownableitem_id')->all();
            if (JobPaid::loadMultiple($jobs, $post) && JobPaid::validateMultiple($jobs)) {
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

        $searchModel = new JobPaidSearch();
        //$year = $year ?? (new \DateTime())->format('Y');
        //$month_no = $month_no ?? (new \DateTime())->format('n');
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        $dataProvider->pagination = false;
        //$dataProvider->query->andFilterWhere(compact(['year', 'month_no']));
        \Yii::$app->user->returnUrl = \yii\helpers\Url::current();

        $director = User::findOne(['role' => \vova07\rbac\Module::ROLE_COMPANY_HEAD])->officer;
        $sefSLA = User::findOne(['role' => \vova07\rbac\Module::ROLE_LOGISTIC_AND_ADMINISTRATION_DEPARTMENT_HEAD])->officer;


        return $this->render("index-print", [
            'dataProvider' => $dataProvider, 'searchModel'=>$searchModel,
            'director' => $director,
            'sefSLA' => $sefSLA
        ]);
    }

    public function actionIndexOrdersReport()
    {
        /**
         * @TODO fix for spellout formatter
         */
        $oldSourceLanguage = \Yii::$app->sourceLanguage;
        \Yii::$app->sourceLanguage = 'ro-RO';
        $searchModel = new JobPaidSearch();
        //$year = $year ?? (new \DateTime())->format('Y');
        //$month_no = $month_no ?? (new \DateTime())->format('n');
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        $dataProvider->pagination->pageSize = 0;
        //$dataProvider->query->andFilterWhere(compact(['year', 'month_no']));

        if ($post = \Yii::$app->request->post()) {
            $jobs = $dataProvider->query->indexBy('__ownableitem_id')->all();
            if (JobPaid::loadMultiple($jobs, $post) && JobPaid::validateMultiple($jobs)) {
                foreach ($jobs as $job) {
                    if (!$job->save(false)){
                        throw new \LogicException();
                    };
                }
            }


        };

        $director = User::findOne(['role' => \vova07\rbac\Module::ROLE_COMPANY_HEAD])->officer;
        $educator = User::findOne(['role' => \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR])->officer;

        $res =  $this->render("index-orders-report", [
            'dataProvider' => $dataProvider,
            'searchModel'=>$searchModel,
            'director' => $director,
            'educator' => $educator,
        ]);
        \Yii::$app->sourceLanguage = $oldSourceLanguage;
        return $res;
    }
    public function actionIndexCertificatsReport()
    {
        /**
         * @TODO fix for spellout formatter
         */
        $oldSourceLanguage = \Yii::$app->sourceLanguage;
        \Yii::$app->sourceLanguage = 'ro-RO';
        $searchModel = new JobPaidSearch();
        //$year = $year ?? (new \DateTime())->format('Y');
        //$month_no = $month_no ?? (new \DateTime())->format('n');
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        $dataProvider->pagination->pageSize = 0;
        //$dataProvider->query->andFilterWhere(compact(['year', 'month_no']));

        if ($post = \Yii::$app->request->post()) {
            $jobs = $dataProvider->query->indexBy('__ownableitem_id')->all();
            if (JobPaid::loadMultiple($jobs, $post) && JobPaid::validateMultiple($jobs)) {
                foreach ($jobs as $job) {
                    if (!$job->save(false)){
                        throw new \LogicException();
                    };
                }
            }


        };

        $sefSLA = User::findOne(['role' => \vova07\rbac\Module::ROLE_LOGISTIC_AND_ADMINISTRATION_DEPARTMENT_HEAD])->officer;
        $educator = User::findOne(['role' => \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR])->officer;
        $sefSF = User::findOne(['role' => \vova07\rbac\Module::ROLE_FINANCE_DEPARTMENT_HEAD])->officer;

        $res =  $this->render("index-certificats-report", [
            'dataProvider' => $dataProvider, 'searchModel'=>$searchModel,
            'sefSLA' => $sefSLA,
            'educator' => $educator,
            'sefSF' => $sefSF
        ]);
        \Yii::$app->sourceLanguage = $oldSourceLanguage;
        return $res;
    }
    public function actionCloneList()
    {

    }

    public function actionCreate($prison_id, $year, $month_no)
    {

        $model = new JobPaidForm();
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
        if (is_null($model = Event::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t("ITEM_NOT_FOUND"));
        };

        return $this->render('view', ['model'=>$model]);
    }
    public function actionDelete($id)
    {
        if (is_null($model = JobPaidForm::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        if ($model->delete()){
             return $this->goBack();;
        };
        throw new \LogicException(Module::t('default',"CANT_DELETE"));
    }

    public function actionUpdate($id)
    {
        if (is_null($model = Event::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()){
                if ($model->save()){
                    return $this->goBack();;
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
        $models = JobPaid::findAll(['prison_id'=>$prison_id,'year'=>$prevYear,'month_no'=>$prevMonthNo]);
        if ($models){
            foreach ($models as $model)
            {
              $newModel = new JobPaid;
              $newModel->attributes = $model->getAttributes(['prison_id','prisoner_id','type_id','half_time']);
              $newModel->year = $year;
              $newModel->month_no = $month_no;
              $newModel->autoFillDaysHours();
              $newModel->save(true);
            }

        }
        $searchModel = new JobPaidSearch();
        $searchModel->attributes = compact(["prison_id","year","month_no"]);


        $redirectUrl[$searchModel->formName()] = compact(["prison_id","year","month_no"]);
        $redirectUrl[0] = 'index';
       return $this->redirect($redirectUrl);

    }

    public function actionCreateTabular($prison_id, $year, $month_no)
    {
        $jobList = JobPaidList::find()->active()->all();
        foreach ($jobList as $jobItem)
        {
            $model = JobPaid::findOne([
                'prison_id'=>$jobItem['prison_id'],
                'prisoner_id' => $jobItem['assigned_to'],
                'half_time' => $jobItem['half_time'],
                'month_no' => $month_no,
                'year' => $year,
            ]);
            if (is_null($model)){
                $newModel = new JobPaid();
                $newModel->prison_id = $prison_id;
                $newModel->type_id = $jobItem->type_id;
                $newModel->half_time = $jobItem->half_time;
                $newModel->prisoner_id = $jobItem->assigned_to;
                $newModel->month_no = $month_no;
                $newModel->year = $year;
                $newModel->autoFillDaysHours();
                $newModel->save();
            }



        }
        $searchModel = new JobPaidSearch();
        $searchModel->attributes = compact(["prison_id","year","month_no"]);


        $redirectUrl[$searchModel->formName()] = compact(["prison_id","year","month_no"]);
        $redirectUrl[0] = 'index';
        return $this->redirect($redirectUrl);
    }

}
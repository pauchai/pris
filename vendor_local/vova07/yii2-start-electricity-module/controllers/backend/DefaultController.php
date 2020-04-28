<?php
namespace vova07\electricity\controllers\backend;
use common\fixtures\DeviceAccountingFixture;
use DeepCopyTest\Matcher\Y;
use http\Url;
use phpDocumentor\Reflection\Types\Array_;
use vova07\base\components\BackendController;
use vova07\electricity\models\backend\DeviceAccountingSearch;
use vova07\electricity\models\backend\GenerateTabularDataForm;
use vova07\electricity\models\Device;
use vova07\electricity\models\DeviceAccounting;
use vova07\electricity\models\DeviceAccountingQuery;
use vova07\events\models\Event;
use vova07\events\Module;
use vova07\finances\models\backend\BalanceByPrisonerView;
use vova07\finances\models\backend\BalanceByPrisonerViewSearch;
use vova07\finances\models\Balance;
use vova07\tasks\models\backend\CommitteeSearch;
use vova07\tasks\models\Committee;
use vova07\users\models\backend\User;
use vova07\users\models\Prisoner;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
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
                'actions' => ['index'],
                'roles' => [\vova07\rbac\Module::PERMISSION_ELECTRICITY_LIST]
            ],
            [
                'allow' => true,
                'actions' => ['create'],
                'roles' => [\vova07\rbac\Module::PERMISSION_ELECTRICITY_CREATE]
            ],
            [
                'allow' => true,
                'actions' => ['update'],
                'roles' => [\vova07\rbac\Module::PERMISSION_ELECTRICITY_UPDATE]
            ],
            [
                'allow' => true,
                'actions' => ['delete'],
                'roles' => [\vova07\rbac\Module::PERMISSION_ELECTRICITY_DELETE]
            ],
            [
                'allow' => true,
                'actions' => ['view'],
                'roles' => [\vova07\rbac\Module::PERMISSION_ELECTRICITY_VIEW]
            ],
            [
                'allow' => true,
                'actions' => ['prisoner-devices','mass-change-statuses','generate-tabular-data'],
                'roles' => ['@']
            ],
        ];
        return $behaviors;
    }

    public function actions()
    {
        return [
            'prisoner-devices' => [
                'class' => \kartik\depdrop\DepDropAction::class,
                'outputCallback' => function ($selectedId, $params) {

                    return Prisoner::findOne($selectedId)->getDevices()->select(['__ownableitem_id as id','title as name'])->asArray()->all();
                }
            ]
        ];
    }
    public function actionIndex()
    {
        $searchModel = new DeviceAccountingSearch();

        $dataProvider = $searchModel->search(\Yii::$app->request->get());



       // if ($this->isPrintVersion)
            $dataProvider->pagination = false;
        //$dataProvider->query->planing();
        $newModel = new DeviceAccounting();
        $generateTabularDataFormModel = new GenerateTabularDataForm();
        $generateTabularDataFormModel->dateRange = $searchModel->dateRange;
        $generateTabularDataFormModel->validate();


        \Yii::$app->user->setReturnUrl(\yii\helpers\Url::current());

        return $this->render("index", ['dataProvider'=>$dataProvider, 'newModel' => $newModel,'searchModel' => $searchModel,'generateTabularDataFormModel' => $generateTabularDataFormModel]);
    }

    public function actionCreate()
    {

        $model = new DeviceAccounting();




        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){
                return $this->goBack();
            } else {
                \Yii::$app->session->setFlash('error',join("<br/>" ,$model->getFirstErrors()));
            }
        }
        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);

    }

    public function actionView($id)
    {
        if (is_null($model = Committee::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t("ITEM_NOT_FOUND"));
        };

        return $this->render('view', ['model'=>$model]);
    }
    public function actionDelete($id)
    {
        if (is_null($model = DeviceAccounting::findOne($id)))
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
        if (is_null($model = DeviceAccounting::findOne($id)))
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

    public function actionMassChangeStatuses()
    {
        if (\Yii::$app->request->isPost) {
            if (!is_null(\Yii::$app->request->post('change_status'))){
                $selectedIds = \Yii::$app->request->post('selection');
                $query = DeviceAccounting::find()->andWhere(['in', DeviceAccounting::primaryKey(), $selectedIds]);
                $models = $query->all();
                foreach ($models as $deviceAccounting)
                {
                    $deviceAccounting->status_id = \Yii::$app->request->post('status_id');
                    $deviceAccounting->save();
                }

            } elseif (!is_null(\Yii::$app->request->post('mass_delete'))) {
                $selectedIds = \Yii::$app->request->post('selection');
                $query = DeviceAccounting::find()->andWhere(['in', DeviceAccounting::primaryKey(), $selectedIds]);
                $models = $query->all();
                foreach ($models as $deviceAccounting)
                {
                    $deviceAccounting->delete();

                }            }





        }
        return $this->goBack();
    }

    public function actionGenerateTabularData()
    {
        $model = new GenerateTabularDataForm();
        $model->load(\Yii::$app->request->post());
        $model->validate();
        $model->generateOrSyncDevicesAccounting();


        return $this->goBack();

    }


}
<?php
namespace vova07\electricity\controllers\backend;
use common\fixtures\DeviceAccountingFixture;
use http\Url;
use vova07\base\components\BackendController;
use vova07\electricity\models\backend\DeviceAccountingSearch;
use vova07\electricity\models\DeviceAccounting;
use vova07\electricity\models\DeviceAccountingQuery;
use vova07\events\models\Event;
use vova07\events\Module;
use vova07\tasks\models\backend\CommitteeSearch;
use vova07\tasks\models\Committee;
use vova07\users\models\backend\User;
use vova07\users\models\Prisoner;
use yii\base\Model;
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
                'actions' => ['prisoner-devices','mass-change-statuses'],
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
        //$dataProvider->query->planing();
        $newModel = new DeviceAccounting();

        return $this->render("index", ['dataProvider'=>$dataProvider, 'newModel' => $newModel,'searchModel' => $searchModel]);
    }

    public function actionCreate()
    {

        $model = new DeviceAccounting();




        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){
                return $this->redirect(['index']);
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
            return $this->redirect(['index']);
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
                    return $this->redirect(['index']);
                };
            };
        }

        return $this->render("update", ['model' => $model,'cancelUrl' => ['index']]);
    }

    public function actionMassChangeStatuses()
    {
        $models = DeviceAccounting::find()->indexBy(DeviceAccounting::primaryKey())->all();
        if (Model::loadMultiple($models, \Yii::$app->request->post()) && Model::validateMultiple($models)){
            foreach($models as $deviceAccaunting){
                $deviceAccaunting->save(false);
            }

        }
        return $this->redirect(\yii\helpers\Url::to(['index']));
    }


}
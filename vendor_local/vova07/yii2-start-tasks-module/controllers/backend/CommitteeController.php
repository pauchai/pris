<?php
namespace vova07\tasks\controllers\backend;
use vova07\base\components\BackendController;
use vova07\events\models\Event;
use vova07\events\Module;
use vova07\tasks\models\backend\CommitteeSearch;
use vova07\tasks\models\Committee;
use vova07\users\models\backend\User;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class CommitteeController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PRISONERS_SECURITY_LIST]
            ],
            [
                'allow' => true,
                'actions' => ['create'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PRISONERS_SECURITY_CREATE]
            ],
            [
                'allow' => true,
                'actions' => ['update'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PRISONERS_SECURITY_UPDATE]
            ],
            [
                'allow' => true,
                'actions' => ['delete'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PRISONERS_SECURITY_DELETE]
            ],
            [
                'allow' => true,
                'actions' => ['view'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PRISONERS_SECURITY_VIEW]
            ],
        ];
        return $behaviors;
    }

    public function actionIndex()
    {

        $committeeNotFinishedProvider = new ActiveDataProvider();
        $committeeNotFinishedProvider->sort = false;
        $committeeNotFinishedProvider->query = Committee::find()->andWhere('isnull(`date_finish`)');


        $searchModel = new CommitteeSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        $dataProvider->sort = false;
        $dataProvider->query->orderBy('date_finish DESC');
        $dataProvider->query->andWhere('not isnull(`date_finish`)');
        //$dataProvider->query->planing();
        $newModel = new Committee;
        $newModel->assigned_to = \Yii::$app->user->getId();
        $newModel->status_id = Committee::STATUS_INIT;
        return $this->render("index", ['committeeNotFinishedProvider' => $committeeNotFinishedProvider, 'dataProvider'=>$dataProvider, 'newModel' => $newModel,'searchModel' => $searchModel]);
    }

    public function actionCreate()
    {

        $model = new Committee();




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
        if (is_null($model = Committee::findOne($id)))
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
        if (is_null($model = Committee::findOne($id)))
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

}
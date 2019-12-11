<?php
namespace vova07\events\controllers\backend;
use vova07\base\components\BackendController;
use vova07\events\models\backend\EventParticipantSearch;
use vova07\events\models\backend\EventSearch;
use vova07\events\models\Event;
use vova07\events\models\EventParticipant;
use vova07\events\Module;
use vova07\users\models\backend\User;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class PrisonerEventsController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => [\vova07\rbac\Module::PERMISSION_EVENT_PLANING_LIST]
            ],
            [
                'allow' => true,
                'actions' => ['create'],
                'roles' => [\vova07\rbac\Module::PERMISSION_EVENT_PLANING_CREATE]
            ],
            [
                'allow' => true,
                'actions' => ['update','change-status'],
                'roles' => [\vova07\rbac\Module::PERMISSION_EVENT_PLANING_UPDATE]
            ],
            [
                'allow' => true,
                'actions' => ['delete'],
                'roles' => [\vova07\rbac\Module::PERMISSION_EVENT_PLANING_DELETE]
            ],
            [
                'allow' => true,
                'actions' => ['view'],
                'roles' => [\vova07\rbac\Module::PERMISSION_EVENT_PLANING_VIEW]
            ],
        ];
        return $behaviors;
    }

    public function actionIndex($prisoner_id)
    {
        $searchModel = new EventParticipantSearch();
        $searchModel->prisoner_id = $prisoner_id;
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        //$dataProvider->query->planing();
        return $this->render("index", ['dataProvider'=>$dataProvider]);
    }

    public function actionCreate()
    {

        $model = new Event();
        $model->status_id = Event::STATUS_ACTIVE;
        $model->assigned_to = \Yii::$app->user->getId();


        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){
                //return $this->redirect(['view', 'id'=>$model->getPrimaryKey()]);
                return $this->redirect(['index']);
            } else {
                \Yii::$app->session->setFlash('error',join("<br/>" ,$model->getFirstErrors()));
            }
        }

        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);
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
        if (is_null($model = User::findOne($id)))
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
        if (is_null($model = Event::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()){
                if ($model->save()){
                    //return $this->redirect(['view', 'id'=>$model->getPrimaryKey()]);
                    return $this->redirect(['index']);
                };
            };
        }

        return $this->render("update", ['model' => $model,'cancelUrl' => ['index']]);
    }

    public function actionChangeStatus($id)
    {
        if (is_null($model = Event::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()){
                if ($model->save()){
                    return $this->redirect(['index', 'id'=>$model->getPrimaryKey()]);
                };
            };
        }

        return $this->render("update", ['model' => $model]);
    }

}
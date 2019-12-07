<?php
namespace vova07\humanitarians\controllers\backend;
use vova07\base\components\BackendController;
use vova07\events\models\Event;
use vova07\events\Module;
use vova07\humanitarians\models\backend\HumanitarianPrisonerSearch;
use vova07\humanitarians\models\HumanitarianPrisoner;
use vova07\tasks\models\backend\CommitteeSearch;
use vova07\tasks\models\Committee;
use vova07\users\models\backend\PrisonerSearch;
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
                'actions' => ['index'],
                'roles' => [Module::PERMISSION_PROGRAM_PLANING_LIST]
            ],
            [
                'allow' => true,
                'actions' => ['create'],
                'roles' => [Module::PERMISSION_PROGRAM_PLANING_CREATE]
            ],
            [
                'allow' => true,
                'actions' => ['update'],
                'roles' => [Module::PERMISSION_PROGRAM_PLANING_UPDATE]
            ],
            [
                'allow' => true,
                'actions' => ['delete'],
                'roles' => [Module::PERMISSION_PROGRAM_PLANING_DELETE]
            ],
            [
                'allow' => true,
                'actions' => ['view'],
                'roles' => [Module::PERMISSION_PROGRAM_PLANING_VIEW]
            ],
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $searchModel = new PrisonerSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        return $this->render("index", ['dataProvider'=>$dataProvider]);
    }

    public function actionCreate()
    {

        $model = new HumanitarianPrisoner();




        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){
                return $this->redirect(['index']);
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
                    return $this->redirect(['view', 'id'=>$model->getPrimaryKey()]);
                };
            };
        }

        return $this->render("update", ['model' => $model,'cancelUrl' => ['index']]);
    }

}
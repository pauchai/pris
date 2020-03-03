<?php
namespace vova07\concepts\controllers\backend;
use vova07\base\components\BackendController;
use vova07\concepts\Module;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class ClassesController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index', 'create', 'update', 'delete'],
                'roles' => [\vova07\rbac\Module::PERMISSION_CONCEPT_CREATE]
            ],
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $searchModel = new \vova07\concepts\models\backend\Cla;
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        //$dataProvider->query->planing();

        return $this->render("index", ['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
    }

    public function actionCreate()
    {

        $model = new Device();




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
        if (is_null($model = Device::findOne($id)))
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
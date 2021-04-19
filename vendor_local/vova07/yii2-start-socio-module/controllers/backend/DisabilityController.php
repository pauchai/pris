<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\socio\controllers\backend;



use vova07\base\components\BackendController;
use vova07\socio\models\backend\DisabilitySearch;
use vova07\socio\models\backend\MaritalStatusSearch;
use vova07\socio\models\Disability;
use vova07\socio\models\MaritalStatus;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use vova07\socio\Module;

class DisabilityController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index', 'create', 'delete', 'update'],
                //'roles' => [\vova07\rbac\Module::PERMISSION_SOCIO_LIST],
            ],

        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        \Yii::$app->user->setReturnUrl(Url::current());
        $searchModel = new DisabilitySearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        if ($this->isPrintVersion)
                $dataProvider->pagination = false;

        return $this->render("index", ['searchModel'=>$searchModel,'dataProvider'=>$dataProvider]);
    }
    public function actionCreate()
    {

        $model = new Disability();


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

    public function actionUpdate($id)
    {

        if (is_null($model = Disability::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){
                //return $this->redirect(['view', 'id'=>$model->getPrimaryKey()]);
                return $this->redirect(['index']);
            } else {
                \Yii::$app->session->setFlash('error',join("<br/>" ,$model->getFirstErrors()));
            }
        }

        return $this->render("update", ['model' => $model,'cancelUrl' => ['index']]);

    }

    public function actionDelete($id)
    {

        if (is_null($model = Disability::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        if ($model->delete()){
            return $this->redirect(['index']);
        };
        throw new \LogicException(Module::t('default',"CANT_DELETE"));
    }

}
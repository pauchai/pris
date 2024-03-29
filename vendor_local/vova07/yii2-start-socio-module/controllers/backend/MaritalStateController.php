<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\socio\controllers\backend;



use vova07\base\components\BackendController;
use vova07\socio\models\backend\MaritalStateSearch;
use vova07\socio\models\MaritalState;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use vova07\socio\Module;

class MaritalStateController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index', 'create', 'delete','update', 'view'],
                'roles' => [\vova07\rbac\Module::PERMISSION_SOCIO_LIST],
            ],

        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        \Yii::$app->user->setReturnUrl(Url::current());
        $searchModel = new MaritalStateSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        if ($this->isPrintVersion)
                $dataProvider->pagination = false;

        return $this->render("index", ['searchModel'=>$searchModel,'dataProvider'=>$dataProvider]);
    }
    public function actionCreate()
    {

        $model = new MaritalState();


        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){
                //return $this->redirect(['view', 'id'=>$model->getPrimaryKey()]);
                $this->goBack(['index']);
            } else {
                \Yii::$app->session->setFlash('error',join("<br/>" ,$model->getFirstErrors()));
            }
        } else {

            $model->setAttributes([
                '__person_id' => \Yii::$app->request->get('person_id'),
                'ref_person_id' => \Yii::$app->request->get('ref_person_id'),
            ]);
        }

        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);
    }
    public function actionUpdate($id)
    {

        if (is_null($model = MaritalState::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){
                //return $this->redirect(['view', 'id'=>$model->getPrimaryKey()]);
                return $this->goBack(['index']);;
            } else {
                \Yii::$app->session->setFlash('error',join("<br/>" ,$model->getFirstErrors()));
            }
        }

        return $this->render("update", ['model' => $model,'cancelUrl' => ['index']]);
   }

    public function actionDelete($id)
    {

        if (is_null($model = MaritalState::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        if ($model->delete()){
            return $this->goBack(['index']);
        };
        throw new \LogicException(Module::t('default',"CANT_DELETE"));
    }


    public function actionView($id)
    {
        if (is_null($model = MaritalState::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        return $this->render('view', ['model'=>$model]);
    }
}
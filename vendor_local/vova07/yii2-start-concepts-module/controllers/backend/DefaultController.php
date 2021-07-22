<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\concepts\controllers\backend;



use vova07\base\components\BackendController;
use vova07\concepts\models\backend\ConceptSearch;
use vova07\concepts\models\Concept;
use vova07\concepts\models\ConceptDict;
use vova07\psycho\models\backend\PrisonerCharacteristicSearch;
use vova07\psycho\models\PsyCharacteristic;
use vova07\psycho\Module;
use vova07\users\models\backend\PrisonerViewSearch;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class DefaultController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => [\vova07\rbac\Module::PERMISSION_CONCEPTS_LIST],
            ],

            [
                'allow' => true,
                'actions' => ['create', 'create-dict-ajax'],
                'roles' => [\vova07\rbac\Module::PERMISSION_CONCEPT_CREATE],
            ],

            [
                'allow' => true,
                'actions' => ['update'],
                'roles' => [\vova07\rbac\Module::PERMISSION_CONCEPT_UPDATE],
            ],

            [
                'allow' => true,
                'actions' => ['view'],
                'roles' => [\vova07\rbac\Module::PERMISSION_CONCEPT_VIEW],
            ],
            [
                'allow' => true,
                'actions' => ['delete'],
                'roles' => [\vova07\rbac\Module::PERMISSION_CONCEPT_DELETE],
            ],

        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        \Yii::$app->user->setReturnUrl(Url::current());
        $searchModel = new ConceptSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        if ($this->isPrintVersion)
                $dataProvider->pagination = false;

        return $this->render("index", ['searchModel'=>$searchModel,'dataProvider'=>$dataProvider]);
    }

    public function actionCreate()
    {

        $model = new Concept();
        $model->status_id = Concept::STATUS_ACTIVE;
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

    public function actionUpdate($id)
    {
        if (is_null($model = Concept::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()){
                if ($model->save()){
                    //return $this->redirect(['view', 'id'=>$model->getPrimaryKey()]);
                    return $this->goBack();
                };
            };
        }

        return $this->render("update", ['model' => $model,'cancelUrl' => ['index']]);
    }

    public function actionView($id)
    {
        \Yii::$app->user->setReturnUrl(Url::current());

        if (is_null($model = Concept::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t("ITEM_NOT_FOUND"));
        };


        return $this->render('view', ['model'=>$model]);
    }

    public function actionDelete($id)
    {
        if (is_null($model = Concept::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        if ($model->delete()){
            return $this->goBack();
        };
        throw new \LogicException(Module::t('default',"CANT_DELETE"));
    }

    public function actionCreateDictAjax()
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;



        $model = new ConceptDict();
        //$model->scenario = Officer::SCENARIO_LITE;


        if ($model->load(\Yii::$app->request->post())){
            if ($model->validate() && $model->save()) {

                // JSON response is expected in case of successful save
                return ['success' => true, 'model' => $model->attributes];

            } else {
                return ['success' => false, 'error' => $model->getErrorSummary(true)];


            }
        } else {

            if ($suggestion = \Yii::$app->request->get('suggestion'))
                $model->title = $suggestion;
        }




        return $this->renderAjax("create_dict", ['model' => $model, 'cancelUrl' => ['index']]);


    }
}
<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\concepts\controllers\backend;



use vova07\base\components\BackendController;
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
                'actions' => ['create'],
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


        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        \Yii::$app->user->setReturnUrl(Url::current());
        $searchModel = new PrisonerCharacteristicSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        if ($this->isPrintVersion)
                $dataProvider->pagination = false;

        return $this->render("index", ['searchModel'=>$searchModel,'dataProvider'=>$dataProvider]);
    }


    public function actionCreate($person_id)
    {
        $model = new PsyCharacteristic();
        $model->__person_id = $person_id;

        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()) {
                if ($model->save()) {
                    return $this->goBack();
                }
            };
        }
        foreach($model->getErrorSummary(true) as $errorStr){
            \Yii::$app->session->setFlash("error",$errorStr);
        }

        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);
    }



    public function actionUpdate($person_id)
    {
        if (is_null($model = PsyCharacteristic::findOne($person_id)))
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



}
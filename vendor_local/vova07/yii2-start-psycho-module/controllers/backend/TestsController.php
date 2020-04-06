<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\psycho\controllers\backend;



use vova07\base\components\BackendController;
use vova07\psycho\models\backend\PrisonerCharacteristicSearch;
use vova07\psycho\models\backend\PrisonerTestSearch;
use vova07\psycho\models\backend\PsyTestSearch;
use vova07\psycho\models\PsyCharacteristic;
use vova07\psycho\models\PsyTest;
use vova07\psycho\Module;
use vova07\users\models\backend\PrisonerViewSearch;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class TestsController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index', 'create', 'update'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PSYCHO_TESTS],


            ],
            [
                'allow' => true,
                'actions' => ['delete'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PSYCHO_TESTS],
                'verbs' => ['POST']
            ],

        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        \Yii::$app->user->setReturnUrl(Url::current());
        $testSearch = new PsyTestSearch();
        $testsDataProvider = $testSearch->search(\Yii::$app->request->get());
        $searchModel = new PrisonerTestSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        if ($this->isPrintVersion)
                $dataProvider->pagination = false;

        return $this->render("index", ['searchModel'=>$searchModel,'dataProvider'=>$dataProvider, 'testSearch' => $testSearch]);
    }


    public function actionCreate($prisoner_id)
    {
        $model = new PsyTest();
        $model->prisoner_id = $prisoner_id;

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

    public function actionDelete($id)
    {
        if (is_null($model = PsyTest::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

            $model->delete();
        return $this->goBack();

        //return $this->render("update", ['model' => $model,'cancelUrl' => ['index']]);
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
<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\jobs\controllers\backend;



use vova07\base\components\BackendController;
use vova07\documents\models\backend\DocumentSearch;
use vova07\documents\models\Document;
use vova07\documents\Module;
use vova07\jobs\models\backend\JobNotPaidTypeSearch;
use vova07\jobs\models\backend\JobPaidTypeSearch;
use vova07\jobs\models\JobNotPaidType;
use vova07\jobs\models\JobPaidType;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
class PaidTypesController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index','create','view','delete','update'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PAID_TYPE_LIST]
            ]
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        \Yii::$app->user->returnUrl = Url::current();
        $searchModel = new JobPaidTypeSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        return $this->render("index", ['searchModel'=>$searchModel,'dataProvider'=>$dataProvider]);
    }

    public function actionCreate()
    {
        $model = new JobPaidType();


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

    public function actionView($id)
    {
        if (is_null($model = JobPaidType::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        return $this->render('view', ['model'=>$model]);
    }
    public function actionDelete($id)
    {
        if (is_null($model = JobPaidType::findOne($id)))
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
        if (is_null($model = JobPaidType::findOne($id)))
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
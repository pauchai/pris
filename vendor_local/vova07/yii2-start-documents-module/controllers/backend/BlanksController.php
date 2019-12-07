<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\documents\controllers\backend;



use vova07\base\components\BackendController;
use vova07\documents\models\backend\BlankSearch;

use vova07\documents\models\Blank;

use vova07\prisons\Module;

use yii\helpers\Json;

use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class BlanksController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index','create','view','delete','update','view-content','blank-content'],
                'roles' => ['@']
            ]
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $searchModel = new BlankSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        return $this->render("index", ['dataProvider'=>$dataProvider]);
    }

    public function actionCreate()
    {
        $model = new Blank();


        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){
                return $this->redirect(['view', 'id'=>$model->getPrimaryKey()]);
            }
        }

        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);
    }

    public function actionView($id)
    {
        if (is_null($model = Blank::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t("ITEM_NOT_FOUND"));
        };
        return $this->render('view', ['model'=>$model]);
    }

    public function actionViewContent($id)
    {
        if (is_null($model = Blank::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t("ITEM_NOT_FOUND"));
        };
        return $model->content;
    }
    public function actionDelete($id)
    {
        if (is_null($model = Blank::findOne($id)))
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
        if (is_null($model = Blank::findOne($id)))
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


    public function actionBlankContent($id)
    {
        if (is_null($model = Blank::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        if (!\Yii::$app->request->isAjax) {
            throw new HttpException(400, 'Only ajax request is
allowed.');
        }
        return Json::encode(['content' => $model->content]);
    }


}
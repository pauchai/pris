<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\users\controllers\backend;


use lajax\translatemanager\models\Language;
use vova07\base\components\BackendController;
use vova07\users\models\backend\OfficerSearch;
use vova07\prisons\models\backend\PrisonSearch;
use vova07\prisons\models\Company;
use vova07\users\models\Officer;
use vova07\prisons\models\Prison;

use vova07\prisons\Module;
use vova07\users\models\Ident;
use vova07\users\models\Person;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class OfficersController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index','create','view','delete','update'],
                'roles' => ['@']
            ]
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $searchModel = new OfficerSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        return $this->render("index", ['dataProvider'=>$dataProvider]);
    }

    public function actionCreate()
    {
        //$model = new Officer();
        //$person =  new Person();
        //$person->ident = new Ident();
        //$model->person = $person;

        $model = new Officer();

       // $model->person->ident = new Ident();


        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            $model->person->ident = new Ident();
           // $model->loadRelations(\Yii::$app->request->post());
         //   $validatedModel = $model->validate();
            if ( $model->save()){
                return $this->redirect(['view', 'id'=>$model->getPrimaryKey()]);
            } else {
                foreach($model->getErrorSummary(true) as $errorStr){
                    \Yii::$app->session->setFlash("error",$errorStr);
                }


             //   $this->refresh();
            }
        } else {
            $model->person = new Person;
            $model->person->ident = new Ident();
        }

        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);
    }

    public function actionView($id)
    {
        if (is_null($model = Officer::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        return $this->render('view', ['model'=>$model]);
    }
    public function actionDelete($id)
    {
        if (is_null($model = Officer::findOne($id)))
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
        if (is_null($model = Officer::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        $person = $model->person;
        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->person->validate()){
                if ($model->save()){
                    return $this->redirect(['view', 'id'=>$model->getPrimaryKey()]);
                };
            };
        }

        return $this->render("update", ['model' => $model,'cancelUrl' => ['index']]);
    }
}
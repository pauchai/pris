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
use yii\helpers\Url;
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
                'actions' => ['index','create', 'create-lite','view','delete','update'],
                'roles' => ['@']
            ]
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $searchModel = new OfficerSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        \Yii::$app->user->setReturnUrl(Url::current());

        return $this->render("index", ['dataProvider'=>$dataProvider]);
    }

    public function actionCreate()
    {


        $model = new Officer([
          //  'company_id' => \Yii::$app->base->company->primaryKey
        ]);

        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
          //  $model->person->ident = new Ident();
           // $model->loadRelations(\Yii::$app->request->post());
         //   $validatedModel = $model->validate();
            if ( $model->save()){
                return $this->goBack();
            } else {
                foreach($model->getErrorSummary(true) as $errorStr){
                    \Yii::$app->session->setFlash("error",$errorStr);
                }

            }
        } else {
            $model->person = new Person;
           // $model->person->ident = new Ident();
        }

        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);
    }
    public function actionCreateLite()
    {


        $model = new Officer([

              'company_id' => \Yii::$app->base->company->primaryKey
        ]);
        $model->scenario = Officer::SCENARIO_LITE;

        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            $model->person->ident = new Ident();
            // $model->loadRelations(\Yii::$app->request->post());
            //   $validatedModel = $model->validate();
            if ( $model->save()){
                return $this->goBack();
            } else {
                foreach($model->getErrorSummary(true) as $errorStr){
                    \Yii::$app->session->setFlash("error",$errorStr);
                }

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
            return $this->goBack();
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
                    return $this->goBack();
                };
            };
        }

        return $this->render("update", ['model' => $model,'cancelUrl' => ['index']]);
    }
}
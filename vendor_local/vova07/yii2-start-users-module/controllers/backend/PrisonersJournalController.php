<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\users\controllers\backend;


use budyaga\cropper\actions\UploadAction;
use lajax\translatemanager\models\Language;
use vova07\base\components\BackendController;
use vova07\prisons\models\Sector;
use vova07\users\models\backend\PrisonerLocationJournalSearch;
use vova07\users\models\backend\PrisonerSearch;
use vova07\prisons\models\backend\PrisonSearch;
use vova07\prisons\models\Company;
use vova07\prisons\models\Prison;
use vova07\users\models\Prisoner;
use vova07\users\models\PrisonerLocationJournal;
use vova07\users\Module;
use vova07\users\models\Ident;
use vova07\users\models\Person;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PrisonersJournalController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => ['@'],

            ]
        ];
        return $behaviors;
    }



    public function actionIndex()
    {
      //  $access = \Yii::$app->authManager->checkAccess(\Yii::$app->user->id,Module::PERMISSION_PRISONERS_LIST);
       // if (!$access) {echo "break";die();}
        $searchModel = new PrisonerLocationJournalSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        return $this->render("index", ['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
    }

    public function actionCreate()
    {
        $prisoner = new Prisoner();
        $prisoner->person = new Person();

        if (\Yii::$app->request->post()){
            $prisoner->load(\Yii::$app->request->post());
            $prisoner->person->ident = new Ident();
            if ($prisoner->save()){
                return $this->redirect(['view', 'id'=>$prisoner->getPrimaryKey()]);
            }else {
                foreach ($prisoner->getErrorSummary(true) as $errorStr) {
                    \Yii::$app->session->setFlash("error", $errorStr);
                }
            }
        }

        return $this->render("create", ['model' => $prisoner,'cancelUrl' => ['index']]);
    }

    public function actionView($id)
    {
        if (is_null($model = Prisoner::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t("ITEM_NOT_FOUND"));
        };
        return $this->render('view', ['model'=>$model]);
    }
    public function actionDelete($id)
    {
        if (is_null($model = Prisoner::findOne($id)))
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
        $model = Prisoner::findOne($id);
        if (is_null($model))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        $person = $model->person;
        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()){
                if ($model->save(false)){
                    return $this->redirect(['view', 'id'=>$model->getPrimaryKey()]);
                } else {
                    foreach ($model->getErrorSummary(true) as $errorStr) {
                    \Yii::$app->session->setFlash("error", $errorStr);
                    };
                }
            } else {
                foreach ($model->getErrorSummary(true) as $errorStr) {
                    \Yii::$app->session->setFlash("error", $errorStr);
                };

            };
        }

        return $this->render("update", ['model' => $model,'cancelUrl' => ['index']]);
    }
}
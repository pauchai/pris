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
use vova07\users\models\backend\PrisonerSearch;
use vova07\prisons\models\backend\PrisonSearch;
use vova07\prisons\models\Company;
use vova07\prisons\models\Prison;
use vova07\users\models\Prisoner;
use vova07\users\Module;
use vova07\users\models\Ident;
use vova07\users\models\Person;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PrisonerController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['create','view','delete','update','upload-preview-photo','upload-photo','prison-sectors','sector-cells'],
                'roles' => ['@'],

            ],
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PRISONERS_LIST],

            ]
        ];
        return $behaviors;
    }

    public function actions()
    {
        return [
            'upload-photo' => [
                'class' => UploadAction::class,
                'url' => $this->module->personPhotoUrl,
                'path' => \Yii::getAlias($this->module->personPhotoPath),
            ],
            'upload-preview-photo' => [
                'class' => UploadAction::class,
                'url' => $this->module->personPreviewUrl,
                'path' => \Yii::getAlias($this->module->personPreviewPath),
            ],
            'prison-sectors' => [
                'class' => \kartik\depdrop\DepDropAction::class,
                'outputCallback' => function ($selectedId, $params) {

                    return Prison::findOne($selectedId)->getSectors()->select(['__ownableitem_id as id','title as name'])->asArray()->all();
                }
            ],
            'sector-cells' => [
                'class' => \kartik\depdrop\DepDropAction::class,
                'outputCallback' => function ($selectedId, $params) {

                    return Sector::findOne($selectedId)->getCells()->select(['__ownableitem_id as id','number as name'])->asArray()->all();
                }
            ]

        ];
    }

    public function actionIndex()
    {
      //  $access = \Yii::$app->authManager->checkAccess(\Yii::$app->user->id,Module::PERMISSION_PRISONERS_LIST);
       // if (!$access) {echo "break";die();}
        $searchModel = new PrisonerSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
//        $dataProvider->sort->attributes['person.first_name'] = [
//          'asc' => ['person.first_name' => SORT_ASC],
//            'desc' => ['person.first_name' => SORT_DESC],
//
//        ];
        if ($this->isPrintVersion)
           $dataProvider->pagination->pageSize = false;

         //   return $this->render("index_print", ['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
        //} else
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
<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\users\controllers\backend;


use budyaga\cropper\actions\UploadAction;
use kartik\grid\EditableColumnAction;
use lajax\translatemanager\models\Language;
use vova07\base\components\BackendController;
use vova07\prisons\models\Sector;
use vova07\users\models\backend\PrisonerSearch;
use vova07\users\models\backend\PrisonerViewSearch;
use vova07\prisons\models\backend\PrisonSearch;
use vova07\prisons\models\Company;
use vova07\prisons\models\Prison;
use vova07\users\models\Prisoner;
use vova07\users\Module;
use vova07\users\models\Ident;
use vova07\users\models\Person;
use yii\base\Model;
use yii\data\ActiveDataProvider;
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
                'actions' => ['upload-preview-photo','upload-photo','prison-sectors','sector-cells','edit-sector'],
                'roles' => ['@'],

            ],
            [
                'allow' => true,
                'actions' => ['create'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PRISONERS_CREATE],

            ],
            [
                'allow' => true,
                'actions' => ['update'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PRISONERS_UPDATE],

            ],
            [
                'allow' => true,
                'actions' => ['delete'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PRISONERS_DELETE],

            ],
            [
                'allow' => true,
                'actions' => ['view'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PRISONERS_VIEW],

            ],
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PRISONERS_LIST],

            ],
            [
            'allow' => true,
            'actions' => ['cells'],
            'roles' => [\vova07\rbac\Module::PERMISSION_PRISONERS_CELLS],

        ]
        ];
        return $behaviors;
    }

    public function actions()
    {
        $ret = [
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
        return  array_replace_recursive($ret, [
             'edit-sector' => [                                   // identifier for your editable column action
                 'class' => EditableColumnAction::class, // action class name
                 'modelClass' => Prisoner::class,            // the model for the record being edited
                 'scenario' => Model::SCENARIO_DEFAULT,        // model scenario assigned before validation & update
                 'outputValue' => function ($model, $attribute, $key, $index) {
                              return  $model->sector->title ;  // return a calculated output value if desired
                        },
                 'outputMessage' => function($model, $attribute, $key, $index) {
                              return '';                              // any custom error to return after model save
                        },
                 'showModelErrors' => true,                    // show model validation errors after save
                 'errorOptions' => ['header' => ''],            // error summary HTML options
                 'formName' => 'PrisonerView'
                    // 'postOnly' => true,
                 // 'ajaxOnly' => true,
                 // 'findModel' => function($id, $action) {},
                 // 'checkAccess' => function($action, $model) {}

             ]
         ]);
    }

    public function actionIndex(bool $isLight=true)
    {
      //  $access = \Yii::$app->authManager->checkAccess(\Yii::$app->user->id,Module::PERMISSION_PRISONERS_LIST);
       // if (!$access) {echo "break";die();}
        $searchModel = new PrisonerViewSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
//        $dataProvider->sort->attributes['person.first_name'] = [
//          'asc' => ['person.first_name' => SORT_ASC],
//            'desc' => ['person.first_name' => SORT_DESC],
//
//        ];
        if (\Yii::$app->request->isPost && \Yii::$app->request->isAjax)
        {
            $postParams =  \Yii::$app->request->post();
            $prisoner = Prisoner::findOne($postParams['editableKey']);
            $prisoner->load(\Yii::$app->request->post('PrisonerView'));
            $prisoner->save();

        }
        if ($this->isPrintVersion)
           $dataProvider->pagination->pageSize = false;

         //   return $this->render("index_print", ['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
        //} else
            return $this->render("index", ['dataProvider'=>$dataProvider,'searchModel' => $searchModel, 'isLight' => $isLight]);
    }

    public function actionCells()
    {
        $searchModel = new PrisonerSearch();
        $searchModel->prison_id = Company::findOne(Company::ID_PRISON_PU1)->__ownableitem_id;

        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        $dataProvider->query->active();

        if ($this->isPrintVersion)
        $dataProvider->pagination = false;

        $dataProvider->query->orderBy('prison_id, sector_id, cell_id');

        return $this->render("cells", ['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);

    }

    public function actionCreate()
    {
        $prisoner = new Prisoner();
        $prisoner->person = new Person();

        if (\Yii::$app->request->post()){
            $prisoner->load(\Yii::$app->request->post());
          // $prisoner->person->ident = new Ident();
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

        $prisonerSearch = new PrisonerViewSearch();
        $dataProvider = $prisonerSearch->searchFromSession();
        $dataProvider->pagination = false;
       // $searchModels = $dataProvider->getModels();
        $searchKeys = $dataProvider->getKeys();
        $arrayIndex = array_search($id,$searchKeys);
        $prevId = isset($searchKeys[$arrayIndex-1])?$searchKeys[$arrayIndex-1]:null;
        $nextId = isset($searchKeys[$arrayIndex+1])?$searchKeys[$arrayIndex+1]:null;


        return $this->render('view', ['model'=>$model, 'prevId' => $prevId,'nextId' => $nextId]);
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
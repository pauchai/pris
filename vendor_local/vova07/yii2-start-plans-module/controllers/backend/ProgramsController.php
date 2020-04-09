<?php
namespace vova07\plans\controllers\backend;
use http\Url;
use vova07\base\components\BackendController;
use vova07\plans\components\UpdateProgramAction;
use vova07\plans\models\backend\ProgramPrisonerSearch;
use vova07\plans\models\backend\ProgramSearch;
use vova07\plans\models\Program;
use vova07\plans\Module;
use vova07\users\models\backend\User;
use vova07\users\models\backend\UserSearch;
use vova07\users\models\Ident;
use yii\helpers\Json;
use yii\web\Application;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class ProgramsController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index','create','view', 'view-print', 'delete','update'],
                'roles' => ['@']
            ]
        ];
        return $behaviors;
    }
  //  public function actions()
  //  {
  //      return [
  //          'update' => UpdateProgramAction::class,
  //      ];
  //  }
    public function actionIndex()
    {
        $searchModel = new ProgramSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        $dataProvider->query->orderBy('status_id');
        \Yii::$app->user->setReturnUrl(\yii\helpers\Url::current());
        return $this->render("index", ['dataProvider'=>$dataProvider, 'searchModel' => $searchModel]);
    }

    public function actionCreate()
    {

        $model = new Program();



        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){

                //return Json::encode(array('status' => 'warning', 'type' => 'warning', 'message' => 'Contact can not created.'));

               // return Json::encode(array('status' => 'success', 'type' => 'success', 'message' => 'Contact created successfully.'));
                return $this->goBack();


//                return $this->redirect(['view', 'id'=>$model->getPrimaryKey()]);
            }
        }
        if (\Yii::$app->request->isAjax){
            return $this->renderAjax("create", ['model' => $model,'cancelUrl' => ['index']]);
        } else {
            return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);
        }

    }

    public function actionView($id)
    {
        \Yii::$app->user->setReturnUrl(\yii\helpers\Url::current());

        if (is_null($model = Program::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default', "ITEM_NOT_FOUND"));
        };
        $programPrisonerSearch = new ProgramPrisonerSearch();
        $dataProvider = $programPrisonerSearch->search(['program_id'=>$id],'');
        $dataProvider->query->forPrisonersActiveAndEtapped();

        return $this->render('view', ['model'=>$model,'dataProvider' => $dataProvider]);
    }
    public function actionViewPrint($id)
    {
        \Yii::$app->user->setReturnUrl(\yii\helpers\Url::current());

        if (is_null($model = Program::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default', "ITEM_NOT_FOUND"));
        };
        $programPrisonerSearch = new ProgramPrisonerSearch();
        $dataProvider = $programPrisonerSearch->search(['program_id'=>$id],'');
        $dataProvider->pagination = false;
        $dataProvider->query->forPrisonersActiveAndEtapped();

        return $this->render('view_print', ['model'=>$model,'dataProvider' => $dataProvider]);
    }
    public function actionDelete($id)
    {
        if (is_null($model = Program::findOne($id)))
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
        if (is_null($model = Program::findOne($id)))
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
<?php
namespace vova07\plans\controllers\backend;
use DeepCopyTest\Matcher\Y;
use vova07\base\components\BackendController;
use vova07\plans\models\backend\ProgramVisitSearch;
use vova07\plans\models\ProgramVisit;
use vova07\plans\Module;
use vova07\users\models\backend\User;
use vova07\users\models\backend\UserSearch;
use vova07\users\models\Ident;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class ProgramVisitsController extends BackendController
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
        \Yii::$app->user->returnUrl = Url::current();
        $searchModel = new ProgramVisitSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        return $this->render('index',['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }
    public function actionCreate()
    {
             $model = new ProgramVisit;
           $key = ArrayHelper::filter(\Yii::$app->request->post(),['program_prisoner_id','date_visit']);
            if (!($model = ProgramVisit::findOne($key))){
                $model = new ProgramVisit;
                $model->load(\Yii::$app->request->post(),'');
                if ($model->validate()){
                    if ($model->save()){
                       // return $this->renderPartial('view_ajax',['model' => $model]);
                    }
                }
            }

            return $this->renderPartial('view_ajax',['model'=>$model]);

    }

    public function actionDelete($id)
    {
        if (is_null($model = ProgramVisit::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        if ($model->delete()){
            return $this->goBack();
        };
        throw new \LogicException(Module::t('default',"CANT_DELETE"));
    }


}
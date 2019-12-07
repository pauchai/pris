<?php
namespace vova07\plans\controllers\backend;
use vova07\base\components\BackendController;
use vova07\plans\models\ProgramVisit;
use vova07\users\models\backend\User;
use vova07\users\models\backend\UserSearch;
use vova07\users\models\Ident;
use yii\helpers\ArrayHelper;
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



}
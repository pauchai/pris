<?php
namespace vova07\plans\controllers\backend;
use vova07\base\components\BackendController;
use vova07\plans\models\backend\ProgramPrisonerSearch;
use vova07\plans\models\backend\ProgramSearch;
use vova07\plans\models\Program;
use vova07\plans\models\ProgramPrisoner;
use vova07\plans\models\ProgramVisit;
use vova07\plans\Module;
use vova07\users\models\backend\User;
use vova07\users\models\backend\UserSearch;
use vova07\users\models\Ident;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class ProgramPrisonersController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['create', 'participants','add-participant','view','update'],
                'roles' => ['@']
            ]
        ];
        return $behaviors;
    }



    public function actionPrograms($id)
    {
        if (!($programPrisoner = ProgramPrisoner::findOne($id))) {
            throw new NotFoundHttpException(Module::t('programs','PROGRAM_NOT_FOUND'));
        };

    }






    public function actionView($id)
    {
        if (!($model = ProgramPrisoner::findOne($id))) {
            throw new NotFoundHttpException(Module::t('programs','PROGRAM_NOT_FOUND'));
        };
        \Yii::$app->user->setReturnUrl(\yii\helpers\Url::current());

        return $this->render('view', ['model'=>$model]);
    }

    public function actionCreate()
    {

        $model = new ProgramPrisoner;


        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()){
                if ($model->save()){
                    return $this->redirect(['view', 'id'=>$model->primaryKey]);
                    //return $this->redirect(['view'],$id);
                };
            };
        }

        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);

    }
    public function actionUpdate($id)
    {

        if (is_null($model = ProgramPrisoner::findOne($id)))
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
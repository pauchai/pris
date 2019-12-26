<?php
namespace vova07\prisons\controllers\backend;
use vova07\base\components\BackendController;
use vova07\events\models\backend\EventSearch;
use vova07\events\models\Event;
use vova07\prisons\models\backend\PrisonerSecurity248Search;
use vova07\prisons\models\backend\PrisonerSecurity251Search;
use vova07\prisons\models\backend\PrisonerSecuritySearch;
use vova07\prisons\models\PrisonerSecurity;
use vova07\users\models\backend\User;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class PrisonerSecurityController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index','index-light'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PRISONERS_SECURITY_LIST]
            ],
            [
                'allow' => true,
                'actions' => ['create'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PRISONERS_SECURITY_CREATE]
            ],
            [
                'allow' => true,
                'actions' => ['update',],
                'roles' => [\vova07\rbac\Module::PERMISSION_PRISONERS_SECURITY_UPDATE]
            ],
            [
                'allow' => true,
                'actions' => ['delete'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PRISONERS_SECURITY_DELETE]
            ],
            [
                'allow' => true,
                'actions' => ['view'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PRISONERS_SECURITY_VIEW]
            ],
        ];
        return $behaviors;
    }

    public function actionIndex(bool $isLight = false)
    {
        $searchModel248 = new PrisonerSecurity248Search();
        $searchModel251 = new PrisonerSecurity251Search();

        $dataProvider248 = $searchModel248->search(\Yii::$app->request->get());
        $dataProvider248->pagination = false;
        $dataProvider251 = $searchModel251->search(\Yii::$app->request->get());
        $dataProvider251->pagination = false;

        return $this->render("index", ['searchModel248'=>$searchModel248,'dataProvider248'=>$dataProvider248,'searchModel251'=>$searchModel251,'dataProvider251'=>$dataProvider251,'isLight' => $isLight]);

    }


    public function actionCreate()
    {
        $model = new PrisonerSecurity();


        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()) {
                if ($model->save()) {
                    //return $this->redirect(['view', 'id' => $model->getPrimaryKey()]);
                    return $this->redirect(['index']);
                }
            };
        }
        foreach($model->getErrorSummary(true) as $errorStr){
            \Yii::$app->session->setFlash("error",$errorStr);
        }

        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);
    }

    public function actionView($id)
    {
        if (is_null($model = PrisonerSecurity::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        return $this->render('view', ['model'=>$model]);
    }
    public function actionDelete($id)
    {
        if (is_null($model = PrisonerSecurity::findOne($id)))
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
        if (is_null($model = PrisonerSecurity::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()){
                if ($model->save()){
                   // return $this->redirect(['view', 'id'=>$model->getPrimaryKey()]);
                    return $this->redirect(['index']);
                };
            };
        }

        return $this->render("update", ['model' => $model,'cancelUrl' => ['index']]);
    }


}
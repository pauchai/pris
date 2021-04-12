<?php
namespace vova07\users\controllers\backend;
use vova07\base\components\BackendController;
use vova07\users\models\backend\User;
use vova07\users\models\backend\UserSearch;
use vova07\users\models\Ident;
use vova07\users\models\Person;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class DefaultController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index','create', 'create-for-person', 'view','delete','update','change-password'],
                'roles' => ['@']
            ]
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        return $this->render("index", ['dataProvider'=>$dataProvider]);
    }

    public function actionCreate()
    {

        $model = new User();

        $model->setScenario(User::SCENARIO_BACKEND_CREATE);

        $model->ident = new Ident();


        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){
                return $this->redirect(['view', 'id'=>$model->getPrimaryKey()]);
            }
        }

        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);
    }

    public function actionCreateForPerson($person_id)
    {

        $model = new User();


       $person = Person::findOne($person_id);
         if ($ident=Ident::findOne($person_id))
             $model->ident = $ident;
         else
             $model->ident = new Ident(['person_id' => $person->primaryKey]);



        $model->setScenario(User::SCENARIO_BACKEND_CREATE);

        //$model->ident = new Ident();


        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){
                return $this->redirect(['view', 'id'=>$model->getPrimaryKey()]);
            }
        }

        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);
    }



    public function actionView($id)
    {
        if (is_null($model = User::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t("ITEM_NOT_FOUND"));
        };
            $person = $model->person;
        return $this->render('view', ['model'=>$model,'person' => $person]);
    }
    public function actionDelete($id)
    {
        if (is_null($model = User::findOne($id)))
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
        if (is_null($model = User::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        $model->setScenario(User::SCENARIO_BACKEND_UPDATE);
        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()){
                if ($model->save()){
                    return $this->redirect(['view', 'id'=>$model->getPrimaryKey()]);
                };
            };
        }

        return $this->render("update", ['model' => $model,'cancelUrl' => ['index']]);
    }
    public function actionChangePassword($id)
    {
        if (is_null($model = User::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        $model->setScenario(User::SCENARIO_BACKEND_CHANGE_PASSWORD);
        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()){
                if ($model->save()){
                    return $this->redirect(['view', 'id'=>$model->getPrimaryKey()]);
                };
            };
        }

        return $this->render("change_password", ['model' => $model,'cancelUrl' => ['index']]);
    }

}
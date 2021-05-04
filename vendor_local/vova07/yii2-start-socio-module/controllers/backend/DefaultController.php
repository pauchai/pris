<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\socio\controllers\backend;



use vova07\base\components\BackendController;
use vova07\psycho\models\backend\PrisonerCharacteristicSearch;
use vova07\psycho\models\PsyCharacteristic;
use vova07\psycho\Module;
use vova07\socio\models\backend\RelationMaritalViewSearch;
use vova07\socio\models\backend\RelationWithMarital;
use vova07\socio\models\Relation;
use vova07\users\models\backend\PrisonerViewSearch;
use vova07\users\models\Officer;
use vova07\users\models\Person;
use vova07\users\models\User;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class DefaultController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'][] =
            [
                'allow' => true,
                'actions' => ['index', 'create-person-ajax'],
                'roles' => [\vova07\rbac\Module::PERMISSION_SOCIO_LIST],
            ];
        return $behaviors;
    }

    public function actionIndex()
    {
        \Yii::$app->user->setReturnUrl(Url::current());

        $searchModel = new RelationMaritalViewSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        return $this->render("index", compact('dataProvider', 'searchModel'));
    }

    public function actionCreateRelation()
    {

        $model = new RelationWithMarital();
        $model->person_id = \Yii::$app->request->get('person_id');


        if (\Yii::$app->request->post()) {
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()) {
                //return $this->redirect(['view', 'id'=>$model->getPrimaryKey()]);
                return $this->goBack(['index']);;
            } else {
                \Yii::$app->session->setFlash('error', join("<br/>", $model->getFirstErrors()));
            }
        }

        return $this->render("create", ['model' => $model, 'cancelUrl' => ['index']]);
    }


    public function actionCreatePersonAjax()
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;



        $model = new Person();
        //$model->scenario = Officer::SCENARIO_LITE;


            if ($model->load(\Yii::$app->request->post())){
                if ($model->validate() && $model->save()) {

                    // JSON response is expected in case of successful save
                    return ['success' => true, 'model' => $model->attributes];

                } else {
                    return ['success' => false, 'error' => $model->getErrorSummary(true)];


                }
            } else {

                if ($suggestion = \Yii::$app->request->get('suggestion'))
                    $model->second_name = $suggestion;
            }




        return $this->renderAjax("create_person", ['model' => $model, 'cancelUrl' => ['index']]);


    }
}

<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\psycho\controllers\backend;



use vova07\base\components\BackendController;
use vova07\psycho\models\backend\PrisonerCharacteristicSearch;
use vova07\psycho\models\PsyCharacteristic;
use vova07\psycho\Module;
use vova07\users\models\backend\PrisonerViewSearch;
use vova07\users\models\Officer;
use vova07\users\models\User;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class DefaultController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [

            [
                'allow' => true,
                'actions' => ['index' ,'index-print'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PSYCHO_LIST],
            ],
            [
                'allow' => true,
                'actions' => ['create'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PSYCHO_CREATE],
            ],
            [
                'allow' => true,
                'actions' => ['update'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PSYCHO_UPDATE],
            ],
            [
                'allow' => true,
                'actions' => ['update'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PSYCHO_DELETE],
            ],

        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        \Yii::$app->user->setReturnUrl(Url::current());
        $searchModel = new PrisonerViewSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        if ($this->isPrintVersion)
                $dataProvider->pagination = false;

        return $this->render("index", ['searchModel'=>$searchModel,'dataProvider'=>$dataProvider]);
    }
    public function actionIndexPrint()
    {
        $this->isPrintVersion = true;
        $this->layout = '@vova07/themes/adminlte2/views/layouts/print.php';
        \Yii::$app->user->setReturnUrl(Url::current());
        $searchModel = new PrisonerViewSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        if ($this->isPrintVersion)
            $dataProvider->pagination = false;

        $director = User::findOne(['role' => \vova07\rbac\Module::ROLE_COMPANY_HEAD])->officer;
        $officer = Officer::findOne(['__person_id' => \Yii::$app->user->identity->person->primaryKey]);

        return $this->render("index_print", [
            'searchModel'=>$searchModel,'dataProvider'=>$dataProvider,
            'director' => $director,
            'officer' => $officer,

        ]);
    }


    public function actionCreate($person_id)
    {
        $model = new PsyCharacteristic();
        $model->__person_id = $person_id;

        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()) {
                if ($model->save()) {
                    return $this->goBack();
                }
            };
        }
        foreach($model->getErrorSummary(true) as $errorStr){
            \Yii::$app->session->setFlash("error",$errorStr);
        }

        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);
    }



    public function actionUpdate($person_id)
    {
        if (is_null($model = PsyCharacteristic::findOne($person_id)))
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
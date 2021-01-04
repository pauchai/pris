<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\reports\controllers\backend;



use vova07\base\components\BackendController;
use vova07\psycho\models\backend\PrisonerCharacteristicSearch;
use vova07\psycho\models\PsyCharacteristic;
use vova07\psycho\Module;
use vova07\reports\models\backend\ReportPrisonerProgramSearch;
use vova07\users\models\backend\PrisonerViewSearch;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class PrisonerProgramController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index',],
                'roles' => ['@'],
            ],

        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $searchModel = new ReportPrisonerProgramSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        $dataProvider->pagination = false;

        return $this->render('index',['dataProvider' => $dataProvider, 'searchModel' => $searchModel]);
    }





}
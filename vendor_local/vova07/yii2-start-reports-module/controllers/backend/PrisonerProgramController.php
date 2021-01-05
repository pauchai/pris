<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\reports\controllers\backend;



use vova07\base\components\BackendController;
use vova07\plans\models\backend\ProgramPrisonerSearch;
use vova07\psycho\models\backend\PrisonerCharacteristicSearch;
use vova07\psycho\models\PsyCharacteristic;
use vova07\psycho\Module;
use vova07\reports\models\backend\ReportPrisonerProgramPlannedSearch;
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
                'actions' => ['planned', 'realized'],
                'roles' => ['@'],
            ],

        ];
        return $behaviors;
    }

    public function actionPlanned()
    {
        $searchModel = new ReportPrisonerProgramPlannedSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        $dataProvider->pagination = false;

        return $this->render('prisoner_program_planned',['dataProvider' => $dataProvider, 'searchModel' => $searchModel]);
    }

    public function actionRealized()
    {
        $searchModel = new ReportPrisonerProgramSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        $dataProvider->pagination = false;

        return $this->render('prisoner_program_realized',['dataProvider' => $dataProvider, 'searchModel' => $searchModel]);
    }




}
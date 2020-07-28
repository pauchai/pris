<?php
namespace vova07\plans\controllers\backend;
use vova07\base\components\BackendController;
use vova07\plans\models\backend\PrisonerPlanViewSearch;
use vova07\plans\models\backend\ProgramDictSearch;
use vova07\plans\models\backend\ProgramSearch;
use vova07\plans\models\PrisonerPlan;
use vova07\plans\models\PrisonerPlanView;
use vova07\plans\models\Program;
use vova07\plans\models\ProgramDict;
use vova07\plans\Module;
use vova07\users\models\backend\User;
use vova07\users\models\backend\UserSearch;
use vova07\users\models\Ident;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class ReportController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => ['@']
            ]
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $searchModel = new PrisonerPlanViewSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        $dataProvider->pagination->setPageSize(100);

        return $this->render("index", ['dataProvider'=>$dataProvider, 'searchModel' => $searchModel]);
    }


}
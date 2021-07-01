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
use vova07\reports\models\PrisonerFullViewPresenter;
use vova07\users\models\backend\PrisonerViewSearch;
use vova07\users\models\Prisoner;
use yii\data\ActiveDataProvider;
use yii\debug\models\timeline\DataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class PrisonerController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['full-view'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PRISONERS_VIEW],
            ],

        ];
        return $behaviors;
    }

    public function actionFullView($id)
    {
        if (is_null($prisoner = Prisoner::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t("ITEM_NOT_FOUND"));
        };
        $presenter = new PrisonerFullViewPresenter(['prisoner' => $prisoner]);



        return $this->render('full_view',compact('presenter'));
    }


}
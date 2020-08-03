<?php
namespace vova07\salary\controllers\backend;
use kartik\grid\EditableColumnAction;
use vova07\base\components\BackendController;
use vova07\finances\models\backend\BalanceByPrisonerViewSearch;
use vova07\finances\models\backend\BalanceByPrisonerView;
use vova07\finances\models\backend\BalanceByPrisonerWithCategoryViewSearch;
use vova07\finances\models\backend\BalanceSearch;
use vova07\finances\models\Balance;
use vova07\finances\Module;
use vova07\imperavi\tests\functional\data\models\Model;
use vova07\prisons\models\OfficerPost;
use vova07\salary\models\backend\SalarySearch;
use vova07\salary\models\SalaryIssue;
use vova07\salary\models\SalaryWithHold;
use vova07\salary\models\SyncModel;
use vova07\salary\models\Salary;
use vova07\users\models\Officer;
use vova07\users\models\Prisoner;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;


/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class WithHoldController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['delete'],
                'roles' => [\vova07\rbac\Module::PERMISSION_SALARY_CHARGE_SALARY_LIST]
            ]
        ];
        return $behaviors;
    }



    public function actionDelete($officer_id, $year, $month_no)
    {

        if (is_null($model = SalaryWithHold::findOne(compact('officer_id', 'year', 'month_no'))))
        {
            throw new NotFoundHttpException(Module::t("ISSUE_NOT_FOUND"));
        };

            $model->delete();

        return $this->goBack();

    }





}
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

class DefaultController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index', 'view', 'with-hold-view', 'create-tabular','change-salary-column', 'change-salary-calculated', 'change-withhold-column'],
                'roles' => [\vova07\rbac\Module::PERMISSION_SALARY_CHARGE_SALARY_LIST]
            ]
        ];
        return $behaviors;
    }


    public function actions()
    {
        return   [
            'change-salary-column' => [                                   // identifier for your editable column action
                'class' => EditableColumnAction::class, // action class name
                'modelClass' => Salary::class,            // the model for the record being edited
                'scenario' => Model::SCENARIO_DEFAULT,        // model scenario assigned before validation & update
                'outputValue' => function ($model, $attribute, $key, $index) {
                    return  $model->$attribute ;  // return a calculated output value if desired
                },
                'outputMessage' => function($model, $attribute, $key, $index) {
                    return '';                              // any custom error to return after model save
                },
                'showModelErrors' => true,                    // show model validation errors after save
                'errorOptions' => ['header' => ''],            // error summary HTML options
                'formName' => 'Salary'
                // 'postOnly' => true,
                // 'ajaxOnly' => true,
                // 'findModel' => function($id, $action) {},
                // 'checkAccess' => function($action, $model) {}

            ],
            'change-salary-calculated' => [                                   // identifier for your editable column action
                'class' => EditableColumnAction::class, // action class name
                'modelClass' => Salary::class,            // the model for the record being edited
                'scenario' => Salary::SCENARIO_RECALCULATE,        // model scenario assigned before validation & update
                'outputValue' => function ($model, $attribute, $key, $index) {
                    return  $model->$attribute ;  // return a calculated output value if desired
                },
                'outputMessage' => function($model, $attribute, $key, $index) {
                    return '';                              // any custom error to return after model save
                },
                'showModelErrors' => true,                    // show model validation errors after save
                'errorOptions' => ['header' => ''],            // error summary HTML options
                'formName' => 'Salary'
                // 'postOnly' => true,
                // 'ajaxOnly' => true,
                // 'findModel' => function($id, $action) {},
                // 'checkAccess' => function($action, $model) {}

            ],
            'change-withhold-column' => [                                   // identifier for your editable column action
                'class' => EditableColumnAction::class, // action class name
                'modelClass' => SalaryWithHold::class,            // the model for the record being edited
                'scenario' => Salary::SCENARIO_DEFAULT,        // model scenario assigned before validation & update
                'outputValue' => function ($model, $attribute, $key, $index) {
                    return  $model->$attribute ;  // return a calculated output value if desired
                },
                'outputMessage' => function($model, $attribute, $key, $index) {
                    return '';                              // any custom error to return after model save
                },
                'showModelErrors' => true,                    // show model validation errors after save
                'errorOptions' => ['header' => ''],            // error summary HTML options
                'formName' => 'SalaryWithHold'
                // 'postOnly' => true,
                // 'ajaxOnly' => true,
                // 'findModel' => function($id, $action) {},
                // 'checkAccess' => function($action, $model) {}

            ]
        ];

    }

    public function actionIndex($at = null)
    {
        \Yii::$app->user->setReturnUrl(Url::current());
        if (is_null($at)){
            $date = new \DateTime();
        } else {
            $date = \DateTime::createFromFormat('Y-m-01', $at);
        }
        $year = $date->format('Y');
        $month_no = $date->format('m');


        if (is_null($salaryIssue = SalaryIssue::findOne(compact('year', 'month_no'))))
            $salaryIssue = new SalaryIssue(['year' => $year, 'month_no' => $month_no]);

        $salaryIssue->validate();//for default values
        return $this->render("index", ['salaryIssue' => $salaryIssue]);
    }



    public function actionView($id)
    {

        if (is_null($model = Salary::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t("ITEM_NOT_FOUND"));
        };


        return $this->render('view', ['model'=>$model]);
    }
    public function actionWithHoldView($id)
    {

        if (is_null($model = SalaryWithHold::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t("ITEM_NOT_FOUND"));
        };


        return $this->render('withhold_view', ['model'=>$model]);
    }

    public function actionCreateMultiple()
    {
         return $this->render("create_multiple", ['model' => $model,'cancelUrl' => ['index']]);
    }


    public function actionCreateTabular()
    {
        $salaryIssue = new SalaryIssue();
        if ($salaryIssue->load(\Yii::$app->request->post()) && $salaryIssue->validate()){
            $salaryIssue->synchronize();
        }
        return $this->goBack();
    }
}
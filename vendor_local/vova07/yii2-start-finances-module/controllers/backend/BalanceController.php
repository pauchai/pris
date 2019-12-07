<?php
namespace vova07\finances\controllers\backend;
use vova07\base\components\BackendController;
use vova07\finances\models\backend\BalanceByPrisonerViewSearch;
use vova07\finances\models\backend\BalanceByPrisonerView;
use vova07\finances\models\backend\BalanceByPrisonerWithCategoryViewSearch;
use vova07\finances\models\backend\BalanceSearch;
use vova07\finances\models\Balance;
use vova07\rbac\Module;


/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class BalanceController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index','print-receipt'],
                'roles' => [Module::PERMISSION_FINANCES_ACCESS]
            ]
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $searchModel = new BalanceSearch();

        $newModel = new Balance();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        if ($newModel->load(\Yii::$app->request->post())) {
            $newModel->save();
        }


        $newModel->attributes = $searchModel->getAttributes(['prisoner_id', 'type_id', 'category_id', 'reason', 'atJui']);

        return $this->render("index", ['dataProvider' => $dataProvider, 'searchModel' => $searchModel, 'newModel' => $newModel]);
    }

    public function actionPrintReceipt()
    {
        $searchModel = new BalanceSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());


        return $this->render("print_receipt", ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]);

    }

}
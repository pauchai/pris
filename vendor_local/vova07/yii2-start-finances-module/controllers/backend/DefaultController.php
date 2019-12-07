<?php
namespace vova07\finances\controllers\backend;
use vova07\base\components\BackendController;
use vova07\finances\models\backend\BalanceByPrisonerViewSearch;
use vova07\finances\models\backend\BalanceByPrisonerView;
use vova07\finances\models\backend\BalanceByPrisonerWithCategoryViewSearch;
use vova07\finances\models\Balance;
use vova07\rbac\Module;


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
                'actions' => ['index',],
                'roles' => [Module::PERMISSION_FINANCES_ACCESS]
            ]
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $searchModel = new BalanceByPrisonerWithCategoryViewSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());


        return $this->render("index", ['dataProvider'=>$dataProvider,'searchModel'=>$searchModel]);
    }

    public function actionCreateMultiple()
    {
         return $this->render("create_multiple", ['model' => $model,'cancelUrl' => ['index']]);
    }


}
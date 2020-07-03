<?php
namespace vova07\salary\controllers\backend;

use vova07\base\components\BackendController;

use vova07\salary\models\backend\BalanceSearch;
use vova07\salary\Module;
use vova07\users\models\Officer;
use yii\data\ActiveDataProvider;


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
                'actions' => ['officer-view'],
                'roles' => [\vova07\rbac\Module::PERMISSION_SALARY_CHARGE_SALARY_LIST]
            ]
        ];
        return $behaviors;
    }

    public function actionOfficerView($id)
    {
        if (is_null($model = Officer::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t("OFFICER_NOT_FOUND"));
        };
        $searchModel = new BalanceSearch;

        $dataProvider = new ActiveDataProvider([
            'query' => $model->getBalances(),
        ]);
        // if ($this->isPrintVersion)
        $dataProvider->pagination->pageSize = false;
        $dataProvider->sort = false;
        $dataProvider->query->orderBy('at ASC');


        return $this->render("view", ['dataProvider'=>$dataProvider,'model'=>$model]);
    }

}
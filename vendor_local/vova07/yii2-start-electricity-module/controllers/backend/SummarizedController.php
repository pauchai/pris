<?php
namespace vova07\electricity\controllers\backend;
use http\Url;
use vova07\base\components\BackendController;

use vova07\electricity\models\backend\CalendarSearch;
use vova07\electricity\models\backend\SummarizedSearch;
use vova07\electricity\models\DeviceAccounting;
use vova07\electricity\models\DeviceQuery;
use yii\base\DynamicModel;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;


/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class SummarizedController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => [\vova07\rbac\Module::PERMISSION_ELECTRICITY_SUMMARIZED_LIST],
            ],

        ];
        return $behaviors;
    }

    public function actionIndex()
    {

        $searchModel = new CalendarSearch;
        $dataProvider = $searchModel->search(\Yii::$app->request->get());


        if ($this->isPrintVersion)
            $dataProvider->pagination = false;
        return $this->render('index',['searchModel' => $searchModel,'dataProvider' => $dataProvider ]);

    }




}
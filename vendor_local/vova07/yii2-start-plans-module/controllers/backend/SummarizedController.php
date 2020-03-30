<?php
namespace vova07\plans\controllers\backend;
use http\Url;
use vova07\base\components\BackendController;

use vova07\plans\models\backend\SummarizedSearch;


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
                'actions' => ['index','index-print'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PROGRAMS_SUMMARIZED_LIST],
            ],

        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $searchModel = new SummarizedSearch;
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        return $this->render('index',['searchModel' => $searchModel,'dataProvider' => $dataProvider ]);


    }



}
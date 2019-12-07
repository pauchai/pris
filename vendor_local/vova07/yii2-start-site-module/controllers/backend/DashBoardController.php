<?php
namespace vova07\site\controllers\backend;

use vova07\base\components\BackendController;

class DashBoardController extends BackendController
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
        return $this->render('index');
    }
}
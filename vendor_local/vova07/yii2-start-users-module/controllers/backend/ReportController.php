<?php
namespace vova07\users\controllers\backend;
use vova07\base\components\BackendController;
use vova07\users\models\backend\PrisonerViewSearch;
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
                'actions' => ['prisoners'],
                'roles' => ['@']
            ]
        ];
        return $behaviors;
    }

    public function actionPrisoners()
    {

        $searchModel = new PrisonerViewSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        if ($this->isPrintVersion)
            $dataProvider->pagination->pageSize = false;

        //   return $this->render("index_print", ['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
        //} else
        return $this->render("index", ['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);

    }



}
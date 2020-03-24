<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\prisons\controllers\backend;



use vova07\base\components\BackendController;
use vova07\prisons\Module;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class EducationSummaryController extends BackendController
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
        $dataProvider = new ActiveDataProvider(['query' => $sector->getCells()]);
        return $this->render("index", ['dataProvider'=>$dataProvider,'searchModel'=>$searchModelsector]);
    }





}
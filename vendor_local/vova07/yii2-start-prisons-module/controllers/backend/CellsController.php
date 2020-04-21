<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\prisons\controllers\backend;


use lajax\translatemanager\models\Language;
use vova07\base\components\BackendController;
use vova07\prisons\models\backend\CellSearch;
use vova07\prisons\models\backend\CompanyDepartmentSearch;
use vova07\prisons\models\backend\CompanySearch;
use vova07\prisons\models\backend\PrisonSearch;
use vova07\prisons\models\backend\SectorSearch;
use vova07\prisons\models\Cell;
use vova07\prisons\models\Company;
use vova07\prisons\models\CompanyDepartment;
use vova07\prisons\models\Prison;
use vova07\prisons\models\Sector;
use vova07\prisons\Module;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CellsController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index','create','view','delete','update'],
                'roles' => ['@']
            ]
        ];
        return $behaviors;
    }

    public function actionIndex($sector_id)
    {
        if (is_null($sector = Sector::findOne($sector_id)))
        {
            throw new NotFoundHttpException(Module::t("default","ITEM_NOT_FOUND"));
        };
        $searchModel = new CellSearch();
        $searchModel->sector = $sector;
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        return $this->render("index", ['dataProvider'=>$dataProvider,'searchModel'=>$searchModel]);
    }

    public function actionCreate($sector_id)
    {
        if (is_null($sector = Sector::findOne($sector_id)))
        {
            throw new NotFoundHttpException(Module::t("default","ITEM_NOT_FOUND"));
        };

        $model = new Cell();
        $model->sector_id = $sector->primaryKey;

            if (\Yii::$app->request->isPost){
                $model->load(\Yii::$app->request->post());
                if ($model->validate() && $model->save()){
                    $params = ['sector_id'=>$sector->primaryKey];
                    $params[0] = 'index';
                    return $this->redirect($params);
                }
            }

        return $this->render("create", ['model' => $model,'cancelUrl' => ['index'],'sectorModel'=>$model]);
    }


    public function actionDelete($id)
    {
        if (is_null($model = Cell::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        $params = [ 'sector_id' => $model->sector_id ];
        $params[0]='index';
        if ($model->delete()){

            return $this->redirect($params);
        };
            throw new \LogicException(Module::t('default',"CANT_DELETE"));
    }

    public function actionView($id)
    {
        if (is_null($model = Cell::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t("ITEM_NOT_FOUND"));
        }
        $dataProvider = (new \yii\data\ActiveDataProvider([
            'query' => $model->getPrisoners(),
            'pagination' => false,
        ]));
        return $this->render('view', ['model'=>$model, 'dataProvider' => $dataProvider]);
    }


    public function actionUpdate($id)
    {
        if (is_null($model = Cell::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()){
                if ($model->save()){
                    $params = ['sector_id'=>$model->sector_id];
                    $params[0] = 'index';
                    return $this->redirect($params);

                };
            };
        }

        return $this->render("update", ['model' => $model,'cancelUrl' => ['index']]);
    }




}
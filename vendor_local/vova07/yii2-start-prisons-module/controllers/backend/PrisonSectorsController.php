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
use vova07\prisons\models\backend\CompanyDepartmentSearch;
use vova07\prisons\models\backend\CompanySearch;
use vova07\prisons\models\backend\PrisonSearch;
use vova07\prisons\models\backend\SectorSearch;
use vova07\prisons\models\Company;
use vova07\prisons\models\CompanyDepartment;
use vova07\prisons\models\Prison;
use vova07\prisons\models\Sector;
use vova07\prisons\Module;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PrisonSectorsController extends BackendController
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

    public function actionIndex($prison_id)
    {
        if (is_null($prison = Prison::findOne($prison_id)))
        {
            throw new NotFoundHttpException(Module::t("default","ITEM_NOT_FOUND"));
        };

        $dataProvider = new ActiveDataProvider(['query' => $prison->getSectors()]);



        return $this->render("index", ['dataProvider'=>$dataProvider,'model'=>$prison]);
    }

    public function actionCreate($prison_id)
    {
        if (is_null($prison = Prison::findOne($prison_id)))
        {
            throw new NotFoundHttpException(Module::t("default","ITEM_NOT_FOUND"));
        };

        $model = new Sector();
        $model->prison_id = $prison->__company_id;



            if (\Yii::$app->request->isPost){
                $model->load(\Yii::$app->request->post());
                if ($model->validate() && $model->save()){
                    $params = ['prison_id'=>$prison->primaryKey];
                    $params[0] = 'index';
                    return $this->redirect($params);
                }
            }


        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);
    }


    public function actionDelete($id)
    {
        if (is_null($model = Sector::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        $params = [ 'prison_id' => $model->prison->primaryKey ];
        $params[0]='index';
        if ($model->delete()){

            return $this->redirect($params);
        };
            throw new \LogicException(Module::t('default',"CANT_DELETE"));
    }




}
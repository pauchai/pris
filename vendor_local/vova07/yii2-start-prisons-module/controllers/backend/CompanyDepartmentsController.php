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
use vova07\prisons\models\Company;
use vova07\prisons\models\CompanyDepartment;
use vova07\prisons\models\Prison;
use vova07\prisons\Module;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CompanyDepartmentsController extends BackendController
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

    public function actionIndex()
    {
        //$company = Company::findOne($id);
        $searchModel = new CompanyDepartmentSearch();
        //$params = [
        //    'CompanyDepartment' => ['company_id' => $id],
        //];

        $params = \Yii::$app->request->get();
        $company = Company::findOne($params['CompanyDepartment']['company_id']);
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        return $this->render("index", ['dataProvider'=>$dataProvider,'company'=>$company]);
    }

    public function actionCreate($company_id)
    {
        if (is_null($company = Company::findOne($company_id)))
        {
            throw new NotFoundHttpException(Module::t("ITEM_NOT_FOUND"));
        };
        $params = [
            'CompanyDepartment' => ['company_id' => $company_id],
        ];
        $model = new CompanyDepartment();



            if (\Yii::$app->request->isPost){
                $model->load(\Yii::$app->request->post());
                if ($model->validate() && $model->save()){
                    $params[0] = 'index';
                    return $this->redirect($params);
                }
            } else {
                 $model->load($params);
            }


        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);
    }


    public function actionDelete($company_id,$department_id)
    {
        if (is_null($model = CompanyDepartment::findOne(['company_id'=>$company_id, 'department_id'=>$department_id])))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        $attributes = $model->getAttributes();
        unset($attributes['department_id']);
        $params = [ $model->formName() => $attributes ];
        $params[0]='index';
        if ($model->delete()){

            return $this->redirect($params);
        };
            throw new \LogicException(Module::t('default',"CANT_DELETE"));
    }




}
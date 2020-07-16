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
use vova07\prisons\models\backend\DivisionSearch;
use vova07\prisons\models\backend\PrisonSearch;
use vova07\prisons\models\Company;
use vova07\prisons\models\CompanyDepartment;
use vova07\prisons\models\Division;
use vova07\prisons\models\Prison;
use vova07\prisons\Module;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class DivisionsController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index','create','view','delete','update','company-divisions'],
                'roles' => ['@']
            ]
        ];
        return $behaviors;
    }

    public function actions()
    {
        return [
            'company-divisions' => [
                'class' => \kartik\depdrop\DepDropAction::class,
                'outputCallback' => function ($selectedId, $params) {
                    return Company::findOne($selectedId)->getDivisions()->select(['division_id as id','title as name'])->asArray()->all();


                }
            ]

        ];
    }

    public function actionIndex()
    {
        //$company = Company::findOne($id);
        $searchModel = new DivisionSearch();

        //$params = [
        //    'CompanyDepartment' => ['company_id' => $id],
        //];

        $params = \Yii::$app->request->get();
        $company = Company::findOne($params['DivisionSearch']['company_id']);
        $newModel = new Division([
            'company_id' => $company->primaryKey
        ]);
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        \Yii::$app->user->setReturnUrl(\yii\helpers\Url::current());

        return $this->render("index", ['dataProvider'=>$dataProvider,'company'=>$company,'newModel' => $newModel]);
    }

    public function actionCreate($company_id)
    {
        if (is_null($company = Company::findOne($company_id)))
        {
            throw new NotFoundHttpException(Module::t("ITEM_NOT_FOUND"));
        };
        $params = [
            'Division' => ['company_id' => $company_id],
        ];
        $model = new Division();



        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){
                $params[0] = 'index';
                return $this->goBack();
            }
        } else {
            $model->load($params);
        }


        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);
    }
    public function actionUpdate($company_id,$division_id)
    {
        if (is_null($model = Division::findOne(['company_id'=>$company_id, 'division_id'=>$division_id])))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        if (\Yii::$app->request->isPost){

            if ($model->load(\Yii::$app->request->post()) && $model->validate()){
                if ($model->save()){
                    return $this->goBack();
                };
            };
        }

        return $this->render("update", ['model' => $model,'cancelUrl' => ['index']]);
    }
    public function actionView($company_id,$division_id)
    {
        if (is_null($model = Division::findOne(['company_id'=>$company_id, 'division_id'=>$division_id])))
        {

            throw new NotFoundHttpException(Module::t("ITEM_NOT_FOUND"));
        };
        return $this->render('view', ['model'=>$model]);
    }

    public function actionDelete($company_id,$division_id)
    {
        if (is_null($model = Division::findOne(['company_id'=>$company_id, 'division_id'=>$division_id])))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        $attributes = $model->getAttributes();

        if ($model->delete()){

            return $this->goBack();
        };
        throw new \LogicException(Module::t('default',"CANT_DELETE"));
    }




}
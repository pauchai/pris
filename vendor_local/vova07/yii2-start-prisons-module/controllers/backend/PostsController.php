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
use vova07\prisons\models\backend\PostSearch;
use vova07\prisons\models\backend\PrisonSearch;
use vova07\prisons\models\Company;
use vova07\prisons\models\CompanyDepartment;
use vova07\prisons\models\Division;
use vova07\prisons\models\Post;
use vova07\prisons\models\Prison;
use vova07\prisons\Module;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PostsController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index','create','view','delete','update','division-posts'],
                'roles' => ['@']
            ]
        ];
        return $behaviors;
    }

    public function actions()
    {
        return [
            'division-posts' => [
                'class' => \kartik\depdrop\DepDropAction::class,
                'outputCallback' => function ($selectedId, $params) {
                    return Division::findOne($selectedId)->getPosts()->select(['post_id as id','title as name'])->asArray()->all();


                }
            ]

        ];
    }

    public function actionIndex()
    {

        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        \Yii::$app->user->setReturnUrl(Url::current());

        return $this->render("index", ['dataProvider'=>$dataProvider, 'searchModel' => $searchModel]);
    }

    public function actionCreate($company_id, $division_id)
    {
        if (is_null($division = Division::findOne(['company_id' => $company_id, 'division_id' => $division_id])))
        {
            throw new NotFoundHttpException(Module::t("ITEM_NOT_FOUND"));
        };

        $model = new Post();
        $model->setAttributes(compact('company_id','division_id'));



        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){
                return $this->goBack();
            }
        }


        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);
    }


    public function actionDelete($company_id,$division_id, $post_id)
    {
        if (is_null($model = Division::findOne(['company_id'=>$company_id, 'division_id'=>$division_id , 'post_id' => $post_id])))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        if ($model->delete()){

            return $this->goBack();
        };
        throw new \LogicException(Module::t('default',"CANT_DELETE"));
    }




}
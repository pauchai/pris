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
use vova07\prisons\models\backend\RankSearch;
use vova07\prisons\models\Company;
use vova07\prisons\models\CompanyDepartment;
use vova07\prisons\models\Division;
use vova07\prisons\models\OfficerPost;
use vova07\prisons\models\Post;
use vova07\prisons\models\Prison;
use vova07\prisons\models\Rank;
use vova07\prisons\Module;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RanksController extends BackendController
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

        $searchModel = new RankSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        \Yii::$app->user->setReturnUrl(Url::current());

        return $this->render("index", ['dataProvider'=>$dataProvider, 'searchModel' => $searchModel]);
    }


    public function actionCreate()
    {


        $model = new Rank();




        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){
                return $this->goBack();
            }
        }


        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);
    }

    public function actionDelete($id)
    {
        if (is_null($model = Rank::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        if ($model->delete()){

            return $this->goBack();
        };
        throw new \LogicException(Module::t('default',"CANT_DELETE"));
    }

    public function actionUpdate($id)
    {
        if (is_null($model = Rank::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()){
                if ($model->save()){
                    return $this->goBack();
                };
            };
        }

        return $this->render("update", ['model' => $model,'cancelUrl' => ['index']]);
    }


}
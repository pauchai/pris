<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\documents\controllers\backend;


use lajax\translatemanager\models\Language;
use vova07\base\components\BackendController;
use vova07\documents\models\backend\BlankSearch;
use vova07\prisons\models\backend\CompanySearch;
use vova07\documents\models\backend\BlankPrisonerSearch;
use vova07\prisons\models\backend\PrisonSearch;
use vova07\documents\models\Blank;
use vova07\documents\models\BlankPrisoner;
use vova07\prisons\models\Company;
use vova07\prisons\models\Prison;
use vova07\prisons\Module;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BlankPrisonersController extends BackendController
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
        $searchModel = new BlankPrisonerSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());


        return $this->render("index", ['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
    }

    public function actionCreate()
    {

        $blankPrisonerSearch = new BlankPrisonerSearch;
        $blankPrisonerSearch->load(\Yii::$app->request->get());

        $model = new BlankPrisoner();
        $model->setAttributes($blankPrisonerSearch->attributes);
        //$model->blank_id = $blank->id;
        //$model->prisoner_id = $prisoner_id;
        //$model->content = $blank->content;

        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){
                $urlParam = $model->getPrimaryKey();
                $urlParam[0] = 'view';
                return $this->redirect($urlParam);
            }
        } else {
            $model->setAttributes($blankPrisonerSearch->attributes);
            if ($model->blank_id){
                $model->content = $model->blank->content;
            }
        }

        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);
    }

    public function actionView($blank_id,$prisoner_id)
    {

        if (is_null($model = BlankPrisoner::findOne(['blank_id'=>$blank_id,'prisoner_id'=>$prisoner_id])))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

            return $this->render('view', ['model'=>$model]);


    }
    public function actionDelete($blank_id,$prisoner_id)
    {
        if (is_null($model = BlankPrisoner::findOne(['blank_id'=>$blank_id,'prisoner_id'=>$prisoner_id])))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        if ($model->delete()){
            return $this->redirect(['index']);
        };
            throw new \LogicException(Module::t('default',"CANT_DELETE"));
    }

    public function actionUpdate($blank_id,$prisoner_id)
    {
        if (is_null($model = BlankPrisoner::findOne(['blank_id'=>$blank_id,'prisoner_id'=>$prisoner_id])))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()){
                if ($model->save()){
                    $urlParam = $model->getPrimaryKey();
                    $urlParam[0] = 'view';
                    return $this->redirect($urlParam);
                };
            };
        }

        return $this->render("update", ['model' => $model,'cancelUrl' => ['index']]);
    }


}
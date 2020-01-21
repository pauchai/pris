<?php
namespace vova07\humanitarians\controllers\backend;
use vova07\base\components\BackendController;
use vova07\humanitarians\models\backend\HumanitarianItemSearch;
use vova07\humanitarians\models\HumanitarianItem;

use vova07\humanitarians\Module;
use vova07\users\models\backend\User;
use vova07\users\models\Prisoner;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class ItemsController extends BackendController
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
        \Yii::$app->user->returnUrl = Url::current();
        $searchModel = new HumanitarianItemSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        $model = new HumanitarianItem();

        return $this->render("index", ['dataProvider'=>$dataProvider,'model' => $model]);
    }

    public function actionCreate()
    {

        $model = new HumanitarianItem();



        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){
                return $this->goBack();
            }
        }

        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);
    }

    public function actionView($id)
    {
        if (is_null($model = HumanitarianItem::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        return $this->render('view', ['model'=>$model]);

    }
    public function actionDelete($id)
    {
        if (is_null($model = HumanitarianItem::findOne($id)))
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
        if (is_null($model = HumanitarianItem::findOne($id)))
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
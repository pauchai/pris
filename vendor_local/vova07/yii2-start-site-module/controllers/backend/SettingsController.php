<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\site\controllers\backend;


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
use vova07\site\models\backend\SettingSearch;
use vova07\site\models\Setting;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class SettingsController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index', 'create', 'view', 'delete', 'update'],
                'roles' => ['@']
            ]
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $searchModel = new SettingSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());


        return $this->render("index", ['dataProvider' => $dataProvider, 'model' => $searchModel]);
    }

    public function actionCreate()
    {

        $model = new Setting();

        if (\Yii::$app->request->isPost) {
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()) {
                $params[0] = 'index';
                return $this->redirect($params);
            }
        }


        return $this->render("create", ['model' => $model, 'cancelUrl' => ['index']]);
    }

    public function actionView($id)
    {
        if (is_null($model = Setting::findOne($id))) {
            throw new NotFoundHttpException(Module::t("ITEM_NOT_FOUND"));
        };

        return $this->render('view', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        if (is_null($model = Setting::findOne($id))) {
            throw new NotFoundHttpException(Module::t('default', "ITEM_NOT_FOUND"));
        };
        if ($model->delete()) {
            return $this->redirect(['index']);
        };
        throw new \LogicException(Module::t('default', "CANT_DELETE"));
    }

    public function actionUpdate($id)
    {
        if (is_null($model = Setting::findOne($id))) {
            throw new NotFoundHttpException(Module::t('default', "ITEM_NOT_FOUND"));
        };

        if (\Yii::$app->request->isPost) {
            $model->load(\Yii::$app->request->post());
            if ($model->validate()) {
                if ($model->save()) {
                    return $this->redirect(['index']);
                };
            };
        }

        return $this->render("update", [ 'model' => $model]);

    }
}
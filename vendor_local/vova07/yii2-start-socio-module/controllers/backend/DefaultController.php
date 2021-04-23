<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\socio\controllers\backend;



use vova07\base\components\BackendController;
use vova07\psycho\models\backend\PrisonerCharacteristicSearch;
use vova07\psycho\models\PsyCharacteristic;
use vova07\psycho\Module;
use vova07\socio\models\backend\RelationMaritalViewSearch;
use vova07\users\models\backend\PrisonerViewSearch;
use vova07\users\models\Officer;
use vova07\users\models\User;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class DefaultController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'][] =
            [
                'allow' => true,
                'actions' => ['index' ],
                'roles' => [\vova07\rbac\Module::PERMISSION_SOCIO_LIST],
            ]

        ;
        return $behaviors;
    }

    public function actionIndex()
    {
        $searchModel = new RelationMaritalViewSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        return $this->render("index", compact('dataProvider', 'searchModel'));
    }


}
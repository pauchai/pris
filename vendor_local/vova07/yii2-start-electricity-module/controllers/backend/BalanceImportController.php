<?php
namespace vova07\electricity\controllers\backend;
use common\fixtures\DeviceAccountingFixture;
use http\Url;
use vova07\base\components\BackendController;
use vova07\electricity\models\backend\DeviceAccountingSearch;
use vova07\electricity\models\DeviceAccounting;
use vova07\electricity\models\DeviceAccountingQuery;
use vova07\events\models\Event;
use vova07\events\Module;
use vova07\finances\models\Balance;
use vova07\finances\models\BalanceCategory;
use vova07\tasks\models\backend\CommitteeSearch;
use vova07\tasks\models\Committee;
use vova07\users\models\backend\User;
use vova07\users\models\Prisoner;
use yii\base\Model;
use yii\db\Query;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class BalanceImportController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index','do-process'],
                'roles' => ['@']
            ],

        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $searchModel = new DeviceAccountingSearch();

        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        $dataProvider->query->readyForProcessing();
        //$dataProvider->query->planing();
        $newModel = new DeviceAccounting();

        return $this->render("index", ['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
    }

    public function actionDoProcess()
    {
        if (\Yii::$app->request->isPost){
            $selectedIds = \Yii::$app->request->post('selection');
            $query = DeviceAccounting::find()->andWhere(['in', DeviceAccounting::primaryKey(), $selectedIds]);
            $models = $query->all();
            foreach ($models as $deviceAccounting)
            {
                $balance = new Balance();
                $balance->type_id = Balance::TYPE_CREDIT;
                $balance->category_id =  BalanceCategory::CATEGORY_CREDIT_OTHER;
                $balance->prisoner_id = $deviceAccounting->prisoner_id;
                $balance->amount = $deviceAccounting->getPrice();
                $balance->reason = \vova07\electricity\Module::t('default','ELECTRICITY_FOR_MONTH {0, date, MMM, Y}', $deviceAccounting->to_date) ;
                $balance->atJui = date('d-m-Y');
                if ($balance->save())
                {
                    $deviceAccounting->balance_id = $balance->primaryKey;
                    $deviceAccounting->status_id = DeviceAccounting::STATUS_PROCESSED;
                    $deviceAccounting->save();
                }

            }
            return $this->redirect(\yii\helpers\Url::to(['index']));
        }
    }


}
<?php
namespace vova07\electricity\controllers\backend;
use common\fixtures\DeviceAccountingFixture;
use http\Url;
use vova07\base\components\BackendController;
use vova07\electricity\models\backend\DeviceAccountingSearch;
use vova07\electricity\models\DeviceAccounting;
use vova07\electricity\models\DeviceAccountingBalance;
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
use yii\data\ActiveDataProvider;
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
                'actions' => ['index','view'],
                'roles' => [\vova07\rbac\Module::PERMISSION_ELECTRICITY_BALANCE_IMPORT]
            ],
            [
                'allow' => true,
                'actions' => [ 'do-process', 'delete'],
                'roles' => [\vova07\rbac\Module::PERMISSION_ELECTRICITY_BALANCE_IMPORT]
            ],


        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        \Yii::$app->user->setReturnUrl(\yii\helpers\Url::current());

        $searchModel = new DeviceAccountingSearch();

        //$dataProvider = $searchModel->search(\Yii::$app->request->get());
        $dataProvider = new ActiveDataProvider(
            [
                'query' =>DeviceAccounting::find()->joinWith(
                    [
                        'prisoner' => function ($query){
                            $query->from(['prisoner' => 'prisoner']);
                            $query->joinWith([
                                'person' => function($query){
                                    return $query->from(['person' => 'person']);
                                }
                            ]);
                            return $query;

                        }
                    ])
            ]
        );
        $dataProvider->query->orderBy([
            'person.second_name' => SORT_ASC,
            'from_date' => SORT_ASC,
        ]);
        $dataProvider->query->readyForProcessing();
        $dataProvider->pagination = false;
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
            $groupedByPrisonerModels = [];
            foreach ($models as $deviceAccounting){
                $groupedByPrisonerModels[$deviceAccounting->prisoner_id][] = $deviceAccounting;
            }
            foreach ($groupedByPrisonerModels as $prisonerId => $prisonerDeviceAccountings)
            {
                $groupedSum = 0;
                foreach ($prisonerDeviceAccountings as $deviceAccounting){
                    $groupedSum += $deviceAccounting->price;
                }
                $balance = new Balance();
                $balance->type_id = Balance::TYPE_CREDIT;
                $balance->category_id =  BalanceCategory::CATEGORY_CREDIT_OTHER;
                $balance->prisoner_id = $prisonerId;
                $balance->amount = $groupedSum;
                $balance->reason = \vova07\electricity\Module::t('default','ELECTRICITY_FOR_MONTH {0, date, MMM, Y}', $deviceAccounting->to_date) ;
                $balance->atJui = date('d-m-Y');
                if ($balance->save())
                {
                    foreach ($prisonerDeviceAccountings as $deviceAccounting){
                        $deviceAccounting->status_id = DeviceAccounting::STATUS_PROCESSED;
                        $deviceAccounting->save();

                        $deviceAccountingBalance  = new DeviceAccountingBalance();
                        $deviceAccountingBalance->balance_id = $balance->primaryKey;
                        $deviceAccountingBalance->device_accounting_id = $deviceAccounting->primaryKey;
                        $deviceAccountingBalance->save();
                    }


                }

            }
            return $this->goBack();
        }
    }

    public function actionDelete($id)
    {
        if (is_null($model = DeviceAccounting::findOne($id)))
        {
            throw new NotFoundHttpException(\vova07\finances\Module::t('default',"ITEM_NOT_FOUND"));
        };
        if ($model->delete()){
            return $this->redirect(['index']);
        };
        throw new \LogicException(Module::t('default',"CANT_DELETE"));
    }

    public function actionView($id)
    {
        if (is_null($model = DeviceAccounting::findOne($id)))
        {
            throw new NotFoundHttpException(\vova07\finances\Module::t('default',"ITEM_NOT_FOUND"));
        };
        $balance = new balance();
        $balance->type_id = Balance::TYPE_CREDIT;
        $balance->category_id = BalanceCategory::CATEGORY_CREDIT_OTHER;
        $oldSourceLanguage = \Yii::$app->sourceLanguage;
        \Yii::$app->sourceLanguage = 'ru-RU';
        $balance->reason = \vova07\electricity\Module::t('default','ELECTRICITY_FOR_MONTH {0, date, MMM, Y}', $model->to_date) ;

        if ($model->device->prisoner)

            $balance->reason = \vova07\electricity\Module::t('default','ELECTRICITY_FOR_MONTH {0, date, MMM, Y}', $model->to_date) ;
        else
            $balance->reason = \vova07\electricity\Module::t('default','ELECTRICITY_FOR_MONTH {0, date, MMM, Y}', $model->to_date) . ' ' . $model->device->title ;

        \Yii::$app->sourceLanguage = $oldSourceLanguage;


        $balance->prisoner_id = $model->prisoner_id??$model->device->prisoner_id;
        $balance->amount = $model->getUnPaid();
        $balance->atJui = date('d-m-Y');

        if ($balance->load(\Yii::$app->request->post()) && $balance->validate() ){
            if ($balance->save())
            {
                $deviceAccountingBalance  = new DeviceAccountingBalance();
                $deviceAccountingBalance->balance_id = $balance->primaryKey;
                $deviceAccountingBalance->device_accounting_id = $model->primaryKey;
                $deviceAccountingBalance->save();
            }



            $this->redirect(['view', 'id' => $model->primaryKey]);
        }
        if ($model->getBalances()->sum('amount') >= $model->price)
        {
            $model->status_id = DeviceAccounting::STATUS_PROCESSED;
            $model->save();
        }

        $balanceDataProvider = new ActiveDataProvider([
            'query' => $model->getBalances()
        ]);
        return $this->render('view',['model' => $model,'balance' => $balance,'balanceDataProvider'=>$balanceDataProvider]);
    }



}
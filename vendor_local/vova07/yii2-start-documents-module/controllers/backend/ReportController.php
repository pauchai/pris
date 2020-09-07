<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\documents\controllers\backend;



use vova07\base\components\BackendController;
use vova07\countries\models\Country;
use vova07\documents\helpers\ReportHelper;
use vova07\documents\models\backend\DocumentSearch;
use vova07\documents\models\backend\Report2Model;
use vova07\documents\models\Document;
use vova07\documents\Module;
use vova07\prisons\models\Company;
use vova07\prisons\models\Prison;
use vova07\users\models\Prisoner;
use yii\base\DynamicModel;
use yii\base\Event;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\debug\models\timeline\DataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
class ReportController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => [\vova07\rbac\Module::PERMISSION_DOCUMENTS_LIST]
            ],
            [
                'allow' => true,
                'actions' => ['index2'],
                'roles' => [\vova07\rbac\Module::PERMISSION_DOCUMENTS_LIST]
            ],
            [
                'allow' => true,
                'actions' => ['index2-details'],
                'roles' => [\vova07\rbac\Module::PERMISSION_DOCUMENTS_LIST]
            ],

        ];
        return $behaviors;
    }

    public function actionIndex()
    {

        $availableTypes = [
            Document::TYPE_ID,
            Document::TYPE_USSR_PASSPORT,
            Document::TYPE_ID_PROV,
            Document::TYPE_F9,
            Document::TYPE_APPATRIDE_DOCUMENT,
            //Document::TYPE_PASSPORT,
            Document::TYPE_TRAVEL_DOCUMENT

        ];

        \Yii::$app->user->returnUrl = Url::current();
        $searchModel = new DocumentSearch();
        $searchModel->on(DocumentSearch::EVENT_BEFORE_VALIDATE, function ($ev) {
            /**
             * @var $ev Event
             */
            if (is_null($ev->sender->expiredTo))
                $ev->sender->expiredTo = time();
        });
        $searchModel->status_id = DocumentSearch::STATUS_ACTIVE;
        $dataProvider = $searchModel->search(\Yii::$app->request->get());


        $dataProvider->query->activePrisoners();


        //  if ($this->isPrintVersion)
        $dataProvider->pagination = false;


        $dataProvider->sort->defaultOrder = [
            'type_id' => SORT_ASC,
            'person.second_name' => SORT_ASC,


        ];
        $dataProvider->query->andWhere(['type_id' => $availableTypes]);

        $dataProviderActivePassports = new ActiveDataProvider();
        $dataProviderActivePassports->query = Document::find()->where(['type_id' => Document::TYPE_PASSPORT])->activePrisoners();
        $dataProviderActivePassports->pagination = false;

        $dataProviderForeigners = new ActiveDataProvider();
        $dataProviderForeigners->query = Prisoner::find()->active()->foreigners();
        $dataProviderForeigners->pagination = false;

        $dataProviderStateless = new ActiveDataProvider();
        $dataProviderStateless->query = Prisoner::find()->active()->stateless();
        $dataProviderStateless->pagination = false;

        return $this->render("index", ['searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderActivePassports' => $dataProviderActivePassports,
            'dataProviderForeigners' => $dataProviderForeigners,
            'dataProviderStateless' => $dataProviderStateless

        ]);
    }

    public function actionIndex2()
    {
        $model = new Report2Model();


        return $this->render('index2', ['model' => $model]);
    }

    public function actionIndex2Details($query_name)
    {
        $model = new Report2Model();
         $dataProvider = new ActiveDataProvider(['query' => $model->$query_name]);
         $dataProvider->pagination = false;
        return $this->render('index2details', ['searchModel' => $model, 'dataProvider' => $dataProvider]);
    }




}
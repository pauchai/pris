<?php
namespace vova07\jobs\controllers\backend;
use http\Env\Url;
use vova07\base\components\BackendController;
use vova07\events\models\Event;
use vova07\events\Module;
use vova07\humanitarians\models\backend\HumanitarianPrisonerSearch;
use vova07\humanitarians\models\HumanitarianPrisoner;
use vova07\jobs\models\backend\JobNormalizedViewDaysSearch;
use vova07\jobs\models\backend\JobPaidSearch;
use vova07\jobs\models\backend\JobsGeneralListViewSearch;
use vova07\jobs\models\Holiday;
use vova07\jobs\models\JobNormalizedViewDays;
use vova07\jobs\models\JobPaid;
use vova07\jobs\models\JobPaidForm;
use vova07\jobs\models\JobPaidList;
use vova07\jobs\models\JobPaidNormalizedViewDays;
use vova07\jobs\models\WorkDay;
use vova07\tasks\models\backend\CommitteeSearch;
use vova07\tasks\models\Committee;
use vova07\users\models\backend\PrisonerViewSearch;
use vova07\users\models\backend\User;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Expression;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class GeneralListController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index','summarized' ],
                'roles' => [\vova07\rbac\Module::PERMISSION_GENERAL_LIST_VIEW]
            ],


        ];
        return $behaviors;
    }

    public function actionIndex()
    {

        $searchModel = new JobsGeneralListViewSearch();
        //$year = $year ?? (new \DateTime())->format('Y');
        //$month_no = $month_no ?? (new \DateTime())->format('n');
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        //$dataProvider->pagination->pageSize = 30;
        //$dataProvider->query->andFilterWhere(compact(['year', 'month_no']));
        \Yii::$app->user->returnUrl = \yii\helpers\Url::current();


        return $this->render("index", ['dataProvider' => $dataProvider, 'searchModel'=>$searchModel]);
    }


    public function actionSummarized()
    {
       // $query = JobNormalizedViewDays::find()->select(['category_id', new Expression('count(*)')])->groupBy('category_id');
        $searchNormalizedDays = new JobNormalizedViewDaysSearch();
        $searchNormalizedDaysProvider = $searchNormalizedDays->search(\Yii::$app->request->get());
        list ($sql, $params) = \Yii::$app->db->getQueryBuilder()->build(
            $searchNormalizedDaysProvider->query
                ->select([ new Expression('count(DISTINCT prisoner_id) as cnt, category_id')])
                ->distinct()
                ->andWhere(new Expression('not isnull(hours)'))
                ->groupBy('category_id')
        );
        $dataProvider = new SqlDataProvider([
            //'sql' => " SELECT count(*) as cnt, category_id FROM " . JobNormalizedViewDays::tableName() . " GROUP BY category_id "
            'sql' => $sql,
            'params' => $params
        ]);



        return $this->render('summarized', ['dataProvider' => $dataProvider, 'searchNormalizedDays' => $searchNormalizedDays]);
    }


}
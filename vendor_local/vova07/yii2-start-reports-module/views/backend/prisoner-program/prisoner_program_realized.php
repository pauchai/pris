<?php

use yii\bootstrap\Html;
use vova07\reports\Module;
use vova07\prisons\models\backend\ReportSummarizedSearch;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\backend\PostSearch
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","REPORT_PRISONER_PROGRAM_REALIZED");
$this->params['subtitle'] = 'LIST';
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        //      'url' => ['index'],
    ],
    // $this->params['subtitle']
];
?>

<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],



    ]

);?>
<?php echo $this->render('_search.php', ['searchModel' => $searchModel])?>

<?php echo \yii\grid\GridView::widget(['dataProvider' => $dataProvider,
   // 'layout' => "{items}\n{pager}",


    'columns' => [
        [
            'attribute' => 'fio',
            //'group' => true,
           // 'groupRow' => true,

        ],

        [

            'content' => function($model)use ($searchModel){
                /**
                 * @var $model \vova07\users\models\PrisonerView
                 */

                $conceptQuery = $model->getConcepts();
                $eventQuery = $model->getEvents();
                $jobPaidNormilizedViewQuery = $model->getNormilizedJobsView()->paid();
                $jobNotPaidNormilizedViewQuery = $model->getNormilizedJobsView()->notPaid();
                if ($searchModel->year){
                    $conceptQuery->andWhere(
                        new \yii\db\Expression("YEAR(DATE_ADD(FROM_UNIXTIME(0), INTERVAL date_start SECOND)) = :year", [':year' => $searchModel->year])
                    );
                    $eventQuery->andWhere(
                        new \yii\db\Expression("YEAR(DATE_ADD(FROM_UNIXTIME(0), INTERVAL date_start SECOND)) = :year", [':year' => $searchModel->year])
                    );
                    $jobPaidNormilizedViewQuery->andWhere(
                        new \yii\db\Expression("YEAR(at) = :year", [':year' => $searchModel->year])
                    );
                    $jobNotPaidNormilizedViewQuery->andWhere(
                        new \yii\db\Expression("YEAR(at) = :year", [':year' => $searchModel->year])
                    );
                }

               return $this->render('_prisoner_programs_realized',['query' => $model->getPrisonerPrograms()
                   //->realized()
                   ->andFilterWhere([
                       'date_plan' => $searchModel->year
                   ]

                       //    new \yii\db\Expression("YEAR(DATE_ADD(FROM_UNIXTIME(0), INTERVAL finished_at SECOND)) = :year", [':year' => $searchModel->year])

                   )]) .
                   $this->render('_prisoner_concepts',['query' => $conceptQuery]) .
                   $this->render('_prisoner_events',['query' => $eventQuery]) .

                   $this->render('_prisoner_jobs_info',
                       [
                           'queryPaid' => $jobPaidNormilizedViewQuery,
                           'queryNotPaid' => $jobNotPaidNormilizedViewQuery
                       ]
                   ) ;



            }
        ]
    ]


])?>



<?php \vova07\themes\adminlte2\widgets\Box::end()?>


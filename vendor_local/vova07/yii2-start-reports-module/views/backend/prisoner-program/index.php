<?php

use yii\bootstrap\Html;
use vova07\reports\Module;
use vova07\prisons\models\backend\ReportSummarizedSearch;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\backend\PostSearch
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","REPORT_PRISONER_PROGRAM");
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
                return getPrisonerProgramsPlanned($model->getPrisonerPrograms()->planned()
                    ->andWhere(['date_plan' => $searchModel->year])
                    ->all());
            }
        ],
        [

            'content' => function($model)use ($searchModel){
                /**
                 * @var $model \vova07\users\models\PrisonerView
                 */
               return getPrisonerProgramsRealized($model->getPrisonerPrograms()
                       ->realized()
                       ->andWhere(
                           new \yii\db\Expression("YEAR(DATE_ADD(FROM_UNIXTIME(0), INTERVAL finished_at SECOND)) = :year", [':year' => $searchModel->year])
                       )->all()).
                   getPrisonerConcepts($model->getConcepts()
                       ->andWhere(
                           new \yii\db\Expression("YEAR(DATE_ADD(FROM_UNIXTIME(0), INTERVAL date_start SECOND)) = :year", [':year' => $searchModel->year])
                       )->all()).
                   getPrisonerEvents($model->getEvents() ->andWhere(
                       new \yii\db\Expression("YEAR(DATE_ADD(FROM_UNIXTIME(0), INTERVAL date_start SECOND)) = :year", [':year' => $searchModel->year])
                   )->all()).
                   getJobsInfo($model->getNormilizedJobsView()->andWhere(
                       new \yii\db\Expression("YEAR(at) = :year", [':year' => $searchModel->year])
                   ));
            }
        ]
    ]


])?>



<?php \vova07\themes\adminlte2\widgets\Box::end()?>


<?php
    function getPrisonerProgramsPlanned($items){
        $ret = "<ol>";
        foreach($items as $prisonerProgram){

            $ret .= "<li>" . $prisonerProgram->programDict->title . ', ' . $prisonerProgram->date_plan . "</li>";
        }

        $ret .= "</ol>";


        return $ret;
    }
    function getPrisonerProgramsRealized($items)
    {
        $ret = '<h4>' . Module::t('default', 'PROGRAMS_LABEL') . '</h4>';
        $ret .= "<ol>";;
        foreach($items as $prisonerProgram){
            /**
             * @var $prisonerProgram \vova07\plans\models\ProgramPrisoner
             */
            $ret .= "<li>" . $prisonerProgram->programDict->title .
                    Yii::$app->formatter->asDate($prisonerProgram->finished_at) .
                ', ' . Html::tag('i', $prisonerProgram->getMarkTitle() , ['class' => 'label label-' .\vova07\plans\models\ProgramPrisoner::resolveMarkStyleById($prisonerProgram->mark_id)]) . "</li>";
        }

        $ret .= "</ol>";


        return $ret;
    }
function getPrisonerConcepts($items)
{
    $ret = '<h4>' . Module::t('default', 'CONCEPTS_LABEL') . '</h4>';
    $ret .= "<ol>";
    foreach($items as $concept){

        $ret .= "<li>" . $concept->title .
            Yii::$app->formatter->asDate($concept->date_start) .
            "</li>";
    }

    $ret .= "</ol>";


    return $ret;
}
function getPrisonerEvents($items)
{
    $ret = '<h4>' . Module::t('default', 'EVENTS_LABEL') . '</h4>';
    $ret .= "<ol>";
    foreach($items as $event){

        $ret .= "<li>" . $event->title . ', ' . Yii::$app->formatter->asDate($event->date_start)  . "</li>";
    }

    $ret .= "</ol>";


    return $ret;
}
?>
<?php
function getJobsInfo($query)
{
    /**
     * @var $query \vova07\jobs\models\JobNormalizedViewDaysQuery
     * @var $queryC \vova07\jobs\models\JobNormalizedViewDaysQuery
     */
    $queryC = clone $query;
    /**
     * @var $prisoner \vova07\users\models\PrisonerView
     */
    $ret = '<h4>' . Module::t('default', 'JOBS_LABEL') . '</h4>';
    $ret .= "<ol>";
     $ret .= "<li>" . Module::t('default', 'JOBS_PAID_LABEL'). ':'. $query->paid()->count() . "</li>";
    $ret .= "<li>" . Module::t('default', 'JOBS_NOT_PAID_LABEL'). ':' . $queryC->notPaid()->sum('hours') . "</li>";


    $ret .= "</ol>";


return $ret;
}
?>

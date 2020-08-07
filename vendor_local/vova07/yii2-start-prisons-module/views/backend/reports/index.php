<?php
use kartik\form\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
use vova07\prisons\models\OfficerPost;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\backend\PostSearch
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","REPORT_LOCATIONS");
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
<?php echo $this->render('_index_search.php', ['searchModel' => $searchModel])?>

<?php echo \yii\grid\GridView::widget(['dataProvider' => $locationProvider,
    'layout' => "{items}\n{pager}",
    'caption' => "MOVMENTS",

    'columns' => [
        //['class' => yii\grid\SerialColumn::class],
        'fromSectorCount',
        'toSectorCount',
        'fromPrisonCount',
        'toPrisonCount',

    ]
])?>
<?php echo \yii\grid\GridView::widget(['dataProvider' => $terminateProvider,
    'layout' => "{items}\n{pager}",
    'caption' => "TERMINATE",
    'columns' => [
        //['class' => yii\grid\SerialColumn::class],
        [
          'content' => function($model){return \vova07\users\models\Prisoner::getStatusesForCombo($model['status_id']);}
        ],
        'prisoners_count',

    ]
])?>

<?php echo \yii\grid\GridView::widget(['dataProvider' => $programProvider,
    'layout' => "{items}\n{pager}",
    'caption' => "PROGRAMS",
    'columns' => [
        //['class' => yii\grid\SerialColumn::class],
        'program_title',
        'count_prisoners'
    ]
])?>

<?php echo \yii\grid\GridView::widget(['dataProvider' => $conceptsProvider,
    'layout' => "{items}\n{pager}",
    'caption' => "CONCEPTS",

    'columns' => [
        //['class' => yii\grid\SerialColumn::class],
        'title',
        'participants_count'
    ]
])?>

<?php echo \yii\grid\GridView::widget(['dataProvider' => $eventsProvider,
    'layout' => "{items}\n{pager}",
    'caption' => "EVENTS",

    'columns' => [
        //['class' => yii\grid\SerialColumn::class],
        'title',
        [
            'attribute' => 'participants_count',
            'content' => function($model){
                return Html::a($model['participants_count'],['/events/participants/index', 'event_id' => $model['event_id']]);
            }
        ]

    ]
])?>
<?php echo \yii\grid\GridView::widget(['dataProvider' => $jobsProvider,
    'layout' => "{items}\n{pager}",
    'caption' => "JOBS",

    'columns' => [
        //['class' => yii\grid\SerialColumn::class],
        'category_title',
        'workers_count'
    ]
])?>

<?php echo \yii\grid\GridView::widget(['dataProvider' => $committeeProvider,
    'layout' => "{items}\n{pager}",
    'caption' => "COMMITTEE",

    'columns' => [
        //['class' => yii\grid\SerialColumn::class],
        'prisoner.person.fio',
        'subject',
        'mark'
    ]
])?>




<?php \vova07\themes\adminlte2\widgets\Box::end()?>



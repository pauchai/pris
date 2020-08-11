<?php

use yii\bootstrap\Html;
use vova07\prisons\Module;
use vova07\prisons\models\backend\ReportSummarizedSearch;
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
    'caption' => Module::t('labels', "MOVEMENTS_LABEL"),

    'columns' => [
        //['class' => yii\grid\SerialColumn::class],
        [
            'header' => Module::t('report','FROM_SECTOR_COUNT_LABEL'),
//            'value' => 'fromSectorCount'
            'content' => function($model)use ($searchModel){return Html::a($model['fromSectorCount'],['participants','method' => 'getFromSectorQuery', 'ReportSummarizedSearch'=> $searchModel]);}

        ],
        [
            'header' => Module::t('report','TO_SECTOR_COUNT_LABEL'),
            //'value' => 'toSectorCount'
              'content' => function($model)use ($searchModel){return Html::a($model['toSectorCount'],['participants','method' => 'getToSectorQuery', 'ReportSummarizedSearch'=> $searchModel]);}

        ],
        [
            'header' => Module::t('report','FROM_PRISON_COUNT_LABEL'),
            //'value' => 'fromPrisonCount'
            'content' => function($model)use ($searchModel){return Html::a($model['fromPrisonCount'],['participants','method' => 'getFromPrisonQuery', 'ReportSummarizedSearch'=> $searchModel]);}
        ],
        [
            'header' => Module::t('report','TO_PRISON_COUNT_LABEL'),
            //'value' => 'toPrisonCount'
            'content' => function($model)use ($searchModel){return Html::a($model['toPrisonCount'],['participants', 'method' => 'getToPrisonQuery' , 'ReportSummarizedSearch'=> $searchModel]);}
        ],

    ]
])?>
<?php echo \yii\grid\GridView::widget(['dataProvider' => $terminateProvider,
    'layout' => "{items}\n{pager}",
    'caption' =>  Module::t('labels', "TERMINATE_LABEL"),
    'columns' => [
        //['class' => yii\grid\SerialColumn::class],
        [
          'content' => function($model){return \vova07\users\models\Prisoner::getStatusesForCombo($model['status_id']);}
        ],
        [
            'header' => Module::t('report','PRISONERS_COUNT_LABEL'),
//            'value' => 'prisoners_count'
            'content' => function($model)use ($searchModel){return Html::a($model['prisoners_count'],['participants', 'method' => 'getTerminateQuery', 'arg' => [$model['status_id']] ,'ReportSummarizedSearch'=> $searchModel]);}

        ],


    ]
])?>

<?php echo \yii\grid\GridView::widget(['dataProvider' => $programProvider,
    'layout' => "{items}\n{pager}",
    'caption' =>  Module::t('labels', "PROGRAMS_LABEL"),
    'columns' => [
        //['class' => yii\grid\SerialColumn::class],
        [
            'header' => Module::t('report','PROGRAM_TITLE_LABEL'),
            'value' => 'program_title'
        ],
        [
            'header' => Module::t('report','PRISONERS_COUNT'),
            //'value' => 'count_prisoners'
            'content' => function($model)use ($searchModel){return Html::a($model['count_prisoners'],['participants', 'method' => 'getProgramsQuery', 'arg' => [$model['programdict_id']] ,'ReportSummarizedSearch'=> $searchModel ]);}

        ],

    ]
])?>

<?php echo \yii\grid\GridView::widget(['dataProvider' => $conceptsProvider,
    'layout' => "{items}\n{pager}",
    'caption' =>  Module::t('labels', "CONCEPTS_LABEL"),

    'columns' => [
        //['class' => yii\grid\SerialColumn::class],

        [
            'header' => Module::t('labels', "TITLE_LABEL"),
            'value' => 'title'
        ] ,
        [
            'header' => Module::t('labels', "PARTICIPANTS_COUNT_LABEL"),
//            'value' => 'participants_count'
            'content' => function($model)use ($searchModel){return Html::a($model['participants_count'],['participants', 'method' => 'getConceptsQuery', 'arg' => [$model['concept_id']] ,'ReportSummarizedSearch'=> $searchModel  ]);}
        ] ,

    ]
])?>

<?php echo \yii\grid\GridView::widget(['dataProvider' => $eventsProvider,
    'layout' => "{items}\n{pager}",
    'caption' =>  Module::t('labels', "EVENTS_LABEL"),

    'columns' => [
        //['class' => yii\grid\SerialColumn::class],
        [
            'header' => Module::t('labels', "TITLE_LABEL"),
            'value' => 'title'
        ] ,
        [
            'header' => Module::t('labels', "PARTICIPANTS_COUNT_LABEL"),

           // 'attribute' => 'participants_count',
           'content' => function($model)use ($searchModel){return Html::a($model['participants_count'],['participants', 'method' => 'getEventsQuery', 'arg' => [$model['event_id']] ,'ReportSummarizedSearch'=> $searchModel  ]);}

        ]

    ]
])?>
<?php echo \yii\grid\GridView::widget(['dataProvider' => $jobsProvider,
    'layout' => "{items}\n{pager}",
    'caption' =>  Module::t('labels', "JOBS_LABEL"),

    'columns' => [
        //['class' => yii\grid\SerialColumn::class],

        [
            'header' => Module::t('labels', "CATEGORY_TITLE_LABEL"),
            'value' => 'category_title'
        ] ,
        [
            'header' => Module::t('labels', "WORKERS_COUNT_LABEL"),
            //'value' => 'workers_count'
            'content' => function($model)use ($searchModel){return Html::a($model['workers_count'],['participants', 'method' => 'getJobsQuery', 'arg' => [$model['category_id']] ,'ReportSummarizedSearch'=> $searchModel  ]);}


        ] ,
    ]
])?>

<?php echo \yii\grid\GridView::widget(['dataProvider' => $committeeProvider,
    'layout' => "{items}\n{pager}",
    'caption' =>  Module::t('labels', "COMMITTEE_LABEL"),

    'columns' => [
        //['class' => yii\grid\SerialColumn::class],
        'assignedTo.person.fio',
        'prisoner.person.fio',
        'subject',
        'mark',
    ]
])?>




<?php \vova07\themes\adminlte2\widgets\Box::end()?>



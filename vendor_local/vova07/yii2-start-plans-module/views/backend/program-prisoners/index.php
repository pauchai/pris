<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use kartik\grid\GridView;
$this->title = \vova07\plans\Module::t("default","PROGRAM-PRISONER");
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
        'buttonsTemplate' => '{create}'
    ]

);?>

<?php echo GridView::widget(['dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],

        [
            'attribute' => 'programdict_id',
            'value' => 'programDict.title',
              'filter' => \vova07\plans\models\ProgramDict::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
    'pluginOptions' => ['allowClear' => true],
],
            'filterInputOptions' => ['prompt' => \vova07\plans\Module::t('default','SELECT_PROGRAMS_PROMPT'), 'class'=> 'form-control', 'id' => null]

        ],

        [
          'attribute' => 'planned_by',
          'value' => 'plannedBy.person.fio',
            'filter' => \vova07\users\models\Officer::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => \vova07\plans\Module::t('default','SELECT_OFFICER_PROMPT'), 'class'=> 'form-control', 'id' => null]


        ],


      //  'prison.company.title',
        [
            'attribute' => 'prisoner_id',
            'value' => 'prisoner.fullTitle',
            'filter' => \vova07\users\models\Prisoner::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => \vova07\plans\Module::t('default','SELECT_PRISONER_PROMPT'), 'class'=> 'form-control', 'id' => null]
        ],

        'date_plan',
        [
            'attribute' => 'status_id',
            'value' => 'status',
            'filter' => false,//\vova07\plans\models\ProgramPrisoner::getStatusesForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => \vova07\plans\Module::t('default','SELECT_STATUS_FILTER_PROMPT'), 'class'=> 'form-control', 'id' => null]


        ],
        [
            'attribute' => 'mark_id',
            'value' => 'markTitle',
            'filter' => false, //\vova07\plans\models\ProgramPrisoner::getMarksForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => \vova07\plans\Module::t('default','SELECT_MARKS_FILTER_PROMPT'), 'class'=> 'form-control', 'id' => null]

        ],

        [
            'header' => \vova07\plans\Module::t('default','CREATED_BY'),
            'attribute' => 'ownableitem.created_by',
            'value' => 'ownableitem.createdBy.user.username',
            'filter' => \vova07\users\models\User::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => \vova07\plans\Module::t('default','SELECT_USER_PROMPT'), 'class'=> 'form-control', 'id' => null]

        ],
        [

            'attribute' => 'program.assignedTo.person.fio',


        ],

        [
            'class' => \kartik\grid\ActionColumn::class
        ]
    ]
])?>


<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

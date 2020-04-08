<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\jobs\Module;
use kartik\grid\GridView;
use vova07\jobs\models\backend\JobNormalizedViewDaysSearch;

//use yii\grid\GridView;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","JOBS_GENERAL_LIST");
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

<?php
    $columns = [
        ['class' => yii\grid\SerialColumn::class],
        ['attribute' => 'prisoner_id',
            'value' => function($model){ return $model->prisoner->getFullTitle(true);},
            'filter' => \vova07\users\models\Prisoner::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'attribute' => 'prisoner_id',
                'pluginOptions' => ['allowClear' => true ],
            ],
            'filterInputOptions' => [ 'prompt' => Module::t('default','SELECT_PRISONER'), 'class'=> 'no-print form-control', 'id' => null],

        ],


        ];
    $gridBeforeColumns = [
        [],
        []
    ];

    for ($i=1; $i<=12; $i++){
        $columns[] = [

            'content' => function($model)use($i){ $attribute = 'm'. $i . 'p'; return $model->$attribute;} ,
            'contentOptions' => [
                'class' => 'bg-gray',
                'align' => 'center'


            ],
            'headerOptions' => [
                'class' => 'bg-gray',
                'style' => 'text-align:center; font-weight:bolder'
            ],
            'header' => Html::a('О',['/jobs/default/index', 'JobPaidSearch[month_no]'=>$i, 'JobPaidSearch[year]'=>$searchModel['year']]),

        ];
        $columns[] = [
            'content' => function($model)use($i){ $attribute = 'm'. $i . 'np'; return $model->$attribute;} ,
            'contentOptions' => [
                'align' => 'center'
            ],
            'headerOptions' => [

                'style' => 'text-align:center;font-weight:bolder'
            ],
            'header' => Html::a('Н',['/jobs/not-paid-jobs/index', 'JobNotPaidSearch[month_no]'=>$i, 'JobNotPaidSearch[year]'=>$searchModel['year']],['align'=>'center'])
        ];
        $gridBeforeColumns[] = [
            'content' =>\vova07\site\Module::t('calendar','MONTH_' . $i),
            'options' => [
                'colspan' => 2,
                'style' => 'text-align:center; ',
                'class' => 'label-warning'
                //'class' => 'label-danger'
            ]
        ];

    }
$gridBeforeColumns[] = ['visible' => !$searchModel['year']];

$columns[] = [
    'attribute' => 'year',
    'visible' => !$searchModel['year'],
    'filter' => false,
];

    $gridBeforeHeader = [
    [
        'columns' => $gridBeforeColumns
    ]
    ];
?>
<?php echo $this->render('_search', ['model' => $searchModel])?>

<?php
    if ($searchModel['year']){
        $searchJobNormilizedDays = new JobNormalizedViewDaysSearch();
        $searchJobNormilizedDays->atFromJui = (new DateTime())->setDate($searchModel['year'], 1,1)->format('d-m-Y');
        $searchJobNormilizedDays->atToJui = (new DateTime())->format('d-m-Y');

        echo Html::a(
            Module::t('default','SUMMARIZED_JOBS_INFO'),
            ['summarized',
                'JobNormalizedViewDaysSearch[atFromJui]' => $searchJobNormilizedDays->atFromJui,
                'JobNormalizedViewDaysSearch[atToJui]' => $searchJobNormilizedDays->atToJui
            ]
        );
    }

?>

<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'beforeHeader' =>  $gridBeforeHeader,
    'columns' => $columns
])?>
<?php \vova07\themes\adminlte2\widgets\Box::end()?>



<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\jobs\Module;
use kartik\grid\GridView;
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
        'prisoner.fullTitle',
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

    $gridBeforeHeader = [
    [
        'columns' => $gridBeforeColumns
    ]
    ];
?>
<?php echo $this->render('_search', ['model' => $searchModel])?>


<?php echo GridView::widget(['dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'beforeHeader' =>  $gridBeforeHeader,
    'columns' => $columns
])?>
<?php \vova07\themes\adminlte2\widgets\Box::end()?>


<span class="bg"
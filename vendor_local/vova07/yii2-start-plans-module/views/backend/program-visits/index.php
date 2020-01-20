<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","PROGRAM_VISITS");
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
<?php //echo $this->render('_search', ['model' => $searchModel])?>

<?php echo \kartik\grid\GridView::widget(['dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        'programPrisoner.prisoner.fullTitle',

        [
            'attribute' => 'program_pisoner_id',
            'value' => function($model){
                return $model->programPrisoner->program->programDict->title;
                },
            'filter' => \vova07\plans\models\ProgramPrisoner::getListForCombo(),
            'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => \vova07\plans\Module::t('default','SELECT_PRISONER'), 'class'=> 'form-control', 'id' => null],
            'group' => true,
        ],
        'date_visit',
        'status',


        /*[
          'attribute' => 'date_issue',
            'format' => 'date',
            'filter' => \kartik\widgets\DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'issuedFromJui',
                'attribute2' => 'issuedToJui',
                'type' => \kartik\widgets\DatePicker::TYPE_RANGE,
                'separator' => '-',
                'pluginOptions' => [
                    'allowClear' => true,
                    'format' => 'dd-mm-yyyy']
            ])
        ],*//*[
          'attribute' => 'date_issue',
            'format' => 'date',
            'filter' => \kartik\widgets\DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'issuedFromJui',
                'attribute2' => 'issuedToJui',
                'type' => \kartik\widgets\DatePicker::TYPE_RANGE,
                'separator' => '-',
                'pluginOptions' => [
                    'allowClear' => true,
                    'format' => 'dd-mm-yyyy']
            ])
        ],*/


        [
            'class' => \yii\grid\ActionColumn::class,
            'visible' => !$this->context->isPrintVersion,
            'buttons' => [
                'departments' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', ['company-departments/index','CompanyDepartment[company_id]' => $key], [
                        'title' => \vova07\prisons\Module::t('default', 'DEPARTMENTS'),
                        'data-pjax' => '0',
                    ]);
                },
            ],

        ]
    ]
])?>
<?php \vova07\themes\adminlte2\widgets\Box::end()?>

<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
use kartik\grid\GridView;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","PRISONERS_LOCATION_JOURNAL_TITLE");
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
          'attribute' => 'prisoner_id',
          'value'  => 'prisoner.person.fio',
            'filter' => \vova07\users\models\Prisoner::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => [
                    'allowClear' => true,
                ]

            ],
            'filterInputOptions' => ['prompt' =>  Module::t('default','SELECT_PRISONER_FILTER'), 'class'=> 'form-control', 'id' => null],

        ],

        'prison.company.title',
        'sector.title',
        'cell.number',
        'at:date',
        ['class' => yii\grid\ActionColumn::class]
    ]
])?>
<?php \vova07\themes\adminlte2\widgets\Box::end()?>



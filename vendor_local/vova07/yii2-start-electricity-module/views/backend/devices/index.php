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

$this->title = \vova07\plans\Module::t("default","ELECTRICITY_DEVICES_TITLE");
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
        'title',
        [
            'attribute' => 'prisoner_id',
            'value' => 'prisoner.person.fio',
            'filter' => \vova07\users\models\Prisoner::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true ],
            ],
            'filterInputOptions' => [ 'prompt' => \vova07\electricity\Module::t('default','SELECT_PRISONER'), 'class'=> 'no-print form-control', 'id' => null],
         ],
        'assigned_at:date',
        'unassigned_at:date',
        'sector.title',
        'cell.number',
        'power',
        'enable_auto_calculation',
        'calculationMethod',
        ['class' => \yii\grid\ActionColumn::class]

    ]
])?>


<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

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
$this->params['subtitle'] = '';
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
<?php echo $this->render('_search',['model' => $searchModel])?>

<?php echo GridView::widget(['dataProvider' => $dataProvider,
  //  'filterModel' => $searchModel,
    'striped' => true,
    'hover' => true,
    'showPageSummary' => true,
   // 'panel' => ['type' => 'primary', 'heading' => $this->title],
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
            [
            'attribute' => 'prisoner_id',
                'value' => function($model){
                    if ($model->prisoner)
                        return $model->prisoner->person->getFio(false, true);
                    else
                        return null;
                },
             'group' => true,
            'groupedRow' => true,
            'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
            'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class

        ],
        'title',

        'assigned_at:date',
        'unassigned_at:date',
        'prisoner.sector.title',
        'prisoner.cell.number',
        'power',
        'enable_auto_calculation',
        'calculationMethod',
        ['class' => \yii\grid\ActionColumn::class]

    ]
])?>


<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

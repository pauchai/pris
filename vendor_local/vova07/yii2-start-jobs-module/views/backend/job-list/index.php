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

$this->title = \vova07\plans\Module::t("default","JOB_LIST_TITLE");
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
          'attribute' => 'assigned_to',
          'value' => 'assignedTo.fullTitle',
            'filter' => \vova07\users\models\Prisoner::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true ],
            ],
            'filterInputOptions' => [ 'prompt' => \vova07\jobs\Module::t('default','SELECT_PRISONER'), 'class'=> 'no-print form-control', 'id' => null],

        ],

        'type.title',

        [
            'attribute'=>'half_time',
            'format'=> 'html',
            'value' => function($model){
                $className = $model->half_time?'fa fa-check':'';
                return \yii\bootstrap\Html::tag('i','',['class'=>$className . ' text-success']);
            }
        ],
        'assigned_at:date',
        'deleted_at:date',
        'comment',
        [
            'attribute' => 'status_id',
            'value' => 'status',
            'filter' => \vova07\jobs\models\JobPaidList::getStatusesForCombo()
        ],



        ['class' => \yii\grid\ActionColumn::class]

    ]
])?>


<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

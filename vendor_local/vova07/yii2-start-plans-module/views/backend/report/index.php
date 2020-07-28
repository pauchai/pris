<?php
use vova07\plans\models\PrisonerPlanView;
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

$this->title = \vova07\plans\Module::t("default","PRISONER_PLAN_TITLE");
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

<?php echo \yii\grid\GridView::widget(['dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        'fio',
        'prisoner.status',
        [
            'attribute' => 'status_id',
            'filter' => PrisonerPlanView::getStatusesForCombo(),
            'content' => function($model){return $model->status;}
        ],
        'assigned_at:date',
        'date_finished:date'



    ]
])?>


<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

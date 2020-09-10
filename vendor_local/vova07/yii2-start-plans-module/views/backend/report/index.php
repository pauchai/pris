<?php
use vova07\plans\models\PrisonerPlanView;
use kartik\grid\GridView;
use kartik\date\DatePicker;
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

<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        'fio',

        [
            'attribute' => 'prisoner.status_id',
            'value' =>  "prisoner.status",
            'filter' => \vova07\users\models\Prisoner::getStatusesForCombo(),


        ]
        ,
        [
            'attribute' => 'status_id',
            'filter' => PrisonerPlanView::getStatusesForCombo(),
            'content' => function($model){return $model->status;}
        ],

        [


            'attribute' => 'assignedAtJui',
            //'filterType' => GridView::FILTER_DATE_RANGE,
            'filter' => DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'assignedAtFromJui',
                'attribute2' =>'assignedAtToJui',
                'type' => DatePicker::TYPE_RANGE,
                'separator' => '-',
                'pluginOptions' => [
                    'allowClear' => true,
                    'format' => 'dd-mm-yyyy']
            ])
        ],


        'date_finished:date'



    ]
])?>


<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

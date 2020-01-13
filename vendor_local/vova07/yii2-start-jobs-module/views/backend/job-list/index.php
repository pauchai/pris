<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

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

<?php echo \yii\grid\GridView::widget(['dataProvider' => $dataProvider,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        'assignedTo.person.fio',
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

        ['class' => \yii\grid\ActionColumn::class]

    ]
])?>


<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

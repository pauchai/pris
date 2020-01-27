<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
use vova07\events\Module;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;

//$this->title = Module::t("default","EVENTS_TITLE");
$this->params['subtitle'] = Module::t("default","EVENTS_LIST_TITLE");
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
<?=$this->render('_search',['model' => $searchModel])?>

<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'filterModel' => $searchModel,

    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        'date_start:date',
        'title',
        [
          'attribute' => 'category_id',
            'value' => 'category',
            'class' => \yii\grid\DataColumn::class,
            'filter' => \vova07\events\models\Event::getCategoriesForCombo(),
        ],
        [
            'header' => \yii\bootstrap\Html::tag('span','',['class' => 'fa fa-users'] ),
            'content' => function($model){
                return \yii\bootstrap\Html::a($model->getParticipants()->count(), ['participants/index','event_id' => $model->primaryKey], [
                    'title' => \vova07\plans\Module::t('default', 'EVENT_PARTICIPANTS'),
                    'data-pjax' => '0',
                    'class'=>'btn btn-primary'
                ]);
                return ;
            }
        ],
        [
            'attribute'=>'status_id',
            'content' => function($model){
                if ($model->status_id === \vova07\events\models\Event::STATUS_PLANING){
                    $options = ['class'=>'label label-info'];
                } elseif ($model->status_id === \vova07\events\models\Event::STATUS_FINISHED) {
                    $options = ['class'=>'label label-success'];
                } else {
                    $options = ['class' => 'label label-default'];
                };
                return \yii\helpers\Html::tag('span',$model->status,$options);
            },
            'filter' => \vova07\events\models\Event::getStatusesForCombo(),

        ],
        'assigned.person.fio',



        [
            'class' => ActionColumn::class,

        ]
    ]
])?>


<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

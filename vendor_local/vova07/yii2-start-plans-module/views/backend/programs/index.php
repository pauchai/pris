<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
use vova07\plans\Module;

$this->title = Module::t("default","PROGRAM");
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
        [
            'attribute' => 'programdict_id',
            'value' => 'programDict.title',
            'filter' => \vova07\plans\models\ProgramDict::getListForCombo(),

        ],
        'prison.company.title',
        'order_no',
        'date_start',
        'date_finish',
        [
            'attribute' => 'assigned_to',
            'value' => 'assignedTo.person.fio'
        ],

        [
            'header' => \yii\bootstrap\Html::tag('span','',['class' => 'fa fa-users'] ),
            'content' => function($model){
                return \yii\bootstrap\Html::a( $model->getParticipants()->forPrisonersActiveAndEtapped()->count(), ['view','id' => $model->primaryKey], [
                    'title' => Module::t('default', 'PROGRAM_PARTICIPANTS'),
                    'data-pjax' => '0',
                    'class'=>'btn btn-primary'
                ]);
                return ;
            }

        ],

        [
          'attribute' => 'status_id',
          'content' => function($model){
                if ($model->status_id == \vova07\plans\models\Program::STATUS_FINISHED)
                return \yii\bootstrap\Html::tag('span', $model->status,[
                    'class' => 'label label-success'
                ]);
                    Else
                        return \yii\bootstrap\Html::tag('span', $model->status,[
                            'class' => 'label label-default'
                        ]);
            },
            'filter' => \vova07\plans\models\Program::getStatusesForCombo(),

        ],

        [
            'class' => yii\grid\ActionColumn::class,
            'template' => '{view} {update} {participants} {delete}',
/*            'buttons' => [
                'participants' => function ($url, $model, $key) {
                    return \yii\bootstrap\Html::a('<span class="fa fa-users"></span>', ['program-prisoners/participants','program_id' => $key], [
                        'title' => \vova07\plans\Module::t('default', 'PROGRAM_PARTICIPANTS'),
                        'data-pjax' => '0',
                    ]);
                },
            ],*/
        ]
    ]
])?>


<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

$this->title = \vova07\plans\Module::t("default","PROGRAM");
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
        'programDict.title',
        'prison.company.title',
        'order_no',
        'date_start',
        'date_finish',
        [
            'header' => '',
            'content' => function($model){return $model->getParticipants()->count();}
        ],
        'status',
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

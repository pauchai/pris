<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

$this->title = \vova07\plans\Module::t("default","EVENTS_TITLE");
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
//        'buttonsTemplate' => '{create}'
    ]

);?>

<?php echo \yii\grid\GridView::widget(['dataProvider' => $dataProvider,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        'title',
        [
            'class' => yii\grid\ActionColumn::class,
            'template' => '{participants}',
            'buttons' => [
                'participants' => function ($url, $model, $key) {
                    return \yii\bootstrap\Html::a('<span class="fa fa-users"></span>', ['participants/index','event_id' => $key], [
                        'title' => \vova07\plans\Module::t('default', 'EVENT_PARTICIPANTS'),
                        'data-pjax' => '0',
                    ]);
                },
            ],

        ]
    ]
])?>


<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

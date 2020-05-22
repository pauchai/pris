<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","COMPANIES");
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
        'title',
        [
            'class' => \yii\grid\ActionColumn::class,
            'template' => '{view} {update} {divisions} {delete}',
            'buttons' => [
                'divisions' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', ['divisions/index','DivisionSearch[company_id]' => $key], [
                        'title' => \vova07\prisons\Module::t('default', 'DIVISIONS'),
                        'data-pjax' => '0',
                    ]);
                },
            ],

        ]
    ]
])?>
<?php \vova07\themes\adminlte2\widgets\Box::end()?>



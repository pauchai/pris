<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","PRISON_SECTORS");
$this->params['subtitle'] = $model->company->title;
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
        'buttonsTemplate' => '{cells}{create}',
         'buttons' => [
            'create' => [
                'url' => ['create','prison_id' => $model->primaryKey],
                'icon' => 'fa-plus',
                'options' => [
                    'class' => 'btn-default',
                    'title' => Yii::t('vova07/themes/adminlte2/widgets/box', 'Create'),

                ]
            ],

          ]


    ]

);?>

<?php echo \yii\grid\GridView::widget(['dataProvider' => $dataProvider,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        'title',
        [
            'header' => 'cells',
            'content' => function($model){return $model->getCells()->count();}
        ],
        [
            'class' => \yii\grid\ActionColumn::class,
            'template' => '{cells}{update}{delete}',
            'buttons' => [

                'cells' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', ['cells/index','sector_id' => $key], [
                        'title' => \vova07\prisons\Module::t('default', 'COMPANY_SECTORS'),
                        'data-pjax' => '0',
                    ]);
                },
            ]
        ]
    ]
])?>
<?php \vova07\themes\adminlte2\widgets\Box::end()?>



<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","SECTOR_CELLS");
$this->params['subtitle'] = $searchModel->sector->title;
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
       'url' => ['index'],
    ],
   // $this->params['subtitle']
];
?>

<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{create}',
         'buttons' => [
            'create' => [
                'url' => ['create','sector_id' => $searchModel->sector->primaryKey],
                'icon' => 'fa-plus',
                'options' => [
                    'class' => 'btn-default',
                    'title' => Yii::t('vova07/themes/adminlte2/widgets/box', 'Create'),

                ]
            ]
          ]


    ]

);?>

<?php echo \yii\grid\GridView::widget(['dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        'number',
        'square',
        [
            'header' => 'prisoners count',
            'content' => function($model){
                return $model->getPrisoners()->count();
            }
        ],

        [
            'attribute' => 'squarePerPrisoner',
            'filter' => \vova07\prisons\models\Cell::getSquarePerPrisonerForCombo(),
            'content' => function($model)use ($searchModel){
                return $model->getEstimatePrisonersCount($searchModel->squarePerPrisoner);
            }
        ],

        [
            'class' => \yii\grid\ActionColumn::class,
            'template' => '{view}{update}{delete}'
        ]
    ]
])?>
<?php \vova07\themes\adminlte2\widgets\Box::end()?>



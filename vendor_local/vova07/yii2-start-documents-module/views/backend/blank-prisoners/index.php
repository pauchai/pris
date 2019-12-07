<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \vova07\documents\models\backend\BlankPrisonerSearch
 */
$this->title = Module::t("default","DOCUMENTS_PLAIN");
$this->params['subtitle'] = 'LIST';
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
  //      'url' => ['index'],
    ],
   // $this->params['subtitle']
];
?>

<?php
    $urlParams[$searchModel->formName()] = $searchModel->attributes;
    $urlParams[0] = 'create';

?>

<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{create}',
         'buttons' => [
            'create' => [
                'url' => $urlParams,
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
        'prisoner.person.fio',
        'blank.title',

        [
            'class' => \yii\grid\ActionColumn::class,

        ]
    ]
])?>
<?php \vova07\themes\adminlte2\widgets\Box::end()?>



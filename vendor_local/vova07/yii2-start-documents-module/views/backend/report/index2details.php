<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\documents\models\backend\Report2Model
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","REPORT2_DETAILS");
$this->params['subtitle'] = $searchModel->getAttributeLabel(Yii::$app->request->get('query_name'));
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

    ]

);?>

<?php echo \kartik\grid\GridView::widget(['dataProvider' => $dataProvider,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        [
            'attribute' => 'person_id',
            'value' => function($model){return $model->person->prisoner->getFullTitle(true);},
            'filter' => \vova07\users\models\Prisoner::getListForCombo(),
            'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => \vova07\plans\Module::t('default','SELECT_PRISONER'), 'class'=> 'form-control', 'id' => null],
            'group' => true,
        ],
        'person.IDNP',

    ]
])?>
<?php \vova07\themes\adminlte2\widgets\Box::end()?>

<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Cell
 */
$this->title = Module::t("default","CELL");
$this->params['subtitle'] = $model->sector->title . ':' . $model->number;
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
?>
<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{update}{delete}'
    ]
);?>

<?php echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'square',
        ]
])?>

<?php echo \kartik\grid\GridView::widget([
    'dataProvider' => $dataProvider,
   'columns' => [
        'fullTitle'
   ]
]);



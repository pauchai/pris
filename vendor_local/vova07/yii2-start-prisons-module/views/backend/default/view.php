<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 */
$this->title = Module::t("default","PRISONS");
$this->params['subtitle'] = 'SUBTITLE';
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
       // 'renderBody' => false,
        //'options' => [
        //    'class' => 'box-primary'
        //],
        //'bodyOptions' => [
        //    'class' => 'table-responsive'
        //],
        'buttonsTemplate' => '{update}{delete}'
    ]
);?>

<?php $box->beginBody();?>

<?php echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'company.title',
        'company.address',
        'company.address2'
    ]
])?>

<?php  $box->endBody()?>

<?php $box->beginFooter();?>

<?php $box->endFooter();?>



<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\plans\models\Program
 */
$this->title = Module::t("default","Program");
$this->params['subtitle'] = $model->programDict->title;
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
        'programDict.title',
        'prison.company.title',
        'order_no',
        'date_start',
        'status'
    ]
])?>

<?=$this->render('_participants', ['model' => $model,'dataProvider'=>$dataProvider])?>



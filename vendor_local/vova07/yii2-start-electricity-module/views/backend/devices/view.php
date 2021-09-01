<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\electricity\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\plans\models\Program
 */
$this->title = Module::t("default","DEVICES_TITLE");
$this->params['subtitle'] = $model->title;
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
        'buttonsTemplate' => '{update}'
    ]
);?>

<?php echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'title',
        'prisoner.sector.title',
        'prisoner.cell.number',
        'power',
        'prisoner.fullTitle',
        'assigned_at:date',
        'unassigned_at',
        'status',
        'enable_auto_calculation:boolean',
        'calculationMethod'

    ]
])?>



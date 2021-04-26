<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\socio\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\socio\models\MaritalState
 */
$this->title = Module::t("default","MARITAL_STATE");
$this->params['subtitle'] = $model->person->fio  ;
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
        'person.fio',
        'refPerson.fio',
        'status.title',
        'document.type'

    ]
])?>



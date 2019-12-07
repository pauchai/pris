<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
use vova07\themes\adminlte2\widgets\Box;
use yii\widgets\DetailView;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\PrisonerSecurity
 */
$this->title = Module::t("default","PRISONER_SECURITY_TITLE");
$this->params['subtitle'] = $model->type . "(" . $model->prisoner->person->fio  . ')' ;
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
?>
<?php $box = Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{update}{delete}'
    ]
);?>

<?php echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'prisoner.person.fio',
        'type',
        'dateStartJui',
        'dateEndJui',

    ]
])?>



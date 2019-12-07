<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\documents\models\Document
 */
$this->title = Module::t("default","DOCUMENT");
$this->params['subtitle'] = $model->type . "(" . $model->person->fio  . ')' ;
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
        'type',
        'person.fio',
        'country.iso',
        'seria',
        'IDNP',
      //  'status',
        'dateIssueJui',
        'dateExpirationJui',
    ]
])?>



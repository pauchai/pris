<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\concepts\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
  */
$this->title = Module::t("default","CONCEPT_TITLE");
$this->params['subtitle'] = 'CREATE_NEW';
?>


<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{cancel}'
    ]
);?>

<?php echo $this->render('_dict_form', ['model' => $model, 'box' => $box])?>

<?php \vova07\themes\adminlte2\widgets\Box::end(); ?>





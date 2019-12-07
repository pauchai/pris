<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
use vova07\themes\adminlte2\widgets\Box;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
  */
$this->title = Module::t("default","PRISONER_SECURITY_TYTLE");
$this->params['subtitle'] = $model->type . "(" . $model->prisoner->person->fio  . ')' ;
?>


<?php $box = Box::begin(
    [
       'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{cancel}{delete}'
    ]
);?>

<?php echo $this->render('_form', ['model' => $model, 'box' => $box])?>

<?php Box::end(); ?>





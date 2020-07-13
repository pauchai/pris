<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
  */
$this->title = Module::t("default","OFFICERS");
$this->params['subtitle'] = Module::t('default','CREATE_NEW_OFFICER');
?>


<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{close}'
    ]
);?>

<?php echo $this->render('_officer_form', ['model' => $model, 'box' => $box])?>

<?php \vova07\themes\adminlte2\widgets\Box::end(); ?>





<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\events\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
  */
$this->title = Module::t("default","DISABILITY_TITLE");
$this->params['subtitle'] = $model->title;
?>


<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
       'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{cancel}{delete}'
    ]
);?>

<?php echo $this->render('_form', ['model' => $model, 'box' => $box])?>

<?php \vova07\themes\adminlte2\widgets\Box::end(); ?>





<?php
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Penalty
  */
$this->title = Module::t("default","DEVICE_PAGE_TITLE");
$this->params['subtitle'] = $model->comment;
?>


<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
       'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{cancel}{delete}'
    ]
);?>

<?php echo $this->render('_form', ['model' => $model, 'box' => $box])?>

<?php \vova07\themes\adminlte2\widgets\Box::end(); ?>





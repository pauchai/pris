<?php

use vova07\prisons\Module;
use vova07\themes\adminlte2\widgets\Box;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
  */
$this->title = Module::t("default","PRISONER SECURITY");
$this->params['subtitle'] = 'NEW';
?>


<?php $box = Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{cancel}'
    ]
);?>

<?php echo $this->render('_form', ['model' => $model, 'box' => $box])?>

<?php Box::end(); ?>





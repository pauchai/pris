<?php

use vova07\humanitarians\Module;
use vova07\themes\adminlte2\widgets\Box;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\humanitarians\models\HumanitarianIssue
  */
$this->title = Module::t("default","HUMANITARIAN_ISSUES_TITLE");
$this->params['subtitle'] = Module::t("default","CREATE_NEW")
?>


<?php $box = Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{cancel}'
    ]
);?>

<?php echo $this->render('_form', ['model' => $model, 'box' => $box])?>

<?php Box::end(); ?>





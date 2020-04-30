<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\jobs\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\jobs\models\JobPaidForm
  */
$this->title = Module::t("default","JOBS_TITLE");
$this->params['subtitle'] = Module::t("default","CREATE_NEW")
?>


<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{cancel}'
    ]
);?>

<?php echo $this->render('_form', ['model' => $model, 'box' => $box])?>

<?php \vova07\themes\adminlte2\widgets\Box::end(); ?>





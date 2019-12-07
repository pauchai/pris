<?php
use vova07\themes\adminlte2\widgets\Box;
use vova07\humanitarians\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\humanitarians\models\HumanitarianItem
  */
$this->title = Module::t("default","HUMANITARIAN_ITEMS_TITLE");
$this->params['subtitle'] = $model->title;
?>


<?php $box = Box::begin(
    [
       'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{cancel}{delete}'
    ]
);?>

<?php echo $this->render('_form', ['model' => $model, 'box' => $box])?>

<?php Box::end(); ?>





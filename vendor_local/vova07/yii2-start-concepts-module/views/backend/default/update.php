<?php
use vova07\concepts\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\concepts\models\Concept
  */
$this->title = Module::t("default","CONCEPTS_TITLE");
$this->params['subtitle'] = $model->dict->title;
?>


<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
       'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{cancel}{delete}'
    ]
);?>

<?php echo $this->render('_form', ['model' => $model, 'box' => $box])?>

<?php \vova07\themes\adminlte2\widgets\Box::end(); ?>





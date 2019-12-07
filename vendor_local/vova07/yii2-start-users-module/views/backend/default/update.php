<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\users\models\User
  */
$this->title = Module::t("default","USERS");
$this->params['subtitle'] = $model->username;
?>


<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
       'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{cancel}{delete}'
    ]
);?>

<?php echo $this->render('_form', ['model' => $model, 'box' => $box])?>
<?php echo Html::a(\vova07\users\Module::t('default','CHANGE_PASSWORD'),['change-password','id'=>$model->primaryKey])?>
<?php \vova07\themes\adminlte2\widgets\Box::end(); ?>





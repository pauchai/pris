<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\salary\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\salary\models\Salary
  */
$this->title = Module::t("default","SALARY");
$this->params['subtitle'] = Module::t("default","CREATE");;
?>


<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{cancel}',
        'buttons' => [
            'cancel' => [
                'url' => Yii::$app->user->returnUrl,
                'icon' => 'fa-reply',
                'options' => [
                    'class' => 'btn-default',
                    'title' => Yii::t('vova07/themes/adminlte2/widgets/box', 'Create'),

                ]
            ]
        ]
    ]
);?>

<?php echo $this->render('_form', ['model' => $model, 'box' => $box])?>

<?php \vova07\themes\adminlte2\widgets\Box::end(); ?>




`
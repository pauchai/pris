<?php
use yii\bootstrap\ActiveForm ;

/**
 * @var $model \vova07\videos\models\Video
 * @var $this \yii\web\View
 */
?>
<?php $form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data'
    ]

])?>
<?php echo $form->field($model,'file')->fileInput()?>
<?php echo \yii\bootstrap\Html::submitButton();?>
<?php ActiveForm::end();?>


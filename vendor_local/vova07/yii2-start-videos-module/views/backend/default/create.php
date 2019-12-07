<?php
use yii\bootstrap\ActiveForm ;

/**
 * @var $model \vova07\videos\models\Video
 * @var $this \yii\web\View
 */
?>
<?php $form = ActiveForm::begin(['action'=>isset($action)?$action:null])?>
<?php echo $form->field($model,'title')?>
<?php echo $form->field($model, 'video_url')?>
<?php echo $form->field($model, 'sub_url')?>
<?php echo $form->field($model, 'type')?>
<?php echo $form->field($model, 'thumbnail_url')?>
<?php echo \yii\bootstrap\Html::submitButton();?>
<?php ActiveForm::end();?>


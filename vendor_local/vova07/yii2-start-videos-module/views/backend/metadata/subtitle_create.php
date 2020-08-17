<?php
use yii\bootstrap\ActiveForm ;

/**
 * @var $model \vova07\videos\models\Video
 * @var $this \yii\web\View
 */
?>
<?php $form = ActiveForm::begin([
    'layout' => 'inline',
    'options' => [
        'enctype' => 'multipart/form-data'
    ]

])?>
<?php echo $form->field($model,'file')->fileInput()?> OR
<?php echo $form->field($model,'filename')->dropDownList(
        $video->resolveSubtitles(),
        ['prompt' => '-']
)?>
<?php echo $form->field($model,'type')?>


<?php echo \yii\bootstrap\Html::submitButton();?>
<?php ActiveForm::end();?>


<?php
use yii\bootstrap\ActiveForm ;

/**
 * @var $model \vova07\videos\models\Import
 * @var $this \yii\web\View
 * @var $ytVideo \YoutubeDl\Entity\Video
 */
?>
<?php $form = ActiveForm::begin()?>
<?php echo $form->field($model,'url')?>


<?php if ($model->ytVideoIsLoaded()):?>
    <?php echo $form->field($model,'sub_format')->dropDownList($model->getSubtitlesAvailableForCombo())?>
    <?php echo $form->field($model,'format')->dropDownList($model->getFormatsAbailableForCombo())?>
    <?php echo $form->field($model,'thumb')?>
<?php endif;?>

<?php echo \yii\bootstrap\Html::submitButton();?>

<?php ActiveForm::end();?>




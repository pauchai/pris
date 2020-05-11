<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use vova07\users\models\Prisoner;
/**
 * @var $this \yii\web\View

 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>



<?=$form->field($model,'prisoner_id')->widget(Select2::class,['data' => Prisoner::getListForCombo(),'options'=>['prompt'=>Module::t('forms','SELECT_PRISONER_PROMPT')]]);?>

<?php echo
    $form->field($model,'prison_id')->widget(Select2::class, [
    'data' => \vova07\prisons\models\Company::getListForCombo(),
    'options'=>['id'=>'device_id', 'placeholder'=>Module::t('default','SELECT_PRISON_PROMPT')],

]);?>

<?php echo $form->field($model, 'dateStartJui')->widget(DatePicker::class)?>
<?php echo $form->field($model, 'dateFinishJui')->widget(DatePicker::class)?>
<?php $box->beginFooter();?>
<div class="form-group">
    <?php if ($model->isNewRecord):?>
    <?= Html::submitButton(Module::t('default', 'CREATE'), ['class' => 'btn btn-primary']) ?>
    <?php else: ?>

    <?= Html::submitButton(Module::t('default', 'UPDATE'), ['class' => 'btn btn-primary']) ?>
    <?php endif ?>
</div>
<?php $box->endFooter();?>
<?php ActiveForm::end()?>








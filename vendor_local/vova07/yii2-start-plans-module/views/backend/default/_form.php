<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\plans\Module;
use vova07\plans\models\PrisonerPlan;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\plans\models\PrisonerPlan
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>


<?php echo $form->field($model, '__prisoner_id')->hiddenInput()->label(false)?>
<?php echo $form->field($model, 'assignedAtJui')->widget(\kartik\widgets\DatePicker::class)?>
<?php echo $form->field($model, 'assigned_to')->dropDownList(\vova07\users\models\Officer::getListForCombo())?>
<?php echo $form->field($model, 'dateFinishedJui')->widget(\kartik\widgets\DatePicker::class)?>
<?=$form->field($model,'status_id')->dropDownList(PrisonerPlan::getStatusesForCombo())?>



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








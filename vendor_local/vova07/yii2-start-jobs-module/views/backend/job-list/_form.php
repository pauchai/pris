<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\events\Module;
use vova07\events\models\Event;
use vova07\prisons\models\Prison;
use vova07\users\models\Officer;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\events\models\Event
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>


<?=$form->field($model,'prison_id')->dropDownList(Prison::getListForCombo(),['prompt'=>\vova07\jobs\Module::t('forms','SELECT_PRISON_PROMPT')])?>
<?=$form->field($model,'assigned_to')->dropDownList(\vova07\users\models\Prisoner::getListForCombo(),['prompt'=>\vova07\jobs\Module::t('forms','SELECT_PRISONER_PROMPT')])?>
<?=$form->field($model,'assignedAtJui')->widget(\kartik\widgets\DatePicker::class)?>
<?=$form->field($model,'type_id')->dropDownList(\vova07\jobs\models\JobPaidType::getListForCombo(),['prompt'=>\vova07\jobs\Module::t('forms','SELECT_JOB_TYPE_PROMPT')])?>
<?=$form->field($model,'half_time')->checkbox()?>


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








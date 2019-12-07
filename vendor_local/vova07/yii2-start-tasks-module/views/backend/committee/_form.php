<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\tasks\models\Committee;
use vova07\tasks\Module;
use vova07\users\models\Prisoner;
use vova07\users\models\Officer;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\tasks\models\Committee
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin($params??[]) ?>
<?= $form->field($model,'subject_id')->dropDownList(Committee::getSubjectsForCombo(),['prompt' => Module::t('default','SELECT_SUBJECT_PROMPT')])?>
<?= $form->field($model,'prisoner_id')->dropDownList(Prisoner::getListForCombo(),['prompt' => Module::t('default','SELECT_PRISONER_PROMPT')])?>
<?= $form->field($model,'assigned_to')->dropDownList(Officer::getListForCombo(),['prompt' => Module::t('default','SELECT_OFFICER_PROMPT')])?>
<?= $form->field($model,'dateStartJui')->widget(\kartik\widgets\DatePicker::class)?>
<?= $form->field($model,'dateFinishJui')->widget(\kartik\widgets\DatePicker::class)?>
<?= $form->field($model,'mark_id')->dropDownList(Committee::getMarksForCombo(),['prompt' => Module::t('default','SELECT_MARK_PROMPT')])?>
<?= $form->field($model,'status_id')->dropDownList(Committee::getStatusesForCombo(),['prompt' => Module::t('default','SELECT_STATUS_PROMPT')])?>



<div class="form-group">
    <?php if ($model->isNewRecord):?>
    <?= Html::submitButton(Module::t('default', 'CREATE'), ['class' => 'btn btn-primary']) ?>
    <?php else: ?>

    <?= Html::submitButton(Module::t('default', 'UPDATE'), ['class' => 'btn btn-primary']) ?>
    <?php endif ?>
</div>
<?php $box->endFooter();?>
<?php ActiveForm::end()?>








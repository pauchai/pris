<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>

<?=$form->field($model,'type_id')->dropDownList(\vova07\documents\models\Document::getTypesForCombo(),['prompt' => Module::t('default','SELECT')])?>

<?=$form->field($model,'person_id')->widget(
    \kartik\select2\Select2::class,[
        'data' => \vova07\users\models\Prisoner::getListForCombo(),
        'options'=>['prompt'=>\vova07\plans\Module::t('events','SELECT_PRISONER')]

    ]
)

?>


<?=$form->field($model,'country_id')->dropDownList(\vova07\countries\models\Country::getListForCombo(),['prompt' => Module::t('default','SELECT')])?>
<?=$form->field($model,'seria')?>
<?= $form->field($model, 'dateIssueJui')->widget(\kartik\widgets\DatePicker::class)?>
<?= $form->field($model, 'dateExpirationJui')->widget(\kartik\widgets\DatePicker::class)?>
<?= $form->field($model, 'assigned_to')->widget(
    \kartik\select2\Select2::class,[
        'data' => \vova07\users\models\Officer::getListForCombo(),
        'options'=>['prompt'=>\vova07\plans\Module::t('events','SELECT_OFFICER')]

    ]
)?>
<?= $form->field($model, 'assignedAtJui')->widget(\kartik\widgets\DatePicker::class)?>

<?=$form->field($model,'status_id')->dropDownList(\vova07\documents\models\Document::getStatusesForCombo(),['prompt' => Module::t('default','SELECT')])?>

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








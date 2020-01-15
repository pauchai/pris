<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
use vova07\prisons\models\PrisonerSecurity;
use kartik\date\DatePicker;
use vova07\users\models\Prisoner;

/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>


<?php echo $form->field($model,'prisoner_id')->widget(\kartik\select2\Select2::class,['data' => \vova07\users\models\Prisoner::getListForCombo(),'options' => ['prompt' => Module::t('default','SELECT_PRISONER')]])?>
<?=$form->field($model,'type_id')->dropDownList(PrisonerSecurity::getTypesForCombo(),['clear' => 'true','prompt' => Module::t('default','SELECT_SECURITY_TYPE')])?>
<?= $form->field($model, 'dateStartJui')->widget(DatePicker::className())
?>
<?= $form->field($model, 'dateEndJui')->widget(DatePicker::className())
?>




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








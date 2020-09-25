<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Cell
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>


<?=$form->field($model,'company_id')->hiddenInput()->label(false)?>
<?=$form->field($model,'year')->hiddenInput()->label(false)?>
<?=$form->field($model,'month_no')->hiddenInput()->label(false)?>
<?=$form->field($model,'officer_id')->hiddenInput()->staticControl(['value' => $model->officer->person->fio])->label(false)?>
<?php //echo $form->field($model,'division_id')?>
<?php //echo $form->field($model,'postdict_id')?>
<?=$form->field($model,'rank_rate')?>
<?=$form->field($model,'base_rate')?>
<?=$form->field($model,'full_time')?>
<?=$form->field($model,'work_days')?>
<?=$form->field($model,'amount_rate')?>
<?=$form->field($model,'amount_rank_rate')?>
<?=$form->field($model,'amount_conditions')?>
<?=$form->field($model,'amount_advance')?>
<?=$form->field($model,'amount_rank_rate')?>
<?=$form->field($model,'amount_conditions')?>
<?=$form->field($model,'amount_advance')?>
<?=$form->field($model,'amount_optional')?>
<?=$form->field($model,'amount_diff_sallary')?>
<?=$form->field($model,'amount_additional')?>
<?=$form->field($model,'amount_maleficence')?>
<?=$form->field($model,'amount_vacation')?>
<?=$form->field($model,'amount_sick_list')?>
<?=$form->field($model,'amount_bonus')?>


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








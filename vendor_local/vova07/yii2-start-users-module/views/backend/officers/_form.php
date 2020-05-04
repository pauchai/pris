<?php
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>
<?=$form->field($model,'company_id')->dropDownList(\vova07\prisons\models\Company::getListForCombo(),['prompt'=>Module::t('default','SELECT')])?>
<?=$form->field($model,'department_id')->dropDownList(\vova07\prisons\models\Department::getListForCombo(),['prompt'=>Module::t('default','SELECT')])?>
<?=$form->field($model,'rank_id')->dropDownList(\vova07\users\models\Officer::getRanksForCombo(),['prompt'=>Module::t('default','SELECT_RANK')])?>
<?php if (!$model->isNewRecord):?>

   <?=$form->field($model->person,'__ident_id')->hiddenInput()?>
<?php endif;?>
<?=$form->field($model->person,'first_name')?>
<?=$form->field($model->person,'second_name')?>
<?=$form->field($model->person,'patronymic')?>
<?=$form->field($model->person,'birth_year')?>
<?=$form->field($model->person,'photo_url')?>
<?=$form->field($model->person,'citizen_id')->dropDownList(\vova07\countries\models\Country::getListForCombo(),['prompt'=>Module::t('default','SELECT_COUNTRY')])?>
<?=$form->field($model->person,'address')?>


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








<?php
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\bootstrap\Html;
use vova07\prisons\Module;
use kartik\depdrop\DepDrop;
use vova07\prisons\models\Rank;
use vova07\users\models\Officer;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>
<?php if ($model->company_id) :?>
    <?=$form->field($model,'company_id')->hiddenInput()->label($model->company->title)?>

<?php else:?>
    <?=$form->field($model,'company_id')->dropDownList(
        \vova07\prisons\models\Company::getListForCombo(),
        ['prompt'=>Module::t('default','SELECT'), 'id' => 'company_id']
    )?>

<?php endif;?>


<?php if (!$model->isNewRecord):?>

   <?=$form->field($model->person,'__ident_id')->hiddenInput()?>
<?php endif;?>
<?=$form->field($model->person,'second_name')?>
<?=$form->field($model->person,'first_name')?>
<?=$form->field($model->person,'patronymic')?>
<?=$form->field($model->person,'birth_year')?>
<?=$form->field($model,'rank_id')->dropDownList(Rank::getListForCombo(),['prompt'=>Module::t('default','SELECT_RANK')])?>


<?=$form->field($model,'member_labor_union')->checkbox()?>
<?=$form->field($model,'has_education')->checkbox()?>




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








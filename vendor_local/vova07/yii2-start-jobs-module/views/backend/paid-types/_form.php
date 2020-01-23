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

<?=$form->field($model,'title')?>
<?=$form->field($model,'compensation_id')->dropDownList(\vova07\jobs\models\JobPaidType::getCompensationsForCombo(),['prompt'=>\vova07\jobs\Module::t('default','SELECT_COMPENSATION_PROMPT')])?>
<?=$form->field($model,'category_id')->dropDownList(\vova07\jobs\models\JobPaidType::getCategoryForCombo(),['prompt'=>\vova07\jobs\Module::t('default','SELECT_CATEGORY_PROMPT')])?>
<?=$form->field($model,'hours_per_day')?>
<?=$form->field($model,'hours_per_sa')?>


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








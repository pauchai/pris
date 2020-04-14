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

<?=$form->field($model,'title')?>
<?=$form->field($model,'calculation_method_id')->dropDownList(\vova07\electricity\models\Device::getCalculationMethodsListForCombo(),['prompt'=>Module::t('default','SELECT_CALCULATION_METHOD_PROMPT')])?>

<?=$form->field($model,'prisoner_id')->widget(
    \kartik\select2\Select2::class,[
        'data' => \vova07\users\models\Prisoner::getListForCombo(),
        'options'=>['prompt'=>\vova07\plans\Module::t('events','SELECT_PRISONER')]

    ]
)?>

<?=$form->field($model,'assignedAtJui')->widget(\kartik\widgets\DatePicker::class)?>
<?=$form->field($model,'unassignedAtJui')->widget(\kartik\widgets\DatePicker::class)?>
<?php //echo $form->field($model,'sector_id')->dropDownList(\vova07\prisons\models\Sector::getListForCombo(),['id' => 'sector_id','prompt'=>\vova07\plans\Module::t('events','SELECT_ASSIGNED_TO')])?>
<?php /*echo $form->field($model, 'cell_id')->widget(\kartik\depdrop\DepDrop::class, [
    'data' => \vova07\prisons\models\Cell::getListForCombo($model->sector_id),
    'options'=>['id'=>'cell_id', 'placeholder'=>Module::t('default','SELECT_CELLS_PROMPT')],
    'pluginOptions'=>[
        'depends'=>['sector_id'],
        'url'=>\yii\helpers\Url::to(['/users/prisoner/sector-cells'])
    ]
]);*/?>
<?=$form->field($model,'power')?>
<?=$form->field($model,'enable_auto_calculation')->checkbox()?>




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








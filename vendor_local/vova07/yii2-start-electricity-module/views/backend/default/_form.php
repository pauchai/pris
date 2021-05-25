<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\electricity\Module;

/**
 * @var $this \yii\web\View
 * @var $model \vova07\events\models\Event
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>



<?=$form->field($model,'prisoner_id')->dropDownList(\vova07\users\models\Prisoner::getListForCombo(),['id' => 'prisoner_id', 'prompt'=>Module::t('default','SELECT_PRISONER_PROMPT')])?>
<?php echo
    $form->field($model,'device_id')->widget(\kartik\widgets\Select2::class, [
    'data' => \vova07\electricity\models\Device::getListForCombo($model->prisoner_id),
    'options'=>['id'=>'device_id', 'placeholder'=>Module::t('default','SELECT_DEVICE_PROMPT')],

]);?>

<?php echo $form->field($model,'dateRange')->widget(\kartik\daterange\DateRangePicker::class,[
    'startAttribute' => 'from_date',
    'endAttribute' => 'to_date',
    'convertFormat' =>  true,
    'pluginOptions' => [
        'locale' => ['format' => 'd-m-Y']
    ]
])?>

<?=$form->field($model, 'value')?>
<?=$form->field($model, 'price')?>
<?=$form->field($model, 'skip_auto_calculation')->checkbox()?>
<?=$form->field($model,'status_id')->dropDownList(\vova07\electricity\models\DeviceAccounting::getStatusesForCombo(),['prompt'=>Module::t('default','SELECT_STATUS_PROMPT')])?>
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








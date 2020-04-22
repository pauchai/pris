<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<?php foreach ($deviceAccountings as $deviceAccounting):?>
<?php  $form = ActiveForm::begin(
        [
                'method' => 'POST',
            'action' => ['create'],
            'layout' => 'inline'
        ]
    )?>
    <?php echo Html::submitButton($deviceAccounting->device->title )?>
    <?php echo $form->field($deviceAccounting,'device_id')->hiddenInput()?>

    <?php echo $form->field($deviceAccounting,'prisoner_id')->widget(\kartik\widgets\Select2::class, [
        'data' => \vova07\users\models\Prisoner::getListForCombo(),
        'options'=>['id'=>'device_id', 'placeholder'=>\vova07\electricity\Module::t('default','SELECT_PRISONER_PROMPT')],

    ]);?>
    <?php echo $form->field($deviceAccounting,'dateRange')->hiddenInput()?>
    <?php echo $form->field($deviceAccounting,'status_id')->hiddenInput()?>

<?php ActiveForm::end();?>
<?php endforeach;?>
<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\concepts\Module;
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

<?=$form->field($model,'prison_id')->dropDownList(Prison::getListForCombo(),['prompt'=>Module::t('default','SELECT_PRISON')])?>
<?=$form->field($model,'dateStartJui')->widget(\kartik\widgets\DatePicker::class)?>
<?=$form->field($model,'dateFinishJui')->widget(\kartik\widgets\DatePicker::class)?>
<?=$form->field($model,'assigned_to')->dropDownList(Officer::getListForCombo(),['prompt'=> Module::t('default','SELECT_ASSIGNED_TO')])?>
<?=$form->field($model,'status_id')->dropDownList(\vova07\concepts\models\Concept::getStatusesForCombo(),['prompt'=>Module::t('default','SELECT_CONCEPT_STATUS')])?>





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








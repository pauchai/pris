<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\plans\Module;
use vova07\events\models\Event;
use vova07\prisons\models\Prison;
use vova07\plans\models\ProgramPrisoner;
use vova07\users\models\Officer;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\plans\models\ProgramPrisoner
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>

<?=$form->field($model,'programdict_id')->dropDownList(\vova07\plans\models\ProgramDict::getListForCombo(),['prompt'=>Module::t('default','SELECT_PROGRAM')])?>
<?=$form->field($model,'prison_id')->dropDownList(Prison::getListForCombo(),['prompt'=> Module::t('default','SELECT_PRISON')])?>
<?=$form->field($model,'prisoner_id')->dropDownList(\vova07\users\models\Prisoner::getListForCombo(),['prompt'=>Module::t('default','SELECT_PRISONER')])?>
<?=$form->field($model,'program_id')->dropDownList(\vova07\plans\models\Program::getListForCombo(),['prompt'=>Module::t('default','SELECT_GROUP')])?>
<?=$form->field($model,'date_plan')?>
<?=$form->field($model,'planned_by')->dropDownList(\vova07\users\models\Officer::getListForCombo(),['prompt'=>Module::t('default','SELECT_CONDAMNATUL')])?>
<?=$form->field($model,'mark_id')->dropDownList(ProgramPrisoner::getMarksForCombo(),['prompt'=>Module::t('default','SELECT_MARK')])?>
<?=$form->field($model,'status_id')->dropDownList(\vova07\plans\models\ProgramPrisoner::getStatusesForCombo(),['prompt'=>Module::t('default','SELECT_PROGRAM_PRISONER_STATUS')])?>


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








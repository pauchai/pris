<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use \vova07\users\models\Prisoner;
use \vova07\jobs\Module;
use \vova07\jobs\models\JobPaidType;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\jobs\models\JobPaid
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>

<?=$form->field($model,'prison_id')->staticControl(['value'=>$model->prison->company->title])?>
<?=$form->field($model,'prison_id')->hiddenInput()->label(false);?>
<?=$form->field($model,'month_no')->staticControl()?>
<?=$form->field($model,'month_no')->hiddenInput()->label(false);?>
<?=$form->field($model,'year')->staticControl()?>
<?=$form->field($model,'year')->hiddenInput()->label(false)?>
<?=$form->field($model,'prisoner_id')->dropDownList(Prisoner::getListForCombo(),['prompt' => Module::t('default','SELECT_PRISONER_TITLE')])?>
<?=$form->field($model,'type_id')->dropDownList(JobPaidType::getListForCombo(),['prompt' => Module::t('default','SELECT_JOB_TYPE_TITLE')])?>

<?=$form->field($model,'half_time')->checkbox()?>
<?=$form->field($model,'auto_fill')->checkbox()?>





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








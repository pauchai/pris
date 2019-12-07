<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\events\Module;
use vova07\site\models\Setting;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\events\models\Event
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>

<?=$form->field($model,'prison_id')->dropDownList(\vova07\prisons\models\Prison::getListForCombo(),['prompt'=>\vova07\site\Module::t('default','SELECT_PRISON_PROMPT')])?>
<?=$form->field($model,Setting::SETTING_FIELD_COMPANY_DIRECTOR)->dropDownList(\vova07\users\models\Officer::getListForCombo(),['prompt'=>\vova07\site\Module::t('events','SELECT_DIRECTOR_PROMPT')])?>
<?=$form->field($model,Setting::SETTING_FIELD_ELECTRICITY_KILO_WATT_PRICE)?>




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








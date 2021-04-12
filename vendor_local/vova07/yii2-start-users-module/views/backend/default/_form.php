<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\users\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>



<?=$form->field($model,'username')?>
<?=$form->field($model,'email')?>
<?php if ($model->isNewRecord === true):?>
<?=$form->field($model,'password')?>
<?=$form->field($model,'repassword')?>
<?php endif;?>
<?=$form->field($model,'status_id')->dropDownList(\vova07\users\models\User::getStatusesForCombo(),['prompt'=>Module::t('default','SELECT')])?>
<?=$form->field($model,'role')->dropDownList(\vova07\users\models\User::getRolesForCombo(),['prompt' => Module::t('default','ROLES_SELECT_PROMPT')])?>

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








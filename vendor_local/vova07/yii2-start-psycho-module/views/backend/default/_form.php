<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\psycho\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

/**
 * @property integer $risk_id
 * @property boolean $feature_violent
 * @property boolean $feature_self_torture
 * @property boolean $feature_sucide
 * @property boolean $feature_addiction_alcohol
 * @property boolean $feature_addiction_drug

 */
?>


<?php $form = ActiveForm::begin()?>


<?php // echo $form->field($model,'risk_id')->dropDownList(\vova07\psycho\models\PsyCharacteristic::getRiskForCombo(),['prompt' => Module::t('default','SELECT_RISK_PROMPT')])?>
<?=$form->field($model,'feature_violent')->checkbox()?>
<?=$form->field($model,'feature_self_torture')->checkbox()?>
<?=$form->field($model,'feature_sucide')->checkbox()?>
<?=$form->field($model,'feature_addiction_alcohol')->checkbox()?>
<?=$form->field($model,'feature_addiction_drug')->checkbox()?>

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








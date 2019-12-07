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

<?=$form->field($model,'type_id')->dropDownList(\vova07\documents\models\Document::getTypesForCombo(),['prompt' => Module::t('default','SELECT')])?>
<?=$form->field($model,'person_id')->dropDownList(\vova07\users\models\Prisoner::getListForCombo(),['prompt' => Module::t('default','SELECT')])?>
<?=$form->field($model,'country_id')->dropDownList(\vova07\countries\models\Country::getListForCombo(),['prompt' => Module::t('default','SELECT')])?>
<?=$form->field($model,'seria')?>
<?=$form->field($model,'IDNP')?>
<?= $form->field($model, 'dateIssueJui')->widget(\yii\jui\DatePicker::className(),
    [

        'options' => [
            'class' => 'form-control',
        ],
        'clientOptions' => [
            'dateFormat' => 'dd.mm.yy',

        ]
    ]) ?>

<?= $form->field($model, 'dateExpirationJui')->widget(\yii\jui\DatePicker::className(),
    [

        'options' => [
            'class' => 'form-control',
        ],
        'clientOptions' => [
            'dateFormat' => 'dd.mm.yy',

        ]
    ]) ?>

<?=$form->field($model,'status_id')->dropDownList(\vova07\documents\models\Document::getStatusesForCombo(),['prompt' => Module::t('default','SELECT')])?>

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








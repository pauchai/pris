<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\psycho\Module;
use vova07\psycho\models\PsyTest;
use kartik\select2\Select2;
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


<?php  echo $form->field($model,'prisonerFio')->staticControl()?>
<?php  echo $form->field($model,'atJui')->widget(\kartik\date\DatePicker::class)?>
<?php echo $form->field($model, 'status_id')->widget(Select2::class,
    [

        'data' => PsyTest::getStatusesForCombo(),
        'options' => [
            'placeholder' => Module::t('default','SELECT_STATUS_LABEL'),
        ],
        'pluginOptions' => [
                'allowClear' => 'true'
        ]
    ]
)
?>

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








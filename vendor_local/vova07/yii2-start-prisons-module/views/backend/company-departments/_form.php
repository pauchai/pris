<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
use vova07\prisons\models\Division;
use kartik\widgets\DepDrop;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>

<?=$form->field($model,'company_id')->hiddenInput(['id' => 'company_id'])->label($model->company->title)?>
<?=$form->field($model,'department_id')->widget(DepDrop::class,[
    'type' => DepDrop::TYPE_SELECT2,

    'data' => $model->company->getDivisionsForCombo(),
    'pluginOptions' => [
        'depends'=>['company_id' .  $model->primaryKey],
        'url'=>\yii\helpers\Url::to(['sector-cells']),

    ],

    'select2Options' => [
        'pluginOptions' => [
            'allowClear'=>true

        ],
        'options' => [
            'placeholder' => Module::t('default','SELECT_DIVISIONS'),
        ],
    ],
]) ?>




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








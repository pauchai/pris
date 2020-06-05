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

<?=$form->field($model,'division_id')->widget(DepDrop::class,[
    'type' => DepDrop::TYPE_SELECT2,

    'data' => \vova07\prisons\models\DivisionDict::getListForCombo(),
    'pluginOptions' => [
        'depends'=>['company_id' ],
        'url'=>\yii\helpers\Url::to(['companies/company-divisions']),

    ],

    'select2Options' => [
        'pluginOptions' => [
            'allowClear'=>true

        ],
        'options' => [
            'placeholder' => Module::t('default','SELECT_DIVISIONS'),
        ],
    ],
])

?>
<?=$form->field($model,'title')->widget(\vova07\base\components\widgets\DepInput::class,[
        'depends' => 'division-division_id'
])?>





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








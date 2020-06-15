<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\PostDict
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>

<?=$form->field($model,'id')?>
<?=$form->field($model,'iso_id')->widget(
        \kartik\select2\Select2::class,
        [
            'data' => \vova07\prisons\models\PostIso::getListForCombo(),
            'pluginOptions' => [
                'allowClear'=>true

            ],
            'options' => [
                'placeholder' => Module::t('default','SELECT_POST_ISO'),
            ],
        ]
)?>

<?=$form->field($model,'title')->widget(\vova07\base\components\widgets\DepInput::class,[
    'depends' => 'post_dict_iso_id'
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








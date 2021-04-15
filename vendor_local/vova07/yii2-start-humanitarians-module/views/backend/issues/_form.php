<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\humanitarians\Module;
use vova07\prisons\models\Company;
use vova07\humanitarians\models\HumanitarianItem;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\humanitarians\models\HumanitarianIssue
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>

<?=$form->field($model,'dateIssueJui')->widget(\yii\jui\DatePicker::class)?>
<?=$form->field($model,'items')->dropDownList(HumanitarianItem::getListForCombo(),['multiple'=>'on']) ?>
<?=$form->field($model,'company_id')->widget(\kartik\select2\Select2::class,
    [

        'data' => Company::getListForCombo(),
        'options' => [
            'placeholder' => Module::t('default','SELECT_COMPANY_LABEL'),
        ],
        'pluginOptions' => [
            'allowClear' => 'true'
        ]
    ]
)?>



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







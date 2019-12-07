<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\humanitarians\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\humanitarians\models\HumanitarianIssue
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>

<?=$form->field($model,'dateIssueJui')->widget(\yii\jui\DatePicker::class)?>
<?=$form->field($model,'items')->dropDownList(\vova07\humanitarians\models\HumanitarianItem::getListForCombo(),['multiple'=>'on']) ?>



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







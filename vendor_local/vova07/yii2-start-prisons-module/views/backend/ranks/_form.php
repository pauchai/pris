<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
use vova07\prisons\models\Division;
use kartik\widgets\DepDrop;
use \vova07\base\components\widgets\DepInput;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Rank
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>


<?=$form->field($model,'id')?>
<?=$form->field($model,'title')?>
<?=$form->field($model,'rate')?>


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








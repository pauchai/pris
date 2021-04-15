<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\socio\Module;
use vova07\socio\models\Relation;
use vova07\users\models\Prisoner;
use vova07\users\models\Person;
use vova07\socio\models\RelationType;

/**
 * @var $this \yii\web\View
 * @var $model \vova07\events\models\Event
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>

<?=$form->field($model,'person_id')->dropDownList(Prisoner::getListForCombo(),['prompt'=>Module::t('default','SELECT_PRISONER_LABEL')])?>
<?=$form->field($model,'ref_person_id')->dropDownList(Person::getListForCombo(),['prompt'=>Module::t('default','SELECT_REF_PERSON_LABEL')])?>
<?=$form->field($model,'type_id')->dropDownList(RelationType::getListForCombo(),['prompt'=>Module::t('default','SELECT_REF_PERSON_LABEL')])?>

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








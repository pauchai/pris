<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\socio\Module;

use vova07\users\models\Prisoner;
use vova07\socio\models\DisabilityGroup;
use kartik\depdrop\DepDrop;
use vova07\documents\models\Document;
use yii\helpers\Url;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\socio\models\Disability
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>

<?=$form->field($model,'person_id')->dropDownList(Prisoner::getListForCombo(),['id' => 'person-id', 'prompt'=>Module::t('default','SELECT_PRISONER_LABEL')])?>
<?=$form->field($model,'group_id')->dropDownList(DisabilityGroup::getListForCombo(),['prompt'=>Module::t('default','SELECT_REF_PERSON_LABEL')])?>
<?=$form->field($model,'document_id')->widget(DepDrop::class, [
    'options' => ['id'=>'document-id'],
    'type' => DepDrop::TYPE_SELECT2,

    'data' =>  isset($model->person)?Document::getDocumentsForCombo($model->person->getDocuments()):[],

    'pluginOptions'=>[
        'depends'=>['person-id'],
        'placeholder' => Module::t('default','SELECT_DOCUMENT_LABEL'),
        'url' => Url::to(['/documents/default/person-documents'])
    ]
]);?>

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








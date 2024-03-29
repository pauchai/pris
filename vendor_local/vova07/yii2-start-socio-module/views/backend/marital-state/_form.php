<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\socio\Module;
use vova07\socio\models\MaritalStatus;
use vova07\users\models\Prisoner;
use vova07\users\models\Person;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use vova07\documents\models\Document;

/**
 * @var $this \yii\web\View
 * @var $model \vova07\socio\models\MaritalState
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>
<?php if ($model->isNewRecord):?>
<?=$form->field($model,'__person_id')->dropDownList(Prisoner::getListForCombo(),['id' => 'person-id', 'prompt'=>Module::t('default','SELECT_PRISONER_LABEL')])?>
<?php endif;?>
<?=$form->field($model,'ref_person_id')->dropDownList(Person::getListForCombo(),['prompt'=>Module::t('default','SELECT_PRISONER_LABEL')])?>
<?=$form->field($model,'status_id')->dropDownList(MaritalStatus::getListForCombo(),['prompt'=>Module::t('default','SELECT_REF_PERSON_LABEL')])?>
<?=$form->field($model,'document_id')->widget(DepDrop::class, [
    'options' => ['id'=>'document-id'],
    'type' => DepDrop::TYPE_SELECT2,

    'data' =>  isset($model->person)?Document::getDocumentsForCombo($model->person->getDocuments()):[],
    'select2Options' => [
        'pluginOptions' => [
            'placeholder' => Module::t('default','SELECT_DOCUMENT_LABEL'),
            'allowClear' => true,
        ]
    ],
    'pluginOptions'=>[
        'depends'=>['person-id'],
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








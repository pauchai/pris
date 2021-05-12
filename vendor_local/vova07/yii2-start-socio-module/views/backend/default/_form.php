<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\socio\Module;
use vova07\documents\models\Document;
use vova07\users\models\Prisoner;
use vova07\users\models\Person;
use vova07\socio\models\RelationType;
use vova07\socio\models\MaritalStatus;
use kartik\widgets\DepDrop;
use yii\helpers\Url;
use kartik\select2\Select2;
use vova07\base\components\widgets\Select2WithAdd;

/**
 * @var $this \yii\web\View
 * @var $model \vova07\socio\models\backend\RelationWithMarital
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>
<?php if ($model->isNewRecord):?>
<?=$form->field($model,'person_id')->widget(\kartik\widgets\Select2::class,[
        'data' => Prisoner::getListForCombo(),
        'pluginOptions' => [
                'placeholder' => Module::t('default','SELECT_PRISONER_LABEL'),
                'allowClear' => true,
        ]
    ])?>
<?php endif;?>

<?=$form->field($model,'ref_person_id')->widget(Select2WithAdd::class,
[
    'data' => Person::getListForCombo(),
    'newUrl' => ['create-person-ajax'],

    'pluginOptions' => [

        'placeholder' =>Module::t('default','SELECT_REF_PERSON_LABEL'),

        'allowClear' => true,
    ]
]

)
?>


<?=$form->field($model,'type_id')->dropDownList(RelationType::getListForCombo(),['prompt'=>Module::t('default','SELECT_RELATION_TYPE_LABEL')])?>
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
<?=$form->field($model,'marital_status_id')->dropDownList(MaritalStatus::getListForCombo(),['prompt'=>Module::t('default','SELECT_MARITAL_STATUS_LABEL')])?>

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








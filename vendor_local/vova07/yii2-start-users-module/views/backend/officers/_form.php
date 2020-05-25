<?php
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\bootstrap\Html;
use vova07\prisons\Module;
use kartik\depdrop\DepDrop;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>
<?=$form->field($model,'company_id')->dropDownList(
        \vova07\prisons\models\Company::getListForCombo(),
        ['prompt'=>Module::t('default','SELECT'), 'id' => 'company_id']
)?>
<?=$form->field($model,'division_id')->widget(DepDrop::class,[

    'type' => DepDrop::TYPE_SELECT2,

    'data' => $model->company->getDivisionsForCombo(),
    'pluginOptions' => [
        'depends'=>['company_id'],
        'url'=>\yii\helpers\Url::to(['/prisons/divisions/company-divisions']),

    ],


    'select2Options' => [
        'pluginOptions' => [
            'allowClear'=>true

        ],

    ],
    'options' => [
        'id' => 'division_id',

        'placeholder' => Module::t('default','SELECT_DIVISIONS'),
    ],
]) ?>

<?=$form->field($model,'post_id')->widget(DepDrop::class,[
    'type' => DepDrop::TYPE_SELECT2,

    'data' => $model->division->getPostsForCombo(),
    'pluginOptions' => [
        'depends'=>['company_id', 'division_id'],
        'url'=>\yii\helpers\Url::to(['/prisons/posts/division-posts']),

    ],

    'select2Options' => [
        'pluginOptions' => [
            'allowClear'=>true

        ],

    ],
    'options' => [
        'placeholder' => Module::t('default','SELECT_POSTS'),
    ],

]) ?>

<?=$form->field($model,'rank_id')->dropDownList(\vova07\users\models\Officer::getRanksForCombo(),['prompt'=>Module::t('default','SELECT_RANK')])?>

<?php if (!$model->isNewRecord):?>

   <?=$form->field($model->person,'__ident_id')->hiddenInput()?>
<?php endif;?>
<?=$form->field($model->person,'first_name')?>
<?=$form->field($model->person,'second_name')?>
<?=$form->field($model->person,'patronymic')?>
<?=$form->field($model->person,'birth_year')?>
<?=$form->field($model->person,'photo_url')?>
<?=$form->field($model->person,'citizen_id')->dropDownList(\vova07\countries\models\Country::getListForCombo(),['prompt'=>Module::t('default','SELECT_COUNTRY')])?>
<?=$form->field($model->person,'address')?>


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








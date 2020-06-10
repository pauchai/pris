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
<?=$form->field($newModel,'officer_id')->hiddenInput()?>
<?=$form->field($model,'company_id')->hiddenInput(['id' => 'company_id'])->label($model->company->title)?>

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

<?=$form->field($model,'postdict_id')->widget(DepDrop::class,[
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
<?=$form->field($model,'full_time')->checkbox()?>



<?=$form->field($newModel,'benefit_class')->dropDownList(
    \vova07\salary\models\SalaryBenefit::getListForCombo(),
    [
        'prompt' => \vova07\salary\Module::t('default','BENEFIT_CLASS')
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








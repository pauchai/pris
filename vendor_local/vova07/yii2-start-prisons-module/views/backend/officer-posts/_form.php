<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
use vova07\prisons\models\Division;
use kartik\widgets\DepDrop;
use yii\helpers\ArrayHelper;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\OfficerPost
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin([
    //    'enableClientValidation' => true,
    'method' => 'POST',
    'options' => ['id' => 'main-form']
    ])?>
<?=$form->field($model,'officer_id')->hiddenInput()->label(ArrayHelper::getValue($model,'officer.person.fio'))?>
<?=$form->field($model,'company_id',['inputOptions' => ['id' => 'company_id']])->hiddenInput()->label(ArrayHelper::getValue($model,'company.title'))?>

<?=$form->field($model,'division_id')->widget(DepDrop::class,[

    'type' => DepDrop::TYPE_SELECT2,

    'data' => $model->company?$model->company->getDivisionsForCombo():[],
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

    'data' => $model->division?$model->division->getPostsForCombo():[],
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



<?=$form->field($model,'benefit_class', ['inputOptions' => ['id' => 'benefit-class-id']])->dropDownList(
    \vova07\salary\models\SalaryBenefit::getListForCombo(),
    [
        'prompt' => \vova07\salary\Module::t('default','BENEFIT_CLASS')
    ]
)?>
<?php
    $calculateBaseRateAction = \yii\helpers\Url::to(['/prisons/officer-posts/calculate-base-rate']);
 $this->registerJs(<<<JS
(function(){
    var requestBaseRate = function(){
        var postData = $("#main-form").serialize()
        $.ajax({
            type: "POST",
            url: '$calculateBaseRateAction',
            data: postData,
            success: function(baseRateValue){
             $("#base-rate").val(baseRateValue);
            } 
        });
    }
    window.onClickCalculateBaseRate = requestBaseRate;
})();
 
 
JS
);


    echo Html::button(Module::t('labels','CALCULATE'), [
       // 'data-confirm' => 'Calculate?',
        'onClick' =>'onClickCalculateBaseRate();return false;'
    ])

    ?>
<?=$form->field($model, 'base_rate', ['inputOptions' => ['id' => 'base-rate']])?>




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








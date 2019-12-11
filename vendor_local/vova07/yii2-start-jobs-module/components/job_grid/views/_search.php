<?php
use yii\bootstrap\ActiveForm;
use vova07\jobs\models\backend\JobPaidSearch;
use vova07\prisons\models\Prison;
use vova07\jobs\helpers\Calendar;
use yii\bootstrap\Html;

/**
 * @var $model JobPaidSearch
 */
?>
<?php


    $prevDate = $model->getDateTime()->modify('first day of previous month');
    $nextDate = $model->getDateTime()->modify('first day of next month');
    $paramsPrevious[$model->formName()] = ['month_no'=> $prevDate->format('m'),'year'=>$prevDate->format('Y')];
    $paramsNext[$model->formName()] = ['month_no'=> $nextDate->format('m'),'year'=>$nextDate->format('Y')];
?>
<h2>
<?php echo Html::a(Html::tag('i','',['class'=>'fa fa-chevron-left']),\yii\helpers\Url::current($paramsPrevious))?>
<?php //echo $model->getDateTime()->format('M, Y')?>
<?php echo  Html::dropDownList('yearMonth',$model->yearMonth,Calendar::getListYearMonth($model->yearMonth),['id'=>'year_month'])?>
<?php echo Html::a(Html::tag('i','',['class'=>'fa fa-chevron-right']),\yii\helpers\Url::current($paramsNext))?>
</h2>
<div class="job-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'id' => 'job-search-form'
    ]) ?>

    <?= $form->field($model,'yearMonth')->hiddenInput(['yearMonth'])->label(false);//dropDownList(Calendar::getListYearMonth($model->yearMonth))?>
    <?= $form->field($model, 'prison_id')->hiddenInput()->label(false);//dropDownList(Prison::getListForCombo(),['prompt'=>\vova07\prisons\Module::t('events','SELECT_PRISON')])?>


    <?php ActiveForm::end();?>

</div>

<?php
    $monthYearElementId = \yii\helpers\BaseHtml::getInputId($model,'yearMonth');


    $this->registerJs(<<<JS

        $('#year_month').on('change',function(event){
            $('#' + '$monthYearElementId').val($(this).val());    
            $('#job-search-form').submit();    
        }) ;
JS
    );
?>
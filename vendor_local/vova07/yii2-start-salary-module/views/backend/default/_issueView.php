<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use vova07\jobs\helpers\Calendar;
use vova07\salary\Module;
use vova07\salary\models\SalaryIssue;

$prevDate = $model->getAt(false)->modify('first day of previous month');
$nextDate = $model->getAt(false)->modify('first day of next month');
$paramsPrevious['at'] = $prevDate->format('Y-m-01');
$paramsNext['at'] = $nextDate->format('Y-m-01');
?>

<?php
$monthYearElementId = \yii\helpers\BaseHtml::getInputId($model,'at');


$this->registerJs(<<<JS

        $('#at').on('change',function(event){
            $('#' + '$monthYearElementId').val($(this).val());    
            $('#month-search-form').submit();    
        }) ;
JS
);
?>


<div class="row">
    <div class="col-sm-3">

        <div class="form-control">
            <?php echo Html::a(Html::tag('i','',['class'=>'fa fa-chevron-left ']),\yii\helpers\Url::current($paramsPrevious))?>
            <?php //echo $model->getDateTime()->format('M, Y')?>
        <?php echo  Html::dropDownList('at',$model->at,Calendar::getListYearMonth($model->at),['id'=>'at'])?>
        <?php echo Html::a(Html::tag('i','',['class'=>'fa fa-chevron-right']),\yii\helpers\Url::current($paramsNext))?>
        </div>
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'layout' => 'inline',
            'method' => 'get',
            'id' => 'month-search-form'
        ]) ?>
        <?= Html::input('hidden','at',null, ['id' => $monthYearElementId ]);?>

        <?php ActiveForm::end();?>
    </div>
    <div class="col-sm-5">


        <?php if ($model->isNewRecord):?>
            <?php $formNew = ActiveForm::begin([
                'method' => 'post',
                'action' => ['/salary/issue/create' ]
            ]);
            ?>
            <?php echo $formNew->field($model,'year')->hiddenInput()->label(false)?>
            <?php echo $formNew->field($model,'month_no')->hiddenInput()->label(false)?>
            <?= Html::submitButton(Module::t('default', 'START_SALARY_ISSUE'), ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end();?>
        <?php else:?>
            <?php $formNew = ActiveForm::begin([
                'layout' => "inline",
                'method' => 'post',
                'action' => ['issue-update' , 'year' => $model->year, 'month_no' => $model->month_no ]
            ]);
            ?>
            <?php echo $formNew->field($model,'year')->hiddenInput()->label(false)?>
            <?php echo $formNew->field($model,'month_no')->hiddenInput()->label(false)?>
            <?php echo $formNew->field($model,'status_id')->dropDownList(
                SalaryIssue::getStatusesForCombo(),
                ['prompt' => '']
            )
            ?>

            <?= Html::submitButton(Module::t('default', 'UPDATE'), ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end();?>


        <?php endif;?>

    </div>

</div>









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
<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
    ]
);?>
<h2>
    <?php echo Html::a(Html::tag('i','',['class'=>'fa fa-chevron-left']),\yii\helpers\Url::current($paramsPrevious))?>
    <?php //echo $model->getDateTime()->format('M, Y')?>
    <?php echo  Html::dropDownList('at',$model->at,Calendar::getListYearMonth($model->at),['id'=>'at'])?>
    <?php echo Html::a(Html::tag('i','',['class'=>'fa fa-chevron-right']),\yii\helpers\Url::current($paramsNext))?>
</h2>
<div class="month-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'id' => 'month-search-form'
    ]) ?>

    <?= Html::input('hidden','at',null, ['id' => $monthYearElementId ]);?>



    <?php ActiveForm::end();?>

</div>

<?php $box->beginBody()?>
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
        'action' => ['/salary/issue/update' , 'year' => $model->year, 'month_no' => $model->month_no ]
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

    <?php $syncForm = \kartik\form\ActiveForm::begin([
        'action' => ['create-tabular']
    ])?>


    <?php echo $syncForm->field($model,'year')->hiddenInput()->label(false) ?>
    <?php echo $syncForm->field($model,'month_no')->hiddenInput()->label(false) ?>
    <?php             echo \yii\helpers\Html::submitButton(
        Module::t('default', 'SYNC_TABULAR_FOR_MONTH'),

        ['class' => 'btn btn-info']
    );
    ?>
    <?php \kartik\form\ActiveForm::end()?>
<?php endif;?>
<?php $box->endBody()?>




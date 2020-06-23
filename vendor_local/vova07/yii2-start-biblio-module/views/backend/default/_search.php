<?php
use yii\bootstrap\ActiveForm;
use vova07\jobs\models\backend\JobPaidSearch;
use vova07\prisons\models\Prison;
use vova07\jobs\helpers\Calendar;
use yii\bootstrap\Html;

/**
 * @var $model \vova07\salary\models\backend\SalarySearch
 */
?>
<?php


$prevDate = $model->getAt(false)->modify('first day of previous month');
$nextDate = $model->getAt(false)->modify('first day of next month');
$paramsPrevious[$model->formName()] = ['at'=> $prevDate->format('Y-m-1')];
$paramsNext[$model->formName()] = ['at'=> $nextDate->format('Y-m-1')];
?>
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

        <?= $form->field($model,'at')->hiddenInput()->label(false);?>



        <?php ActiveForm::end();?>

    </div>

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
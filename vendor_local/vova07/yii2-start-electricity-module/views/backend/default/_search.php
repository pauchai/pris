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

$currDate = (new DateTime)->setTimestamp($model->from_date);
$prevDate = clone($currDate);
$nextDate = clone($currDate);
$ranges[$model->dateRange] = $model->dateRange;
for ($i=1 ; $i<=3;$i++){
    $prevDate = clone($prevDate)->modify('first day of previous month');
    $range = join(' - ', Calendar::getRangeForDate($prevDate, Calendar::RANGE_MONTH,'d-m-Y'));
    $prevRanges[$range] = $range;
    $nextDate = clone($nextDate)->modify('first day of next month');
    $range = join(' - ', Calendar::getRangeForDate($nextDate, Calendar::RANGE_MONTH,'d-m-Y'));
    $nextRanges[$range] = $range;

}

$ranges = array_merge(array_reverse($prevRanges), $ranges, $nextRanges);
//$ranges[$model->dateRange] = $model->dateRange;


//$prevDate = (new DateTime)->setTimestamp($model->from_date)->modify('first day of previous month');
//$nextDate = (new DateTime)->setTimestamp($model->from_date)->modify('first day of next month');

//$rangePrevious = join(' - ', Calendar::getRangeForDate($prevDate, Calendar::RANGE_MONTH,'d-m-Y'));
//$rangeNext = join(' - ',Calendar::getRangeForDate($nextDate, Calendar::RANGE_MONTH,'d-m-Y'));



//$paramsPrevious[$model->formName()] = ['dateRange'=> $rangePrevious ];
//$paramsPrevious[$model->formName()] = ['dateRange'=> $rangeNext ];

?>

<?php $form = \kartik\form\ActiveForm::begin(['type' => 'inline','method' => 'get'])?>
<?php echo $form->field($model,'dateRange')->dropDownList(
    $ranges, ['prompt' => \vova07\electricity\Module::t('default','SELECT_RANGE_PROMPT')]
)?>
<?php echo Html::submitButton('SELECT_MONTH',['class' => 'form-control btn btn-info'])?>

<?php \kartik\form\ActiveForm::end()?>


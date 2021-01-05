<?php
use kartik\form\ActiveForm;
use kartik\widgets\DatePicker;
use vova07\prisons\models\Sector;
use yii\bootstrap\Html;
?>

<?php $form=ActiveForm::begin(['type' => 'inline', 'method' => 'get'])?>
<?=$form->field($searchModel,'year')->dropDownList(\vova07\reports\models\backend\ReportPrisonerProgram::getYearsForFilterCombo(),['prompt' => 'select year'])?>
<?=$form->field($searchModel,'sector_id')->dropDownList(Sector::getListForCombo(),['prompt' => 'select sector'])?>
<?=Html::submitButton('filter',['class' => 'form-control btn btn-info no-print '])?>
<?php ActiveForm::end();?>

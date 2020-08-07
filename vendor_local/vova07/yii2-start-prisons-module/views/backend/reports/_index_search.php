<?php
use kartik\form\ActiveForm;
use kartik\widgets\DatePicker;
use vova07\prisons\models\Sector;
use yii\bootstrap\Html;
?>

<?php $form=ActiveForm::begin(['type' => 'inline', 'method' => 'get'])?>
<?=$form->field($searchModel,'fromJui')->widget(DatePicker::class,
    [])?>
<?=$form->field($searchModel,'toJui')->widget(DatePicker::class,
    [])?>
<?=$form->field($searchModel,'sector_id')->dropDownList(Sector::getListForCombo(),['prompt' => 'select sector'])?>
<?=Html::submitButton('filter',['class' => 'form-control btn btn-info no-print '])?>
<?php ActiveForm::end();?>

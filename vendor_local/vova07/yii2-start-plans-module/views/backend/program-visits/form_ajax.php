<?php
/**
 * @var $this \yii\web\View
 * @var $model \vova07\plans\models\ProgramVisit
 */
?>


<?php
$action = [
    'program_id' => $model->program_id,
    'prisoner_id' => $model->prisoner_id,
    'date_visit' => $model->date_visit,

];
$action[0] = 'program-visits/create';
?>
<?php  $form = \yii\widgets\ActiveForm::begin(['action' => $action]);

echo $form->field($model,'program_id')->hiddenInput()->label(false);
echo $form->field($model,'prisoner_id')->hiddenInput()->label(false);
echo $form->field($model,'date_visit')->hiddenInput()->label(false);
echo $form->field($model,'status_id')->label(false)->dropDownList(\vova07\plans\models\ProgramVisit::getStatusesForCombo(),['prompt'=>\vova07\plans\Module::t('programs','SELECT_STATUS')]);

\yii\widgets\ActiveForm::end();
?>

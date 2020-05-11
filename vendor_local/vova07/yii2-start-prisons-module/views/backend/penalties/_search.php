<?php

use vova07\jobs\models\backend\JobPaidSearch;
use vova07\users\models\Prisoner;
use kartik\form\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
use kartik\select2\Select2;

/**
 * @var $model JobPaidSearch
 */
?>





<?php $form = ActiveForm::begin(['type' => 'inline','method' => 'get'])?>
<?php echo $form->field($model,'prisoner_id')->widget(Select2::class,[
    'data' => Prisoner::getListForCombo(),
        'pluginOptions' => ['allowClear' => true ],
    'options'=>[
        'prompt'=>Module::t('forms','SELECT_PRISONER_PROMPT')]

    ]
);?>

<?php echo Html::submitButton('SELECT_MONTH',['class' => 'form-control btn btn-info no-print '])?>

<?php \kartik\form\ActiveForm::end()?>


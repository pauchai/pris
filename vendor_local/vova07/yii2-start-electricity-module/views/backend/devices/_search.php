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



<?php $form = \kartik\form\ActiveForm::begin(['type' => 'inline','method' => 'get'])?>

<?php echo $form->field($model,'prisoner_id')->widget(
    \kartik\select2\Select2::class,
    [
        'pluginOptions'=>[
            'allowClear'=>true,

        ],
        'options' => [

                'class' => 'no-print',
                'placeholder' => \vova07\electricity\Module::t('default', 'SELECT_PRISONER_PROMPT'),


        ],

        'data' => \vova07\users\models\Prisoner::getListForCombo()
    ]

)?>

<?php echo Html::submitButton('FILTER_BUTTON_CAPTION',['class' => 'form-control btn btn-info no-print '])?>

<?php \kartik\form\ActiveForm::end()?>


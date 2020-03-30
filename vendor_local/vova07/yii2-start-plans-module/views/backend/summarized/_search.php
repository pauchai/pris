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


<div class="post-search">
    <?php $form = ActiveForm::begin([

        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'atFromJui')->widget(\kartik\widgets\DatePicker::class,[
                'options' => ['autocomplete'=>'off'],

            ]);
            ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'atToJui')->widget(\kartik\widgets\DatePicker::class,[
                'options' => ['autocomplete'=>'off'],

            ]);
            ?>
        </div>


    </div>
    <div class="form-group">
        <?= Html::submitButton(\vova07\site\Module::t('default','SEARCH_LABEL'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>


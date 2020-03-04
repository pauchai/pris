<?php
use yii\bootstrap\ActiveForm;
use vova07\jobs\models\backend\JobPaidSearch;

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
            <?= $form->field($model, 'dateStartFromJui')->widget(\kartik\widgets\DatePicker::class,[
                'options' => ['autocomplete'=>'off'],

            ]);
            ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'dateStartToJui')->widget(\kartik\widgets\DatePicker::class, ['options' => ['autocomplete'=>'off']]) ?>

        </div>

    </div>
    <div class="form-group">
        <?= Html::submitButton(\vova07\site\Module::t('default','SEARCH_LABEL'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(\vova07\site\Module::t('default','RESET_LABEL'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>


<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;


?>


<div class="post-search">
    <?php $form = ActiveForm::begin([

        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'atFromJui')->widget(\kartik\widgets\DatePicker::class,[
                'options' => ['autocomplete'=>'off'],

            ]); ?>
            <?php //echo $form->field($searchModel,'hasTest')->input('text',['value' => \vova07\psycho\models\backend\PrisonerTestSearch::HASTEST_WITH_TEST])?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'atToJui')->widget(\kartik\widgets\DatePicker::class, ['options' => ['autocomplete'=>'off']]) ?>

        </div>

    </div>
    <div class="form-group">
        <?= Html::submitButton(\vova07\site\Module::t('default','DATE_FILTER_LABEL'), ['class' => 'btn btn-primary']) ?>

    </div>

    <?php ActiveForm::end(); ?>
</div>


<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use kartik\widgets\DatePicker;

/**
 * @var $model \vova07\electricity\models\backend\SummarizedSearch
 */
?>


<div class="post-search">
    <?php $form = ActiveForm::begin([

        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'year')->widget(\kartik\touchspin\TouchSpin::class,[
                   'pluginOptions' =>[
                       'min' => 1900,
                       'max' => 2030,
                       'verticalbuttons' => true,
                    ]
            ]);
            ?>
        </div>



    </div>
    <div class="form-group">
        <?= Html::submitButton(\vova07\site\Module::t('default','SEARCH_LABEL'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>


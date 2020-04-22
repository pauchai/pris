<?php
    use vova07\prisons\models\Prison;
    use vova07\documents\Module;
?>
<div class="document-search">
    <?php $form = \yii\bootstrap\ActiveForm::begin(
        [
                'layout' => 'inline',
            'action' => ['index'],
            'method' => 'get'
        ])?>

    <div class="form-group">
    <?php echo $form->field($model,'issuedFromJui')->widget(\kartik\widgets\DatePicker::class,[
        'options'=>[
            'autocomplete' => 'off',
            'placeholder' => Yii::t('default','SELECT_ISSUED_FROM_PROMPT'),
        ]
    ]) ?>
    <?php echo $form->field($model,'issuedToJui')->widget(\kartik\widgets\DatePicker::class,[
        'options'=>[
            'autocomplete' => 'off',
            'placeholder' => Yii::t('default','SELECT_ISSUED_TO_PROMPT'),
        ]
    ]) ?>


    <?php echo $form->field($model,'expiredFromJui')->widget(\kartik\widgets\DatePicker::class,[
        'options'=>[
            'autocomplete' => 'off',
            'placeholder' => Yii::t('default','SELECT_EXPIRED_FROM_PROMPT'),
        ]
    ]) ?>
    <?php echo $form->field($model,'expiredToJui')->widget(\kartik\widgets\DatePicker::class,[
        'options'=>[
            'autocomplete' => 'off',
            'placeholder' => Yii::t('default','SELECT_EXPIRED_TO_PROMPT'),
        ]
    ]) ?>
    <?php echo $form->field($model,'companyId')->widget(
        \kartik\select2\Select2::class,[
            'data' => Prison::getListForCombo(),
            'pluginOptions' => ['allowClear' => true],
            'options'=>['prompt'=>Module::t('default','SELECT_PRISONS_PROMPT')]

        ]
    )?>
    </div>
    <div class="form-group">
        <?php echo \yii\helpers\Html::submitButton('Search',['class' => 'btn btn-primary'])?>
        <?php echo \yii\bootstrap\Html::resetButton('Сбросить', ['class' => 'btn btn-default'])?>

    </div>
    <?php \yii\bootstrap\ActiveForm::end()?>
</div>
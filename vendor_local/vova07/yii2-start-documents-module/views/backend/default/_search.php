<div class="document-search">
    <?php $form = \yii\bootstrap\ActiveForm::begin(
        [
                'layout' => 'inline',
            'action' => ['index'],
            'method' => 'get'
        ])?>

    <div class="form-group">
    <?php echo $form->field($model,'issuedFromJui')->widget(\kartik\widgets\DatePicker::class) ?>

    <?php echo $form->field($model,'issuedToJui')->widget(\kartik\widgets\DatePicker::class) ?>
    </div>
    <div class="form-group">
        <?php echo \yii\helpers\Html::submitButton('Search',['class' => 'btn btn-primary'])?>
        <?php echo \yii\bootstrap\Html::resetButton('Сбросить', ['class' => 'btn btn-default'])?>

    </div>
    <?php \yii\bootstrap\ActiveForm::end()?>
</div>
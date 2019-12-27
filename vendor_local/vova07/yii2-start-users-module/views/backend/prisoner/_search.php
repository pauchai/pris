<div class="document-search">
    <?php $form = \yii\bootstrap\ActiveForm::begin(
        [
             'layout' => 'inline',
            'action' => ['index'],
            'method' => 'get'
        ])?>

    <?php echo $form->field($model,'enabledSaveSearch')->checkbox()?>


    <div class="form-group">
        <?php echo \yii\helpers\Html::submitButton('Save',['class' => 'btn btn-primary'])?>
        <?php echo \yii\bootstrap\Html::resetButton('Сбросить', ['class' => 'btn btn-default'])?>

    </div>
    </div>
    <?php \yii\bootstrap\ActiveForm::end()?>
</div>
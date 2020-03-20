<div class="document-search">
    <?php $form = \yii\bootstrap\ActiveForm::begin(
        [
           //  'layout' => 'inline',
            //'action' => ['index'],
            'method' => 'get'
        ])?>

    <?php echo $form->field($model,'hasPsycho')->checkbox()?>


    <div class="form-group">
        <?php echo \yii\helpers\Html::submitButton('Search',['class' => 'btn btn-primary'])?>


    </div>
    </div>
    <?php \yii\bootstrap\ActiveForm::end()?>
</div>
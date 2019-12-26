<div class="document-search">
    <?php $form = \yii\bootstrap\ActiveForm::begin(
        [
             //  'layout' => 'inline',
            'action' => ['index'],
            'method' => 'get'
        ])?>

    <div class="row">

    <div class = 'col-md-6'>
    <?php echo $form->field($model,'termStartFromJui')->widget(\kartik\widgets\DatePicker::class,['defaultOptions' => ['placeholder' => $model->getAttributeLabel('termStartFromJui')]]) ?>
    </div>
        <div class = 'col-md-6'>
    <?php echo $form->field($model,'termStartToJui')->widget(\kartik\widgets\DatePicker::class,['defaultOptions' => ['placeholder' => $model->getAttributeLabel('termStartToJui')]]) ?>
    </div>
    </div>
    <div class="row">

        <div class = 'col-md-6'>
        <?php echo $form->field($model,'termFinishFromJui')->widget(\kartik\widgets\DatePicker::class,['defaultOptions' => ['placeholder' => $model->getAttributeLabel('termFinishFromJui')]]) ?>
        </div>
        <div class = 'col-md-6'>
        <?php echo $form->field($model,'termFinishToJui')->widget(\kartik\widgets\DatePicker::class,['defaultOptions' => ['placeholder' => $model->getAttributeLabel('termFinishToJui')]]) ?>
    </div>
    </div>
    <div class="row">

        <div class = 'col-md-6'>
        <?php echo $form->field($model,'termUdoFromJui')->widget(\kartik\widgets\DatePicker::class,['defaultOptions' => ['placeholder' => $model->getAttributeLabel('termUdoFromJui')]]) ?>
        </div>
        <div class = 'col-md-6'>
        <?php echo $form->field($model,'termUdoToJui')->widget(\kartik\widgets\DatePicker::class,['defaultOptions' => ['placeholder' => $model->getAttributeLabel('termUdoToJui')]]) ?>
    </div>
    </div>

    <div class="row">

    <div class="form-group">
        <?php echo \yii\helpers\Html::submitButton('Search',['class' => 'btn btn-primary'])?>
        <?php echo \yii\bootstrap\Html::resetButton('Сбросить', ['class' => 'btn btn-default'])?>

    </div>
    </div>
    <?php \yii\bootstrap\ActiveForm::end()?>
</div>
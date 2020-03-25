<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\plans\models\Program
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin(['id' => 'form-add-program'])?>

<?=$form->field($model,'programdict_id')->dropDownList(\vova07\plans\models\ProgramDict::getListForCombo(),['prompt' => \vova07\plans\Module::t('default','SELECT')])?>
<?=$form->field($model,'prison_id')->dropDownList(\vova07\prisons\models\Prison::getListForCombo(),['prompt' => \vova07\plans\Module::t('default','SELECT')])?>
<?=$form->field($model,'order_no')?>
<?=$form->field($model,'dateStartJui')->widget(\yii\jui\DatePicker::class);?>
<?=$form->field($model,'assigned_to')->widget(\kartik\widgets\Select2::class,[
        'data' => \vova07\users\models\Officer::getListForCombo(),
        'pluginOptions' => ['allowClear' => true ],
        'options' => ['prompt' =>  \vova07\plans\Module::t('default', 'SELECT_OFFICER')]
])?>
<?=$form->field($model,'status_id')->dropDownList(\vova07\plans\models\Program::getStatusesForCombo(),['prompt' => \vova07\plans\Module::t('default','SELECT_STATUS')])?>



<?php $box->beginFooter();?>
<div class="form-group">
    <?php if ($model->isNewRecord):?>
    <?= Html::submitButton(Module::t('default', 'CREATE'), ['class' => 'btn btn-primary']) ?>
    <?php else: ?>

    <?= Html::submitButton(Module::t('default', 'UPDATE'), ['class' => 'btn btn-primary']) ?>
    <?php endif ?>
</div>
<?php $box->endFooter();?>
<?php ActiveForm::end()?>


<?php
$script = <<< JS

        $("#form-add-program").on('beforeSubmit', function (event) { 
            event.preventDefault();            
            var form_data = new FormData($('#form-add-program')[0]);
            $.ajax({
                   url: $("#form-add-program").attr('action'), 
                   dataType: 'JSON',  
                   cache: false,
                   contentType: false,
                   processData: false,
                   data: form_data, //$(this).serialize(),                      
                   type: 'post',                        
                   beforeSend: function() {
                   },
                   success: function(response){                      
                       alert(response.message); 
                       $('#model').modal('hide');
                   },
                   complete: function() {
                   },
                   error: function (data) {
                      alert("There may a error on uploading. Try again later");    
                   }
                });                
            return false;
        });

JS;
$this->registerJs($script);
?>





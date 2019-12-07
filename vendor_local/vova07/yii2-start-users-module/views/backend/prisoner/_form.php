<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>
<?php if (!$model->isNewRecord):?>
    <?php echo $form->field($model->person,'__ident_id')->hiddenInput()?>
<?php endif;?>
<div class="row">
    <div class = 'col-md-6'>
<?php echo $form->field($model,'status_id')->dropDownList(\vova07\users\models\Prisoner::getStatusesForCombo(),['prompt'=>Module::t('default','SELECT_STATUS')])?>
    </div>
    <div class = 'col-md-6'>
<?=$form->field($model,'article')?>
    </div>
</div>
<div class="row">
    <div class = 'col-md-4'>
        <?=$form->field($model,'termStartJui')->widget(\kartik\widgets\DatePicker::className())?>
    </div>
    <div class = 'col-md-4'>
        <?=$form->field($model,'termUdoJui')->widget(\kartik\widgets\DatePicker::className())?>
    </div>
    <div class = 'col-md-4'>
        <?=$form->field($model,'termFinishJui')->widget(\kartik\widgets\DatePicker::className())?>
    </div>
</div>

<div class="row">
    <div class = 'col-md-4'>
        <?=$form->field($model,'prison_id')->dropDownList(\vova07\prisons\models\Prison::getListForCombo(),['id'=>'prison-id','prompt'=>Module::t('default','SELECT_PRISON')])?>
    </div>
    <div class = 'col-md-4'>
        <?= $form->field($model, 'sector_id')->widget(\kartik\depdrop\DepDrop::classname(), [


            'data' => \vova07\prisons\models\Sector::getListForCombo($model->prison_id),
            'options'=>['id'=>'sector-id', 'placeholder'=>Module::t('default','SELECT_SECTOR')],
            'pluginOptions'=>[
                'depends'=>['prison-id'],

                'url'=>\yii\helpers\Url::to(['prison-sectors'])
            ]
        ]);?>
    </div>
    <div class = 'col-md-4'>
        <?= $form->field($model, 'cell_id')->widget(\kartik\depdrop\DepDrop::classname(), [


            'data' => \vova07\prisons\models\Cell::getListForCombo($model->sector_id),
            'options'=>['id'=>'cell-id', 'placeholder'=>Module::t('default','SELECT_CELL')],
            'pluginOptions'=>[
                'depends'=>['sector-id'],

                'url'=>\yii\helpers\Url::to(['sector-cells'])
            ]
        ]);?>

    </div>
</div>
<div class="row">
    <div class="col-md-6">

        <?=$form->field($model->person,'first_name')?>
    <?=$form->field($model->person,'second_name')?>
    <?=$form->field($model->person,'patronymic')?>
    <?=$form->field($model->person,'birth_year')?>
    <?=$form->field($model->person,'citizen_id')->dropDownList(\vova07\countries\models\Country::getListForCombo(),['prompt'=>Module::t('default','SELECT_COUNTRY')])?>
    <?=$form->field($model->person,'address')?>




    </div>
    <div class = 'col-md-6'>
        <?=$form->field($model->person,'photo_url')->widget(\budyaga\cropper\Widget::className(), [
            'uploadUrl' => \yii\helpers\Url::toRoute(['upload-photo']),
        ])?>
    </div>
</div>









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







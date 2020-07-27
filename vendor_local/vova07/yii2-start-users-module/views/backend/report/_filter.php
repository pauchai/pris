<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\users\Module;
?>
<?php $form = ActiveForm::begin(['layout' => 'inline', 'method' => 'GET'])?>
<?=$form->field($searchModel, 'birth_year_from',['inputOptions' => ['placeholder'=>$searchModel->getAttributeLabel('birth_year_from')]])?>
<?=$form->field($searchModel, 'birth_year_to',['inputOptions' => ['placeholder'=>$searchModel->getAttributeLabel('birth_year_to')]])?>

<?= Html::submitButton(Module::t('default', 'FILTER'), ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end()?>

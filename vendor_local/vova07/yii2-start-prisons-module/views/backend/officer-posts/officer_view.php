<?php
use vova07\themes\adminlte2\widgets\Box;
use vova07\prisons\Module;
use kartik\form\ActiveForm;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\users\models\Officer
 */
$this->title = Module::t("default","OFFICER");
$this->params['subtitle'] = $model->person->fio;
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
?>
<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
       // 'renderBody' => false,
        //'options' => [
        //    'class' => 'box-primary'
        //],
        //'bodyOptions' => [
        //    'class' => 'table-responsive'
        //],
        'buttonsTemplate' => '{update}{delete}'
    ]
);?>

<?php $form=ActiveForm::begin([
    'method' => 'post',
    'action' => ['create'],
    'type' => 'inline',
])?>
<?=$form->field($newOfficerPost, 'officer_id')->hiddenInput()?>
<?=$form->field($newOfficerPost, 'company_id')->hiddenInput()?>
<?= \yii\helpers\Html::submitButton(Module::t('default', 'CREATE_POST'), ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end();?>
<?php Box::end();?>
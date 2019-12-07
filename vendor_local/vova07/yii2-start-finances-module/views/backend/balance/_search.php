<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 30.10.2019
 * Time: 13:07
 */

?>

<?php $form = \yii\bootstrap\ActiveForm::begin(['layout'=>'inline','method'=>'get'])?>
    <?=$form->field($searchModel,'prisoner_id')->widget(\kartik\widgets\Select2::class,[
    'data'=>\vova07\users\models\Prisoner::getListForCombo(),
    'pluginOptions' => ['allowClear' => true],
    'options'=>['prompt' => \vova07\finances\Module::t('default','SELECT_PRISONER'), 'class'=> 'form-control', 'id' => null],

])?>
    <?=$form->field($searchModel,'type_id')->widget(\kartik\widgets\Select2::class,[
    'data'=>\vova07\finances\models\Balance::getTypesForCombo(),
     'pluginOptions' => ['allowClear' => true],
    'options'=>['prompt' => \vova07\finances\Module::t('default','SELECT_TYPE'), 'class'=> 'form-control', 'id' => null],

])?>
<?=$form->field($searchModel,'category_id')->widget(\kartik\widgets\Select2::class,[
    'data'=>\vova07\finances\models\BalanceCategory::getListForCombo(),
    'pluginOptions' => ['allowClear' => true],
    'options'=>['prompt' => \vova07\finances\Module::t('default','SELECT_CATEGORY'), 'class'=> 'form-control', 'id' => null],

])?>
<?=$form->field($searchModel,'reason',['inputOptions'=>['placeholder'=>\vova07\finances\Module::t('default','REASON')]])?>
<?=$form->field($searchModel,'atJui')->widget(\kartik\date\DatePicker::class,[
   // 'pluginOptions'=>['format'=>'yyyy-mm-dd'],
    'options'=>['placeholder'=>\vova07\finances\Module::t('default','DATE_AT')]
    //'options'=>['prompt' => \vova07\finances\Module::t('default','SELECT_AT_DATE'), 'class'=> 'form-control', 'id' => null],

])?>


    <?php echo \yii\bootstrap\Html::submitButton('',['class' => 'fa fa-filter form-control'])?>
<?php \yii\bootstrap\ActiveForm::end()?>

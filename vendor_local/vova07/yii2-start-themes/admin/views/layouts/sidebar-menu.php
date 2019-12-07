<?php

/**
 * Sidebar menu layout.
 *
 * @var \yii\web\View $this View
 */

use vova07\themes\admin\widgets\Menu;

?>

<div class="user-panel">
    <?php $form = \yii\bootstrap\ActiveForm::begin([
        'method' => 'post',
        'action' => ['/prisons/default/switch-location'],
        'layout' => 'inline',
        'options' => [
            'class' => 'navbar-form  navbar-brand'
        ]
    ])?>
    <?php $model = new \vova07\prisons\models\LocationSwitchForm();

    ?>
    <?php echo $form->field($model,"location")->dropDownList(\vova07\prisons\models\Location::getRootLocationsForDropDown(),[
        'prompt' => Yii::t("vova07/themes/admin","STATISTICA_LA_GENERAL")
    ]) ?>
    <?php  echo \yii\bootstrap\Html::submitButton(\vova07\prisons\Module::t('prisons', 'SWITCH_LOCATION'), ['class' => 'btn btn-primary']) ?>

    <?php $form = \yii\bootstrap\ActiveForm::end()?>
    <br/>
</div>

<?php if(Yii::$app->PU->location):?>
<?php echo $this->render('_sidebar-pu')?>
<?php else:?>
<?php echo $this->render('_sidebar-complex');?>
<?php endif;?>

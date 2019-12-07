<?php

/**
 * @var \yii\web\View $this View
 */

?>
    <div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Admin</b>LTE</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg"><?=\vova07\site\Module::t("default",'SIGN_IN_TO_START_SESSION')?></p>

        <?php $form = \yii\bootstrap\ActiveForm::begin()?>
        <div class="form-group has-feedback">

            <?php echo $form->field($model,'username')->textInput(['placeholder' => $model->getAttributeLabel('username')])->label(false)?>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">

            <?php echo  $form->field($model,'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')])->label(false)?>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8">

                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat"><?=\vova07\site\Module::t('default','LOGIN')?></button>
            </div>
            <!-- /.col -->
        </div>
        <?php \yii\bootstrap\ActiveForm::end() ?>




        <a href="#"><?=\vova07\site\Module::t("default",'FORGOT_MY_PASSWORD')?></a><br>


    </div>
    <!-- /.login-box-body -->
    </div>

<?php
$this->registerJs( <<< JS
    $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' /* optional */
        });

JS
, \yii\web\View::POS_READY) ;

?>
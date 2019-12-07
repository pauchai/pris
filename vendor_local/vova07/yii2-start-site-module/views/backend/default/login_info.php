<?php

/**
 * @var \yii\web\View $this View
 */

?>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">YOU_ARE_SIGNED_IN_AS</p>


             <div class="form-group has-feedback">

                    <?=$model->username ?>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">


                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <a  class="btn btn-primary btn-block btn-flat" href="<?=\yii\helpers\Url::to(['logout'])?>">LOGOUT</a>

                </div>
                <!-- /.col -->
            </div>






    </div>
    <!-- /.login-box-body -->

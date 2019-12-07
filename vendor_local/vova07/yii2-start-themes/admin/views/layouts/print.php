<?php

/**
 * Theme main layout.
 *
 * @var \yii\web\View $this View
 * @var string $content Content
 */

use vova07\themes\admin\widgets\Alert;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\ActiveForm;
use vova07\prisons\models\LocationSwitchForm;
use vova07\prisons\models\Location;

?>
<style>
    body {
        padding:30px;
    }
    @media  print {

        a,input,select, button{visibility:hidden;}
    }
</style>
<?php $this->beginPage(); ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <?= $this->render('//layouts/head') ?>
    </head>
    <body >
    <header>
        <?php echo Html::beginTag('div', ['class' => 'box-tools pull-left']);?>
        <a href="javascript:print()"><i class="fa fa-print"></i></a>
        <?php echo Html::endTag("div");?>
    </header>
    <?php $this->beginBody(); ?>

    <?= $content ?>

    <?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage(); ?>
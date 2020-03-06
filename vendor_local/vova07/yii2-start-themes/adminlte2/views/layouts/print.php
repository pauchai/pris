<?php

/**
 * Theme main layout.
 *
 * @var \yii\web\View $this View
 * @var string $content Content
 */

use vova07\themes\admin\widgets\Alert;
use yii\helpers\Html;

dmstr\web\AdminLteAsset::register($this);

?>
<style>
    body {


    }
    body, table, h1,h2,h3,h4,h5,h6,h7  {
        font-family: "Times New Roman" !important;
    }



    @media  print {

       /*
        input,select, button, .select2 ,.input-group{display:none;}
       */

        a,a[href]:after {
            content: " " !important;
        }
        .btn-danger {
            background-color: red !important;
        }
    }

</style>
<?php $this->beginPage(); ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>

        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes" name="viewport">
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>    </head>
    <body >
    <header class="no-print">
        <?php echo Html::beginTag('div', ['class' => 'box-tools pull-left ']);?>
        <a  href="javascript:print()"><i class="fa fa-print"></i></a>
        <?php echo Html::endTag("div");?>
    </header>
    <?php $this->beginBody(); ?>

    <?= $content ?>
    <?php $this->registerJs(
        <<<JS
window.print();
JS


    )?>
    <?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage(); ?>


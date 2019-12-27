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
        padding:30px;
    }
    @media  print {

        input,select, button, .select2 ,.input-group{display:none !important;}

        a,a[href]:after {
            content: " " !important;
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
    <header>
        <?php echo Html::beginTag('div', ['class' => 'box-tools pull-left no-print']);?>
        <a  href="javascript:print()"><i class="fa fa-print"></i></a>
        <?php echo Html::endTag("div");?>
    </header>
    <?php $this->beginBody(); ?>
    <p>
       <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
    </p>
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


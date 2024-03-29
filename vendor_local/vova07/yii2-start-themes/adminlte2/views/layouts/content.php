<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;
use lajax\translatemanager\widgets\ToggleTranslate;

?>
<div class="content-wrapper">
    <section class="content-header">
        <?php if (isset($this->blocks['content-header'])) { ?>
            <h1><?= $this->blocks['content-header'] ?></h1>
        <?php } else { ?>
            <h1>
                <?php
                if ($this->title !== null) {
                    echo \yii\helpers\Html::encode($this->title);
                } else {
                    echo \yii\helpers\Inflector::camel2words(
                        \yii\helpers\Inflector::id2camel($this->context->module->id)
                    );
                    echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                } ?>
            </h1>
        <?php } ?>

        <?=
        Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
    </section>
    <?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
        [
                'buttonsTemplate' => '{print}'
        ]

    );?>
    <?php \vova07\themes\adminlte2\widgets\Box::end()?>
    <section class="content">
        <?= Alert::widget() ?>

        <?= $content ?>
    </section>
</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">

        <b>Version</b> 2.0
    </div>

    <strong>Copyright &copy; 2014-2015 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights
    reserved.
</footer>

<?= $this->render('right')?>
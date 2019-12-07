<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $box \vova07\themes\adminlte2\widgets\Box
  */

?>


<?php $form = ActiveForm::begin()?>

<?php if ($model->isNewRecord):?>
    <?=$form->field($model,'id')?>
<?php else: ?>
    <?=$form->field($model,'id')->staticControl()?>
<?php endif;?>

<?=$form->field($model,'title')?>
<?=$form->field($model,'content')->widget(
    \vova07\imperavi\Widget::className(),
    [
        'settings' => [
            'buttons' => [
                'html',
                'formatting',
                'bold',
                'italic',
                'underline',
                'deleted',
                'unorderedlist',
                'orderedlist',
                'alignment'
            ],
            'plugins' => [
                    'table',
            ],
            'minHeight' => 300

        ]
    ]
)?>


<?php $box->beginFooter();?>
<div class="form-group">
    <?php if ($model->isNewRecord):?>
    <?= Html::submitButton(Module::t('default', 'CREATE'), ['class' => 'btn btn-primary']) ?>
    <?php else: ?>

    <?= Html::submitButton(Module::t('default', 'UPDATE'), ['class' => 'btn btn-primary']) ?>
    <?php endif ?>
</div>
<?php $box->endFooter();?>
<?php ActiveForm::end()?>








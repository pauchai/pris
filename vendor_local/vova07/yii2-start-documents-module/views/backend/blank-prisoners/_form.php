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

<?=$form->field($model,'blank_id')->dropDownList(\vova07\documents\models\Blank::getListForCombo(),[
        'prompt' => Module::t('default','SELECT'),
    'onChange' => 'blankIdOnChange($(this).val());'
])?>
<?=$form->field($model,'prisoner_id')->dropDownList(\vova07\users\models\Prisoner::getListForCombo(),['prompt' => Module::t('default','SELECT')])?>
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

<?php
$url = \yii\helpers\Url::toRoute(['blanks/blank-content']);
$this->registerJs(<<<JS
var contentTextArea = $('#blankprisoner-content');
window.contentTextArea = contentTextArea;
var writeContent = function(options) {
if (typeof options === 'object') {
    contentTextArea.redactor('code.set',options.content)
}
};

var blankIdOnChange = function(id){
$.ajax({
dataType: 'json',
url: '$url&id=' + id ,
success: writeContent});
};

window.blankIdOnChange = blankIdOnChange;
JS
,\yii\web\View::POS_READY);

?>








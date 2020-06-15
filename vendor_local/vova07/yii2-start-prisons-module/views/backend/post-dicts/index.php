<?php
use kartik\form\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\PostDict
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","POST_DICTS");
$this->params['subtitle'] = 'LIST';
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
  //      'url' => ['index'],
    ],
   // $this->params['subtitle']
];
?>

<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{create}'
    ]

);?>

<?php echo \yii\grid\GridView::widget(['dataProvider' => $dataProvider,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        'id',
        'title',
        [
            'class' => \yii\grid\ActionColumn::class,
            'buttons' => [
                'divisions' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', ['divisions/index','DivisionSearch[company_id]' => $key], [
                        'title' => \vova07\prisons\Module::t('default', 'DIVISIONS'),
                        'data-pjax' => '0',
                    ]);
                },
            ],

        ]
    ]
])?>


<?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_INLINE,
        'action' => ['create']
])?>

<?=$form->field($newModel,'id')?>
<?=$form->field($newModel,'iso_id')->widget(
    \kartik\select2\Select2::class,
    [
        'data' => \vova07\prisons\models\PostIso::getListForCombo(),
        'pluginOptions' => [
            'allowClear'=>true

        ],
        'options' => [
            'placeholder' => Module::t('default','SELECT_POST_ISO'),
        ],
    ]
)?>

<?=$form->field($newModel,'title')->widget(\vova07\base\components\widgets\DepInput::class,[
    'depends' => 'postdict-iso_id'
])?>


<?php $box->beginFooter();?>
<div class="form-group">
    <?php if ($newModel->isNewRecord):?>
        <?= Html::submitButton(Module::t('default', 'CREATE'), ['class' => 'btn btn-primary']) ?>
    <?php else: ?>

        <?= Html::submitButton(Module::t('default', 'UPDATE'), ['class' => 'btn btn-primary']) ?>
    <?php endif ?>
</div>
<?php $box->endFooter();?>
<?php ActiveForm::end()?>



<?php \vova07\themes\adminlte2\widgets\Box::end()?>



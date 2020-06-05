<?php
use kartik\form\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","COMPANY DEPARTMENTS");
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
        'buttonsTemplate' => '{create}',
         'buttons' => [
            'create' => [
                'url' => ['create','company_id' => $company->primaryKey],
                'icon' => 'fa-plus',
                'options' => [
                    'class' => 'btn-default',
                    'title' => Yii::t('vova07/themes/adminlte2/widgets/box', 'Create'),

                ]
            ]
          ]


    ]

);?>

<?php echo \yii\grid\GridView::widget(['dataProvider' => $dataProvider,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        'company.title',
        'title',
        [
            'class' => \yii\grid\ActionColumn::class,
            'template' => '{posts} {delete}',
            'buttons' => [
                'posts' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', ['posts/index','PostSearch'=>$key], [
                        'title' => \vova07\prisons\Module::t('default', 'POSTS'),
                        'data-pjax' => '0',
                    ]);
                },
            ],
        ]
    ]
])?>
<?php \vova07\themes\adminlte2\widgets\Box::end()?>


<?php $form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_INLINE,
    'action' => ['create','company_id' => $company->primaryKey]
])?>

<?=$form->field($newModel,'company_id')->hiddenInput(['id' => 'company_id'])->label($newModel->company->title)?>

<?=$form->field($newModel,'division_id')->widget(\kartik\depdrop\DepDrop::class,[
    'type' => \kartik\depdrop\DepDrop::TYPE_SELECT2,

    'data' => \vova07\prisons\models\DivisionDict::getListForCombo(),
    'pluginOptions' => [
        'depends'=>['company_id' ],
        'url'=>\yii\helpers\Url::to(['companies/company-divisions']),

    ],

    'select2Options' => [
        'pluginOptions' => [
            'allowClear'=>true

        ],

    ],
    'options' => [
        'placeholder' => Module::t('default','SELECT_DIVISIONS'),
    ],
])
?>

<?=$form->field($newModel,'title')->widget(\vova07\base\components\widgets\DepInput::class,[
    'depends' => 'division-division_id'
])?>




    <div class="form-group">
        <?php if ($newModel->isNewRecord):?>
            <?= Html::submitButton(Module::t('default', 'CREATE'), ['class' => 'btn btn-primary']) ?>
        <?php else: ?>

            <?= Html::submitButton(Module::t('default', 'UPDATE'), ['class' => 'btn btn-primary']) ?>
        <?php endif ?>
    </div>

<?php ActiveForm::end()?>
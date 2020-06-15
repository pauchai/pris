<?php
use kartik\form\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
use vova07\prisons\models\OfficerPost;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\backend\PostSearch
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","DEPARTMENT POSTS");
$this->params['subtitle'] = 'LIST';
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
  //      'url' => ['index'],
    ],
   // $this->params['subtitle']
];
?>
<?php
$createParam = $searchModel->getAttributes(['company_id','division_id']);
$createParam[0] = 'create';
?>
<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{create}',
         'buttons' => [
            'create' => [
                'url' => $createParam,
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
        'division.title',
        'postDict.title',
        'title',
        'rates',
        [
                'header' => 'actual_rates',
            'format' => 'html',
            'content' => function($model){
                $urlParam[0] = '/prisons/officer-posts/index';
                $urlParam['OfficerPostSearch'] = [
                    'company_id'=>$model->company_id,
                    'division_id'=>$model->division_id,
                    'postdict_id'=>$model->postdict_id
                ];
                return \yii\helpers\Html::a(
                        $model->getOfficerPosts()->sumRates(),
                    $urlParam
                );
            }
        ],
        [
            'class' => \yii\grid\ActionColumn::class,
            'template' => '{delete}',

        ],

    ]
])?>


<?php $form = ActiveForm::begin([
        'action' => ['create', 'company_id' => $newModel->company_id,'division_id' => $newModel->division_id],
        'type' => ActiveForm::TYPE_INLINE
])?>

<?=$form->field($newModel,'company_id')->hiddenInput(['id' => 'company_id'])->label($newModel->company->title)?>
<?=$form->field($newModel,'division_id')->widget(\kartik\depdrop\DepDrop::class,[
    'type' => \kartik\depdrop\DepDrop::TYPE_SELECT2,

    'data' => $newModel->company->getDivisionsForCombo(),
    'pluginOptions' => [
        'depends'=>['company_id' ],
        'url'=>\yii\helpers\Url::to(['companies/company-divisions']),

    ],

    'select2Options' => [
        'pluginOptions' => [
            'allowClear'=>true

        ],
        'options' => [
            'placeholder' => Module::t('default','SELECT_DIVISIONS'),
            'id' => 'division_id'
        ],
    ],
])
?>
<?=$form->field($newModel,'postdict_id')->widget(\kartik\widgets\Select2::class,[

    'data' => \vova07\prisons\models\PostDict::getListForCombo(),
    'pluginOptions' => [
        'allowClear'=>true
    ],



    'options' => [
        'placeholder' => Module::t('default','SELECT_POSTS'),
    ],

])
?>
<?=$form->field($newModel,'title')->widget(\vova07\base\components\widgets\DepInput::class,[
    'depends' => 'post-postdict_id'
])?>
<?=$form->field($newModel,'rates')?>






<div class="form-group">
    <?php if ($newModel->isNewRecord):?>
        <?= Html::submitButton(Module::t('default', 'CREATE'), ['class' => 'btn btn-primary']) ?>
    <?php else: ?>

        <?= Html::submitButton(Module::t('default', 'UPDATE'), ['class' => 'btn btn-primary']) ?>
    <?php endif ?>
</div>

<?php ActiveForm::end()?>
<?php \vova07\themes\adminlte2\widgets\Box::end()?>



<?php
use kartik\form\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
use kartik\depdrop\DepDrop;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\backend\PostSearch
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","OFFICER_POSTS");
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
$createParam = $searchModel->getAttributes(['officer_id', 'company_id','division_id','postdict_id']);
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
        [
          'attribute' =>  'officer.person.fio',
          'format' => 'html',
          'content' => function($model){
                return Html::a($model->officer->person->fio,
                    ['/users/officers/view',
                        'id' => $model->officer_id,

                    ]);
            }
        ],

        'company.title',
        'division.title',
        'postDict.title',
        [
          'header' => '',
          'content' => function($model){
              $officerMainPost = $model->officer->officerPost;
              if (isset($officerMainPost) &&
                $officerMainPost->officer_id == $model->officer_id &&
                  $officerMainPost->company_id == $model->company_id &&
                  $officerMainPost->division_id == $model->division_id &&
                  $officerMainPost->postdict_id == $model->postdict_id

              )
                    return Html::tag('i',
                            '',
                        [
                            'class' => 'fa fa-check'
                    ]);

          }
        ],
        'full_time:boolean',
        'benefitClass.title',
        [
            'class' => \yii\grid\ActionColumn::class,
            'template' => '{delete}',

        ]
    ]
])?>


<?php $form = ActiveForm::begin([
    'action' => ['create', 'company_id' => $newModel->company_id,'division_id' => $newModel->division_id],
    'type' => ActiveForm::TYPE_INLINE
])?>

<?=$form->field($newModel,'officer_id')->widget(
        \kartik\select2\Select2::class,
        [
                'data' => \vova07\users\models\Officer::getListForCombo(),
            'pluginOptions' => [
                'allowClear'=>true

            ],
            'options' => [

                'placeholder' => Module::t('default','SELECT_OFFICER'),
            ],
        ]
)?>
<?=$form->field($newModel,'company_id')->hiddenInput(['id' => 'company_id'])?>
<?=$form->field($newModel,'division_id')->widget(DepDrop::class,[

    'type' => DepDrop::TYPE_SELECT2,

    'data' => $newModel->company->getDivisionsForCombo(),
    'pluginOptions' => [
        'depends'=>['company_id'],
        'url'=>\yii\helpers\Url::to(['/prisons/divisions/company-divisions']),

    ],


    'select2Options' => [
        'pluginOptions' => [
            'allowClear'=>true

        ],

    ],
    'options' => [
        'id' => 'division_id',

        'placeholder' => Module::t('default','SELECT_DIVISIONS'),
    ],
]) ?>

<?=$form->field($newModel,'postdict_id')->widget(DepDrop::class,[
    'type' => DepDrop::TYPE_SELECT2,

    'data' => $newModel->division?$newModel->division->getPostsForCombo():[],
    'pluginOptions' => [
        'depends'=>['company_id', 'division_id'],
        'url'=>\yii\helpers\Url::to(['/prisons/posts/division-posts']),

    ],

    'select2Options' => [
        'pluginOptions' => [
            'allowClear'=>true

        ],

    ],
    'options' => [
        'placeholder' => Module::t('default','SELECT_POSTS'),
    ],

]) ?>


<?=$form->field($newModel,'full_time')->checkbox()?>


<?=$form->field($newModel,'benefit_class')->dropDownList(
        \vova07\salary\models\SalaryBenefit::getListForCombo(),
        [
                'prompt' => \vova07\salary\Module::t('default','BENEFIT_CLASS')
        ]
)?>





<div class="form-group">
    <?php if ($newModel->isNewRecord):?>
        <?= Html::submitButton(Module::t('default', 'CREATE'), ['class' => 'btn btn-primary']) ?>
    <?php else: ?>

        <?= Html::submitButton(Module::t('default', 'UPDATE'), ['class' => 'btn btn-primary']) ?>
    <?php endif ?>
</div>

<?php ActiveForm::end()?>
<?php \vova07\themes\adminlte2\widgets\Box::end()?>



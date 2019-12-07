<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
use vova07\themes\adminlte2\widgets\Box;
use vova07\finances\Module;
use yii\grid\SerialColumn;
//use yii\grid\GridView;
use vova07\finances\components\GridView;
use vova07\finances\components\DataColumnWithHeaderAction;
use vova07\finances\models\BalanceCategory;
use \yii\bootstrap\Html;
use vova07\users\models\Prisoner;
use \vova07\finances\components\DataColumnWithButtonAction;

$this->title = Module::t("default","BALANCE_TITLE");
$this->params['subtitle'] = 'LIST';
$this->params['breadcrumbs'] = [
    [
        'label' => 'FINANCES_DEFAULT',
        'url' => ['/finances/default/index']
    ] ,
    [
        'label' => $this->title,
        //      'url' => ['index'],
    ],
    // $this->params['subtitle']
];


?>

<?php $box = Box::begin(
    [
        'title' => $this->params['subtitle'],
//        'buttonsTemplate' => '{create}'
    ]

);?>


<?php // echo $this->render('_search',['searchModel' => $searchModel])?>

<?php

    ;
    echo Html::a('',\yii\helpers\Url::current([0=>'print-receipt']),['class' => 'fa fa-print'])
?>
<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'filterModel'=>$searchModel,
    'pjax' => false,
    'showFooter' => true,
   /* 'afterFooter' => [
        [
            'columns' => [

                [],
                [
                    'content' =>  $form->field($newModel,'prisoner_id')->widget(\kartik\widgets\Select2::class,[
                        'data'=>\vova07\users\models\Prisoner::getListForCombo(),
                        'pluginOptions' => ['allowClear' => true],
                        'options'=>[
                         //   'disabled' => $searchModel->prisoner_id?'disabled':false,
                            'prompt' => \vova07\finances\Module::t('default','SELECT_PRISONER'), 'class'=> 'form-control', 'id' => null
                        ],

                    ])->label(false)  ,
                ],
                [
                    'content' => $form->field($newModel,'type_id')->widget(\kartik\widgets\Select2::class,[
                        'data'=>\vova07\finances\models\Balance::getTypesForCombo(),
                        'pluginOptions' => ['allowClear' => true],
                        'options'=>[
                        //    'disabled' => $searchModel->type_id?'disabled':false,
                            'prompt' => \vova07\finances\Module::t('default','SELECT_TYPE'), 'class'=> 'form-control', 'id' => null],

                    ])->label(false) ,
                ],
                [
                    'content' => $form->field($newModel,'category_id')->widget(\kartik\widgets\Select2::class,[
                        'data'=>\vova07\finances\models\BalanceCategory::getListForCombo(),
                        'pluginOptions' => ['allowClear' => true],
                        'options'=>[
                       //     'disabled' => $searchModel->category_id?'disabled':false,
                            'prompt' => \vova07\finances\Module::t('default','SELECT_CATEGORY'), 'class'=> 'form-control', 'id' => null],

                    ])->label(false) ,
                ],
                [
                    'content' => $form->field($newModel,'amount',[
                        'inputOptions' =>[               'disabled' => $searchModel->amount?'disabled':false]
                    ])->label(false) ,
                ],
                [
                    'content' => $form->field($newModel,'reason',[
                      //  'inputOptions' =>[               'disabled' => $searchModel->reason?'disabled':false]
                    ])->label(false) ,
                ],
                [
                    'content' => $form->field($newModel,'atJui',[
                      //  'inputOptions' =>[               'disabled' => $searchModel->atJui?'disabled':false]
                    ])->widget(\kartik\date\DatePicker::class,[
                     //   'pluginOptions'=>['format'=>'yyyy-mm-dd'],
                        'options'=>['prompt' => \vova07\finances\Module::t('default','SELECT_AT_DATE'), 'class'=> 'form-control', 'id' => null],

                    ])->label(false) ,
                ],
                [
                    'content' => \yii\helpers\Html::submitButton('',['class' => 'form-control fa fa-plus']),
                ]
            ]
        ]
    ],*/
    'columns' => [
        ['class' => SerialColumn::class],
        [
            'attribute' => 'prisoner_id',
            'value' => 'prisoner.fullTitle',
            'filter' => Prisoner::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => Module::t('default','SELECT_PRISONER'), 'class'=> 'form-control', 'id' => null],

        ],
        [
            'attribute' => 'type_id',
            'content' => function($model){
                   return \yii\helpers\Html::tag('span',$model->typeTitle);
            },
            'filter' => \vova07\finances\models\Balance::getTypesForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => Module::t('default','SELECT_TYPE'), 'class'=> 'form-control', 'id' => null],


        ],
        [
            'attribute' => 'category_id',
            'content' => function($model){

                return \yii\helpers\Html::tag('span',$model->category->short_title);
            },
            'filter' => BalanceCategory::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => Module::t('default','SELECT_CATEGORY'), 'class'=> 'form-control', 'id' => null],

        ],
        [
          'attribute' => 'amount',
            'content' => function($model){
                if ($model->type_id === \vova07\finances\models\Balance::TYPE_DEBIT){
                    $options = ['class' => 'text-success','style'=>"font-weight:bolder"];
                } else {
                    $options = ['class' => 'text-danger','style'=>"font-weight:bolder"];
                }
                return \yii\helpers\Html::tag('span',$model->amount,$options);
            },

        ],

        'reason',
        [
            'attribute' => 'atJui',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
               // 'pluginOptions'=>['format'=>'yyyy-mm-dd']
            ]
        ],

    ]
])?>
<div class="">


    <?php $form=\kartik\form\ActiveForm::begin([
            'type' => \kartik\form\ActiveForm::TYPE_INLINE
    ])?>
    <?=\kartik\builder\Form::widget([
            'model' => $newModel,
        'form' => $form,
        'attributes' => [
              'prisoner_id' => ['type' => ($searchModel->prisoner_id)?\kartik\builder\Form::INPUT_HIDDEN:\kartik\builder\Form::INPUT_WIDGET,
              'widgetClass' => \kartik\widgets\Select2::class,
                  'options' => [
                      'data'=>\vova07\users\models\Prisoner::getListForCombo(),
                      'pluginOptions' => ['allowClear' => true],
                      'options'=>[
                          //   'disabled' => $searchModel->prisoner_id?'disabled':false,
                          'prompt' => \vova07\finances\Module::t('default','SELECT_PRISONER'), 'class'=> 'form-control', 'id' => null,
                        // 'disabled' => ($searchModel->prisoner_id)?'disabled':false
                      ]
              ]
              ],
                  'type_id' => ['type' => ($searchModel->type_id)?\kartik\builder\Form::INPUT_HIDDEN:\kartik\builder\Form::INPUT_WIDGET,
                      'widgetClass' => \kartik\widgets\Select2::class,
                      'options' => [
                          'data'=>\vova07\finances\models\Balance::getTypesForCombo(),
                          'pluginOptions' => ['allowClear' => true],
                          'options'=>[
                              //   'disabled' => $searchModel->prisoner_id?'disabled':false,
                              'prompt' => \vova07\finances\Module::t('default','SELECT_TYPE'), 'class'=> 'form-control', 'id' => null],
                        //  'disabled' => ($searchModel->type_id)?'disabled':false
                          ]
                      ],
                  'category_id' => ['type' => ($searchModel->category_id)?\kartik\builder\Form::INPUT_HIDDEN:\kartik\builder\Form::INPUT_WIDGET,
                      'widgetClass' => \kartik\widgets\Select2::class,
                      'options' => [
                          'data'=>\vova07\finances\models\BalanceCategory::getListForCombo(),
                          'pluginOptions' => ['allowClear' => true],
                          'options'=>[
                              //     'disabled' => $searchModel->category_id?'disabled':false,
                              'prompt' => \vova07\finances\Module::t('default','SELECT_CATEGORY'), 'class'=> 'form-control', 'id' => null],
                        //  'disabled' => ($searchModel->category_id)?'disabled':false

                      ]
                  ],
            'amount' => [
                    'type' => ($searchModel->amount)?\kartik\builder\Form::INPUT_HIDDEN:\kartik\builder\Form::INPUT_TEXT,

                    'options' =>[
                        //'disabled' => ($searchModel->amount)?'disabled':false,
                        'placeholder' => Module::t('forms','AMOUNT_LABEL')
                    ]


        ],
            'reason' => [
                    'type' => ($searchModel->reason)?\kartik\builder\Form::INPUT_HIDDEN:\kartik\builder\Form::INPUT_TEXT,
                'options' =>[
                //        'disabled' => $searchModel->reason?'disabled':false
                ]
            ],
            'atJui' => ['type' => ($searchModel->atJui)?\kartik\builder\Form::INPUT_HIDDEN:\kartik\builder\Form::INPUT_WIDGET,
                'widgetClass' => \kartik\date\DatePicker::class,
                'options' => [
                    'pluginOptions' => ['allowClear' => true],
                    'options'=>[
                        //   'disabled' => $searchModel->prisoner_id?'disabled':false,
                        'prompt' => \vova07\finances\Module::t('default','SELECT_AT_DATE'),
                   // 'disabled' => ($searchModel->type_id)?'disabled':false
                ]
            ],

            ],


    ]
    ])?>
    <?=\yii\helpers\Html::submitButton('',['class' => 'form-control fa fa-plus'])?>

    <?php \kartik\form\ActiveForm::end()?>
</div>



<?php  Box::end()?>

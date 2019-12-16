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
use kartik\grid\GridView;
use vova07\finances\components\DataColumnWithHeaderAction;
use vova07\finances\models\BalanceCategory;
use \yii\bootstrap\Html;
use vova07\users\models\Prisoner;
use \vova07\finances\components\DataColumnWithButtonAction;

$this->title = Module::t("default","FINANCES_TITLE");
$this->params['subtitle'] = 'LIST';
$this->params['breadcrumbs'] = [
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
<?php
    $DebitCategories = BalanceCategory::findAll(['type_id'=>\vova07\finances\models\Balance::TYPE_DEBIT]);
    $debitCreateCategoryItems = [];
    foreach ($DebitCategories as $category){
        $debitCreateCategoryItems[] = ['label'=>$category->title, 'url'=>'create'];
    }

?>
<?php
$creditCategories = BalanceCategory::findAll(['type_id'=>\vova07\finances\models\Balance::TYPE_CREDIT]);
$creditCreateCategoryItems = [];
foreach ($creditCategories as $category){
    $creditCreateCategoryItems[] = ['label'=>$category->title, 'url'=>'create'];
}

?>

<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'filterModel'=>$searchModel,
    'pjax'=>true,

    'beforeHeader' => [
        [
            'columns' => [
                [],
                [],
                [],
                [
                    'content' => \yii\helpers\Html::a(
                        Module::t('labels','DEBIT_LABEL'),
                        \yii\helpers\Url::to(['/finances/balance/index',(new \vova07\finances\models\backend\BalanceSearch())->formName().'[type_id]' => \vova07\finances\models\Balance::TYPE_DEBIT]),
                    [
                        'class' => 'label-success'
                        ]
                    ),
                    'options' => [
                        'colspan' => 4,
                        'style' => 'text-align:center',
                        'class' => 'label-success'
                    ]
                ],
                [
                    'content' => \yii\helpers\Html::a(
                        Module::t('labels','CREDIT_LABEL'),
                        \yii\helpers\Url::to(['/finances/balance/index',(new \vova07\finances\models\backend\BalanceSearch())->formName().'[type_id]' => \vova07\finances\models\Balance::TYPE_CREDIT]),
                        [
                            'class' => 'label-danger'
                        ]
                    ),
                    'options' => [
                        'colspan' => 4,
                        'style' => 'text-align:center',
                        'class' => 'label-danger'
                    ]
                ],

                []
            ]
        ]
    ],
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
            'attribute' => 'prisoner.sector_id',
            'content' => function($model){
                    if ($model->prisoner->sector_id)
                    return $model->prisoner->sector->title;
                    else
                        return null;
                    },
            'filter' => \vova07\prisons\models\Sector::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => Module::t('default','SELECT_SECTOR'), 'class'=> 'form-control', 'id' => null],


        ],
        [
            'class' => DataColumnWithButtonAction::class,
            'attribute' => 'category1',
            'options' => ['class' => 'text-success']
        ],
        [
            'class' => DataColumnWithButtonAction::class,
            'attribute' => 'category2',
            'options' => ['class' => 'text-success']
        ],
        [
            'class' => DataColumnWithButtonAction::class,
            'attribute' => 'category3',
            'options' => ['class' => 'text-success']
        ],
        [
            'class' => DataColumnWithButtonAction::class,
            'attribute' => 'category4',
            'options' => ['class' => 'text-success']
        ],
/*        [
            'class' => DataColumnWithButtonAction::class,
            'attribute' => 'debit',
            'type_id' => \vova07\finances\models\Balance::TYPE_DEBIT,
            'label' => 'total',
            'options' => ['class' => 'text-success','style'=>'font-weight:bolder']
        ],*/



        [
            'class' => DataColumnWithButtonAction::class,
            'attribute' => 'category5',
            'options' => ['class' => 'text-danger']
        ],
        [
            'class' => DataColumnWithButtonAction::class,
            'attribute' => 'category6',
            'options' => ['class' => 'text-danger']
        ],
        [
            'class' => DataColumnWithButtonAction::class,
            'attribute' => 'category7',
            'options' => ['class' => 'text-danger']
        ],
        [
            'class' => DataColumnWithButtonAction::class,
            'attribute' => 'category8',
            'options' => ['class' => 'text-danger']
        ],
   /*     [
            'class' => DataColumnWithButtonAction::class,
            'attribute' => 'credit',
            'type_id' => \vova07\finances\models\Balance::TYPE_CREDIT,
            'label' => 'total',
            'options' => ['class' => 'text-danger','style'=>'font-weight:bolder']
        ],*/


        /*[
            'attribute' => 'debit',
            'class' => DataColumnWithHeaderAction::class,
            'buttonItems' =>$debitCreateCategoryItems
        ],
        [
            'attribute' => 'credit',
            'class' => DataColumnWithHeaderAction::class,
            'buttonItems' =>$creditCreateCategoryItems

        ],*/
        [
            'class' => DataColumnWithButtonAction::class,
            'attribute' => 'remain',
            'enableResolveCategoryAndType' => false,
            'contentOptions' => function($model){
                if ($model->remain < 0){
                    $options = ['class'=>' btn-default'];
                } else {
                    $options = ['class'=>'btn-default'];
                }
               // return Html::tag('div',$model->remain,$options);
                return $options;
            },


        ],
        [
            'attribute' => 'only_debt',


            'label'=>false,

            'filterType' => GridView::FILTER_CHECKBOX,
            'filterInputOptions' => ['class'=>''],
            'contentOptions' => function($model){
                if ($model->remain < 0){
                    $options = ['class'=>'fa fa-check btn-danger'];
                } else {
                    $options = ['class'=>''];
                }
                $options['style'] = 'font-weight:bolder';
                // return Html::tag('div',$model->remain,$options);
                return $options;
            },

        ],
        [
            'class' => \yii\grid\ActionColumn::class,
            'template' => '{print}',
            'buttons' => [
                'print' => function ($url, $model, $key) {
                        $urlOptions = ['view','id'=>$model->primaryKey];
                             return  Html::a('', $urlOptions,['class'=>'fa fa-print']);
                         },
            ]

        ]



    ]
])?>


<?php  Box::end()?>

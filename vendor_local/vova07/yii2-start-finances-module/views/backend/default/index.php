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
    $archiveUrl = [
        'prisoner_id' => $searchModel['prisoner_id'],
        'prisoner.status_id' => $searchModel['prisoner.status_id'],
    ];
    $archiveUrl[0] = '/finances/balance/print-archive';
    echo Html::a('ARCHIVE', $archiveUrl);
?>
<?php
    $gridColumns  = [
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
            'attribute' => 'prisoner.status_id',
            'value' => 'prisoner.status',
            'filter' => \vova07\users\models\Prisoner::getStatusesForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => Module::t('default','SELECT_STATUS'), 'class'=> 'form-control', 'id' => null],


        ]
        ];
$debitColumnNames = ['category1','category2','category3','category4'];
$debitCnt = 0;
foreach ($debitColumnNames as $columnName){
    if (!Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_FINANCES_LIST))
        continue;

        $debitCnt++;
    $gridColumns[] = [
        'class' => DataColumnWithButtonAction::class,
        'attribute' => $columnName,
        'options' => ['class' => 'text-success']
    ];
};
$credirColumnNames = ['category5','category6','category7','category8'];
$creditCnt = 0;
foreach ($credirColumnNames as $columnName){
    if (!Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_FINANCES_LIST))
        continue;

        $creditCnt++;
        $gridColumns[] = [
            'class' => DataColumnWithButtonAction::class,
            'attribute' => $columnName,
            'options' => ['class' => 'text-success']
        ];
};

       $gridColumns[] = [
            'class' => \vova07\finances\components\DataColumnWithButtonActionRemain::class,
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
        ];
        $gridColumns[] = [
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

        ];
        $gridColumns[] = [
            'visible' => Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_FINANCES_LIST),
            'class' => \yii\grid\ActionColumn::class,
            'template' => '{print}',
            'buttons' => [
                'print' => function ($url, $model, $key) {
                    $urlOptions = ['view','id'=>$model->primaryKey];
                    return  Html::a('', $urlOptions,['class'=>'fa fa-print']);
                },
            ]

        ];

$debitHeader = [
    'content' => \yii\helpers\Html::a(
        Module::t('labels','DEBIT_LABEL'),
        \yii\helpers\Url::to(['/finances/balance/index',(new \vova07\finances\models\backend\BalanceSearch())->formName().'[type_id]' => \vova07\finances\models\Balance::TYPE_DEBIT]),
        [
            'class' => 'label-success'
        ]
    ),
    'options' => [
        'colspan' => $debitCnt,
        'style' => 'text-align:center',
        'class' => 'label-success'
    ]
];
$creditHeader = [
    'content' => \yii\helpers\Html::a(
        Module::t('labels','CREDIT_LABEL'),
        \yii\helpers\Url::to(['/finances/balance/index',(new \vova07\finances\models\backend\BalanceSearch())->formName().'[type_id]' => \vova07\finances\models\Balance::TYPE_CREDIT]),
        [
            'class' => 'label-danger'
        ]
    ),
    'options' => [
        'colspan' => $creditCnt,
        'style' => 'text-align:center',
        'class' => 'label-danger'
    ]
];



$gridBeforeHeader = [
    [
        'columns' => [
            [],
            [],
            [],
            [],

            $debitCnt?$debitHeader:[],
            $creditCnt?$creditHeader:[],

            []
        ]
    ]
];
?>

<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'filterModel'=>$searchModel,
    'pjax'=>true,

    'beforeHeader' => $gridBeforeHeader,
    'columns' => $gridColumns
])?>


<?php  Box::end()?>

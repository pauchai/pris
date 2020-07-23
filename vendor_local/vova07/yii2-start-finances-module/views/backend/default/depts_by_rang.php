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
use yii\db\Expression;

$this->title = Module::t("default","FINANCES_TITLE");
$this->params['subtitle'] = 'DEPTS';
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
            'attribute' => 'prisoner.sector_id',
            //'group' => true,
            //'groupedRow' => true,
            'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
            'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class

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
$debitCnt = 0;


$creditCnt = 0;


       $gridColumns[] = [
          //  'class' => \vova07\finances\components\DataColumnWithButtonActionRemain::class,
            'attribute' => 'fromJui',
           //'filter' => \vova07\prisons\models\Sector::getListForCombo(),
           'pageSummary' => true,
           'pageSummaryFunc' => GridView::F_SUM,
           'filterType' => GridView::FILTER_DATE,
           'filterWidgetOptions' => [
               'pluginOptions' => [
                   'allowClear' => true,
                    'format' => 'dd-mm-yyyy'
               ],
           ],
           'filterInputOptions' => ['prompt' => Module::t('default','SELECT_SECTOR'), 'class'=> 'form-control', 'id' => null],

           //'enableResolveCategoryAndType' => false,
            'contentOptions' => function($model)use($searchModel){
                /**
                 *@var $model \vova07\users\models\Prisoner
                 */
                $typeDebitId = \vova07\finances\models\Balance::TYPE_DEBIT;
                $typeCreditId = \vova07\finances\models\Balance::TYPE_CREDIT;
                $remain = $model->prisoner->getBalances()->where(['<=','at', $searchModel->from])->sum(new Expression("CASE WHEN type_id=$typeDebitId THEN amount WHEN type_id=$typeCreditId THEN -amount END"));
                if ($remain < 0){
                    $options = ['class'=>' btn-danger'];
                } else {
                    $options = ['class'=>'btn-default'];
                }
                // return Html::tag('div',$model->remain,$options);
                return $options;
            },
           'value' => function($model)use($searchModel){
               $typeDebitId = \vova07\finances\models\Balance::TYPE_DEBIT;
               $typeCreditId = \vova07\finances\models\Balance::TYPE_CREDIT;
               $remain = $model->prisoner->getBalances()->where(['<=','at', $searchModel->from])->sum(new Expression("CASE WHEN type_id=$typeDebitId THEN amount WHEN type_id=$typeCreditId THEN -amount END"));
              return $remain;
           },

        ];

$gridColumns[] = [
    //  'class' => \vova07\finances\components\DataColumnWithButtonActionRemain::class,
    'attribute' => 'toJui',
    //'filter' => \vova07\prisons\models\Sector::getListForCombo(),
    'pageSummary' => true,
    'pageSummaryFunc' => GridView::F_SUM,

    'filterType' => GridView::FILTER_DATE,
    'filterWidgetOptions' => [
        'pluginOptions' => [
            'allowClear' => true,
            'format' => 'dd-mm-yyyy'
        ],
    ],
    'filterInputOptions' => ['prompt' => Module::t('default','SELECT_SECTOR'), 'class'=> 'form-control', 'id' => null],

    //'enableResolveCategoryAndType' => false,
    'contentOptions' => function($model)use($searchModel){
        /**
         *@var $model \vova07\users\models\Prisoner
         */
        $typeDebitId = \vova07\finances\models\Balance::TYPE_DEBIT;
        $typeCreditId = \vova07\finances\models\Balance::TYPE_CREDIT;
        $remain = $model->prisoner->getBalances()->where(['<=','at', $searchModel->to])->sum(new Expression("CASE WHEN type_id=$typeDebitId THEN amount WHEN type_id=$typeCreditId THEN -amount END"));
        if ($remain < 0){
            $options = ['class'=>' btn-danger'];
        } else {
            $options = ['class'=>'btn-default'];
        }
        // return Html::tag('div',$model->remain,$options);
        return $options;
    },
    'value' => function($model)use($searchModel){
        $typeDebitId = \vova07\finances\models\Balance::TYPE_DEBIT;
        $typeCreditId = \vova07\finances\models\Balance::TYPE_CREDIT;
        $remain = $model->prisoner->getBalances()->where(['<=','at', $searchModel->to])->sum(new Expression("CASE WHEN type_id=$typeDebitId THEN amount WHEN type_id=$typeCreditId THEN -amount END"));
        return $remain;
    }
];
$gridColumns[] = [
    //  'class' => \vova07\finances\components\DataColumnWithButtonActionRemain::class,
    'header' => 'DIFF',
    //'filter' => \vova07\prisons\models\Sector::getListForCombo(),

    'pageSummary' => true,
    'pageSummaryFunc' => GridView::F_SUM,
    //'enableResolveCategoryAndType' => false,
    'contentOptions' => function($model)use($searchModel){
        /**
         *@var $model \vova07\users\models\Prisoner
         */
        $typeDebitId = \vova07\finances\models\Balance::TYPE_DEBIT;
        $typeCreditId = \vova07\finances\models\Balance::TYPE_CREDIT;
        $fromRemain = $model->prisoner->getBalances()->where(['<=','at', $searchModel->from])->sum(new Expression("CASE WHEN type_id=$typeDebitId THEN amount WHEN type_id=$typeCreditId THEN -amount END"));
        $toRemain = $model->prisoner->getBalances()->where(['<=','at', $searchModel->to])->sum(new Expression("CASE WHEN type_id=$typeDebitId THEN amount WHEN type_id=$typeCreditId THEN -amount END"));
        $diff = $toRemain - $fromRemain;
        if ($diff < 0){
            $options = ['class'=>' btn-danger'];
        } else {
            $options = ['class'=>'btn-default'];
        }
        // return Html::tag('div',$model->remain,$options);
        return $options;
    },
    'value' => function($model)use($searchModel){
        $typeDebitId = \vova07\finances\models\Balance::TYPE_DEBIT;
        $typeCreditId = \vova07\finances\models\Balance::TYPE_CREDIT;
        $typeDebitId = \vova07\finances\models\Balance::TYPE_DEBIT;
        $typeCreditId = \vova07\finances\models\Balance::TYPE_CREDIT;
        $fromRemain = $model->prisoner->getBalances()->where(['<=','at', $searchModel->from])->sum(new Expression("CASE WHEN type_id=$typeDebitId THEN amount WHEN type_id=$typeCreditId THEN -amount END"));
        $toRemain = $model->prisoner->getBalances()->where(['<=','at', $searchModel->to])->sum(new Expression("CASE WHEN type_id=$typeDebitId THEN amount WHEN type_id=$typeCreditId THEN -amount END"));
        $diff = $toRemain - $fromRemain;
        return $diff;
    }

];
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
                /**
                 *@var $model \vova07\users\models\Officer
                 */
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
    'label'=>false,

    'attribute' => 'hasDevices',
    'filterType' => GridView::FILTER_CHECKBOX,
    'filterInputOptions' => ['class'=>''],
    'content' => function($model){

        return Html::a($model->prisoner->getDevices()->count(),['/electricity/devices/index', 'DeviceSearch[prisoner_id]' => $model->prisoner_id],['label label-default']);
    }


];
$gridColumns[] = [
    'label'=>false,

    'attribute' => 'withoutJobs',
    'filterType' => GridView::FILTER_CHECKBOX,
    'filterInputOptions' => ['class'=>''],
    'content' => function($model){
        $cnt = $model->prisoner->getJobs()->count();
        if ($cnt)
            return Html::a($cnt,['/jobs/job-list/index', 'JobPaidListSearch[assigned_to]' => $model->prisoner_id],['label label-default']);
        else
            return '';
    }


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




?>


<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'filterModel'=>$searchModel,
    'showPageSummary' => true,

    'pjax'=>true,

    'columns' => $gridColumns
])?>


<?php  Box::end()?>

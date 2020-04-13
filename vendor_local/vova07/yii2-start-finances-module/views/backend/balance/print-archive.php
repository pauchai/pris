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

$this->title = Module::t("default","BALANCE_ARCHIVE_TITLE");
$this->params['subtitle'] = '';
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


<?php $remain = null; $lastPrisonerId = null ?>
<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'striped' => true,
    'hover' => true,
    'showPageSummary' => true,
    'panel' => ['type' => 'primary', 'heading' => $this->title],
    'columns' => [
      //  ['class' => SerialColumn::class],
        [
            'attribute' => 'prisoner_id',
            'group' => true,
            'groupedRow' => true,
            'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
            'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class


            'value' => function($model){
                return $model['fio'];
            },
            'groupFooter' => function ($model, $key, $index, $widget) { // Closure method
                //$remain = \vova07\finances\models\backend\BalanceByPrisonerView::find()->byPrisonerId($model['prisoner_id'])->sum('remain');
                return [
                  //  'mergeColumns' => [[1,3]], // columns to merge in summary

                    'content' => [             // content to show in each summary cell
                       // 1 => 'Summary (' . $model['fio'] . ')',
                       // 2 => GridView::F_SUM,
                     //   3 => GridView::F_SUM,
                      //  4 => GridView::F_SUM,
                      //  5 => ' ' . $remain
                    ],
                    'contentFormats' => [      // content reformatting for each summary cell
                      //  2 => ['format' => 'number', 'decimals' => 2],//   5 => ['format' => 'number', 'decimals' => 0],
                    //    3 => ['format' => 'number', 'decimals' => 2],
                    //    4 => ['format' => 'number', 'decimals' => 2],
                     //   5 => ['format' => 'number', 'decimals' => 2]
                    ],
                    'contentOptions' => [      // content html attributes for each summary cell
                        //1 => ['style' => 'font-variant:small-caps'],
                        //4 => ['style' => 'text-align:right'],
                        //5 => ['style' => 'text-align:right'],
                        //6 => ['style' => 'text-align:right'],
                    ],
                    // html attributes for group summary row
                    'options' => ['class' => 'info table-info','style' => 'font-weight:bold;']
                ];
            }

        ],
        [
          'attribute' => 'at',
          'format' => 'date',
          'header' => Module::t('default', 'AT_TITLE')
        ],
        [
            'attribute' => 'reason',
            'header' => Module::t('default', 'REASON_TITLE')
        ],
        [
            'attribute' => 'debit',
            'header' => Module::t('default', 'DEBIT_TITLE')
        ],
        [
            'attribute' => 'credit',
            'header' => Module::t('default', 'CREDIT_TITLE')
        ],
        [
            'header' => Module::t('default', 'REMAIN_TITLE'),
             'value' => function($model)use(&$remain, &$lastPrisonerId){


                if (is_null($lastPrisonerId) || $model['prisoner_id'] <> $lastPrisonerId){
                    $remain = 0;
                }
                $lastPrisonerId = $model['prisoner_id'];

                $remain += $model['debit'] - $model['credit'];

                return $remain;
            }
        ]

    ]
])?>



<?php  Box::end()?>

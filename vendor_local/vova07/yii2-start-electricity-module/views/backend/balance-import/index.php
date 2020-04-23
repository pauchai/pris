<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use vova07\jobs\helpers\Calendar;
use vova07\electricity\Module;
use kartik\grid\GridView;

$this->title = \vova07\plans\Module::t("default","ELECTRICITY_ACCOUNTING_TITLE");
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
<?=Module::t('labels','KWT_PRICE_LABEL')?>:<a class="" href="<?=\yii\helpers\Url::to(['/site/settings/index'])?>"><?php echo \vova07\site\models\Setting::getValue(\vova07\site\models\Setting::SETTING_FIELD_ELECTRICITY_KILO_WATT_PRICE)?></a> kwt


<?php $form = ActiveForm::begin([
        'action' => \yii\helpers\Url::to(['do-process'])
])?>
<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        [
            'attribute' => 'prisoner_id',
            'value' => function($model){
                if ($model->prisoner)
                    return $model->prisoner->person->getFio(false, true);
                else
                    return null;

            },
            'group' => true,
            'groupedRow' => true,

            'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
            'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class

            'groupFooter' => function ($model, $key, $index, $widget) { // Closure method

                if ($model->prisoner)
                    $remain = ($model->prisoner->balance instanceof \vova07\finances\models\backend\BalanceByPrisonerView)?$model->prisoner->balance->remain:0;
                else
                    $remain = 0;

                return [
                      'mergeColumns' => [[2,3]], // columns to merge in summary

                    'content' => [             // content to show in each summary cell
                        2 => Module::t('default','TOTAL_TITLE'),
                        // 2 => GridView::F_SUM,
                        //   3 => GridView::F_SUM,
                        4 => GridView::F_SUM,
                        5 => GridView::F_SUM,
                        6 =>" " . $remain,
                    ],
                    'contentFormats' => [      // content reformatting for each summary cell
                        //  2 => ['format' => 'number', 'decimals' => 2],//   5 => ['format' => 'number', 'decimals' => 0],
                        //    3 => ['format' => 'number', 'decimals' => 2],
                        4 => ['format' => 'number', 'decimals' => 2],
                        5 => ['format' => 'number', 'decimals' => 2]
                    ],
                    'contentOptions' => [      // content html attributes for each summary cell
                        2 => ['style' => 'text-align:right'],
                        3 => ['style' => 'text-align:center'],
                        4 => ['style' => 'text-align:center'],
                        5 => ['style' => 'text-align:center'],
                    ],
                    // html attributes for group summary row
                    'options' => ['class' => 'info table-info','style' => 'font-weight:bold;']
                ];
            }
        ],
        [
            'hAlign' => GridView::ALIGN_CENTER,
            'attribute' => 'device.title'

        ],
        [
            'hAlign' => GridView::ALIGN_CENTER,
            'attribute' => 'dateRange'
        ],
        [
            'hAlign' => GridView::ALIGN_CENTER,
            'attribute' => 'value'
        ],



        [
            'hAlign' => GridView::ALIGN_CENTER,

            'header' =>  Module::t('labels','PRICE_ACCOUNTING_VALUE'),
            'content' => function($model){
             $ret = $model->value * \vova07\site\models\Setting::getValue(\vova07\site\models\Setting::SETTING_FIELD_ELECTRICITY_KILO_WATT_PRICE);
             return  Yii::$app->formatter->asDecimal($ret,2);
            }
        ],
        [
                'header' => Module::t('default','REMAIN_LABEL'),
                'content' => function($model){return '';} ,
        ],
        [
                'attribute' => 'status',
                'content' => function($model){
                    if ($model->status_id >= \vova07\electricity\models\DeviceAccounting::STATUS_READY_FOR_PROCESSING)
                        return $model->status;


                }
        ],

        [
            'class' =>  \yii\grid\CheckboxColumn::class,


        ],

         [
            'class' =>  \yii\grid\ActionColumn::class,
            'template' => '{view}{delete}'


        ]


    ]
]);

?>
<?= Html::submitButton(Module::t('default', 'SUBMIT'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end()?>


<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

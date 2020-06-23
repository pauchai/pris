<?php
/**
 * @var $generateTabularDataFormModel \vova07\electricity\models\backend\GenerateTabularDataForm
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use vova07\jobs\helpers\Calendar;
use vova07\electricity\Module;
use kartik\grid\GridView;

$this->title = \vova07\plans\Module::t("default","ELECTRICITY_ACCOUNTING_TITLE");
$this->params['subtitle'] = '';
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
<?php if ($this->context->isPrintVersion):?>
<h1 align="center"><b>LISTA</b></h1>
<h2 align="center">achitării energiei electrice pentru aparatajul electric </h2>
    <h2 align="center">aflat în gestiunea condamnaţilor Penitenciarului nr.1 Taraclia  pe perioada <?=$searchModel->dateRange?>
        / <?=Module::t('labels','KWT_PRICE_LABEL')?>:<a class="" href="<?=\yii\helpers\Url::to(['/site/settings/index'])?>"><?php echo \vova07\site\models\Setting::getValue(\vova07\site\models\Setting::SETTING_FIELD_ELECTRICITY_KILO_WATT_PRICE)?></a> kwt
    </h2>

<br/>
<?php endif;?>

<?php echo $this->render('_search',['model' => $searchModel])?>


<?php echo \yii\bootstrap\Html::a(Module::t('default' , 'SYNC_TABULAR_DATA'),['generate-tabular-data','DeviceAccountingSearch'=>['dateRange' => $searchModel->dateRange]],['class' => 'no-print btn btn-default'])?>

    <?php $form = ActiveForm::begin([
        'action' => \yii\helpers\Url::to(['mass-change-statuses'])
    ])?>
    <?php echo GridView::widget(['dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'layout' => $this->context->isPrintVersion?"{items}\n{pager}":"{summary}\n{items}\n{pager}",
        'showPageSummary' => true,
        //'showFooter' => true,
        'columns' => [
            [
                'class' => \kartik\grid\SerialColumn::class,
            ],
            [
                'attribute' => 'prisoner_id',
                'pageSummary' => '',
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
                          //'mergeColumns' => [[2,3]], // columns to merge in summary

                        'content' => [             // content to show in each summary cell
                             2 => Module::t('default','TOTAL_TITLE'),
                            // 2 => GridView::F_SUM,
                            //   3 => GridView::F_SUM,
                            3 => GridView::F_SUM,
                            4 => GridView::F_SUM,
                            5 =>" " . $remain,
                        ],
                        'contentFormats' => [      // content reformatting for each summary cell
                            //  2 => ['format' => 'number', 'decimals' => 2],//   5 => ['format' => 'number', 'decimals' => 0],
                            //    3 => ['format' => 'number', 'decimals' => 2],
                                3 => ['format' => 'number', 'decimals' => 2],
                               4 => ['format' => 'number', 'decimals' => 2]
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
                'attribute' => 'device.title',
                'pageSummary' => Module::t('default', "PAGE_SUMMARY"),
                //'pageSummaryFunc' => function(),
            ],

          //  'dateRange',
            [
               'attribute' => 'value',
                'hAlign' => GridView::ALIGN_CENTER,
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
             ],

            [
                'hAlign' => GridView::ALIGN_CENTER,
                'header' => Module::t('labels','PRICE_ACCOUNTING_VALUE'),
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
                'value' => function($model){
                    $ret = $model->getPrice();
                    //return  Yii::$app->formatter->asDecimal($ret,2);
                    return round($ret, 2);
                }
            ],
            [
                'hAlign' => GridView::ALIGN_CENTER,
                'content' => function($model){return '';},
                'header' =>  Module::t('default','REMAIN_TITLE')
            ],
            [
              'attribute' => 'dateRange',
                'content' => function($model)use($searchModel){
                    $deviceAccountingSearchFromDateTime = (new DateTime)->setTimestamp($searchModel->from_date );
                    $deviceAccountingSearchToDateTime =  (new DateTime)->setTimestamp($searchModel->to_date );

                    $deviceAssignedDateTime = (new DateTime)->setTimestamp($model->device->assigned_at);
                    $deviceUnAssignedDateTime = (new DateTime)->setTimestamp($model->device->unassigned_at);

                    if ($deviceAssignedDateTime > $deviceAccountingSearchFromDateTime && $deviceAssignedDateTime < $deviceAccountingSearchToDateTime)
                        return $model->dateRange;


                    if ($deviceUnAssignedDateTime > $deviceAccountingSearchFromDateTime && $deviceUnAssignedDateTime < $deviceAccountingSearchToDateTime)
                        return $model->dateRange;

                }
            ],
            [
                'hAlign' => GridView::ALIGN_CENTER,
                'attribute' => 'prisoner.sector.title'
            ],
            [
                'hAlign' => GridView::ALIGN_CENTER,
                'attribute' =>             'prisoner.cell.number',
            ],



            [
                'hAlign' => GridView::ALIGN_CENTER,

                'attribute' => 'status',
                'visible' => !$this->context->isPrintVersion,
            ],
            [
                'label' =>Module::t('default','PAID_LABEL'),
                'visible' => !$this->context->isPrintVersion,

                'content' => function($model)
                {
                    //ArrayHelper::map(self::find()->asArray()->all(), '__ownableitem_id', 'title');
                    $arr = [];
                    foreach ($model->getBalances()->select('prisoner_id')->distinct()->all() as $balance)

                        $arr[] = Html::a($balance->person->second_name,['/finances/default/view','id' => $balance->prisoner_id]);

                    return join('<br/>', $arr);
                }
            ],

             [

                 'class' =>  \yii\grid\CheckboxColumn::class,
                 'visible' => !$this->context->isPrintVersion,

             ],
/*            [
                'hAlign' => GridView::ALIGN_CENTER,

                'visible' => !$this->context->isPrintVersion,
                 'attribute' =>'balance.atJui',
            ],*/

            [

                'class' => \yii\grid\ActionColumn::class,
                'template' => '{update}{delete}',
                'visible' => !$this->context->isPrintVersion,
            ],


        ],
        /*'beforeFooter' => [
            [
                'columns' => [


                    [ 'options' => [
                        'colspan' => 5,
                        ]
                    ],


                    [
                        'content' => $dataProvider->query->sum('remain'),


                    ],
                ]
            ]
        ]*/

    ]);

    ?>
    <?=Html::dropDownList('status_id',null, \vova07\electricity\models\DeviceAccounting::getStatusesForCombo(), ['prompt' => Module::t('default','SELECT_STATUS_PROMPT'),'class' => 'no-print'])?>
    <?= Html::submitButton(Module::t('default', Module::t('default','CHANGE_STATUS')), ['name' => 'change_status','class' => 'btn btn-primary no-print']) ?>
    <?= Html::submitButton(Module::t('default', Module::t('default','DELETE')), ['name' => 'mass_delete','class' => 'btn btn-danger no-print']) ?>
    <?php ActiveForm::end()?>






<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

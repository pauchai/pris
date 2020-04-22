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
<h1 align="center">Lista</h1>
<h2 align="center">spre achitare al energiei electrice folosită de către condamnaţi </h2>
<h2 align="center">
    pe perioada <?=$searchModel->dateRange?>
    / <?=Module::t('labels','KWT_PRICE_LABEL')?>:<a class="" href="<?=\yii\helpers\Url::to(['/site/settings/index'])?>"><?php echo \vova07\site\models\Setting::getValue(\vova07\site\models\Setting::SETTING_FIELD_ELECTRICITY_KILO_WATT_PRICE)?></a> kwt

</h2>
<br/>
<?php endif;?>

<?php echo $this->render('_search',['model' => $searchModel])?>


<?php $formGenerateTabularData = ActiveForm::begin([
    'action' => \yii\helpers\Url::to(['generate-tabular-data'])
])?>
<?php echo $formGenerateTabularData->field($generateTabularDataFormModel,'dateRange')->hiddenInput()->label(false)?>
<?php echo \yii\bootstrap\Html::submitButton("SYNC_TABULAR_DATA",['class' => 'no-print'])?>
<?php ActiveForm::end();?>
<?php
 echo $this->render('_devices_without_prisoner', ['deviceAccountings' => $generateTabularDataFormModel->getDeviceAccountingsWithoutPrisoner()]);
?>


    <?php $form = ActiveForm::begin([
        'action' => \yii\helpers\Url::to(['mass-change-statuses'])
    ])?>
    <?php echo GridView::widget(['dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'layout' => $this->context->isPrintVersion?"{items}\n{pager}":"{summary}\n{items}\n{pager}",

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

                    $remain = ($model->prisoner->balance instanceof \vova07\finances\models\backend\BalanceByPrisonerView)?$model->prisoner->balance->remain:0;

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
            'device.title',
          //  'dateRange',
            [
               'attribute' => 'value',
                'hAlign' => GridView::ALIGN_CENTER,
             ],

            [
                'hAlign' => GridView::ALIGN_CENTER,
                'header' => Module::t('labels','PRICE_ACCOUNTING_VALUE'),
                'value' => function($model){
                    $ret = $model->getPrice();
                    //return  Yii::$app->formatter->asDecimal($ret,2);
                    return $ret;
                }
            ],
            [
                'hAlign' => GridView::ALIGN_CENTER,
                'content' => function($model){return '';},
                'header' =>  Module::t('default','REMAIN_TITLE')
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

                 'class' =>  \yii\grid\CheckboxColumn::class,
                 'visible' => !$this->context->isPrintVersion,

             ],
            [
                'hAlign' => GridView::ALIGN_CENTER,

                'visible' => !$this->context->isPrintVersion,
                 'attribute' =>'balance.atJui',
            ],

            [

                'class' => \yii\grid\ActionColumn::class,
                'visible' => !$this->context->isPrintVersion,
            ],
            [
                'hAlign' => GridView::ALIGN_CENTER,

                'header' => Module::t('default','SIGNE_TITLE'),
                    'value' => function($model){return '';},
                    'visible' => $this->context->isPrintVersion,

            ]

        ],

    ]);

    ?>
    <?=Html::dropDownList('status_id',null, \vova07\electricity\models\DeviceAccounting::getStatusesForCombo(), ['prompt' => Module::t('default','SELECT_STATUS_PROMPT'),'class' => 'no-print'])?>
    <?= Html::submitButton(Module::t('default', 'SUBMIT'), ['class' => 'btn btn-primary no-print']) ?>
    <?php ActiveForm::end()?>






<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

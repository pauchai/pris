<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use vova07\jobs\helpers\Calendar;
use vova07\electricity\Module;

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
<?php echo $this->render('_search',['model' => $searchModel])?>

<?php $form = ActiveForm::begin([
        'action' => \yii\helpers\Url::to(['mass-change-statuses'])
])?>
<?php echo \yii\grid\GridView::widget(['dataProvider' => $dataProvider,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        'prisoner.person.fio',
        'device.title',
        'dateRange',
        'value',
        [
                'header' => Module::t('labels','PRICE_ACCOUNTING_VALUE'),
            'content' => function($model){
             $ret = $model->getPrice();
             return  Yii::$app->formatter->asDecimal($ret,2);
            }
        ],
        [
                'content' => function($model)use($form){
                    if ($model->status_id >= \vova07\electricity\models\DeviceAccounting::STATUS_READY_FOR_PROCESSING){
                        return $model->status;
                    } else {
                        return $form->field($model,'[' . $model->primaryKey.  ']status_id')->label(false)->dropDownList(\vova07\electricity\models\DeviceAccounting::getStatusesForCombo(),['prompt'=>Module::t('default','SELECT_STATUS_PROMPT')]);
                    }

                }
        ],
        'balance.atJui',

        [
            'class' => \yii\grid\ActionColumn::class,
        ]

    ]
]);

?>
<?= Html::submitButton(Module::t('default', 'SUBMIT'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end()?>


<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

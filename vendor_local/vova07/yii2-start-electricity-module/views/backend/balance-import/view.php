<?php
use yii\bootstrap\Html;
use vova07\electricity\Module;
use vova07\themes\adminlte2\widgets\Box;
use yii\bootstrap\ActiveForm;
use kartik\grid\GridView;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\plans\models\Program
 */
$this->title = Module::t("import","DEVICE_ACCAUNT_BALANCE_TITLE");
$this->params['subtitle'] = $model->device->title;
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => Yii::$app->user->returnUrl,
    ],
    $this->params['subtitle']
];
?>
<?php $box = Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{update}{delete}'
    ]
);?>

<?php echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'device.title',
        'dateRange',
        [
            'label' =>  Module::t('default','TO_PAY_LABEL'),
            'value' => function($model){
                return $model->price;
            }
        ],
        [
            'label' =>  Module::t('default','PAID_LABEL'),
            'value' => function($model){
                return $model->getBalances()->sum('amount');
            }
        ],
        [
            'label' => Module::t('default','REMAIN_LABEL'),
            'value' => function($model){
                return $model->price - $model->getBalances()->sum('amount');
            }
        ],
        [
            'attribute' => 'status_id',
            'value' => function($model){
                return $model->status;
            }
        ]


    ]
])?>
<?php Box::end();?>

<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => '',

    ]
);?>

<?php echo GridView::widget([
    'dataProvider' => $balanceDataProvider,
    'columns' => [
        'atJui',
        [
          'content' => function($model){
            return Html::a($model->prisoner->person->fio,['/finances/default/view','id' => $model->prisoner_id]);
          }
        ],

        'amount'
    ]

])?>

<?php $form = ActiveForm::begin([
    'method' => "POST",
    'layout' => 'inline'
])?>
<?php echo $form->field($balance,'amount')->input('text',[
    'placeholder' => 'amount',

])?>
<?php echo $form->field($balance,'prisoner_id')->widget(
    \kartik\select2\Select2::class,[
        'data' => \vova07\users\models\Prisoner::getListForCombo(),
        'options'=>['prompt'=>Module::t('import','SELECT_PRISONER')],
        'pluginOptions'=>['allowClear'=>true],

    ]
)?>
<?php echo $form->field($balance,'reason')->input('text',[
    'placeholder' => 'reason'
])?>
<?php echo Html::submitButton('',['class' => 'fa fa-plus'])?>
<?php ActiveForm::end()?>
<?php Box::end();?>

<?php $this->registerCss(<<<CSS
    #balance-reason {
        width:30em;
    }    
CSS
);?>
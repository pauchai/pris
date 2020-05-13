<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
use kartik\grid\GridView;

$this->title = \vova07\plans\Module::t("default","ELECTRICITY_DEVICES_TITLE");
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
<?php echo $this->render('_generate_tabular_data_filter',['model' => $searchModel, 'deviceAccountingSearch' => $deviceAccountingSearch])?>

<?php $form = \yii\bootstrap\ActiveForm::begin()?>

<?php echo GridView::widget(['dataProvider' => $dataProvider,
   // 'filterModel' => $searchModel,

    // 'panel' => ['type' => 'primary', 'heading' => $this->title],
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

        ],
        'title',

       // 'assigned_at:date',
        'prisoner.sector.title',
        'prisoner.cell.number',
        'power',
        //'enable_auto_calculation',
        'calculationMethod',
       // 'unassigned_at:date',

        [
            'attribute' => 'status_id',
            'value' => 'status',

        ],
        [
            'header' => '',
            'content' => function($model, $key, $index)use (&$form, &$deviceAccountingSearch){


                $deviceAccounting = new \vova07\electricity\models\DeviceAccounting([
                   'device_id' => $model->primaryKey,
                    'prisoner_id' => $model->prisoner_id,
                ]);

                /**
                 * @TODO make correct date format for from_date and to_date
                 */
                $deviceAccountingSearchFromDateTime = DateTime::createFromFormat('d-m-Y',$deviceAccountingSearch->from_date );
                $deviceAccountingSearchToDateTime = DateTime::createFromFormat('d-m-Y',$deviceAccountingSearch->to_date );

                $deviceAssignedDateTime = (new DateTime)->setTimestamp($deviceAccounting->device->assigned_at);
                $deviceUnAssignedDateTime = (new DateTime)->setTimestamp($deviceAccounting->device->unassigned_at);

                if ($deviceAssignedDateTime > $deviceAccountingSearchFromDateTime && $deviceAssignedDateTime < $deviceAccountingSearchToDateTime)
                    $deviceAccounting->from_date = $deviceAssignedDateTime->getTimestamp();
                else
                    $deviceAccounting->from_date = $deviceAccountingSearchFromDateTime->getTimestamp();


                if ($deviceUnAssignedDateTime > $deviceAccountingSearchFromDateTime && $deviceUnAssignedDateTime < $deviceAccountingSearchToDateTime)
                    $deviceAccounting->to_date = $deviceUnAssignedDateTime->getTimestamp();
                else
                    $deviceAccounting->to_date = $deviceAccountingSearchToDateTime->getTimestamp();



                $html = $form->field($deviceAccounting,'['.$index . ']dateRange')->widget(\kartik\daterange\DateRangePicker::class,[
                        'startAttribute' => 'from_date',
                        'endAttribute' => 'to_date',
                        'convertFormat' =>  true,
                        'pluginOptions' => [
                            'locale' => ['format' => 'd-m-Y']
                        ]
                    ])->label(false).
                    $form->field($deviceAccounting,'['.$index . ']device_id')->hiddenInput()->label(false).
                    $form->field($deviceAccounting,'['.$index . ']prisoner_id')->hiddenInput()->label(false);

                return \yii\helpers\Html::tag('div',$html,['class' => 'deviceAccountingInputs']);

            }
        ],
    ]
])?>
<?php echo \yii\helpers\Html::submitButton('SYNC',['class' => 'form-control btn btn-info no-print '])?>

<?php \yii\bootstrap\ActiveForm::end()?>

<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

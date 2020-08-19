<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","DOCUMENTS");
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
<?php echo $this->render('_search', ['model' => $searchModel])?>

<?php echo \kartik\grid\GridView::widget(['dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        [
            'attribute' => 'type',
            'group' => true,
            'groupedRow' => true,

            'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
            'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class

            'filter' => \vova07\documents\models\Document::getTypesForCombo(),
            'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => \vova07\plans\Module::t('default','SELECT_TYPE'), 'class'=> 'form-control', 'id' => null],

        ],
        [
            'attribute' => 'person_id',
            'value' => function($model){return $model->person->prisoner->getFullTitle(true);},
            'filter' => \vova07\users\models\Prisoner::getListForCombo(),
            'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => \vova07\plans\Module::t('default','SELECT_PRISONER'), 'class'=> 'form-control', 'id' => null],
            'group' => true,
        ],
        [
            'header' => '',
            'value' => 'country.iso'
        ],

        [
            'content' => function($model){
                return \yii\helpers\ArrayHelper::getValue($model,'person.prisoner.balance.remain');
            }
        ],
        'date_expiration:date',

            [
                'attribute' => 'person.prisoner.term_finish',
                'content' => function($model)use($searchModel){
                    //$content = Html::tag('span', Yii::$app->formatter->asRelativeTime($doc->date_expiration ),['class'=>' label label-danger']);
                    $attributeValueInt = 0;
                    $attributeValue = \yii\helpers\ArrayHelper::getValue($model, 'person.prisoner.term_finish');
                    $attributeJuiValue = \yii\helpers\ArrayHelper::getValue($model, 'person.prisoner.termFinishJui');

                    if ($attributeValue)
                        $attributeValueInt = DateTime::createFromFormat('Y-m-d', $attributeValue)->getTimestamp();


                    $style = ['class' => 'label label-default'];
                    if ($searchModel->expiredTo > $attributeValueInt){
                        $style = ['class' => 'label label-danger'];
                    }




                    $content = Html::tag('span', $attributeJuiValue, array_merge($style, ['style' => 'font-size:100%']));
                    return $content;
                },
            // 'value' => 'person.prisoner.termFinishJui',
            ]



    ]
])?>

<?php echo \kartik\grid\GridView::widget(['dataProvider' => $dataProviderForeigners,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],

        [
            'attribute' => 'person.fio',

        ],
        [
            'header' => '',
            'value' => 'person.country.iso'
        ],

        [
            'content' => function($model){
                return \yii\helpers\ArrayHelper::getValue($model,'person.prisoner.balance.remain');
            }
        ],

        [
            'attribute' => 'term_finish',
            'content' => function($model){
                //$content = Html::tag('span', Yii::$app->formatter->asRelativeTime($doc->date_expiration ),['class'=>' label label-danger']);

                $currDate = new DateTime();
                $style = ['class' => 'label label-default'];
                $value = null;
                if ($model->term_finish) {
                    $dateTermFinish = new DateTime($model->term_finish);
                    $daysRemain = $currDate->diff($dateTermFinish)->format('%R%a');
                    $value = $model->termFinishJui;

                    if ($daysRemain <= 30){
                        $style = ['class' => 'label label-danger'];
                        $value = $value . ' ' . Yii::$app->formatter->asRelativeTime($value);
                    }
                    elseif ($daysRemain >=30 && $daysRemain <= 30*6)
                        $style = ['class' => 'label label-info'];

                    elseif ($daysRemain >=30*6 && $daysRemain <= 30*12)
                        $style = ['class' => 'label label-success'];
                }
                //  $value = $dateTermFinish->format('d-m-Y') . ' ' . $daysRemain;




                $content = Html::tag('span', $value, array_merge($style, ['style' => 'font-size:100%']));
                return $content;
            },
            'value' => 'termFinishJui',
        ]
       // 'term_finish:date'
    ]
])?>
<?php \vova07\themes\adminlte2\widgets\Box::end()?>

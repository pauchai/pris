<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\documents\Module;
use kartik\grid\GridView;
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

<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'caption' => Module::t('labels', "LOCAL_PEOPLE"),

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

<?php echo GridView::widget(['dataProvider' => $dataProviderActivePassports,
    'filterModel' => $searchModel,
    'caption' => Module::t('labels', "PRISONERS_WITH_ACTIVE_PASSPORTS"),

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

<?php echo GridView::widget(['dataProvider' => $dataProviderForeigners,
    'filterModel' => $searchModel,
    'caption' => Module::t('labels', "FOREIGN_PEOPLE"),

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
            'content' => function($model)use($searchModel){
                $attributeValueInt = 0;
                $attributeValue = \yii\helpers\ArrayHelper::getValue($model, 'term_finish');
                $attributeJuiValue = \yii\helpers\ArrayHelper::getValue($model, 'termFinishJui');

                if ($attributeValue)
                    $attributeValueInt = DateTime::createFromFormat('Y-m-d', $attributeValue)->getTimestamp();


                $style = ['class' => 'label label-default'];
                if ($searchModel->expiredTo > $attributeValueInt){
                    $style = ['class' => 'label label-danger'];
                }




                $content = Html::tag('span', $attributeJuiValue, array_merge($style, ['style' => 'font-size:100%']));
                return $content;
            },
            'value' => 'termFinishJui',
        ]
       // 'term_finish:date'
    ]
])?>

<?php echo GridView::widget(['dataProvider' => $dataProviderStateless,
    'filterModel' => $searchModel,
    'caption' => Module::t('labels', "STATELESS_PEOPLE"),

    'columns' => [
        ['class' => yii\grid\SerialColumn::class],

        [
            'attribute' => 'person.fio',

        ],


        [
            'content' => function($model){
                return \yii\helpers\ArrayHelper::getValue($model,'person.prisoner.balance.remain');
            }
        ],

        [
            'attribute' => 'term_finish',
            'content' => function($model)use($searchModel){
                $attributeValueInt = 0;
                $attributeValue = \yii\helpers\ArrayHelper::getValue($model, 'term_finish');
                $attributeJuiValue = \yii\helpers\ArrayHelper::getValue($model, 'termFinishJui');

                if ($attributeValue)
                    $attributeValueInt = DateTime::createFromFormat('Y-m-d', $attributeValue)->getTimestamp();


                $style = ['class' => 'label label-default'];
                if ($searchModel->expiredTo > $attributeValueInt){
                    $style = ['class' => 'label label-danger'];
                }




                $content = Html::tag('span', $attributeJuiValue, array_merge($style, ['style' => 'font-size:100%']));
                return $content;
            },
            'value' => 'termFinishJui',
        ]
        // 'term_finish:date'
    ]
])?>
<?php \vova07\themes\adminlte2\widgets\Box::end()?>

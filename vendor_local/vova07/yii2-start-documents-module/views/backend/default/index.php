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
            'attribute' => 'type_id',
            'content' => function($model){

                   // $content = $model->type . '|' . $model->seria;
                    $content = Html::tag('span', $model->type,['class'=>' label label-success ']);
                    $content .= ' ' . Html::tag('span', $model->seria,['class'=>'  label label-success']);
                    if ($model->isExpired()){
                        $content .= Html::tag( 'span',Yii::$app->formatter->asRelativeTime($model->date_expiration ),['class'=>' label label-danger']);
                    } else {
//                        $content = Html::tag('span', $content,['class'=>'label label-success']);
                        if ($model->isAboutExpiration()){
                            $content .= ' ' .Html::tag('span', Yii::$app->formatter->asRelativeTime($model->date_expiration ),['class'=>'label label-warning']);

                        }
                    };
                    return $content;


                },
                'filter' => \vova07\documents\models\Document::getTypesForCombo(),
            'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => \vova07\plans\Module::t('default','SELECT_TYPE'), 'class'=> 'form-control', 'id' => null],

        ],
        [
          'attribute' => 'date_issue',
            'format' => 'date',
            'filterType' => \kartik\grid\GridView::FILTER_DATE_RANGE
        ],

        [
            'header' => '',
            'value' => 'country.iso'
        ],
        [
            'class' => \yii\grid\ActionColumn::class,
            'visible' => !$this->context->isPrintVersion,
            'buttons' => [
                'departments' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', ['company-departments/index','CompanyDepartment[company_id]' => $key], [
                        'title' => \vova07\prisons\Module::t('default', 'DEPARTMENTS'),
                        'data-pjax' => '0',
                    ]);
                },
            ],

        ]
    ]
])?>
<?php \vova07\themes\adminlte2\widgets\Box::end()?>

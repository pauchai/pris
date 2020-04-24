<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
use vova07\concepts\Module;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;

//$this->title = Module::t("default","EVENTS_TITLE");
$this->params['subtitle'] = Module::t("default","PARTICIPANTS_LIST_TITLE");
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

<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'filterModel' => $searchModel,

    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        [
            'attribute' => 'concept_id',

            'group' => true,
            //'groupedRow' => true,
            'content' => function ($model) {
                return \yii\helpers\Html::a($model->concept->title, ['/concepts/default/view','id' => $model->concept_id]);
            },
            'filter' => \vova07\concepts\models\Concept::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => \vova07\plans\Module::t('default','SELECT_PRISON'), 'class'=> 'form-control', 'id' => null],



        ],
        [
            'attribute' =>'prisoner_id',
            'content' => function ($model) {
                return \yii\helpers\Html::a($model->prisoner->person->fio, ['/users/prisoner/view','id' => $model->prisoner_id]);
            },
            'filter' => \vova07\users\models\Prisoner::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => \vova07\plans\Module::t('default','SELECT_PRISON'), 'class'=> 'form-control', 'id' => null],



        ],

        [
            'class' => ActionColumn::class,
            'template' => '{delete}'

        ]
    ]
])?>


<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

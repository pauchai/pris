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
$this->title = \vova07\plans\Module::t("default","PROGRAM-PRISONER");
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

<?php echo GridView::widget(['dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        'programDict.title',
        'plannedBy.person.fio',
      //  'prison.company.title',
        [
            'attribute' => 'prisoner_id',
            'value' => 'prisoner.fullTitle',
            'filter' => \vova07\users\models\Prisoner::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => \vova07\plans\Module::t('default','SELECT_PRISONER_PROMPT'), 'class'=> 'form-control', 'id' => null]
        ],

        'date_plan',
        'status',
        [
            'class' => \kartik\grid\ActionColumn::class
        ]
    ]
])?>


<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

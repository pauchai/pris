<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\users\Module;
//use yii\grid\GridView;
use kartik\grid\GridView;
use vova07\users\models\Prisoner;
use vova07\users\models\backend\PrisonerViewSearch;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","CELLS_PRISONERS");
$this->params['subtitle'] = Module::t("default","SUBTITLE_LIST");
?>

<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{create}'
    ]

);?>
<?php echo $this->render('_cells_search',['model' => $searchModel])?>

<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layout' =>"{toolbar}{summary}\n{items}\n{pager}",
    'toolbar' =>  [
        [ ],
        '{export}',
        '{toggleData}'
    ],
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],

/*        [
            'attribute' => 'prison_id',
            'group' => true,

        ],*/
        [
            'attribute' => 'sector_id',
            'visible' => !$searchModel->sector_id,
            'value' => 'sector.title',
            //'filter' => \vova07\prisons\models\Sector::getListForCombo(),
            'filter' => false,
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => \vova07\users\Module::t('default','SELECT_SECTOR'), 'class'=> 'form-control', 'id' => null],


            'group' => true,
        ],
        [
            'attribute' => 'cell_id',
            'visible' => !$searchModel->cell_id,
            'value' => 'cell.number',
            'filter' => \vova07\prisons\models\Cell::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => \vova07\users\Module::t('default','SELECT_CELL'), 'class'=> 'form-control', 'id' => null],

            'group' => true
        ],
        [

            'attribute' => '__person_id',

            'filter' => Prisoner::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'attribute' => '__person_id',
                'pluginOptions' => ['allowClear' => true ],
            ],
            'filterInputOptions' => [ 'prompt' => Module::t('default','SELECT_PRISONER'), 'class'=> 'no-print form-control', 'id' => null],

            //'header' => '',
           'content' => function($model){return Html::a($model->person->fio . ' ' . $model->person->birth_year, ['view','id' => $model->primaryKey]);},
           //
           //  'value' => 'person.fio'
        ],
        //'person.fio',
        //'person.birth_year',
 /*       [
            'attribute'  => 'prison_id',
            'header' => '',
            'value' => 'prison.company.title',
            'filter' => \vova07\prisons\models\Prison::getListForCombo(),

        ],*/

    ]
])?>


<?php \vova07\themes\adminlte2\widgets\Box::end()?>



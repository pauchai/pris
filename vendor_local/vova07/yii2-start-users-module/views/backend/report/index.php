<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
//use yii\grid\GridView;
use kartik\grid\GridView;
use vova07\users\models\Prisoner;
use vova07\users\models\backend\PrisonerViewSearch;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","PRISONERS");
$this->params['subtitle'] = Module::t("default","SUBTITLE_LIST");
?>

<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{create}'
    ]

);?>

<?php echo $this->render('_filter', ['searchModel' => $searchModel])?>


<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        [

            'attribute' => 'fio',

            'filter' => Prisoner::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'attribute' => '__person_id',
                'pluginOptions' => ['allowClear' => true ],
            ],
            'filterInputOptions' => [ 'prompt' => Module::t('default','SELECT_PRISONER'), 'class'=> 'no-print form-control', 'id' => null],

            //'header' => '',
            'content' => function($model){return Html::a($model->person->getFio(false,true), ['/users/prisoner/view','id' => $model->primaryKey]);},
            //
            //  'value' => 'person.fio'
        ],

           'birth_year'

    ]
])?>


<?php \vova07\themes\adminlte2\widgets\Box::end()?>



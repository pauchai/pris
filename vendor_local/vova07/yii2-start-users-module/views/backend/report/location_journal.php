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
 * @var $searchModel
 */
$this->title = Module::t("default","PRISONERS_REPORT");
$this->params['subtitle'] = Module::t("default","PRISONERS_LOCATION_JOURNAL {0}", $searchModel->year) ;
?>

<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{create}'
    ]

);?>

<?php $filterForm = ActiveForm::begin(['layout' => 'inline', 'method' => 'GET'])?>
<?=$filterForm->field($searchModel,'year')?>
<?=$filterForm->field($searchModel,'sector_id')->dropDownList(\vova07\prisons\models\Sector::getListForCombo(),['prompt' => 'sector'])?>
<?=Html::submitButton(Module::t('default', 'FILTER'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end()?>




<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'filterModel' => $searchModel,

    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        [
            'class' => \kartik\grid\DataColumn::class,
            'attribute' => 'sector.title',
            'group' => true,
            'groupedRow' => true,

        ],
        [
            'attribute' => 'prisoner.person.fio',

        ],

        'prisoner.person.birth_year',
        'prisoner.person.nationality',
        'prisoner.person.education',
        'prisoner.person.speciality',
        'prisoner.person.address',
        'prisoner.criminal_records',
        'prisoner.article',
        //'prisoner.term_finish_origin',
        [
            'attribute' => 'prisoner.term_finish',
            'content' => function($model){return
                    \yii\helpers\ArrayHelper::getValue($model, 'prisoner.termFinishJui')
                . ' (' . \yii\helpers\ArrayHelper::getValue($model, 'prisoner.term').')'
                ;}
        ]
        ,
        'prisoner.termUdoJui',
        'prisoner.takenSpeciality',
        'at:date',
        'status',
        //'sector.title',
        'next.at:date',
        [
            'attribute' => 'next.sector.title',
            'content' => function($model){
                $ret = \yii\helpers\ArrayHelper::getValue($model,'next.sector.title') ;
                $ret .= '(' . \yii\helpers\ArrayHelper::getValue($model,'next.status') .')';
                return $ret;
            }
        ]




    ]
])?>

<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => 'prisoners',
        'buttonsTemplate' => '{minimize}'
    ]

);?>






<?php echo GridView::widget(['dataProvider' => $prisonerDataProvider,


    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        [
            'class' => \kartik\grid\DataColumn::class,
            'attribute' => 'sector.title',


        ],
        [
            'attribute' => 'person.fio',

        ],

        'person.birth_year',
        'person.nationality',
        'person.education',
        'person.speciality',
        'person.address',
        'criminal_records',
        'article',
        [
          'attribute' =>   'termFinishJui',
            'content' => function($model){
                return \yii\helpers\ArrayHelper::getValue($model, 'termFinishJui')
                    . ' (' . \yii\helpers\ArrayHelper::getValue($model, 'term'). ')';
            }
        ],
        'termUdoJui',
        'takenSpeciality',






    ]
])?>


<?php \vova07\themes\adminlte2\widgets\Box::end()?>

<?php \vova07\themes\adminlte2\widgets\Box::end()?>







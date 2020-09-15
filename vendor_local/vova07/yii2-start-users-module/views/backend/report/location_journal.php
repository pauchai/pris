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
        'prisoner.term_finish',
        'prisoner.term_udo',
        'prisoner.takenSpeciality',
        'at:date',
        'next.at:date',
        'next.sector.title',



    ]
])?>


<?php \vova07\themes\adminlte2\widgets\Box::end()?>



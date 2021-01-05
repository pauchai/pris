<?php

use yii\bootstrap\Html;
use vova07\reports\Module;
use vova07\prisons\models\backend\ReportSummarizedSearch;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\backend\PostSearch
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","REPORT_PRISONER_PROGRAMS_PLANNED");
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



    ]

);?>
<?php echo $this->render('_search.php', ['searchModel' => $searchModel])?>

<?php echo kartik\grid\GridView::widget(['dataProvider' => $dataProvider,
   // 'layout' => "{items}\n{pager}",
    'filterModel' => $searchModel,

    'columns' => [
            [
                'attribute' => 'prisoner_id',
                'content' => function($model){return $model->person->fio . ', '. $model->prisoner->sector->title ;},
                'group' => true
            ],



        'programDict.title',
        'date_plan',
        'finished_at:date',






    ]


])?>



<?php \vova07\themes\adminlte2\widgets\Box::end()?>


<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\jobs\Module;
use vova07\jobs\models\JobNormalizedViewDays;
use vova07\jobs\models\backend\JobNormalizedViewDaysSearch;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\documents\models\Document
 */
$this->title = Module::t("default","GENERAL_LISTSUMMARIZED");
$this->params['subtitle'] = '' ;
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
?>
<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => ''
    ]
);?>
<?php echo $this->render('_search_range_summarized', ['model' => $searchNormalizedDays])?>

<?php echo \kartik\grid\GridView::widget(['dataProvider' => $dataProvider,
    //'filterModel' => $searchNormalizedDays,
    'showHeader' => false,
    'layout' => "{items}\n{pager}",

        'columns' =>[
            [
            'attribute' => 'category_id',
            'content' => function($model)use ($searchNormalizedDays){

                return JobNormalizedViewDays::getCategoriesForCombo($model['category_id']);
            }
            ],
            [
                'attribute' => 'cnt',
                'content' =>  function($model)use ($searchNormalizedDays){

                    $newBieCount = JobNormalizedViewDays::getNewBieByYearForCategoryId($searchNormalizedDays->atFrom, $model['category_id']);
                    return $model['cnt'] . '/' . $newBieCount;
                }
            ]


        ]

])?>




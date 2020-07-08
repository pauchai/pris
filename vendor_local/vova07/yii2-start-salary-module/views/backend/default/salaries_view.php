<?php

use vova07\salary\Module;
use kartik\grid\GridView;

use yii\helpers\Html;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\salary\models\Salary
 */
$this->title = Module::t("default","SALARIES_TITLE");
$this->params['subtitle'] = $officer->person->fio. ' ' . $salaryIssue->at;
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
        'buttonsTemplate' => '{update}{delete}'
    ]
);?>
<?php

    $columns =['base_rate', 'work_days', 'amount_rate', 'amount_rank_rate', 'amount_conditions', 'amount_advance', 'amount_optional', 'amount_diff_sallary', 'amount_additional', 'amount_maleficence', 'amount_vacation', 'amount_bonus'];
    $columns[] = [
        'attribute' => 'total',
        'pageSummary' => true,
        'pageSummaryFunc' => GridView::F_SUM,
    ]
    ?>

<?php echo GridView::widget([
    'showPageSummary' => true,

    'dataProvider' => $dataProvider,
    'columns' => $columns
])?>


<?php \vova07\themes\adminlte2\widgets\Box::end(); ?>


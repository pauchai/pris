<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
use vova07\salary\Module;
use kartik\grid\GridView;
use vova07\salary\models\SalaryIssue;

//$this->title = Module::t("default","EVENTS_TITLE");
$this->params['subtitle'] = Module::t("default","SALARY_CHARGES");
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        //      'url' => ['index'],
    ],
    // $this->params['subtitle']
];
?>

<?=$this->render('_issueView',['model' => $salaryIssue])?>
<?php if (!$salaryIssue->isNewRecord): ?>

<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
       // 'title' => $this->params['subtitle'],
       // 'buttonsTemplate' => '{create}'
    ]

);?>

    <?php
        if (in_array($salaryIssue->status_id ,[SalaryIssue::STATUS_SALARY])){
            $columns = require("_salary_columns.php");
            $dataProvider = new \yii\data\ActiveDataProvider(['query' => $salaryIssue->getSalaries()]);
        } elseif (in_array($salaryIssue->status_id ,[SalaryIssue::STATUS_WITHHOLD])){
            $columns = require("_withhold_columns.php");
            $dataProvider = new \yii\data\ActiveDataProvider(['query' => $salaryIssue->getWithHolds()]);
        //}elseif (in_array($salaryIssue->status_id ,[SalaryIssue::STATUS_CARD])){
        //    $columns = require("_finished_columns.php");
        //    $dataProvider = new \yii\data\ActiveDataProvider(['query' => $salaryIssue->getWithHolds()]);
        }  elseif (in_array($salaryIssue->status_id ,[ SalaryIssue::STATUS_FINISHED])){
            $columns = require("_finished_columns.php");
            $dataProvider = new \yii\data\ActiveDataProvider(['query' => $salaryIssue->getWithHolds()]);
        }

    ?>

<?php echo GridView::widget(['dataProvider' => $dataProvider,
       // 'formatter' => ['class' => \yii\i18n\Formatter::class,'nullDisplay' => ''],
        //'emptyCell' => '',

   // 'floatHeader' => true,
    'pjax' => true,
    'columns' => $columns


])?>



<?php  \vova07\themes\adminlte2\widgets\Box::end()?>


<?php endif?>
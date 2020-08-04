<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $salaryIssue SalaryIssue
 */
use vova07\salary\Module;
use kartik\grid\GridView;
use vova07\salary\models\SalaryIssue;
use vova07\users\models\OfficerView;
use vova07\users\models\Person;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

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
            $query = $salaryIssue->getSalaries();
            $dataProvider = (new \yii\data\ActiveDataProvider(['query' => $query]));
            $dataProvider->setPagination(false);


                //$query->joinWith(['vw_officer' => function($query) { $query->from(['officerView' => OfficerView::tableName()]); }])->orderBy('vw_officer.category_rank_id');

// добавляем сортировку по колонке из зависимости
            //$dataProvider->sort->attributes['vw_officer.category_rank_id'] = [
            //    'asc' => ['author.name' => SORT_ASC],
            //    ’desc’ => [’author.name’ => SORT_DESC],
            //];

        } elseif (in_array($salaryIssue->status_id ,[SalaryIssue::STATUS_WITHHOLD])){
            $columns = require("_withhold_columns.php");
            $query = $salaryIssue->getWithHolds();

            $dataProvider = new \yii\data\ActiveDataProvider(['query' => $query]);
        //}elseif (in_array($salaryIssue->status_id ,[SalaryIssue::STATUS_CARD])){
        //    $columns = require("_finished_columns.php");
        //    $dataProvider = new \yii\data\ActiveDataProvider(['query' => $salaryIssue->getWithHolds()]);
        }  elseif (in_array($salaryIssue->status_id ,[ SalaryIssue::STATUS_FINISHED])){
            $columns = require("_finished_columns.php");
            $query = $salaryIssue->getWithHolds();
            $dataProvider = new \yii\data\ActiveDataProvider(['query' => $query ]);
        }
    $query->joinWith(
        [
            'officerView' => function($query) { $query->from([OfficerView::tableName()]); },
            'person' => function($query) { $query->from([Person::tableName()]); }

            //'person' => function($query) { $query->from([Person::tableName()]); }
        ]
    )->orderBy('vw_officer.category_rank_id, person.second_name');
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
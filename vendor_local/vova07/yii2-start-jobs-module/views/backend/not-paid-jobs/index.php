<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $newModel Committee
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
use vova07\themes\adminlte2\widgets\Box;
use yii\grid\GridView;
//use \kartik\grid\GridView;
//use Zelenin\yii\SemanticUI\widgets\GridView;
use yii\grid\SerialColumn;
use yii\grid\ActionColumn;
use vova07\jobs\Module;
use yii\bootstrap\Html;
use vova07\jobs\models\Holiday;
use yii\bootstrap\ActiveForm;
use \vova07\jobs\helpers\Calendar;

/**
 * @var $searchModel \vova07\jobs\models\backend\JobPaidSearch
 */

\uran1980\yii\modules\i18n\assets\AppAjaxButtonsAsset::register($this);

$this->title = Module::t("default","JOB_NOT_PAID_TITLE");

$this->params['subtitle'] = '';
/*
$this->params['subtitle'] = Html::a(Html::tag('i','',['class'=>'fa  fa-chevron-left']),$prevUrl) .
    $thisMonthDay->format('M') . ' ' . $thisMonthDay->format('Y').
    Html::a(Html::tag('i','',['class'=>'fa  fa-chevron-right']),$nextUrl)
;
*/
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        //      'url' => ['index'],
    ],
    // $this->params['subtitle']
];
?>

<?php $box = Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{create}',
         'buttons' => [
            'create' => [
                'url' => ['create',
                    'prison_id' => \vova07\prisons\models\Company::findOne(['alias'=>'pu-1'])->primaryKey,
                    'month_no' => $searchModel->month_no,
                    'year' => $searchModel->year,
                    ],
                'icon' => 'fa-plus',
                'options' => [
                    'class' => 'btn-default',
                    'title' => Yii::t('vova07/themes/adminlte2/widgets/box', 'Create'),

                ]
            ]
          ]
    ]

);?>




<?php
    $gridColumns = [
    ['class' => SerialColumn::class],
    'prison.company.title',
    'prisoner.person.fio',
    'type.title',
//    'month_no',
//    'year',
    ];


?>

<?php echo \vova07\jobs\components\job_grid\JobGrid::widget([
    'dataProvider' => $dataProvider,
    'filterModel'=> $searchModel,
    'columns' => $gridColumns,
    'fixedColumnsWidth' => [2,4,14,5],
   // 'showOnEmpty' => true,
    'showSyncButton' => false,


])?>




<?php  Box::end()?>




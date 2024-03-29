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

$this->title = Module::t("default","JOB_PAID_TITLE");

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
<?php echo \yii\helpers\Html::a('ordanante',\yii\helpers\Url::current([0=>'index-orders-report']),['class' => 'fa fa-print'])?> |
<?php echo \yii\helpers\Html::a('certificat',\yii\helpers\Url::current([0=>'index-certificats-report']),['class' => 'fa fa-print'])?> |
<?php echo \yii\helpers\Html::a('print',\yii\helpers\Url::current([0=>'index-print']),['class' => 'fa fa-print'])?>
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
    ['class' => \yii\grid\SerialColumn::class],
    // 'prison.company.title',
    'prisoner.fullTitle',
    'type.title',
//    'month_no',
//    'year',
    [
        'attribute'=>'half_time',
        'format'=> 'html',
        'value' => function($model){
            $className = $model->half_time?'fa fa-check':'';
            return \yii\helpers\Html::tag('i','',['class'=>$className . ' text-success']);
        }
    ],
];


?>
<?php echo \vova07\jobs\components\job_grid\JobGrid::widget([
    'showOnEmpty' => true,
    'dataProvider' => $dataProvider,
    'filterModel'=> $searchModel,
    'columns' => $gridColumns,

   // 'fixedColumnsWidth' => [2,15,10,3],
    'showActionButton' => !$this->context->isPrintVersion,


])?>



<?php  Box::end()?>




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

$this->title = '';

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
<table width="100%">
    <tr>
        <td width="50%">
            <p style="font-size:160%">
                <b>Instituţia:</b> <u>Penitenciarul nr.1 Taraclia</u>
            </p >
            <p style="font-size:160%">
                 <b>Detaşamentul:</b> <u>deservirea gospodărească</u>
            </p>
        </td>
        <td width="50%">
            <p style="text-align: center;font-size:200%">
"APROB"
            </p>
            <p style="text-align: center;font-size:200%">
<?=$director->post->title?>
            </p>
            <p style="text-align: center;font-size:200%">
<?=$director->rank->title?>________________________<?=$director->person->getFio(true,false)?>
            </p>


        </td>
    </tr>
</table>

<br/><br/>

<h3 style="text-align: center;font-size:200%">TABEL DE PONTAJ</h3>
<h4 style="text-align: center;font-size:200%">
al detaşamentului deservirii gospodăreşti pe luna: <b><?php echo \vova07\site\Module::t('calendar', 'MONTH_' . $searchModel->month_no)?></b> anului: <?=$searchModel->getYearMonth(false)->format("Y")?>
</h4>

<?php
$gridColumns = [
    ['class' => \yii\grid\SerialColumn::class],
    // 'prison.company.title',
    'prisoner.fullTitle',
    'type.title',
//    'month_no',
//    'year',
    'type.category_id',
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
    'showOnEmpty' => false,
    'dataProvider' => $dataProvider,
    'filterModel'=> $searchModel,
    'columns' => $gridColumns,
    'fixedColumnsWidth' => [2,15,10,3],
    'showSyncButton' => false,
    'enableControlls' => false,
    'summary' => false,
])?>






<p style="text-align: left;font-size:200%">
    <?=$sefSLA->post->title?>,

    <?=$sefSLA->rank->title?> ____________________ <?=$sefSLA->person->fio?>
</p>


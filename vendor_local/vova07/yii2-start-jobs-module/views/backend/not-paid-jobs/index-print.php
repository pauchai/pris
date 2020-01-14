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
<?php $this->registerCss(<<<CSS
/*
.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    border: 1px solid ;
}
*/


CSS
)?>
<table width="100%">
    <tr>
        <td width="60%">
            <p>
                <b>Instituţia:</b> <u>Penitenciarul nr.1 Taraclia</u>
            </p>
            <p>
                 <b>Detaşamentul:</b> <u>deservirea gospodărească</u>
            </p>
        </td>
        <td width="40%">
            <p style="text-align: center">
"APROB"
            </p>
            <p style="text-align: right">
__________________________________________________________________________
            </p>
            <p style="text-align: right">
__________________________________________________________________________
            </p>


        </td>
    </tr>
</table>



<h2 style="text-align: center">TABEL DE PONTAJ</h2>
<p style="text-align: center">
al detaşamentului deservirii gospodăreşti pe luna: <b><?=$searchModel->getYearMonth(false)->format("F")?></b> anului: <?=$searchModel->getYearMonth(false)->format("Y")?>
</p>


<?php
$gridColumns = [
    ['class' => SerialColumn::class],
    // 'prison.company.title',
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
    //'fixedColumnsWidth' => [2,4,14,5],
    'showSyncButton' => false,
    'enableControlls' => false,
    'summary' => false,



])?>






<p style="text-align: right">
    __________________________________________________________________________
</p>
<p style="text-align: right">
    __________________________________________________________________________
</p>


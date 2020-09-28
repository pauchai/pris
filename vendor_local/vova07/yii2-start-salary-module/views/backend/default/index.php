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
$this->params['subtitle'] = Module::t("default","SALARY");
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        //      'url' => ['index'],
    ],
    // $this->params['subtitle']
];
?>

<?=$this->render('_issueView',['model' => $salaryIssue])?>

<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
       // 'title' => $this->params['subtitle'],
       // 'buttonsTemplate' => '{create}'
    ]

);?>



<?php
$urlParams = ['print-receipt', 'at' => (new DateTime())->setDate($salaryIssue->year, $salaryIssue->month_no, '01')->format('Y-m-d')] ;
echo \yii\bootstrap\Html::a('receipt', $urlParams, ['class' => 'btn btn-success fa fa-credit-card']);
?>
<?php
$columns =
    [
        ['class' => yii\grid\SerialColumn::class],
        [
            'attribute' => 'officer.rank.category',
            'group' => true,
            'groupedRow' => true,
            'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
            'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class


        ],
        [
            'attribute' => 'officer.person.fio',
            'format' => 'html',
            'content' =>  function($model,$index,$key){
                $content = \yii\bootstrap\Html::a(
                    $model->officer->person->fio ,
                    ['/users/officers/view',
                        'id' => $model->officer_id,
                    ]);
                ;

                return $content;
            },
        ],

        [
            'attribute' => 'chargesTotal',
            'content' => function($model){
                /**
                 * @var $model \vova07\salary\models\SalaryWithHold
                 */
                $params = $model->primaryKey;
                $params[0] = '/salary/default/salaries-view';
                return \yii\helpers\Html::a(
                    $model->getSalaries()->totalAmount(),
                    $params

                );
            }

        ],
        [
            'attribute' =>  'total',
            'content' => function($model){
                $params = $model->primaryKey;
                $params[0] = '/salary/default/with-hold-view';
                return \yii\helpers\Html::a(
                    $model->total,
                    $params,
                    [
                        'class' => ['text-danger']
                    ]

                );
            }

        ],

        [
            //  'class' => kartik\grid\EditableColumn::class,
            'attribute' => 'amount_card',

        ]

    ];



//    $columns[] = [
//        'attribute' => 'officer.balance.remain',
//        'content' => function($model){
//            return
//                \yii\helpers\Html::a(
//                    ArrayHelper::getValue($model,'officer.balance.remain'),
//                    ['/salary/balance/officer-view', 'id' => $model->officer_id]
//                );
//
//
//        }
//
//    ];

?>

<?php echo GridView::widget(['dataProvider' => $dataProvider,
       // 'formatter' => ['class' => \yii\i18n\Formatter::class,'nullDisplay' => ''],
        //'emptyCell' => '',

   // 'floatHeader' => true,
    'pjax' => true,
    'columns' => $columns


])?>

<?php  \vova07\themes\adminlte2\widgets\Box::end()?>



<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

/**
 * @var $searchModel \vova07\electricity\models\backend\CalendarSearch
 */
use kartik\grid\GridView;
use kartik\grid\DataColumn;
//use yii\grid\GridView;
//use yii\grid\DataColumn;
use vova07\electricity\Module;
use vova07\electricity\models\DeviceAccounting;
use vova07\themes\adminlte2\widgets\Box;

$this->title = Module::t("default","SUMMARIZED_VIEW");
$this->params['subtitle'] = 'LIST';
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
    ]

);?>

<?php //echo $this->render('_search',['model' => $searchModel])?>

<?php
    $columns = [

        [
            'class' => DataColumn::class,
            'attribute' => 'year',
            'filterType' => \kartik\touchspin\TouchSpin::class,
            'filterWidgetOptions' => [
                'pluginOptions' =>[
                    'min' => 1900,
                    'max' => 2030,
                    'verticalbuttons' => true,
                ]
            ],
            'content' => function($model){

                return \yii\bootstrap\Html::tag('span',
                    (new DateTime)->setDate($model['year'],$model['month'],'1')->format('M Y'),
                ['class' => 'label label-default']
                ) ;
            }

        ],

    ];

    foreach ($searchModel->getDevices() as $device)
    {
        /**
         * @var $device \vova07\electricity\models\Device
         */
        $view = $this;
        $deviceSectorTitle = $device->sector? $device->sector->title:'';
        $columns[] = [
          'header' => $device->title . ' ' . $deviceSectorTitle,
            'content' => function($model)use ($device, $searchModel, $view){
                $query = $device->getDeviceAccountings()->andWhere(
                    new \yii\db\Expression("FROM_UNIXTIME(from_date,'%Y-%m') = :year_month",[':year_month' => sprintf('%04d-%02d', $model['year'],$model['month'])])
                );

                return $view->render("_detail",['deviceAccountings' => $query->all()]);
                //return 'kwt:' . $kwt . ' lei:' . $sum . '<br/>' . join('<br/>', $paidFios);

             }
        ];
    }

?>
<?php echo GridView::widget([
   // 'striped' => true,
  //  'hover' => true,
   // 'condensed' => true,
    'filterModel' => $searchModel,
    'dataProvider' => $dataProvider,
    'summary' => '',
    'columns' => $columns


])?>


<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

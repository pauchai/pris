<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
use vova07\socio\Module;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;

//$this->title = Module::t("default","EVENTS_TITLE");
$this->params['subtitle'] = Module::t("default","MARITAL_STATUS_TITLE");
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        //      'url' => ['index'],
    ],
    // $this->params['subtitle']
];
?>


<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{create}'
    ]

);?>

<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'filterModel' => $searchModel,

    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        'person.fio',
        'refPerson.fio',
        'status.title',



        [
            'class' => ActionColumn::class,

        ]
    ]
])?>


<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
use kartik\grid\GridView;

$this->title = \vova07\plans\Module::t("default","PROGRAM_DICTS");
$this->params['subtitle'] = 'LIST';
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
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
       [
           'attribute' =>  'group.title',
           'group' => true,
           'groupedRow' => true,
       ],
        'title',

        [
            'class' => yii\grid\ActionColumn::class,

        ]
    ]
])?>


<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

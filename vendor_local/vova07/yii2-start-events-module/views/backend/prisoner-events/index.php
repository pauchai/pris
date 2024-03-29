<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
use vova07\events\Module;
use \yii\grid\GridView;

$this->title = Module::t("default","EVENTS_TITLE");

    $this->params['subtitle'] = isset($searchModel->prisoner)?$searchModel->prisoner->person->fio:'';

$this->params['subtitle'] .=  ' | ' .  Module::t("default","PRISONER_EVENTS_LIST");

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

<?php echo \yii\grid\GridView::widget(['dataProvider' => $dataProvider,
    'columns' => [
      //  ['class' => yii\grid\SerialColumn::class],
        [
            'attribute' => 'prisoner.person.fio',
            'visible' => !isset($searchModel->prisoner)
        ],
        'event.dateStartJui',
        'event.title',
    ]
])?>


<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

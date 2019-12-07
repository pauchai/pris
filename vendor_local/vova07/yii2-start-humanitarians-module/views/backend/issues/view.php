<?php

use vova07\humanitarians\Module;
use vova07\themes\adminlte2\widgets\Box;
use vova07\humanitarians\models\HumanitarianItem;
use kartik\grid\GridView;
//use uran1980\yii\modules\i18n\components\grid\GridView;
use vova07\prisons\models\Sector;
use yii\bootstrap\Html;
use uran1980\yii\modules\i18n\components\grid\ActionColumn;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\humanitarians\models\HumanitarianIssue
 */


\uran1980\yii\modules\i18n\assets\AppAjaxButtonsAsset::register($this);

$this->title = Module::t("default","HUMANITARIANS_ISSUES_TITLE");
$this->params['subtitle'] = $model->dateIssueJui;
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];


$printUrlParams = \yii\helpers\Url::current(['print' => true]);
    ?>
<?php $box = Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{print}{update}{delete}',
        'buttons'=>[
            'print' => [
                'url' => $printUrlParams,
                'icon' => 'fa-print',
                'options' => [
                    'class' => 'btn-default',
                    'title' => Yii::t('vova07/themes/adminlte2/widgets/box', 'PRINT'),

                ]
            ],
        ]
    ]
);?>

<?php echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'dateIssueJui',


    ]
])?>

<?php  Box::end() ?>



<?php $box = Box::begin(
    [
        'title' => Module::t('default','PRISONERS_LIST'),

    ]

);?>

<?php
$gridColumns = [

    ['class' => yii\grid\SerialColumn::class],
    'person.fio',
    [
        'attribute' => 'sector_id',
        'value' => 'sector.title',
        'filter' => Sector::getListForCombo(),
        'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['prompt' => Module::t('default','SELECT_TYPE'), 'class'=> 'form-control', 'id' => null],

    ]
]
?>

<?php
    $humanitarianItems = $model->getItems();

    $issueModel = $model;
    foreach ($humanitarianItems as $humanitarianItem)
    {
        $gridColumns[] = [

            'class' => \vova07\humanitarians\components\HumanitarianColumn::class,
            'template' => '{toggle-item}',
            'humanitarianItem' => $humanitarianItem,
            'humanitarianIssue' => $model,
            'header' => $humanitarianItem->title,
            'massAction' => \yii\helpers\Url::to(['mass-add', 'issue_id'=>$issueModel->primaryKey, 'item_id' => $humanitarianItem->primaryKey, 'sector_id' => $searchModel['sector_id']])
            //'header'=> $humanitarianItem->title,
            //'content' => function($model){return 'test';}
        ];
    }
?>


<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $gridColumns
])?>

<?php Box::end()?>

<?php

    $this->registerJs(<<<JS
        $(document).on('ajaxButtonSubmit',function(event, data){
              \$i = data.element.closest('i');
           if (data.data.action === 'insert'){
               data.element.removeClass('btn-default').addClass('btn-success');
                data.element.children().removeClass('glyphicon-plus').addClass('glyphicon-minus');  
           }  else {
               data.element.removeClass('btn-success').addClass('btn-default');
                data.element.children().removeClass('glyphicon-minus').addClass('glyphicon-plus');
           }
        });
JS

    )

?>
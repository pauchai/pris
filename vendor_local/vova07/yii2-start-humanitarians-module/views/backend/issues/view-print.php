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


<table width="100%">
    <tr>
        <td width="70%">

        </td>
        <td width="30%">
            <p style="text-align: center;font-size:160%">
                "APROB"
            </p>

            <p style="text-align: right">
            ___________________________________________________________________________________
            </p>
            <br/>
            <p style="text-align: right">
            ___________________________________________________________________________________
            </p>
            <br/>
            <br/>
            <br/>
            <br/>

        </td>
    </tr>
</table>



<h3 style="text-align: center">
    BORDEROU DE ELIBERARE A MATERIALELOR

</h3>
<h3 style="text-align: center">
    PENTRU NECESITĂȚILE PENITENCIARULUI Nr.1 - TARACLIA
</h3>
<h3 style="text-align: center">
    pe luna <b><?php echo \vova07\site\Module::t('calendar', 'MONTH_' . date('m', $model->date_issue))?></b> anului: <?=date('Y', $model->date_issue)?>
</h3>
<br/>
<br/>





<?php

$gridColumns = [

    ['class' => yii\grid\SerialColumn::class],
    'fullTitle',
    [
            'visible' => !$searchModel->sector_id,
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
    $gridColumns[] = [
        'header' => Html::tag('div', Module::t('default','SEMNATURA_LABEL'),['style'=>'text-align:center']) ,
        'content' => function($model){return '';},


    ]
?>
<table style="width:100%">
    <tr>
        <td style="width:50%"><p>Gestionar:_____________________________________________</p></td>
        <td style="width:50%;text-align: right">
            <?php if ($searchModel->sector_id):?>
                sector: <?= $searchModel->sector->title?>
            <?php endif;?>
            <?=$model->dateIssueJui?>
        </td>
    </tr>

</table>
<br/>


<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $gridColumns,
    'summary' => false,
    'filterPosition' => GridView::FILTER_POS_FOOTER,
])?>
<br/>
<p style="font-size:160%">
    A eliberat materialele:
</p>
<br/>
<p>
    ____________________________________________________________________________________________________________________________________________________________________________________
</p>
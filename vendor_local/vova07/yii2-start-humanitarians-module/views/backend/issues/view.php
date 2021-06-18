<?php

use vova07\humanitarians\Module;
use vova07\themes\adminlte2\widgets\Box;
use vova07\humanitarians\models\HumanitarianItem;
use kartik\grid\GridView;
//use uran1980\yii\modules\i18n\components\grid\GridView;
use vova07\prisons\models\Sector;
use vova07\users\models\Prisoner;
use yii\bootstrap\Html;
use uran1980\yii\modules\i18n\components\grid\ActionColumn;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\humanitarians\models\HumanitarianIssue
 */


$this->title = Module::t("default","HUMANITARIANS_ISSUES_TITLE");
$this->params['subtitle'] = $model->dateIssueJui;
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];


    ?>



<?php echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'dateIssueJui',
        'company.title',
        'status'


    ]
])?>



<?php $box = Box::begin(
    [
        'title' => Module::t('default','HUMANITARIAN_FOR_ISSUE'),

    ]

);?>

<?php $columns=require('_grid_columns.php')?>

<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $columns,

])?>

<?php Box::end()?>
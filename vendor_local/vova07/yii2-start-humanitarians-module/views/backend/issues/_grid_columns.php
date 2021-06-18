<?php
use vova07\humanitarians\Module;
use vova07\themes\adminlte2\widgets\Box;
use vova07\humanitarians\models\HumanitarianItem;
use kartik\grid\GridView;

use vova07\prisons\models\Sector;

/**
 * @var $this \yii\web\View
 * @var $model \vova07\humanitarians\models\HumanitarianIssue
 */
$columns = [
    [
        'attribute' => 'fio',
        'header' => Module::t('default', 'PRISONER_FIO'),
        'content' => function($model){
            return $model['fio'];
        },

    ],
    [
        'attribute' => 'sector_id',
        'header' => Module::t('default', 'SECTOR_TITLE'),
        'content' => function($model){
            return Sector::findOne($model['sector_id'])->title;
        },
        'filter' => \vova07\prisons\models\Sector::getListForCombo(),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true ],
        ],
        'filterInputOptions' => [ 'prompt' => Module::t('default','SELECT_SECTORS'), 'class'=> 'no-print form-control', 'id' => null],


    ],

];
$modelsTmp = $dataProvider->getModels();
$modelTmp = reset($modelsTmp);
if (is_array($modelTmp) || is_object($modelTmp)) {
    $matches = [];
    foreach ($modelTmp as $name => $value) {
        if (preg_match('#item_(\d+)#', $name, $matches)){
            $columns[] = [
                'header' => HumanitarianItem::findOne($matches[1])->title,
                'content' => function($model)use ($name){return $model[$name];}
            ];
        }

    }
}

return $columns;
?>
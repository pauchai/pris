<?php

use vova07\humanitarians\Module;
use vova07\themes\adminlte2\widgets\Box;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\humanitarians\models\HumanitarianItem
 */
$this->title = Module::t("default","HUMANITARIANS_ITEMS_TITLE");
$this->params['subtitle'] = $model->title;
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
?>
<?php $box = Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{update}{delete}'
    ]
);?>

<?php echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'title',


    ]
])?>

<?php  Box::end() ?>



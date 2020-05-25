<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","OFFICERS");
$this->params['subtitle'] = 'LIST';
?>

<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{create}'
    ]

);?>

<?php echo \yii\grid\GridView::widget(['dataProvider' => $dataProvider,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        'person.fio',
        'company.title',
        'division.title',
        'rank',
        'post.title',
        ['class' => yii\grid\ActionColumn::class]
    ]
])?>
<?php \vova07\themes\adminlte2\widgets\Box::end()?>



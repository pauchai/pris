<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","PRISONER");
$this->params['subtitle'] = Module::t('default','PRISONERS_LIST');
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
        'company.title',
        [
          'header' => 'sectors',
          'content' => function($model){
              return Html::a($model->getSectors()->count(), ['prison-sectors/index','prison_id' => $model->primaryKey], [
                  'title' => \vova07\prisons\Module::t('default', 'COMPANY_SECTORS'),
                  'data-pjax' => '0',
              ]);
              },
        ],
        [
            'class' => \yii\grid\ActionColumn::class,


        ]
    ]
])?>
<?php \vova07\themes\adminlte2\widgets\Box::end()?>




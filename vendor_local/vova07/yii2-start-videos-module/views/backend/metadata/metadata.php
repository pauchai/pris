<?php

use vova07\videos\Module;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use \vova07\themes\adminlte2\widgets\Box;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
/**
 *
 * @var \yii\web\View $this
 */
$this->title = Module::t("default","VIDEO");
$this->params['subtitle'] = Module::t("default",'META_DATA_TITLE');
$videoModel = $model;
?>



<?php DetailView::widget(
        [
                'model' => $model,
            'attributes' => [
                'title'
                ]
        ]
) ?>

    <?= GridView::widget([
      'dataProvider' => $subsDataProvider,
    'panel' => [
        'type' => GridView::TYPE_PRIMARY
    ],
    'toolbar' =>  [
        ['content'=>
            Html::a(
                Html::tag('i','' ,['class' => 'fa fa-plus']),
                ['subtitle-create', 'video_id' => $model->primaryKey],
                [
                    'class' => 'btn btn-default'
                ]

            )

        ]
    ],
    'columns' => [
        'name',
        'type',
        'filename',
        [
            'class' => \yii\grid\ActionColumn::class,
            'template' => "{delete}",
            'buttons' => [
                'delete' => function($url, $model, $key)use ($videoModel){
                    $url = ['subtitle-delete', 'video_id' => $videoModel->primaryKey, 'key' => $key];
                    return Html::a('delete', $url);
                }
            ]
        ]

    ]
  ]) ?>



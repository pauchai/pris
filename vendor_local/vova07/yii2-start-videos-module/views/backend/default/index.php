<?php
/**
 *
 * @var \vova07\videos\models\VideoSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 */

?>
<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Title</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                    <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                    <i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body">
            <?= \yii\grid\GridView::widget([
      'dataProvider' => $dataProvider,
      'columns' => [
          'id',
          [
              'class' => \yii\grid\DataColumn::class,
              'attribute' => 'thumbnail_url',
              'format' => 'html',
              'value' => function($model){
                  return \yii\bootstrap\Html::img(\vova07\videos\Module::getInstance()->videosBaseUrl . '/' . $model->thumbnail_url, ['width' => 150]);

              },
          ],
          'title',
           [
           'class' => \yii\grid\ActionColumn::class,
               'template' => '{metadata} {view} {update} {delete}',
               'buttons' => [
                   'metadata' => function ($url, $model, $key) {
                                $url = ['/videos/metadata/metadata', 'id' => $model->primaryKey];
                                return  \yii\bootstrap\Html::a('metadata', $url);
                            },
               ]

           ]
      ],
  ]) ?>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            Footer
        </div>
        <!-- /.box-footer-->
    </div>
    <!-- /.box -->

</section>
<!-- /.content -->
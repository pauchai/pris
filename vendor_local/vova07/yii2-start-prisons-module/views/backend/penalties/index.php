<?php
/**
 * @var $generateTabularDataFormModel \vova07\electricity\models\backend\GenerateTabularDataForm
 */

use yii\helpers\Html;
use vova07\prisons\Module;
use kartik\grid\GridView;

$this->title = \vova07\plans\Module::t("default","PENALTIES_TITLE");
$this->params['subtitle'] = '';
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


<?php echo $this->render('_search',['model' => $searchModel])?>



    <?php echo GridView::widget(['dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => $this->context->isPrintVersion?"{items}\n{pager}":"{summary}\n{items}\n{pager}",
        'showPageSummary' => true,
        //'showFooter' => true,
        'columns' => [
            [
                'class' => \kartik\grid\SerialColumn::class,
            ],
            [
                'attribute' => 'prisoner_id',
                'value' => function($model){
                    if ($model->prisoner)
                        return $model->prisoner->person->getFio(false, true);
                    else
                        return null;

                },
                'filter' => false

            ],
            [
                'attribute' => 'prison_id',
                'value' => 'prison.company.title'
                //'pageSummaryFunc' => function(),
            ],
            'comment',

            'date_start:date',
            'date_finish:date',
            [

                'class' => \yii\grid\ActionColumn::class,
                'template' => '{update}{delete}',
                'visible' => !$this->context->isPrintVersion,
            ],


        ],


    ]);

    ?>







<?php  \vova07\themes\adminlte2\widgets\Box::end()?>

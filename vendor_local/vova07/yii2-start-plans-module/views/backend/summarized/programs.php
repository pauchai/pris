<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

/**
 * @var $model \vova07\plans\models\SummarizedModel
 */
use kartik\grid\GridView;
use \vova07\plans\Module;
use yii\bootstrap\Html;
$this->title = \vova07\plans\Module::t("default","SUMMARIZED_PROGRAMS");
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
    ]

);?>

<?php //echo $this->render('_search',['model' => $searchModel])?>


<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        [
            'attribute' => 'fio',
            'value' => function($model){return $model->person->getFio(false,true);}

        ],

        [
            'header' => "EDUCATOR",
            'content' => function($model){
                /**
                 * @var $model \vova07\plans\models\backend\SummarizedProgramsSearch
                 */
                return contentField($model, $model->getPrisonerProgramsByEducator());
            }
        ],
        [
            'header' => "PSYCHOLOGIST",
            'content' => function($model){
                return contentField($model, $model->getPrisonerProgramsByPsychologist());
            }
        ],
        [

            'header' => "SOCIOLOGIST",
            'content' => function($model){
                return contentField($model, $model->getPrisonerProgramsBySociologist());
            }
        ],
        [
                'class' => \kartik\grid\ActionColumn::class,
                'template' => "{view}",
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $url =  ['/plans/program-prisoners/index','ProgramPrisonerSearch[prisoner_id]'=>$model->primaryKey];
                         return  Html::a('', $url,['class' => 'fa fa-list']);
                     } ,
                    ]



        ]
    ]
])?>


<?php  \vova07\themes\adminlte2\widgets\Box::end()?>
<?php
    function contentField($model, \vova07\plans\models\ProgramPrisonerQuery $query)
    {
        /**
         * @var $model \vova07\plans\models\backend\SummarizedProgramsSearch
         */
        $query2 = clone $query;
        $realizedCnt = $query->realized()->count();
        $notRealizedCnt = $query2->notRealized()->count();
        $totalCnt = $realizedCnt + $notRealizedCnt;
        if ($totalCnt == 0)
                return '';

        if ($totalCnt == $realizedCnt)
            $totalOptions = ['class' => 'btn btn-success', 'style'=>['width'=>'100%']];
        else
            $totalOptions =  ['class' => 'btn btn-danger', 'style'=>['width'=>'100%']];


        $progressBarContent = Html::tag('div',
            Html::tag('div',
                Html::tag('span',
                    $realizedCnt,
                    [
                    //    'class' => "sr-only"
                    ]
                ),
                ['style'=>['width'=> round($realizedCnt/$totalCnt * 100) .'%'],'class' => " progress-bar-striped progress-bar progress-bar-success"]
            ) .
            Html::tag('div',
                Html::tag('span',
                    $notRealizedCnt,
                    [
                    //    'class' => "sr-only"
                    ]
                ),
                ['style'=>['width'=>round($notRealizedCnt/$totalCnt * 100) .'%'],'class' => "progress-bar-striped  progress-bar progress-bar-danger"]
            ),
            ['class' => " active" ]
        );

        $totalButtonContent = Html::tag('span',$totalCnt ."($realizedCnt + $notRealizedCnt)", $totalOptions);
        return  $progressBarContent ;
    }
?>


<?php $this->registerCss(
    <<<CSS
@media print{
    .progress-bar-success {
        background-color:#00a65a !important;
       margin:-1px;
        border: solid 1px #00a65a
    }
      .progress-bar-danger {
        background-color:red !important;
        margin:-1px;
        border: solid 1px red
    }
}
table.kv-grid-table thead tr th:nth-child(3),
table.kv-grid-table tbody tr td:nth-child(3),
table.kv-grid-table thead tr th:nth-child(4),
table.kv-grid-table tbody tr td:nth-child(4),
table.kv-grid-table thead tr th:nth-child(5),
table.kv-grid-table tbody tr td:nth-child(5) {
    width: 10em;
}
CSS

)?>
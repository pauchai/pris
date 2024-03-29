<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $newModel Committee
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
use vova07\themes\adminlte2\widgets\Box;
use kartik\grid\GridView;
use yii\grid\SerialColumn;
use vova07\psycho\Module;
use yii\bootstrap\Html;
use vova07\psycho\models\PsyTest;

use vova07\tasks\models\Committee;

use vova07\humanitarians\models\HumanitarianPrisoner;



$this->title = Module::t("default","PSYCHO_TESTS_TITLE");
$this->params['subtitle'] = 'PSYCHO_TESTS_TITLE_LIST_TITLE';
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        //      'url' => ['index'],
    ],
    // $this->params['subtitle']
];
?>

<?php //$printUrlParams = \yii\helpers\Url::current([0=>'index-print'])?>
<?php //echo  \yii\bootstrap\Html::a('APROB',$printUrlParams,['class' => 'fa fa-print'])?>

<?php $box = Box::begin();?>

<?php echo $this->render('_search',['model' => $testSearch, 'searchModel' => $searchModel])?>
<?php echo GridView::widget([
    'dataProvider' => $dataProvider,
    'hover' => true,
    'filterModel' => $searchModel,
    //'floatHeader' => true,
    'columns' => [
        ['class' => SerialColumn::class],
        [
            'attribute' => '__person_id',
            'value' => 'fullTitle',
            'filter' => \vova07\users\models\Prisoner::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true ],
            ],
            'filterInputOptions' => [ 'prompt' => Module::t('default','SELECT_PRISONER'), 'class'=> 'no-print form-control', 'id' => null],


        ],
        [
            'attribute' => 'sector_id',
            'value' => 'sector.title',
            'filter' => \vova07\prisons\models\Sector::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true ],
            ],
            'filterInputOptions' => [ 'prompt' => Module::t('default','SELECT_SECTORS'), 'class'=> 'no-print form-control', 'id' => null],

        ],

        [
            'attribute' =>         'hasTest',
            'header' =>  Module::t('default', 'FILTER_HEADER'),
            'format'=> 'html',
            'content' => function($model)use($testSearch){
                    $content = \yii\bootstrap\Html::a('',['create','prisoner_id'=>$model->primaryKey],['class' => 'fa fa-plus']);
                    foreach ($model->getTests()->fromTo($testSearch->atFrom, $testSearch->atTo)->all() as $key=>$test){
                        $labelOptions = [
                            'title' => $test->atJui . ', ' . $test->status,
                            'class' => 'label label-' . $test->getStatusGlyph()


                        ];
                        $buttonOptions = [
                            'aria-label' => $test->atJui,
                            'data-pjax' => '0',
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'class' => 'btn-xs btn btn-' . $test->getStatusGlyph(). ' glyphicon glyphicon-remove' ,
                        ];
                        if ($test->status_id === PsyTest::STATUS_ID_SUCCESS)
                            $statusOptions = ['class' => 'label fa fa-check  ' ];
                        else
                            $statusOptions = ['class' => 'label bg-red fa fa-paper-plane '];

                        $statusGlyph =  Html::tag('span', ' ', $statusOptions);
                        $trashButton = Html::a(  '', ['delete','id'=>$test->primaryKey] ,$buttonOptions);
                        $content .= Html::tag('span', $test->atJui . $statusGlyph . $trashButton,$labelOptions);



                    };





                return $content;
            },
            'filter' => \vova07\psycho\models\backend\PrisonerTestSearch::getHasTestFilter(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true ],
            ],
            'filterInputOptions' => [ 'prompt' => Module::t('default','SELECT_ALL'), 'class'=> 'no-print form-control', 'id' => null],


        ],

    ],


])?>




<?php  Box::end()?>

<a class="glyphicon-floppy-remove"></a>
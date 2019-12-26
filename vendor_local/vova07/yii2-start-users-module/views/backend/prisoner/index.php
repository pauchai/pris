<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
//use yii\grid\GridView;
use kartik\grid\GridView;
use vova07\users\models\Prisoner;
use vova07\users\models\backend\PrisonerSearch;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\Prison
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","PRISONERS");
$this->params['subtitle'] = Module::t("default","SUBTITLE_LIST");
?>

<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{create}'
    ]

);?>
<?=$this->render('_search',['model' => $searchModel])?>

<?=\kartik\helpers\Html::a($isLight?\vova07\site\Module::t('default','LIST_VERSION_FULL'):\vova07\site\Module::t('default','LIST_VERSION_LIGHT'),\yii\helpers\Url::current(['isLight' => !$isLight]),['class'  => 'btn btn-info no-print'])?>


<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        [
            'attribute' => '__person_id',

            'filter' => Prisoner::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'attribute' => '__person_id',
                'pluginOptions' => ['allowClear' => true ],
            ],
            'filterInputOptions' => [ 'prompt' => Module::t('default','SELECT_PRISONER'), 'class'=> 'no-print form-control', 'id' => null],

            //'header' => '',
           'content' => function($model){return $model->person->fio . ' ' . $model->person->birth_year;},
           //
           //  'value' => 'person.fio'
        ],
        //'person.fio',
        //'person.birth_year',
 /*       [
            'attribute'  => 'prison_id',
            'header' => '',
            'value' => 'prison.company.title',
            'filter' => \vova07\prisons\models\Prison::getListForCombo(),

        ],*/
        [

            'attribute'  => 'sector_id',
            'header' => '',
            'value' => 'sector.title',
            'filter' => \vova07\prisons\models\Sector::getListForCombo(),

        ],
        [
            'attribute' => 'person.IDNP',
            'visible' => $isLight === false,
        ],

        //'person.buletin.seria',
        //'person.buletin.type',
        //'prison.company.title',

        //'sector.title',
        [
            'visible' => $isLight === false,
            'header' => \vova07\users\Module::t('label','DOCUMENTS_TITLE'),
            'content' => function($model){
                if ($buletin = $model->person->buletin){
                    $content = Html::tag('span', $buletin->type,['class'=>' label label-success ']);
                    $content .= ' ' . Html::tag('span', $buletin->seria,['class'=>'  label label-success']);
                    if ($buletin->isExpired()){
                        $content .= Html::tag('span', Yii::$app->formatter->asRelativeTime($buletin->date_expiration ),['class'=>' label label-danger']);
                    } else {
//                        $content = Html::tag('span', $content,['class'=>'label label-success']);
                        if ($buletin->isAboutExpiration()){
                            $content .= ' ' .Html::tag('span', Yii::$app->formatter->asRelativeTime($buletin->date_expiration ),['class'=>'label label-warning']);

                        }
                    };
                    return $content;


                }
            }
        ],

        [
            'header' => '',
            'value' => 'person.country.iso',
        ],
        [
            'visible' => $isLight === false,
            'attribute' => 'person.address',
        ],

        [

          'attribute' => 'article'
        ],

        [


            'attribute' => 'termStartJui',
            'filterType' => GridView::FILTER_DATE_RANGE,
            'filterWidgetOptions' => [

            ]
        ],
        [


            'attribute' => 'termFinishJui',
          //  'filterType' => GridView::FILTER_DATE_RANGE,
        ],

        [


            'attribute' => 'termUdoJui',
           // 'filterType' => GridView::FILTER_DATE_RANGE,
        ],


        [
            'hidden' => $this->context->isPrintVersion,

            'header' => '',
            'content'=> function($model){return Html::img($model->person->photo_preview_url,['class'=>"img-circle img-sm"]);},
        ],
        [
            'visible' => $isLight === false,
            'attribute' => 'status_id',
            'content' => function($model){
                if ($model->status_id > \vova07\users\models\Prisoner::STATUS_ACTIVE){
                    $options = ['class'=>'label label-danger'];
                } else if($model->status_id == \vova07\users\models\Prisoner::STATUS_ACTIVE) {
                    $options = ['class'=>'label label-success'];
                } else {
                    $options = ['class'=>'label label-info'];
                }
                return Html::tag('span',  $model->status,$options);
                },
            //contentOptions' => ['class' => '.no-print'],
            'hidden' => $this->context->isPrintVersion,
            'filter' => \vova07\users\models\Prisoner::getStatusesForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => [
                    'allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' =>  Module::t('default','SELECT_STATUS_FILTER'), 'class'=> 'form-control', 'id' => null],

        ],


        ['class' => \kartik\grid\ActionColumn::class,
            'dropdown' => true,
            'updateOptions' => ['class' => 'disabled'],
            'hidden' => $this->context->isPrintVersion,

        ],
    ]
])?>


<?php \vova07\themes\adminlte2\widgets\Box::end()?>



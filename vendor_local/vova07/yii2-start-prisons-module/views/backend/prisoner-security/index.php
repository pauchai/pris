<?php

use vova07\prisons\Module;
use vova07\prisons\models\PrisonerSecurity;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;
use vova07\themes\adminlte2\widgets\Box;
use vova07\users\models\Prisoner;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\PrisonerSecurity
 * @var $dataProvider248 \yii\data\ActiveDataProvider
 * @var $searchModel248 \vova07\prisons\models\backend\PrisonerSecuritySearch;
  * @var $dataProvider251 \yii\data\ActiveDataProvider
 * @var $searchModel251 \vova07\prisons\models\backend\PrisonerSecuritySearch;
 */
$this->title = Module::t("default","SECURITY");
$this->params['subtitle'] = 'LIST';
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
  //      'url' => ['index'],
    ],
   // $this->params['subtitle']
];
?>

<?=\kartik\helpers\Html::a($isLight?Module::t('security','PRISON_SECURITY_FULL'):Module::t('security','PRISON_SECURITY_LIGHT'),['index','isLight' => !$isLight],['class'  => 'btn btn-info no-print'])?>

<?php $columnDefinition = [
    ['class' => yii\grid\SerialColumn::class],
    [
        'attribute' => 'prisoner_id',
        //'value' => function($model){return $model->prisoner->getFullTitle(true);},
        'content' => function($model){
            if ($commitie = $model->prisoner->getCommities()->subject251()->one())
//            if ($commitie = $model->prisoner->getCommities()->one())
             return $model->prisoner->getFullTitle() . ' ' . \yii\helpers\Html::a( \yii\helpers\Html::tag('i', ' ' ,[
                 'class' => 'fa fa-info ' . ($commitie->status_id == \vova07\tasks\models\Committee::STATUS_FINISHED ? 'text-danger':'') ,
                 'data' => [
                //     'toggle' => 'tooltip',
                 //    'original-title' =>$commitie->subject . ' ' . $commitie->status . ' ' . $commitie->mark
                 ],
                 'title' => $commitie->subject . ' ' . $commitie->status . ' ' . $commitie->mark
             ]),['/tasks/committee/index',  'CommitteeSearch[prisoner_id]' => $commitie->prisoner_id, 'CommitteeSearch[subject_id]' => \vova07\tasks\models\Committee::SUBJECT_251]) ;
            else
             return $model->prisoner->getFullTitle();
         },
        'filter' => Prisoner::getListForCombo(),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['prompt' => Module::t('default','SELECT_PRISONER'), 'class'=> 'form-control', 'id' => null],
        'group' => true,
    ],
    'prisoner.sector.title',

    [
        'attribute' => 'type_id',
        'value' => 'type',
        'filter' => PrisonerSecurity::getTypesForCombo(),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['prompt' => Module::t('default','SELECT_TYPE'), 'class'=> 'form-control', 'id' => null],
        'hidden' => $isLight,
    ],
        [
            'attribute' => 'dateStartJui',
            'hidden' => $isLight
        ],



    [
        'attribute' => 'dateEndJui',
        //'value'=> 'date_end',
        //'format' => 'date',
        'filterType' => GridView::FILTER_DATE,
        'filterInputOptions' => ['prompt' => Module::t('default','SELECT_DATE_END'), 'class'=> 'form-control', 'id' => null],
        'hidden' => $isLight
    ],
    [
        'header' => '',
        'content' => function($model){
            $content ="";
            if ($model->isExpired()){
                $content = \yii\bootstrap\Html::tag('span', Yii::$app->formatter->asRelativeTime($model->date_end ),['class'=>'label label-danger']);
            } else {
                if ($model->isAboutExpiration()){
                    $content .= ' ' .\yii\bootstrap\Html::tag('span', Yii::$app->formatter->asRelativeTime($model->date_end ),['class'=>'label label-warning']);

                }
            };
            return $content;
        },
        'hidden' => $isLight,
    ],

    [
        'class' => ActionColumn::class,
        'hidden' => $this->context->isPrintVersion  ,

    ]
];
?>
<?php $box = Box::begin(
    [
        'title' => Module::t('security',"PRISON_SECURITY_248_TITLE"),
        'buttonsTemplate' => '{create}',

    ]

);?>
<?php if ($this->context->isPrintVersion) {
    $searchModel248 = null;
    $searchModel251 = null;
}?>
<?php echo GridView::widget(['dataProvider' => $dataProvider248,
    'filterModel' => $searchModel248,
    'columns' => $columnDefinition,
])?>
<?php Box::end()?>

<?php $box = Box::begin(
    [
        'title' => Module::t('security',"PRISON_SECURITY_251_TITLE"),
        'buttonsTemplate' => '{create}'
    ]

);?>
<?php echo GridView::widget(['dataProvider' => $dataProvider251,
    'filterModel' => $searchModel251,
    'columns' => $columnDefinition
])?>

<?php Box::end()?>

<?php  if ($this->context->isPrintVersion) $this->registerCss(<<<CSS
    
    .table td {
        padding:0px !important;
        font-size: 150%;
    }
CSS
)
?>


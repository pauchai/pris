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
use kartik\grid\ActionColumn;
use yii\bootstrap\ActiveForm;
use vova07\tasks\models\Committee;
use yii\bootstrap\Html;
use vova07\tasks\Module;

$this->title = \vova07\plans\Module::t("default","COMMITEE_TITLE");
$this->params['subtitle'] = 'LIST';
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        //      'url' => ['index'],
    ],
    // $this->params['subtitle']
];
?>

<?php $box = Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{create}'
    ]

);?>

<?php //$form=\yii\bootstrap\ActiveForm::begin(['action' => ['create']]);?>
<?php /*$newModelFields = [

    [],
    [
        'content' =>  $form->field($newModel,'subject_id')->dropDownList(Committee::getSubjectsForCombo(),['prompt' => Module::t('default','SELECT_SUBJECT_PROMPT')])->label(false) ,
    ],
    [
        'content' => $form->field($newModel,'prisoner_id')->dropDownList(\vova07\users\models\Prisoner::getListForCombo(),['prompt' => Module::t('default','SELECT_PRISONER_PROMPT')])->label(false)
    ],
    [
        'content' => $form->field($newModel,'assigned_to')->dropDownList(\vova07\users\models\Officer::getListForCombo(),['prompt' => Module::t('default','SELECT_OFFICER_PROMPT')])->label(false)
    ],
    [
        'content' => $form->field($newModel,'dateStartJui')->widget(\yii\jui\DatePicker::class,[
            'options' => ['class'=>'form-control','placeholder' => Module::t('default','SELECT_DATE_PROMPT')],
            'dateFormat' => 'yyyy-MM-dd'
        ])->label(false)
    ],
    [
        'content' => $form->field($newModel,'dateFinishJui')->widget(\yii\jui\DatePicker::class,[
            'options' => ['class'=>'form-control','placeholder' => Module::t('default','SELECT_DATE_PROMPT')],
            'dateFormat' => 'yyyy-MM-dd'
        ])->label(false)
    ],
    [
        'content' => $form->field($newModel,'mark_id')->dropDownList(Committee::getMarksForCombo(),['prompt' => Module::t('default','SELECT_MARK_PROMPT')])->label(false)
    ],
    [
        'content' => $form->field($newModel,'status_id')->dropDownList(Committee::getStatusesForCombo(),['prompt' => Module::t('default','SELECT_STATUS_PROMPT')])->label(false)
    ],
    [
        'content' =>     Html::submitButton(Module::t('default', ''), ['class' => 'btn btn-success fa fa-plus form-control'])
    ]
]*/
?>


<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'showFooter' => true,
    'filterModel' => $searchModel,
    'afterFooter' => [[
  //      'columns' => $newModelFields
    ]],
    'columns' => [
        ['class' => SerialColumn::class],

        [
            'attribute' => 'subject_id',
            'value' => 'subject',
            'filter' => Committee::getSubjectsForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => Module::t('default','SELECT_SUBJECT'), 'class'=> 'form-control', 'id' => null],
        ],
        [
            'attribute' => 'prisoner_id',
            'value' => 'prisoner.person.fio',
            'filter' => \vova07\users\models\Prisoner::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => Module::t('default' ,'SELECT_PRISONER'), 'class'=> 'form-control', 'id' => null],

        ],
        [
            'attribute' => 'assigned_to',
            'value' => 'assignedTo.person.fio',
            'filter' => \vova07\users\models\Officer::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => Module::t('default','SELECT_ASSIGNED_TO'), 'class'=> 'form-control', 'id' => null],

        ],

        'dateStartJui',
        'dateFinishJui',


        //'date_start:date',
        //'date_finish:date',
        [
            'attribute' => 'mark_id',
            'value' => 'mark',
            'filter' => Committee::getMarksForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => Module::t('default','SELECT_MARK_FILTER'), 'class'=> 'form-control', 'id' => null],
        ],


        [
            'attribute' => 'status_id',
               'filter' => Committee::getStatusesForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],

            ],
            'filterInputOptions' => ['prompt' => Module::t('default','SELECT_STATUS'), 'class'=> 'form-control', 'id' => null],
               'content' => function($model) {
                    if ($model->status_id === Committee::STATUS_MATERIALS_ARE_READY){
                        $options = ['class' => 'label label-info'];
                    } elseif ($model->status_id === Committee::STATUS_FINISHED)  {
                        $options = ['class' => 'label label-success'];
                    } else {
                        $options = ['class' => 'label label-default'];
                    }

                return Html::tag('span',$model->status,$options);
            },
        ],

        ['class' => ActionColumn::class,
            'hidden' => $this->context->isPrintVersion,

        ]
    ]
])?>
<?php //\yii\bootstrap\ActiveForm::end()?>

<?php // $this->render('_form',['model' =>$newModel,'box' => $box,'params' => ['action' => ['create'], 'layout' => 'inline']])?>

<?php  Box::end()?>


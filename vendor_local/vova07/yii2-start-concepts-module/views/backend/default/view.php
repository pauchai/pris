<?php

use vova07\concepts\Module;
use yii\grid\GridView;

use yii\helpers\Html;
use vova07\concepts\models\ConceptVisit;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\concepts\models\Concept
 */
$this->title = Module::t("default","CONCEPT_TITLE");
$this->params['subtitle'] = $model->title;
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
?>
<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{update}{delete}'
    ]
);?>

<?php echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'title',
        'slug',

    ]
])?>


<?php \vova07\themes\adminlte2\widgets\Box::end(); ?>


<?php $boxClasses = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => 'Participants',
        'buttonsTemplate' => ''
    ]
);?>


<?php
    $newClass = new \vova07\concepts\models\ConceptClass();
    $newClass->concept_id = $model->primaryKey;
    $newClassForm = \kartik\form\ActiveForm::begin([
        'type' => \kartik\form\ActiveForm::TYPE_INLINE,
        'action' => ['/concepts/classes/create'],
      //  'options' => ['class'=>'pull-right']
    ])
?>
    <?=$newClassForm->field($newClass, 'concept_id')->hiddenInput()?>
    <?=$newClassForm->field($newClass, 'atJui')->widget(\yii\jui\DatePicker::class)?>
    <?=\yii\helpers\Html::submitButton('',['class' => 'form-control fa fa-plus'])?>


<?php \kartik\form\ActiveForm::end()?>
<?php
    $gridColumns = [
        ['class' => yii\grid\SerialColumn::class],
        [
                'attribute' => 'participant_id',
                'value' => function($model){return $model->prisoner->getFullTitle(true);}
        ],

    ];

?>
<?php foreach ($model->classes as $classModel):?>
   <?php $gridColumns[] = [
            'header' => Yii::$app->formatter->asDate($classModel->at,'mm/dd') . ' ' . Yii::$app->formatter->asDate($classModel->at,'Y'),
        'content' => function($model)use($classModel){
            $conceptVisit = ConceptVisit::findOne([
                'class_id'=>$classModel->primaryKey,
                'participant_id' => $model->primaryKey
            ]);
            if ($conceptVisit){
                return Html::tag('span',$conceptVisit->getStatus(),['class'=>'label label-' . $conceptVisit->resolveStatusStyle()]);
            } else {
                return Html::tag('div',
                    Html::dropDownList('mark',null,
                        ConceptVisit::getStatusesForCombo(),[
                            'onChange'=>'PARTICIPANTS_GRID.onMarkSelect;',
                            'prompt'=>Module::t('programs','SELECT_VISIT_STATUS')
                        ]),
                    [
                        'data-model' => \yii\helpers\Json::encode([
                            //'program_id' => $model->program_id,
                            //'prisoner_id' => $model->prisoner_id,
                            'class_id' => $classModel->primaryKey,
                            'participant_id' => $model->primaryKey

                        ])
                    ]

                );
            }
        }
          ]
    ?>
<?php endforeach;?>

<?php

    $gridColumns[]   = [
    'class' => \yii\grid\ActionColumn::class,
    'template' => '{delete}',
    'controller' => '/concepts/participants',
]
?>

<?php echo GridView::widget(
    [
        'id' => 'participants',
        'dataProvider' => new \yii\data\ActiveDataProvider(['query' => $model->getConceptParticipants()->orderByFio()]),
        'columns' => $gridColumns,

    ]
)
?>


<div class="">


    <?php $form=\kartik\form\ActiveForm::begin([
        'type' => \kartik\form\ActiveForm::TYPE_INLINE,
        'action' => ['/concepts/participants/create'],
    ])?>
    <?php
        $newConceptParticipant = new \vova07\concepts\models\ConceptParticipant();
        $newConceptParticipant->concept_id = $model->primaryKey;
    ?>
    <?=\kartik\builder\Form::widget([
        'model' => $newConceptParticipant,
        'form' => $form,

        'attributes' => [
            'concept_id' => [
                'type' => \kartik\builder\Form::INPUT_HIDDEN,
            ],
            'prisoner_id' => [
                    'type' => \kartik\builder\Form::INPUT_WIDGET,
                'widgetClass' => \kartik\widgets\Select2::class,
                'options' => [
                    'data'=>\vova07\users\models\Prisoner::getListForCombo(),
                    'pluginOptions' => ['allowClear' => true],
                    'options'=>[

                        //   'disabled' => $searchModel->prisoner_id?'disabled':false,
                        'prompt' => \vova07\concepts\Module::t('default','SELECT_PRISONER'), 'class'=> 'form-control', 'id' => null,
                        // 'disabled' => ($searchModel->prisoner_id)?'disabled':false
                    ]
                ]
            ],



        ]
    ])?>
    <?=\yii\helpers\Html::submitButton('',['class' => 'form-control fa fa-plus'])?>

    <?php \kartik\form\ActiveForm::end()?>
</div>

<?php \vova07\themes\adminlte2\widgets\Box::end(); ?>

<?php
$classVisitsUrl = \yii\helpers\Url::to(['/concepts/visits/create']);
$this->registerJs(
    <<<JS
    PARTICIPANTS_GRID = {
          onMarkSelect:function(event){
//            var tdContainer = $(event.delegateTarget);
            var selectEl = $(event.currentTarget);
            var divContainer = selectEl.parent().closest('div');            
            var dataModel  = divContainer.data('model');
            dataModel.status_id = selectEl.val();
            $.ajax({
                type:'POST',
                url:'$classVisitsUrl',
                data: dataModel,
                success: function(data) {
                    divContainer.fadeOut();
                   divContainer.html(data).fadeIn();
                }
            })
            
            
        }
    }
    
 
    $('body  ').on('change','#participants>table>tbody>tr select',PARTICIPANTS_GRID.onMarkSelect);
                
   
    

JS
)
?>
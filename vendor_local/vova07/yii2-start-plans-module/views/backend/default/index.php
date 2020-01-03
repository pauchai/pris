<?php
/**
 * @var $prisoner \vova07\users\models\Prisoner
 * @var $this \yii\web\View
 * @var $prisonerPrograms
 * @var $prisonerRequirements
 */
use vova07\plans\Module;
use yii\bootstrap\ActiveForm;
use \kartik\grid\GridView;
//use yii\grid\GridView;
use kartik\grid\SerialColumn;
use yii\helpers\Html;
use \vova07\plans\models\programPrisoner;

$this->title =  Module::t('default', 'PLANUL INDIVIDUAL DE EXECUTAREA PEDEPSEI');
$this->params['subtitle'] = '';
$this->params['breadcrumbs'][] = [
        'label'=>$prisoner->person->fio,
    'url' => ['/users/prisoner/view','id'=>$prisoner->primaryKey]

];
$this->params['breadcrumbs'][] = Module::t('default', 'PLANUL INDIVIDUAL ');;
$printUrlParams['prisoner_id'] = $prisoner->primaryKey;
$printUrlParams[0] = 'index-print';
//$printUrlParams['print']=true;
?>

<?php echo Html::a('prev', \yii\helpers\Url::current(['prisoner_id' => $prevPrisonerId]));?>  |
<?php echo Html::a('next', \yii\helpers\Url::current(['prisoner_id' => $nextPrisonerId]));?>
<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{print}{update}{delete}',
        'buttons'=>[
            'print' => [
                'url' => $printUrlParams,
                'icon' => 'fa-print',
                'options' => [
                    'class' => 'btn-default',
                    'title' => Yii::t('vova07/themes/adminlte2/widgets/box', 'PRINT'),

                ]
            ],
        ]
    ]
);?>

<?php $box->beginBody();?>
<?php // echo \vova07\users\widgets\PrisonerInfo::widget(['prisoner'=>$prisoner])?>


    <h3><?= Module::t("default","Palierul asistential") ?></h3>



<?php $this->registerCss(
    <<<CSS
.kv-table-caption{text-align:left;}
#requirements-container .btn{text-align:left;}
#program-plan-container .btn{text-align:left;}

CSS

)?>
<?php echo GridView::widget(['dataProvider' => $prisonerRequirementsDataProvider,
    'options' => ['id' => 'requirements-container'],
    'showHeader' => false,

    'layout' => "{items}\n{pager}",
    'caption' => Module::t("prisons","REQUIREMENTS"),

    'columns' => [
        //['class' => SerialColumn::class],

        [
                'attribute' => 'content',
            'content' => function($model){
                return \yii\bootstrap\Html::a($model->content,'#',['class' => 'btn btn-success btn-block ']);
            },
           //'contentOptions' => ['class' => 'btn btn-success btn-block']
        ],
        [
            'content' => function($model){
                return Html::a('','#',['class' => 'fa fa-info','title'=>$model->ownableitem->createdBy->user->role, 'data-toggle'=>'popover','data-content'=>'test popover']);
            },
            'options' => ['width' => '1em'],
            'visible' => $this->context->isPrintVersion===false
            ,
        ],
        [
                'class' => \yii\grid\ActionColumn::class,
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model, $key) {

                    return  Html::a('', ['/plans/requirements-prisoner/delete','id'=>$model->primaryKey],['class' => 'fa fa-trash']);
                }

            ],

        ]
    ]
])?>
<?php $form=\yii\bootstrap\ActiveForm::begin();?>
<div class="row">
<?php echo   Html::tag('div',
    $form->field($newRequirement,'content')->widget(\kartik\widgets\TypeaheadBasic::class, [
        'data' => \vova07\plans\models\Requirement::getRequirementsForCombo(),
        'pluginOptions' => ['highlight' => true],
        'options' => ['autocomplete' => 'off', 'placeholder' => Module::t('forms','START_TYPING_REQUIREMENT_PROMPT')],
    ])->label(false) .
    Html::tag('span',
        Html::submitButton('',['class'=>'btn btn-info btn-flat  fa fa-plus ']),
        ['class'=>'input-group-btn']
    ),
    ['class' => 'input-group input-group-sm']);?>
</div>
<?php \yii\bootstrap\ActiveForm::end();?>



<?php echo GridView::widget(['dataProvider' => $prisonerProgramsDataProvider,
    'options' => ['id' => 'program-plan-container'],
    'caption' =>  Module::t('prisons',"PROGRAME ȘI ACTIVITĂȚI OBLIGATORII"),

    'layout' => "{items}\n{pager}",
    'showHeader' => false,
    //'showFooter' => true,

    'columns' => [
        //['class' => SerialColumn::class],


        [
            'attribute' => 'programdict_id',
            // 'contentOptions' => ['class' => 'btn btn-success btn-block'],
            'content' => function($model){
                return \yii\bootstrap\Html::a($model->programDict->title,\yii\helpers\Url::to(['/plans/program-prisoners/view','id'=>$model->primaryKey]),['class' => 'btn btn-success btn-block']);
            }
        ],

        [
            'content' => function($model){
                if ($model->status_id == programPrisoner::STATUS_FINISHED)
                    return $model->status . ' ' . $model->markTitle. ' ' . $model->date_plan  ;
                 elseif ($model->status_id == programPrisoner::STATUS_PLANED)
                    return $model->date_plan;
                elseif ($model->status_id == programPrisoner::STATUS_ACTIVE)
                    if ($model->program)
                        return $model->program->date_start;
                    else
                        return \yii\helpers\Html::tag('span','bad',['class'=>'text-danger']);

                elseif(Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_PRISONER_PLAN_PROGRAMS_PLANING))

                    return \yii\bootstrap\Html::tag('div',
                        \yii\helpers\Html::input('text','date_plan',null,['class'=>'form-control year-selector', 'autocomplete'=>'off', 'style'=>'width:5em;']),
                        ['data-model'=>$model->toArray(['__ownableitem_id'])]
                    );


            },
          //  'options' => ['width' => '20em']
        ],
        [
            'content' => function($model){
                return Module::t('programs','{one}_FROM_{from}',[
                    'one'=>$model->getVisits()->presented()->count(),
                    'from'=> $model->getVisits()->exceptDoesntPresentedValid()->count()
                ]);
            },

        ],
        [
        'content' => function($model){
            return Html::a('','#',['class' => 'fa fa-info','title'=>$model->ownableitem->createdBy->user->role, 'data-toggle'=>'popover','data-content'=>'test popover']);
        },
        'options' => ['width' => '1em'],
        'visible' => $this->context->isPrintVersion===false


    ],


    ]
])?>

    <div class="row">
        <?php $form = ActiveForm::begin();?>
        <?php echo $form->field($newProgramPrisoner,'programdict_id')->widget(\vova07\themes\adminlte2\widgets\DropdownWithButton::class,['items'=>\vova07\plans\models\ProgramDict::getListForCombo()])->label(false)?>
        <?php ActiveForm::end();?>
    </div>

<?php  $box->endBody()?>



<?php \vova07\themes\adminlte2\widgets\Box::end()?>




<?=$this->render('_comments')?>
<?php

    $programPlanUrl =  \yii\helpers\Url::to(['program-plans/change-year']);

?>

<?php $this->registerJs(

      <<<JS
    $('#program-plan-container input').on('keydown',
    function(event)
    {
        if (event.originalEvent.keyCode == 13){
              var tdContainer = $(event.delegateTarget);
            var inputEl = $(event.currentTarget);
            
            var divContainer = inputEl.parent().closest('div');            
            var dataModel  = divContainer.data('model');
            dataModel.date_plan = inputEl.val();
            $.ajax({
                type:'POST',
                url:'$programPlanUrl'+'&id=' +  dataModel.__ownableitem_id,
                data: dataModel,
                success: function(data) {
                    divContainer.fadeOut();
                   divContainer.html(data).fadeIn();
                }
            })
        }
    }
    );
JS

)?>
<?php
use kartik\grid\GridView;
/**
 * @var $this \yii\web\View
 *@var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \vova07\plans\models\backend\ProgramPrisonerSearch
 * @var $programProvider \yii\data\ActiveDataProvider
 * @var $newProgram \vova07\plans\models\Program|null
 *
 */
$this->title = \vova07\plans\Module::t("default","PROGRAM_PLANS");
$this->params['subtitle'] = 'LIST';
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


    <?php echo GridView::widget(['id'=>'program-prisoners','dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        [
            'visible' => false,
            'attribute' => 'prison_id',
            'value' => 'prison.company.title',
            'filter' => \vova07\prisons\models\Prison::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => \vova07\plans\Module::t('default','SELECT_PRISON'), 'class'=> 'form-control', 'id' => null],
            'group' => true,
        ],
        [
            'attribute' => 'programdict_id',
            'value' => 'programDict.title',
            'filter' => \vova07\plans\models\ProgramPrisoner::getProgramDistinctForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => \vova07\plans\Module::t('default','SELECT_PROGRAM'), 'class'=> 'form-control', 'id' => null],
            'group' => true,
        ],
        [
            'attribute' => 'prisoner_id',
            'value' => function($model) {return $model->prisoner->getFullTitle(true);},
             'filter' => \vova07\users\models\Prisoner::getListForCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                 'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => \vova07\plans\Module::t('default','SELECT_PROGRAM'), 'class'=> 'form-control', 'id' => null],

        ],


        [
            'attribute' => 'date_plan',
            'filter' => \vova07\plans\models\ProgramPrisoner::getYearsForFilterCombo(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['prompt' => \vova07\plans\Module::t('default','SELECT_YEAR'), 'class'=> 'form-control', 'id' => null]
        ],
        [


            'attribute' => 'prisoner.term_udo',
            'content' => function($model){
                //$content = Html::tag('span', Yii::$app->formatter->asRelativeTime($doc->date_expiration ),['class'=>' label label-danger']);

                $currDate = new DateTime();
                $style = ['class' => 'label label-default'];
                $value = null;
                if ($model->prisoner->term_udo) {
                    $dateTermUdo = new DateTime($model->prisoner->term_udo);
                    $daysRemain = $currDate->diff($dateTermUdo)->format('%R%a');
                    $value = $model->prisoner->termUdoJui ;

                    if ($daysRemain <=0 ){
                        $style = ['class' => 'label label-danger'];
                        $value = $value . ' ' . Yii::$app->formatter->asRelativeTime($value);
                    }
                    elseif ($daysRemain >0 && $daysRemain <= 30*6)
                        $style = ['class' => 'label label-info'];
                    elseif ($daysRemain >=30*6 && $daysRemain <= 30*12)
                        $style = ['class' => 'label label-success'];
                }
                //  $value = $dateTermFinish->format('d-m-Y') . ' ' . $daysRemain;




                $content = \yii\helpers\Html::tag('span', $value, array_merge($style, ['style' => 'font-size:100%']));


                return $content;
            },

        ],
        [



            'attribute' => 'prisoner.term_finish',
            'content' => function($model){
                //$content = Html::tag('span', Yii::$app->formatter->asRelativeTime($doc->date_expiration ),['class'=>' label label-danger']);

                $currDate = new DateTime();
                $style = ['class' => 'label label-default'];
                $value = null;
                if ($model->prisoner->term_finish) {
                    $dateTermFinish = new DateTime($model->prisoner->term_finish);
                    $daysRemain = $currDate->diff($dateTermFinish)->format('%R%a');
                    $value = $model->prisoner->termFinishJui;

                    if ($daysRemain <= 30){
                        $style = ['class' => 'label label-danger'];
                        $value = $value . ' ' . Yii::$app->formatter->asRelativeTime($value);
                    }
                    elseif ($daysRemain >=30 && $daysRemain <= 30*6)
                        $style = ['class' => 'label label-info'];

                    elseif ($daysRemain >=30*6 && $daysRemain <= 30*12)
                        $style = ['class' => 'label label-success'];
                }
                //  $value = $dateTermFinish->format('d-m-Y') . ' ' . $daysRemain;




                $content = \yii\helpers\Html::tag('span', $value, array_merge($style, ['style' => 'font-size:100%']));


                return $content;
            },



        ],

//        [
//          'attribute' => 'plannedBy.person.fio',
//          'value' => function($model){
//                if ($model->plannedBy)
//                    return $model->plannedBy->person->getFio(true);
//                else
//                    return null;
//                },
//          'visible' => !$this->context->isPrintVersion,
//        ],
        [
                'class' => \yii\grid\CheckboxColumn::class
        ],

        [
            'visible' => !$this->context->isPrintVersion,
            'class' => \kartik\grid\ActionColumn::class,
            'dropdown' => true,
            'buttons' =>  [
            'update' => function ($url, $model, $key) {
                     return  \yii\bootstrap\Html::a('', ['/plans/program-prisoners/update','id'=>$model->primaryKey],['class'=>"fa fa-edit"]) ;
                 },
                'delete' => function ($url, $model, $key) {
                    return  \yii\bootstrap\Html::a('', ['/plans/program-prisoners/delete','id'=>$model->primaryKey],['class'=>"fa fa-trash"]) ;
                },
            ],

        ]
    ]
])?>



<?php echo \yii\bootstrap\Html::submitButton(\vova07\plans\Module::t('default', "ADD_SELECTED_PARTICIPANTS_TO_PROGRAM"),['id'=>'add-participants','class' => 'btn btn-block','data'=>['action' => \yii\helpers\Url::to(['add-participants'])]]);?>


<?php $form = \yii\bootstrap\ActiveForm::begin(['layout'=>'inline','action'=>['/plans/programs/create']]);?>

<?php if ($newProgram->programdict_id && $newProgram->prison_id):?>
    <?php
    $newProgramFields = [

        [],
        [
            'content' =>$form->field($newProgram,'programdict_id')->hiddenInput().
            $form->field($newProgram,'prison_id')->hiddenInput().
            $form->field($newProgram,'status_id')->hiddenInput()
        ],
        [
            'content' => $form->field($newProgram,'date_start')->widget(\yii\jui\DatePicker::class,[

                'dateFormat' => 'yyyy-MM-dd'])->input('',['class'=>'form-control','autocomplete'=>'off','placeholder'=>\vova07\plans\Module::t('default','DATE_START_ATTRIBUTE')])
        ],
        [
            'content' =>  $form->field($newProgram,'order_no',['inputOptions' => ['placeholder'=>\vova07\plans\Module::t('default','ORDER_NO_ATTRIBUTE')]])->input('',['class'=>'form-control','autocomplete'=>'off'])
        ],

        [
            'content' =>  $form->field($newProgram,'assigned_to')->widget(\kartik\widgets\Select2::class,[
                'data' => \vova07\users\models\Officer::getListForCombo(),
                'pluginOptions' => ['allowClear' => true ],
                'options' => ['prompt' =>  \vova07\plans\Module::t('default', 'SELECT_OFFICER')]
            ])
        ],

        [
            'content' =>  \yii\bootstrap\Html::submitButton('',['class'=>'fa fa-plus'])
        ]
    ]
    ?>

<?php else:?>
    <?php $newProgramFields = []?>
<?php endif;?>
<?php echo GridView::widget(['id'=>'programs',
    'showFooter' => true,
        'afterFooter' => [[
            'columns' => $newProgramFields
        ]],
        'dataProvider' => $programProvider,
    'columns' => [
            ['class'=> \kartik\grid\RadioColumn::class],
        'programDict.title',
        'date_start',
        'order_no',
        [
                'header'=>\vova07\plans\Module::t('default','PROGRAM_PARTICIPANTS_TITLE'),
            'content' => function($model){
                $programUrl = ['id'=>$model->primaryKey];
                $programUrl[0]='/plans/programs/view';
                return \yii\bootstrap\Html::a($model->getParticipants()->count(),$programUrl) ;
                }
        ],
        [
            'attribute' => 'assigned_to',
            'value' => 'assignedTo.person.fio'
        ]




    ]
])?>
<?php \yii\bootstrap\ActiveForm::end()?>





<?php $box->beginFooter()?>




<?php $actionUrl = \yii\helpers\Url::to(['add-participants']);
    $this->registerJs(<<<JS
    var FromToGrid = {
        'init':function(){
            $('#add-participants').on('click',function(e){FromToGrid.clickAddParticipants(e);});
            $('input[name=kvradio]').on('change',function(e){FromToGrid.resolveButtonDisabled();});
            $('input[name="selection[]"]').on('change',function(e){FromToGrid.resolveButtonDisabled();});
            $('input[name=kvradio]:last').click();
            this.resolveButtonDisabled();
            
            
        },
        'resolveButtonDisabled':function(){
            if ($('input[name=kvradio]:checked').length>0 && $('input[name="selection[]"]:checked').length>0)
                {
                $('#add-participants').removeClass('disabled');        
                } else {
                $('#add-participants').addClass('disabled');
                }
              
        },
        'clickAddParticipants':function(e){
            var programId = $('input[name=kvradio]:checked').val();
            //var prisonsSelections = $('input[name="selection[]"]:checked');
            var programPrisonerIds = $('#program-prisoners').yiiGridView('getSelectedRows');
              $.post({
                 url: '$actionUrl',
                dataType: 'json',
                  data: {
                     'ProgramPrisoner': programPrisonerIds,
                     'Program':programId
                     },
                success: function(data) {
                  //alert('I did it! Processed checked rows.')
                 // location.reload();
                },
           });
        }

    };
    $('#modelButton').click(function(){
        $('#model').modal('show')
            .find('#modelContent')
            .load($(this).attr('value'));
    });
    
    
    
    FromToGrid.init();
JS


);?>

<?php $box->endFooter()?>
<?php \vova07\themes\adminlte2\widgets\Box::end()?>





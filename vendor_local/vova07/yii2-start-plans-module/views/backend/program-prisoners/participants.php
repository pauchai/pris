<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\plans\Module;
/**
 * @var $this \yii\web\View
 * @var $program \vova07\plans\models\Program
 * @var $newParticipant \vova07\plans\models\ProgramPrisoner
 */
$this->title = Module::t("default","PROGRAM");
$this->params['subtitle'] = $program->programDict->title;
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
        'buttonsTemplate' => '{update}{delete}',
        'buttons' => [
                'update' => [
                    'url' => ['/plans/programs/update','id' => $program->primaryKey],
                    'icon' => 'fa-edit',
                    'options' => [
                        'class' => 'btn-default',
                        'title' => Yii::t('vova07/themes/admin/widgets/box', 'update')
                    ]
                ],
                            'delete' => [
                    'url' => ['/plans/programs/delete','id' => $program->primaryKey],
                    'icon' => 'fa-trash',
                    'options' => [
                        'class' => 'btn-default',
                        'title' => Yii::t('vova07/themes/admin/widgets/box', 'delete')
                    ]
                ]
        ]
    ]
);?>

<?php echo \yii\widgets\DetailView::widget([
    'model' => $program,
    'attributes' => [
        'programDict.title',
        'prison.company.title',
        'order_no',
        'date_start',
        'status'

    ]
])?>


<?php $box = \vova07\themes\adminlte2\widgets\Box::end()?>


<?php \yii\widgets\Pjax::begin()?>
<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => Module::t("default","PROGRAM_PARTICIPANTS"),

    ]
);?>


<?php $gridColumns = [
    ['class' => yii\grid\SerialColumn::class],
    [
            'attribute' => 'prisoner.person.fio',
        'value' => function($model){return $model->prisoner->getFullTitle(true);}
    ],

    ]
?>
<?php
foreach($program->getProgramVisits()->distinctDates() as $dateValue){
    $gridColumns[] = [
            'header' => $dateValue,
        'content' => function($model)use($dateValue){
            $programVisit = \vova07\plans\models\ProgramVisit::findOne([
                'program_prisoner_id'=>$model->primaryKey,
                'date_visit' => $dateValue
            ]);
            if ($programVisit){
                return Html::tag('span',$programVisit->getStatus(),['class'=>'label label-' . $programVisit->resolveStatusStyle()]);
            } else {
                return Html::tag('div',
                    Html::dropDownList('mark',null,
                        \vova07\plans\models\ProgramVisit::getStatusesForCombo(),[
                                'onChange'=>'PARTICIPANTS_GRID.onMarkSelect;',
                                'prompt'=>Module::t('programs','SELECT_VISIT_STATUS')
                        ]),
                        [
                           'data-model' => \yii\helpers\Json::encode([
                               //'program_id' => $model->program_id,
                               //'prisoner_id' => $model->prisoner_id,
                               'program_prisoner_id' => $model->primaryKey,
                               'date_visit' => $dateValue

                           ])
                        ]

                    );
            }
            }

    ];
}
?>
<?php $gridColumns[] = [

    'header' =>Html::a(Html::tag('i', '' ,['class'=>'fa fa-plus']), "#",['id'=>"newDateColumnButton"]).
        \yii\jui\DatePicker::widget([
            'id' => 'newDateColumnDatePicker',
            'dateFormat' => 'yyyy-MM-dd',
            'options' => [
                'placeholder'=>Module::t('programs','NEW_DATE')
            ]
        ]),
    'content' => function($model){
            return Module::t('programs','{one}_FROM_{from}',[
                    'one'=>$model->getVisits()->presented()->count(),
                'from'=> $model->getVisits()->exceptDoesntPresentedValid()->count()
            ]);

        }
];
 $gridColumns[] = [

    'header' =>Module::t('programs','MARK'),
    'content' => function($model){
        return Html::tag('span', \vova07\plans\models\ProgramPrisoner::getMarkTitleById($model->resolveMark()),
            ['class' => 'label label-' . \vova07\plans\models\ProgramPrisoner::resolveMarkStyleById($model->resolveMark())]);
    }
]
?>
<?php $this->registerCss(
        <<<CSS

         .grid-view {
            overflow-x: scroll;
           }


.grid-view table {
  
  width: 1900px;
  
}
.grid-view table thead tr th:nth-child(1),
.grid-view  table tbody tr td:nth-child(1) {
  background:#eff1f7;
  top: auto;
   left:0;
  position: absolute;
  width: 3em;
}


.grid-view table thead tr th:nth-child(2),
.grid-view  table tbody tr td:nth-child(2) {
  background:#eff1f7;
   
  top: auto;
    left:3em;
  position: absolute;
  width: 16em;
}

.grid-view  table thead tr th:nth-child(3),
.grid-view  table tbody tr td:nth-child(3) {
  padding-left: 23em;
  /*to show second column behind the first*/
}

CSS


)?>
<?php echo \yii\grid\GridView::widget(['id' => 'participants','dataProvider' => $dataProvider,
    'columns' => $gridColumns,
])?>


<?php echo \common\widgets\Alert::widget()?>





<?php $box = \vova07\themes\adminlte2\widgets\Box::end()?>
<?php \yii\widgets\Pjax::end()?>







    <script  id="datePickerTemplate" type="text/template" >
        <?php echo \yii\jui\DatePicker::widget([
            'id' => 'newDateColumnDatePicker',
            'options' => [
                'placeholder'=>Module::t('programs','SELECT_DATE')
            ]
        ])?>
    </script>
    <script  id="ProgramVisitTemplate" type="text/template" >

        <?php
        $programParams = [
            'program_prisoner_id' => $newParticipant->primaryKey,
            'date_visit' => '${date_visit}',
        ];
        $programVisit = new \vova07\plans\models\ProgramVisit($programParams);
        $action = [];
        $action[0] = 'program-visits/create';
        ?>


        <?php  $form = ActiveForm::begin(['action' => $action])?>

        <?php
        echo $form->field($programVisit,'program_prisoner_id')->hiddenInput()->label(false);
        echo $form->field($programVisit,'date_visit')->hiddenInput()->label(false);
        echo $form->field($programVisit,'status_id')->label(false)->dropDownList(\vova07\plans\models\ProgramVisit::getStatusesForCombo(),['prompt'=>Module::t('programs','SELECT_STATUS')]);

        ActiveForm::end();
        ?>


    </script>

    <script  id="ProgramVisitSelectMarkTemplate" type="text/template" >


        <?php echo Html::dropDownList('mark',null,\vova07\plans\models\ProgramVisit::getStatusesForCombo(),['prompt'=>Module::t('programs','SELECT_MARK')])?>


    </script>


<?php
    $programVisitsUrl = \yii\helpers\Url::to(['program-visits/create']);
    $this->registerJs(
<<<JS
    PARTICIPANTS_GRID = {
        selectedDate:null,
        getTemplate:function(name){
            var resultText  = $('#' + name).html() 
            return  resultText;
        },
        showNewRow:function(){
          var rowTemplate = $('#rowTemplate').html();
          $('#participants>tbody').append(rowTemplate);  
        },
        tokenizeTemplate:function(name){
            var template = this.getTemplate(name);
           var tokens = template.split(/\\$\{(.+?)\}/g);
           return tokens;
        },
      
        renderTemplate(name, params){
            itemsTpl = this.tokenizeTemplate(name);
            return itemsTpl.map(
                    function(tok, i) { return (i % 2) ? params[tok] : tok; }
                ).join('');
            },
        
        onNewDateColumn:function(){
                     
        },
        onMarkSelect:function(event){
//            var tdContainer = $(event.delegateTarget);
            var selectEl = $(event.currentTarget);
            var divContainer = selectEl.parent().closest('div');            
            var dataModel  = divContainer.data('model');
            dataModel.status_id = selectEl.val();
            $.ajax({
                type:'POST',
                url:'$programVisitsUrl',
                data: dataModel,
                success: function(data) {
                    divContainer.fadeOut();
                   divContainer.html(data).fadeIn();
                }
            })
            
            
        }
    }
    
   $("body").on('change','#add-participant select ', function(e){
      // alert('test');
       $(this).parents('form').submit();
   }) ;
   $("body").on('click','#newDateColumnButton',function(e){
        PARTICIPANTS_GRID.selectedDate = $('#newDateColumnDatePicker').val();
        $('#newDateColumnDatePicker').val(null)
       if (!PARTICIPANTS_GRID.selectedDate){
           alert("SELECT DATE");
           return;
           
       };
      var currentThEl = $(this).parent('th');
      var currIndex = currentThEl.index();
            
            var td = $('<th></th>').insertBefore(currentThEl);
            newInput = $('<input/>').val(PARTICIPANTS_GRID.selectedDate);
            td.append(newInput);
            $('#participants>table>tbody>tr td:nth-child('+(currIndex+1)+')').each(function(){
                
                tr = $(this).parent('tr');
                var div = $('<div>').attr('data-model',JSON.stringify({
                    program_prisoner_id: tr.data('key'),
                    date_visit: PARTICIPANTS_GRID.selectedDate
                })).append(PARTICIPANTS_GRID.getTemplate('ProgramVisitSelectMarkTemplate'));
                var td = $('<td>').insertBefore($(this)).append(div);
                          
            });

       
   });
    $('body  ').on('change','#participants>table>tbody>tr select',PARTICIPANTS_GRID.onMarkSelect);
                
   
    

JS
)
?>

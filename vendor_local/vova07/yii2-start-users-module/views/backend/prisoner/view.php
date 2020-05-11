<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
use \vova07\events\models\backend\EventParticipantSearch;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\users\models\Prisoner
 */
$this->title = Module::t("default","PRISONER");
$this->params['subtitle'] = Module::t('default',"DETAIL_INFORMATION");
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
?>

<?php $this->registerCss(
        <<<CSS
    #w2 td img{
        width:200px;
        
    }
    #w1 li >a> span{
        margin-left:5px;        
    }
      
CSS

)?>
<?php
$balanceRemain  = $model->getBalances()->debit()->sum('amount') - $model->getBalances()->credit()->sum('amount');
  \yii\bootstrap\NavBar::begin();
  echo \yii\bootstrap\Nav::widget([
      'encodeLabels' => false,
      'items' => [

          [
                  'label' => Module::t('default','PLAN_INDIVIDUAL_DE_EXECUTAREA_PEDEPSEI') .
                      Html::tag('span',
                          Html::tag('span',$model->getRequirements()->count(), ['class' => "label label-primary pull-right"] ).
                          Html::tag('span',$model->getPrisonerPrograms()->count(), ['class' => "label label-primary pull-right"] ),
                          ['class' => 'pull-right-container']),
              'url' => ['/plans/default/index','prisoner_id'=>$model->primaryKey]
          ],
          ['label' =>  Module::t('default','ACTIVITĂȚI_OPȚIONALE').
              Html::tag('span',
                  Html::tag('span',$model->getEvents()->count(), ['class' => "label label-primary pull-right"] ),
                  ['class' => 'pull-right-container']),
              'url' => ['/events/prisoner-events/index', 'prisoner_id'=> $model->primaryKey]],
          [
              'label' => Module::t('default','DOCUMENTS_IDENTIFICATION') .
                  Html::tag('span',
                      Html::tag('span',$model->person->getDocuments()->count(), ['class' => "label label-primary pull-right"] ),
                      ['class' => 'pull-right-container']),

              'url' => ['/documents/default', 'DocumentSearch[person_id]'=>$model->primaryKey],

          ],
          [
              'label' => Module::t('default','PRISONER_LOCATION_JOURNAL') .
                  Html::tag('span',
                      Html::tag('span',$model->getLocationJournal()->count(), ['class' => "label label-primary pull-right"] ),
                      ['class' => 'pull-right-container']),
              'url' => ['/users/prisoners-journal/index', 'PrisonerLocationJournalSearch[prisoner_id]'=>$model->primaryKey],

          ],
          [
              'label' => Module::t('default','PRISONER_BALANCE') .
                  Html::tag('span',
                      Html::tag('span',
                          Yii::$app->formatter->asDecimal($balanceRemain,2)
                          , ['class' => "label " . (($balanceRemain<0)?'label-danger':'label-success'). " pull-right"] ),
                      ['class' => 'pull-right-container']),

              'url' => ['/finances/default/view', 'id'=>$model->primaryKey],

          ],
          [
              'label' => Module::t('default','PRISONER_ELECTRICITY') .
                            Html::tag('span',
                                Html::tag('span',$model->getDevices()->count(), ['class' => "label label-primary pull-right"] ),
                                ['class' => 'pull-right-container']),


              'url' => ['/electricity/devices/index', 'DeviceSearch[prisoner_id]'=>$model->primaryKey],

          ],

          [
              'label' => Module::t('default','PRISONER_COMMITIES') .
                  Html::tag('span',
                      Html::tag('span',$model->getCommities()->count(), ['class' => "label label-primary pull-right"] ),
                      ['class' => 'pull-right-container']),
              'url' => ['/tasks/committee'],

          ],

          [
              'label' => Module::t('default','PRISONER_JOBS') .
                  Html::tag('span',
                      Html::tag('span',$model->getJobs()->count(), ['class' => "label label-primary pull-right"] ),

                      ['class' => 'pull-right-container']),


              'url' => ['/jobs/general-list/index', 'JobsGeneralListViewSearch[prisoner_id]'=>$model->primaryKey],

          ],

          [
              'label' => Module::t('default','PRISONER_SECURITY') .
                  Html::tag('span',
                      Html::tag('span',$model->getPrisonerSecurity()->count(), ['class' => "label label-primary pull-right"] ),
                      ['class' => 'pull-right-container']),


              'url' => ['/prisons/prisoner-security/index', 'PrisonerSecurity251Search[prisoner_id]'=>$model->primaryKey, 'PrisonerSecurity248Search[prisoner_id]'=>$model->primaryKey],

          ],

          [
              'label' => Module::t('default','PRISONER_CONCEPTS') .
                  Html::tag('span',
                      Html::tag('span',$model->getConceptParticipants()->count(), ['class' => "label label-primary pull-right"] ),
                      ['class' => 'pull-right-container']
                  ),


              'url' => ['/concepts/participants/index', 'ConceptParticipantSearch[prisoner_id]'=>$model->primaryKey],

          ],
          [
              'label' => Module::t('default','PRISONER_PENALTIES') .
                  Html::tag('span',
                      Html::tag('span',$model->getPenalties()->count(), ['class' => "label label-primary pull-right"] ),
                      ['class' => 'pull-right-container']
                  ),


              'url' => ['/prisons/penalties/index', 'PenaltySearch[prisoner_id]'=>$model->primaryKey],

          ],

      ],
      'options' => ['class' => 'navbar-nav'],
  ]);
  \yii\bootstrap\NavBar::end();



?>

<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{update}{delete}'
    ]
);?>

<?php $box->beginBody();?>


<?php echo \yii\helpers\Html::a('prev',\yii\helpers\Url::current(['id' => $prevId]));?> |
<?php echo \yii\helpers\Html::a('next',\yii\helpers\Url::current(['id' => $nextId]));?>

<?php echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'id' => 'w2',
    'attributes' => [
            [
              'attribute' => 'person.photo_url',
                'format' => 'image',
            ],

        'status',
        'person.fio',
        'person.birth_year',
        'person.address',
        'article',
        'termStartJui',
        'termFinishJui',
        'termUdoJui',
        'prison.company.title',
        'sector.title',
        'cell.number',
        'prisonerSecurity.type'


    ]
])?>





<?php  $box->endBody()?>

<?php $box->beginFooter();?>

<?php $box->endFooter();?>

<?php \vova07\themes\adminlte2\widgets\Box::end()?>

<?php
/* echo  \kartik\detail\DetailView::widget([
    'enableEditMode' => false,
    'panel' => [
        //'heading' => $model->person->fio,
    ],
    'model' => $model,


    'attributes' => [

        [
            'columns' => [
                [
                        'format' => 'html',
                    'label' => false,
                    'value' => ($model->person)?($model->person->fio .' ' .$model->person->birth_year . '<br/> ' . $model->person->address):''
                ],
                [
                    'label' => false,
                    'value' => $model->person->photo_url,
                    'format' => 'image'
                ],

            ],
        ],
        [

            'columns' => [
                'status',
                'article',
            ]

        ],

        [
            'columns' => [
                'termStartJui',
                'termFinishJui',
                'termUdoJui',
            ]
        ],

        [
            'columns' => [

                [
                    'label' => $model->getAttributeLabel('prison.company.title'),
                    'value' => $model->prison->company->title
                ],
                [
                    'label' => $model->getAttributeLabel('sector.title'),
                    'value' => ($model->sector)?$model->sector->title:'',
                ],
                [
                    'label' => $model->getAttributeLabel('cell.number'),
                    'value' => ($model->cell)?$model->cell->number:''
                ],

            ]
        ],







    ],

]);
*/?>


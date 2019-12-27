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
<?php

  \yii\bootstrap\NavBar::begin();
  echo \yii\bootstrap\Nav::widget([
      'items' => [
          [
                  'label' => Module::t('default','PLAN_INDIVIDUAL_DE_EXECUTAREA_PEDEPSEI'),
              'url' => ['/plans/default/index','prisoner_id'=>$model->primaryKey]
          ],
          ['label' =>  Module::t('default','ACTIVITĂȚI_OPȚIONALE'),
              'url' => ['/events/prisoner-events/index', 'prisoner_id'=> $model->primaryKey]],
          [
              'label' => Module::t('default','DOCUMENTS_IDENTIFICATION'),
              'url' => ['/documents/default', 'DocumentSearch[person_id]'=>$model->primaryKey],

          ]
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
        'cell.number'

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


<?php
use kartik\form\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use vova07\themes\adminlte2\widgets\Box;
use vova07\prisons\models\OfficerPost;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\prisons\models\backend\PostSearch
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Module::t("default","OFFICER_POSTS");
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



    ]

);?>
<?php //\yii\widgets\Pjax::begin()?>
<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'pjax' => true,
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        [
          'attribute' =>  'officer.person.fio',
          'format' => 'html',

          'content' => function($model){
              /**
               * @var $model \vova07\prisons\models\OfficerPostView
               */


              ob_start();
              $paramUrl  = ['/users/officers/delete', 'id' => $model->officer->primaryKey];
              $form = ActiveForm::begin([
                  'action' => $paramUrl,
                  'method' => 'post',
                  'type' => 'inline'
              ]);
              echo  Html::submitButton('', ['class' => 'fa fa-minus']);

              ActiveForm::end();
              $deleteForm = ob_get_contents();
              ob_end_clean();

                return $deleteForm . Html::a(ArrayHelper::getValue($model,'officer.person.fio'),
                    ['/users/officers/view',
                        'id' => $model->officer->primaryKey,

                    ]);
            },
             'group' => true,
        'groupedRow' => true,
      //  'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
      //  'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class

            'groupFooter' => function ($model){
                $officerPostNew = new OfficerPost($model->getAttributes(['officer_id', 'company_id']));

                ob_start();
                $form = ActiveForm::begin([
                    'action' => ['create'],
                    'method' => 'post',
                    'type' => 'inline'
                ]);
                echo $form->field($officerPostNew, 'officer_id')->hiddenInput();
                echo $form->field($officerPostNew, 'company_id')->hiddenInput();
                echo  Html::submitButton('', ['class' => 'fa fa-plus']);

                ActiveForm::end();
                $newForm = ob_get_contents();
                ob_end_clean();

    return [
    //'mergeColumns' => [[2,3]], // columns to merge in summary

    'content' => [             // content to show in each summary cell

        // 2 => GridView::F_SUM,
        //   3 => GridView::F_SUM,
        3 => $newForm,
        //4 => GridView::F_SUM,

    ],
    'contentFormats' => [      // content reformatting for each summary cell
        //  2 => ['format' => 'number', 'decimals' => 2],//   5 => ['format' => 'number', 'decimals' => 0],
        //    3 => ['format' => 'number', 'decimals' => 2],
        //3 => ['format' => 'number', 'decimals' => 2],
        //4 => ['format' => 'number', 'decimals' => 2]
    ],
    'contentOptions' => [      // content html attributes for each summary cell
       // 2 => ['style' => 'text-align:right'],
        //3 => ['style' => 'text-align:center'],

    ],
    // html attributes for group summary row
    'options' => ['class' => 'info table-info','style' => 'font-weight:bold;']
];},

        ],
        'officerPost.company.title',
        'officerPost.division.title',
        'officerPost.postDict.title',
        'officerPost.benefitClass.title',
        [
            'class' => \kartik\grid\BooleanColumn::class,
            'attribute' => 'officerPost.isMain',
        ],

        [
            'class' => \kartik\grid\BooleanColumn::class,
            'attribute' => 'officerPost.full_time',
        ],
        'officerPost.title',


    ]
])?>

<?=Html::a('',['/users/officers/create-lite'],['class' => 'fa fa-plus'])?>
<?php //\yii\widgets\Pjax::end()?>
<?php Box::end()?>



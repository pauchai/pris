<?php
use kartik\form\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use vova07\themes\adminlte2\widgets\Box;
use vova07\prisons\models\OfficerPost;
use vova07\base\components\widgets\Modal;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use vova07\prisons\models\Rank;
use kartik\widgets\Select2;

//use yii\bootstrap\Modal;
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
<?=Html::a(Module::t('label', 'CREATE OFFICER'), ['/prisons/officer-posts/officer-create'], ['class' => 'btn-officer-new btn btn-success']) ?>

<?php $form = ActiveForm::begin(['method'=>'get' ])?>
<?php echo $form->field($searchModel,'category_id')
    //->dropDownList(Rank::getCategoriesForCombo(),['prompt' => '-']);
    ->dropDownList( Rank::getCategoriesForCombo(), [
        'prompt' => '-'
    ]);
?>
<?php $this->registerJs(<<<JS
    $("#officerpostviewsearch-category_id").on('change', function(){ $('#w0').submit();})

JS
)
?>

<?php ActiveForm::end()?>

<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'pjax' => true,
    'options' => ['id' => 'grid_officer_posts'],
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],

        [
          'attribute' =>  'officer.person.fio',



             'group' => true,
        //'groupedRow' => true,
        'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
        'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class

            'groupFooter' => function ($model){

/*

                $modalContent = ModalAjax::widget([
                    'id' => 'create_officer_post' . $model->officer->company_id,
                    'header' => 'CREATE POST',
                    'toggleButton' => [
                        'label' => 'CREATE POST',
                        'class' => 'btn btn-primary pull-right'
                    ],
                    'url' => Url::to(['/prisons/officer-posts/create', 'company_id' => $model->officer->company_id, 'officer_id' => $model->officer_id]), // Ajax view with form to load
                    'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
                    'size' => ModalAjax::SIZE_SMALL,
                    'options' => ['class' => 'header-primary'],
                    'autoClose' => true,
                    'pjaxContainer' => '#grid_officer_posts-pjax',

                ]);*/
                $modalContent = Html::a(Module::t('labels','CREATE POST'), ['/prisons/officer-posts/create', 'company_id' => $model->officer->company_id, 'officer_id' => $model->officer_id],
                    ['class' => "btn btn-default btn_post_new btn-xs"]);
                $deleteButton = Html::a(Module::t('labels', 'DELETE OFFICER'),['/users/officers/delete' , 'id' => $model->officer_id,
                    'class' => 'btn btn-default btn-xs'],
                    [
                 'title' => "Delete officer",
                  'aria-label'=> "Delete officer",
                  'data-pjax' => '1',
                   'data-method' => "post",
                   'data-confirm' =>"Are you sure to delete this item?"
                ]);
                $editOfficerButton = Html::a(Module::t('labels', 'EDIT_OFFICER'), ['/users/officers/update', 'id' => $model->officer_id],
                    ['class' => "btn btn-default  btn-xs fa fa-edit"]);

    return [
    //'mergeColumns' => [[2,3]], // columns to merge in summary

    'content' => [             // content to show in each summary cell

         2 => $deleteButton,
        //   3 => GridView::F_SUM,
        3 => $modalContent,
        9 => $editOfficerButton,

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

       // 'officerPost.company.title',
        'officerPost.division.title',
        'post.title',
        [
                'header' => Module::t('labels', 'OFFICER_POST_TITLE_LABEL'),
                'attribute' => 'officerPost.benefitClass.title'
        ],

        [
            'class' => \kartik\grid\BooleanColumn::class,
            'attribute' => 'officerPost.isMain',
        ],

        [
            'class' => \kartik\grid\BooleanColumn::class,
            'attribute' => 'officerPost.full_time',
        ],
        [
            'class' => \kartik\grid\BooleanColumn::class,
            'attribute' => 'officer.member_labor_union',
        ],
        [
            'class' => \kartik\grid\BooleanColumn::class,
            'attribute' => 'officer.has_education',
        ],
        //'officerPost.title',
        //'base_rate',
        [
                'header' => '',
            'class' => \kartik\grid\ActionColumn::class,
            'template' => "{update_post}{update}{delete}",
            'buttons' => [
                'update_post' => function($url, $model){
                    $name = 'update_post';
                    $title = 'update_post';

                    //  $opts = "{$name}Options";
                    $options = ['title' => $title, 'aria-label' => $title, 'data-pjax' => '0'];

                    return \yii\bootstrap\Html::a($name,
                        \yii\helpers\Url::to(['/prisons/posts/update',  'company_id' => $model->company_id , 'division_id' => $model->division_id, 'postdict_id' => $model->postdict_id]),
                        $options
                    ) ;
                }
            ],
            'visibleButtons' => [
              'update_post' =>      function($model){return !is_null($model->officerPost);},
                'update' =>      function($model){return !is_null($model->officerPost);},
                'delete' =>      function($model){return !is_null($model->officerPost);},
            ]

        ]

    ],

])?>

<?=Html::a('CREATE OFFICER', ['/prisons/officer-posts/officer-create'], ['class' => 'btn-officer-new btn btn-success']) ?>

<?php
echo ModalAjax::widget([
    'id' => 'create-officer',
    'header' => 'CREATE OFFICER',
    'selector' => 'a.btn-officer-new', // all buttons in grid view with href attribute

 //   'toggleButton' => [
 //       'label' => 'CREATE OFFICER',
 //       'class' => 'btn btn-primary pull-right'
 //   ],
  //  'url' => Url::to(['/prisons/officer-posts/officer-create']), // Ajax view with form to load
    'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
    'size' => ModalAjax::SIZE_LARGE,
    'options' => ['class' => 'header-primary'],
    'autoClose' => true,
    'pjaxContainer' => '#grid_officer_posts-pjax',

]);;?>


<?php
echo ModalAjax::widget([
    'id' => 'create_button',
    'selector' => 'a.btn_post_new', // all buttons in grid view with href attribute

    'header' => 'Create Company',

    'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
    'size' => ModalAjax::SIZE_LARGE,
    'options' => ['class' => 'header-primary'],
    'autoClose' => true,
    'pjaxContainer' => '#grid_officer_posts-pjax',

]);;?>


<div class="list-items">

</div>

<?php Box::end()?>

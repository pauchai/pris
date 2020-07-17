<?php
use kartik\form\ActiveForm;
use yii\bootstrap\Html;
use vova07\prisons\Module;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use vova07\themes\adminlte2\widgets\Box;
use vova07\prisons\models\OfficerPost;
use vova07\base\components\widgets\Modal;
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
<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'pjax' => true,
    'options' => ['id' => 'grid_officer_posts'],
    'columns' => [
        ['class' => yii\grid\SerialColumn::class],
        [
          'attribute' =>  'officer.person.fio',



             'group' => true,
        'groupedRow' => true,
        'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
        'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class

            'groupFooter' => function ($model){

                ob_start();
              $modal =  Modal::begin([

    'options' => [ 'id' => 'modal_create_post' . $model->officer_id],
    'clientEvents' => [
        'hidden.bs.modal' => 'function(e){$("#grid_officer_posts").yiiGridView("applyFilter");}',
        //'loaded.bs.modal' => <<<function(e){$("#modal_create_officer").find("form").);}'
    ],
    'toggleButton' => ['tag' => 'a',
        'title' => 'Create Post',
        'href' =>  \yii\helpers\Url::to(['/prisons/officer-posts/create', 'company_id' => $model->officer->company_id, 'officer_id' => $model->officer_id]),
        'data-target' => '#modal_create_post' . $model->officer_id,
        'label' => 'CREATE POST'],
]);

 Modal::end();

                $modalContent = ob_get_contents();
                ob_end_clean();

                $deleteButton = Html::a('DELETE OFFICER',['/users/officers/delete' , 'id' => $model->officer_id,
                    'class' => 'fa fa-trash'],
                    [
                 'title' => "Delete officer",
                  'aria-label'=> "Delete officer",
                  'data-pjax' => "0",
                   'data-method' => "post",
                   'data-confirm' =>"Are you sure to delete this item?"
                ]);

    return [
    //'mergeColumns' => [[2,3]], // columns to merge in summary

    'content' => [             // content to show in each summary cell

         2 => $deleteButton,
        //   3 => GridView::F_SUM,
        3 => $modalContent,
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
        //'officerPost.title',
        [
            'class' => \kartik\grid\ActionColumn::class,
            'template' => "{update}{delete}"

        ]

    ],

])?>
<?php $modal =  Modal::begin([

    'options' => [ 'id' => 'modal_create_officer'],
    'clientEvents' => [
        'hidden.bs.modal' => 'function(e){$("#grid_officer_posts").yiiGridView("applyFilter");}',
        //'loaded.bs.modal' => <<<function(e){$("#modal_create_officer").find("form").);}'
    ],
    'toggleButton' => ['tag' => 'a',
        'title' => 'Create Officer',
        'href' =>  \yii\helpers\Url::to(['/prisons/officer-posts/officer-create']),
        'data-target' => '#modal_create_officer',
        'label' => 'CREATE OFFICER'],
]);
?>
<?php  Modal::end();?>







<?php Box::end()?>



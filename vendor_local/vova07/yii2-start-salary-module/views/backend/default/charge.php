<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $salaryIssue SalaryIssue
 */
use vova07\salary\Module;
use kartik\grid\GridView;
use vova07\salary\models\SalaryIssue;
use vova07\users\models\OfficerView;
use vova07\users\models\Person;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

//$this->title = Module::t("default","EVENTS_TITLE");
$this->params['subtitle'] = Module::t("default","SALARY_CHARGES");
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        //      'url' => ['index'],
    ],
    // $this->params['subtitle']
];
?>

<?=$this->render('_issueView',['model' => $salaryIssue])?>
<?php $syncForm = ActiveForm::begin([
    'action' => ['create-tabular'],
    'layout' => "inline",

])?>


<?php echo $syncForm->field($salaryIssue,'year', ['inputOptions' => ['id' => 'yearr']])->hiddenInput()->label(false) ?>
<?php echo $syncForm->field($salaryIssue,'month_no',['inputOptions' => ['id' => 'monthh']])->hiddenInput()->label(false) ?>
<?php             echo \yii\helpers\Html::submitButton(
    Module::t('default', 'SYNC_TABULAR_FOR_MONTH'),

    ['class' => 'btn btn-info']
);
?>
<?php ActiveForm::end()?>

<?php if (!$salaryIssue->isNewRecord): ?>

    <?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
        [
             'title' => $this->params['subtitle'],
            // 'buttonsTemplate' => '{create}'
        ]

    );?>

    <?php
    $columns =
        [
            // ['class' => yii\grid\SerialColumn::class],
            [
                //'header' => '',
                'class' => \kartik\grid\ActionColumn::class,
                'template' => "{delete}  {update}"
            ],

            [
                'attribute' => 'officer.rank.category',
                'group' => true,
                'groupedRow' => true,
                'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
                'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class


            ],
            [
                'class' => \kartik\grid\DataColumn::class,
                'attribute' => 'personView.fio',

                'format' => 'html',
                'content' =>  function($model,$index,$key){
                    $content =  Html::a(Html::tag('i', '', ['class' => 'fa fa-plus']), ['/salary/default/create',  'Salary' => ['company_id' => $model->company_id, 'officer_id' => $model->officer_id, 'year' => $model->year, 'month_no' => $model->month_no]],
                            ['class' => "btn btn-default btn_post_new btn-xs"]) . ' ';

                    $content .= \yii\bootstrap\Html::a(
                        $model->officer->person->fio ,
                        ['/users/officers/view',
                            'id' => $model->officer_id,
                        ]);
                    ;

                    return $content;

                },

                'group' => true,
                //'groupedRow' => true,
                'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
                'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
//        'groupHeader' => function ($model){
//
//            /*
//
//                            $modalContent = ModalAjax::widget([
//                                'id' => 'create_officer_post' . $model->officer->company_id,
//                                'header' => 'CREATE POST',
//                                'toggleButton' => [
//                                    'label' => 'CREATE POST',
//                                    'class' => 'btn btn-primary pull-right'
//                                ],
//                                'url' => Url::to(['/prisons/officer-posts/create', 'company_id' => $model->officer->company_id, 'officer_id' => $model->officer_id]), // Ajax view with form to load
//                                'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
//                                'size' => ModalAjax::SIZE_SMALL,
//                                'options' => ['class' => 'header-primary'],
//                                'autoClose' => true,
//                                'pjaxContainer' => '#grid_officer_posts-pjax',
//
//                            ]);*/
//            $modalContent = Html::a(Html::tag('i', '', ['class' => 'fa fa-plus']), ['/salary/default/create',  'Salary' => ['company_id' => $model->company_id, 'officer_id' => $model->officer_id, 'year' => $model->year, 'month_no' => $model->month_no]],
//                ['class' => "btn btn-default btn_post_new btn-xs"]);
//
//
//            return [
//                'mergeColumns' => [[3,4]], // columns to merge in summary
//
//                'content' => [             // content to show in each summary cell
//
//
//                    3 => ArrayHelper::getValue($model,'officer.person.fio'),
//                    5 => $modalContent,
//                    20 => GridView::F_SUM,
//
//
//                ],
//                'contentFormats' => [      // content reformatting for each summary cell
//                    //  2 => ['format' => 'number', 'decimals' => 2],//   5 => ['format' => 'number', 'decimals' => 0],
//                    //    3 => ['format' => 'number', 'decimals' => 2],
//                    //3 => ['format' => 'number', 'decimals' => 2],
//                    //4 => ['format' => 'number', 'decimals' => 2]
//                    20 => ['format'=>'number', 'decimals'=>2, 'decPoint'=>'.', 'thousandSep'=>',']
//                ],
//                'contentOptions' => [      // content html attributes for each summary cell
//                    // 2 => ['style' => 'text-align:right'],
//                    //3 => ['style' => 'text-align:center'],
//
//                ],
//                // html attributes for group summary row
//                'options' => ['class' => 'info table-info','style' => 'font-weight:bold;']
//            ];},
            ],
            [
                'attribute' => 'postDict.title',
                'format' => 'html',
                'content' =>  function($model,$index,$key){
                    $content = ArrayHelper::getValue($model,'postDict.title')
                        . ' ' . (ArrayHelper::getValue($model, "full_time")?'полная':'полставки');


                    return $content;
                },
            ],
//        [
//            'class' => kartik\grid\EditableColumn::class,
//            'attribute' => 'full_time',
//            'hAlign' => GridView::ALIGN_CENTER,
//            'refreshGrid' => true,
//            'editableOptions' =>  function ($model, $key, $index){
//
//                return [
//
//                    'formOptions' => [
//                        'action' => ['change-salary-calculated']
//                    ],
//                ];
//            },
//        ],

//        [
//            'class' => kartik\grid\EditableColumn::class,
//            'attribute' => 'base_rate',
//            'hAlign' => GridView::ALIGN_CENTER,
//            'refreshGrid' => true,
//            'editableOptions' =>  function ($model, $key, $index){
//
//                return [
//
//                    'formOptions' => [
//                        'action' => ['change-salary-calculated']
//                    ],
//                ];
//            },
//        ],


        [
            'class' => kartik\grid\EditableColumn::class,
            'attribute' => 'work_days',
            'hAlign' => GridView::ALIGN_CENTER,

           'refreshGrid' => false,
            'editableOptions' =>  function ($model, $key, $index){
        $funcString = <<<JSS

JSS;


                return [
                    'valueIfNull' => 0,
                    'asPopover' => false,
                    'formOptions' => [
                        'action' => ['change-salary-column']
                    ],
                    'pluginEvents' => [
                        "editableSuccess"=>"function(event, val, form, data) {
                             
                            $.each(data.attributes, (index, value) => {
                                if (value != null) {
                                var \$element = $('#' + 'salary-$index-' + index + '-cont');
                               \$element.find('.kv-editable-value').html(value);
                                 \$element.find('.kv-editable-input').val(value);
                                }
                                
                                
                            }); 
                           
                            }",

                    ],
                ];
            },
        ],








    ];
    $attributes = ['amount_rate', 'amount_rank_rate', 'amount_conditions', 'amount_advance',
        //'amount_optional',
        'amount_diff_sallary', 'amount_additional', 'amount_maleficence', 'amount_vacation', 'amount_sick_list',  'amount_bonus', 'total'];
    foreach ($attributes as $attribute){
        $columns[] = [
            'class' => kartik\grid\EditableColumn::class,
            'hAlign' => GridView::ALIGN_CENTER,

            'attribute' => $attribute,
            'refreshGrid' => false,
            'editableOptions' =>  function ($model, $key, $index){
                $funcString = <<<JSS

JSS;


                return [
                    'valueIfNull' => 0,
                    'asPopover' => false,
                    'formOptions' => [
                        'action' => ['change-salary-column']
                    ],
                    'pluginEvents' => [
                        "editableSuccess"=>"function(event, val, form, data) {
                             
                            $.each(data.attributes, (index, value) => {
                                if (value != null) {
                                var \$element = $('#' + 'salary-$index-' + index + '-cont');
                               \$element.find('.kv-editable-value').html(value);
                                 \$element.find('.kv-editable-input').val(value);
                                }
                                
                                
                            }); 
                           
                            }",

                    ],
                ];
            },
            'content' => function($model,$key, $index, $column)
            {
                $attribute =  $column->attribute;

                return round(\yii\helpers\ArrayHelper::getValue($model, $attribute, 0),2);
            }
        ];
    }


//    $columns[] = [
//
//        'class' =>  \kartik\grid\CheckboxColumn::class,
//
//    ];



    ?>

    <?php echo GridView::widget(['dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        // 'formatter' => ['class' => \yii\i18n\Formatter::class,'nullDisplay' => ''],
        //'emptyCell' => '',

        // 'floatHeader' => true,
        'pjax' => true,
        'columns' => $columns


    ])?>

    <?php  \vova07\themes\adminlte2\widgets\Box::end()?>


<?php endif?>
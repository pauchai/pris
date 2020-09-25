<?php

use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Html;
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
        'attribute' => 'officer.person.fio',
        'class' => \kartik\grid\DataColumn::class,
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
            'refreshGrid' => true,
            'editableOptions' =>  function ($model, $key, $index){

                return [

                    'formOptions' => [
                        'action' => ['change-salary-calculated']
                    ],
                ];
            },
        ],


//        [
//            'class' => kartik\grid\EditableColumn::class,
//            'attribute' => 'rank_rate',
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
        'attribute' => 'amount_rate',
        'hAlign' => GridView::ALIGN_CENTER,

        'content' => function($model,$key, $index, $column)
        {
            $attribute =  $column->attribute;
            return round($model->$attribute,2);
        }
    ],
    [
        'attribute' => 'amount_rank_rate',
        'hAlign' => GridView::ALIGN_CENTER,

        'content' => function($model,$key, $index, $column)
        {
            $attribute =  $column->attribute;
            return round($model->$attribute,2);
        }
    ],

   ];
    $attributes = ['amount_conditions', 'amount_advance',
        //'amount_optional',
        'amount_diff_sallary', 'amount_additional', 'amount_maleficence', 'amount_vacation', 'amount_sick_list',  'amount_bonus'];
    foreach ($attributes as $attribute){
        $columns[] = [
            'class' => kartik\grid\EditableColumn::class,
            'hAlign' => GridView::ALIGN_CENTER,

            'attribute' => $attribute,
            'refreshGrid' => true,
            'editableOptions' =>  function ($model, $key, $index){
                return [
                    'valueIfNull' => '0',
                    'formOptions' => [
                        'action' => ['change-salary-column']
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

    $columns[] = [
        'attribute' => 'total',

    ];
//    $columns[] = [
//
//        'class' =>  \kartik\grid\CheckboxColumn::class,
//
//    ];




return $columns;
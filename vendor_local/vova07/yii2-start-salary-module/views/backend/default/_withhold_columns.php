<?php
use kartik\grid\GridView;

$columns =
    [
    ['class' => yii\grid\SerialColumn::class],
        [
            'attribute' => 'officer.rank.category',
            'group' => true,
            'groupedRow' => true,
            'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
            'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class


        ],
    [
        'attribute' => 'officer.person.fio',
        'format' => 'html',
        'content' =>  function($model,$index,$key){
            $content = \yii\bootstrap\Html::a(
                $model->officer->person->fio ,
                ['/users/officers/view',
                    'id' => $model->officer_id,
                ]);
            ;

            return $content;
        },


    ],


   ];
    $attributes = ['amount_pension', 'amount_income_tax', 'amount_execution_list',
        'amount_labor_union', 'amount_sick_list'];
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
                        'action' => ['change-withhold-column']
                    ],
                ];
            },
            'content' => function($model,$key, $index, $column)
            {
                $attribute =  $column->attribute;
                return round($model->$attribute,2);
            }
        ];
    }
$columns[] = [
    'attribute' => 'total',
    'hAlign' => GridView::ALIGN_CENTER,

    'content' => function($model,$key, $index, $column)
    {
        $attribute =  $column->attribute;
        return round($model->$attribute,2);
    }
];
    $columns[] = [
      'header' => "BANK",
        'hAlign' => GridView::ALIGN_CENTER,

        'content' => function($model) {
         return $model->amount_card = $model->getSalaries()->totalAmount() - $model->total;
      }
    ];
   /* $columns[] =
        [

            'class' => \yii\grid\ActionColumn::class,
            'template' => '{update}{delete}',
            'visible' => !$this->context->isPrintVersion,
        ];*/


    $columns[] = [
      'class' => \kartik\grid\ActionColumn::class,
        'template' => '{delete}',
        'buttons'   => [
            'delete' =>  function ($url, $model) {
                $name = 'delete';
                $title = 'delete';

              //  $opts = "{$name}Options";
                $options = ['title' => $title, 'aria-label' => $title, 'data-pjax' => '0'];
                $item = isset($this->grid->itemLabelSingle) ? $this->grid->itemLabelSingle : Yii::t('kvgrid', 'item');

                $options['data-method'] = 'post';
                $options['data-confirm'] = Yii::t('kvgrid', 'Are you sure to delete this {item}?', ['item' => $item]);


               return \yii\bootstrap\Html::a('delete',
                   \yii\helpers\Url::to(['/salary/with-hold/delete','officer_id' => $model->officer_id , 'year' => $model->year, 'month_no' => $model->month_no]),
               $options
               );
            }
        ]
    ];

$columns[] = [

    'class' =>  \kartik\grid\CheckboxColumn::class,

];


return $columns;
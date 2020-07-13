<?php
$columns =
    [
    ['class' => yii\grid\SerialColumn::class],

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
            'attribute' => $attribute,
            'refreshGrid' => true,
            'editableOptions' =>  function ($model, $key, $index){
                return [

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
    'content' => function($model,$key, $index, $column)
    {
        $attribute =  $column->attribute;
        return round($model->$attribute,2);
    }
];
    $columns[] = [
      'header' => "BANK",
      'content' => function($model) {
         return $model->amount_card = $model->getSalaries()->totalAmount() - $model->total;
      }
    ];




return $columns;
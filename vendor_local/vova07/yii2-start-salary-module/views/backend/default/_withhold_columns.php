<?php
$columns =
    [
    ['class' => yii\grid\SerialColumn::class],

    [
        'attribute' => 'officer.person.fio',
        'format' => 'html',
        'content' =>  function($model,$index,$key){
            $content = \yii\bootstrap\Html::a(
                $model->salary->officer->person->fio ,
                ['/users/officers/view',
                    'id' => $model->salary->officer_id,
                ]);
            ;

            return $content;
        },

        'group' => true,
        'groupedRow' => true,
        'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
        'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class

    ],
        [
            'header' => 'post',
            'format' => 'html',
            'content' =>  function($model,$index,$key){
                $content = $model->salary->postDict->title . ' ' . ($model->salary->officerPost->full_time?'полная':'полставки');


                return $content;
            },
        ],

   ];
    $attributes = ['amount_pension', 'amount_income_tax', 'amount_execution_list',
        'amount_labor_union', 'amount_sick_list', 'amount_card'];
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
$columns[] =  'balance.amount';
$columns[] = 'salary.officer.balance.remain';



return $columns;
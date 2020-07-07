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

        'group' => true,
        'groupedRow' => true,
        'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
        'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class

    ],
    [
        'header' => 'post',
        'format' => 'html',
        'content' =>  function($model,$index,$key){
            $content = $model->postDict->title . ' ' . ($model->officerPost->full_time?'полная':'полставки');


            return $content;
        },
    ],


    [
        'class' => kartik\grid\EditableColumn::class,
        'attribute' => 'work_days',
        'refreshGrid' => true,
        'editableOptions' =>  function ($model, $key, $index){

            return [

                'formOptions' => [
                    'action' => ['change-salary-calculated']
                ],
            ];
        },
    ],
        [
            'attribute' => 'base_rate',
            'content' => function($model,$key, $index, $column)
            {
                $attribute =  $column->attribute;
                return round($model->$attribute,2);
            }
        ],
    [
        'attribute' => 'amount_rate',
        'content' => function($model,$key, $index, $column)
        {
            $attribute =  $column->attribute;
            return round($model->$attribute,2);
        }
    ],
    [
        'attribute' => 'amount_rank_rate',
        'content' => function($model,$key, $index, $column)
        {
            $attribute =  $column->attribute;
            return round($model->$attribute,2);
        }
    ],

   ];
    $attributes = ['amount_conditions', 'amount_advance', 'amount_optional', 'amount_diff_sallary', 'amount_additional', 'amount_maleficence', 'amount_vacation', 'amount_bonus'];
    foreach ($attributes as $attribute){
        $columns[] = [
            'class' => kartik\grid\EditableColumn::class,
            'attribute' => $attribute,
            'refreshGrid' => true,
            'editableOptions' =>  function ($model, $key, $index){
                return [

                    'formOptions' => [
                        'action' => ['change-salary-column']
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
    $columns[] = 'balance.amount';

return $columns;
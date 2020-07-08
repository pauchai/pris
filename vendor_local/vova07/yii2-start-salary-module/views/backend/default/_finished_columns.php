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

        [
            'attribute' => 'salaryBalance.amount',
            'content' => function($model){
                $params = $model->primaryKey;
                $params[0] = '/salary/default/salaries-view';
                return \yii\helpers\Html::a(
                    $model->salaryBalance->amount,
                    $params

                );
            }

        ],
        [
            'attribute' =>  'balance.amount',
            'content' => function($model){
                $params = $model->primaryKey;
                $params[0] = '/salary/default/with-hold-view';
                return \yii\helpers\Html::a(
                    $model->balance->amount,
                    $params,
                    [
                        'class' => ['text-danger']
                    ]

                );
            }

        ],

    ];



    $columns[] = [
        'attribute' => 'officer.balance.remain',
        'content' => function($model){
            return
                \yii\helpers\Html::a(
                    $model->officer->balance->remain,
                    ['/salary/balance/officer-view', 'id' => $model->officer_id]
                );


        }

    ];

return $columns;
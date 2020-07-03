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
            'attribute' => 'balance.amount',
            'content' => function($model){
                return \yii\helpers\Html::a(
                    $model->balance->amount,
                    ['/salary/default/view', 'id' => $model->primaryKey]

                );
            }

        ],
        [
            'attribute' =>  'withHold.balance.amount',
            'content' => function($model){
                return \yii\helpers\Html::a(
                    $model->withHold->balance->amount,
                    ['/salary/default/with-hold-view', 'id' => $model->primaryKey],
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
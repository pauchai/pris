<?php
use \yii\helpers\ArrayHelper;
?>
<?php
    $urlParams = ['print-receipt', 'at' => (new DateTime())->setDate($salaryIssue->year, $salaryIssue->month_no, '01')->format('Y-m-d')] ;
    echo \yii\bootstrap\Html::a('receipt', $urlParams, ['class' => 'btn btn-success fa fa-credit-card']);
    ?>
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
            'attribute' => 'chargesTotal',
            'content' => function($model){
                /**
                 * @var $model \vova07\salary\models\SalaryWithHold
                 */
                $params = $model->primaryKey;
                $params[0] = '/salary/default/salaries-view';
                return \yii\helpers\Html::a(
                    $model->getSalaries()->totalAmount(),
                    $params

                );
            }

        ],
        [
            'attribute' =>  'total',
            'content' => function($model){
                $params = $model->primaryKey;
                $params[0] = '/salary/default/with-hold-view';
                return \yii\helpers\Html::a(
                    $model->total,
                    $params,
                    [
                        'class' => ['text-danger']
                    ]

                );
            }

        ],

        [
          //  'class' => kartik\grid\EditableColumn::class,
            'attribute' => 'amount_card',

        ]

    ];



    $columns[] = [
        'attribute' => 'officer.balance.remain',
        'content' => function($model){
            return
                \yii\helpers\Html::a(
                    ArrayHelper::getValue($model,'officer.balance.remain'),
                    ['/salary/balance/officer-view', 'id' => $model->officer_id]
                );


        }

    ];

return $columns;
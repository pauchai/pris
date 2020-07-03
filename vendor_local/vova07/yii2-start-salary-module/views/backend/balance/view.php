<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
use vova07\themes\adminlte2\widgets\Box;
use vova07\salary\Module;

use kartik\grid\GridView;
use vova07\salary\models\Balance;

$this->title = Module::t("default","SALARY_BALANCE_TITLE");
$this->params['subtitle'] =  '';
$this->params['breadcrumbs'] = [


    [
        'label' => Module::t("default","SALARY_BALANCE_TITLE"),
              'url' => ['index'],
    ],
     $this->params['subtitle']
];
?>
<h3><?=Module::t("default","DETAIL_VIEW") ?> : <i><?=$model->person->fio?> </i></h3>
<?php $box = Box::begin(
    [
        'title' => $this->params['subtitle'],
//        'buttonsTemplate' => '{create}'
    ]

);?>
<?php


$columns = [

    'at',
    'reason',


];

$initRemain = 0;
    $columns[] = [
        'header' => '',
        'content' =>function($model)use (&$initRemain){
            if ($model->amount >= 0){
                $value = $model->amount;
                $initRemain += $value;
                return round($value,2);
            } else {
                return 0;
            }
        }
    ];
    $columns[] = [
        'header' => '',
        'content' =>function($model)use (&$initRemain){
            if ($model->amount < 0){
                $value = $model->amount;
                $initRemain += $value;
                return -round($value,2);
            } else {
                return 0;
            }
        }
    ];
    $columns[] = [
        'header' => 'remain',
        'content' =>function($model)use (&$initRemain){
            $value = $initRemain;
         /*   if ($model->type_id == \vova07\finances\models\Balance::TYPE_DEBIT){
                $initRemain += $model->amount;
            } else {
                $initRemain -= $model->amount;
            }*/
            return round($value,2);
        }
    ]

?>

<?php echo GridView::widget(['dataProvider' => $dataProvider,
//    'pjax'=>true,
    'showFooter' => true,
    'beforeHeader' => [
        [
            'columns' => [
                [],
                [],

                [
                    'content' => \yii\helpers\Html::tag('span',
                        Module::t('labels','DEBIT_LABEL'),

                    [
                        'class' => 'label-success'
                        ]
                    ),
                    'options' => [
                        'colspan' => 1,
                        'style' => 'text-align:center',
                        'class' => 'label-success'
                    ]
                ],
                [
                    'content' => \yii\helpers\Html::tag('span',
                        Module::t('labels','CREDIT_LABEL'),

                        [
                            'class' => 'label-danger'
                        ]
                    ),
                    'options' => [
                        'colspan' => 1,
                        'style' => 'text-align:center',
                        'class' => 'label-danger'
                    ]
                ],


            ]
        ]
    ],
  /*  'afterFooter' => [
        [
            'columns' => [
                [],

                [
                    'content' => "ОСТАТОК: " .  Yii::$app->formatter->asDecimal($model->getBalances()->debit()->sum('amount') - $model->getBalances()->credit()->sum('amount'),2)
                ]
            ]
        ]
    ],*/
    'columns' => $columns
])?>

<?php $box->beginFooter()?>
<h2>Остаток: <?=Yii::$app->formatter->asDecimal($model->getBalances()->sum('amount') ,2)?></h2>
<?php $box->endFooter()?>
<?php  Box::end()?>

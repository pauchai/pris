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
use vova07\finances\Module;
use yii\grid\SerialColumn;
//use yii\grid\GridView;
use kartik\grid\GridView;
use vova07\finances\components\DataColumnWithHeaderAction;
use vova07\finances\models\BalanceCategory;
use \yii\bootstrap\Html;
use vova07\users\models\Prisoner;
use \vova07\finances\components\DataColumnWithButtonAction;

$this->title = Module::t("default","FINANCES_TITLE");
$this->params['subtitle'] =  '';
$this->params['breadcrumbs'] = [


    [
        'label' => Module::t("default","FINANCES_TITLE"),
              'url' => ['index'],
    ],
     $this->params['subtitle']
];
?>
<h3><?=Module::t("default","DETAIL_VIEW") ?> : <i><?=$model->getFullTitle(true)?> </i></h3>
<?php $box = Box::begin(
    [
        'title' => $this->params['subtitle'],
//        'buttonsTemplate' => '{create}'
    ]

);?>
<?php
    $debitCategories = \yii\helpers\ArrayHelper::map(BalanceCategory::findAll(['type_id'=>\vova07\finances\models\Balance::TYPE_DEBIT]),'category_id','short_title');
    $creditCategories = \yii\helpers\ArrayHelper::map(BalanceCategory::findAll(['type_id'=>\vova07\finances\models\Balance::TYPE_CREDIT]),'category_id','short_title');

$columns = [

    'atJui',
    'reason',


];

$initRemain = 0;
    $columns[] = [
        'header' => '',
        'content' =>function($model)use (&$initRemain){
            if ($model->type_id == \vova07\finances\models\Balance::TYPE_DEBIT){
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
            if ($model->type_id == \vova07\finances\models\Balance::TYPE_CREDIT){
                $value = $model->amount;
                $initRemain -= $value;
                return round($value,2);
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
<h2>Остаток: <?=Yii::$app->formatter->asDecimal($model->getBalances()->debit()->sum('amount') - $model->getBalances()->credit()->sum('amount'),2)?></h2>
<?php $box->endFooter()?>
<?php  Box::end()?>

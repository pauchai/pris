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
$this->params['subtitle'] =  $model->fullTitle . '|' . Module::t("default","DETAIL_VIEW") ;
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        //      'url' => ['index'],
    ],
    // $this->params['subtitle']
];
?>

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


];


foreach($debitCategories as $categoryId=>$categoryTitle)
{
    $columns[] = [
        'label' => $categoryTitle,
        'value' => function($model)use($categoryId){
            if ($model->category_id == $categoryId){
                return $model->amount;
            } else {
                return 0;
            }
        }
    ];
}
foreach($creditCategories as $categoryId=>$categoryTitle)
{
    $columns[] = [
        'label' => $categoryTitle,
        'value' => function($model)use($categoryId){
            if ($model->category_id == $categoryId){
                return $model->amount;
            } else {
                return 0;
            }
        }
    ];
}

?>

<?php echo GridView::widget(['dataProvider' => $dataProvider,
//    'pjax'=>true,
    'showFooter' => true,
    'beforeHeader' => [
        [
            'columns' => [
                [],


                [
                    'content' => \yii\helpers\Html::tag('span',
                        Module::t('labels','DEBIT_LABEL'),

                    [
                        'class' => 'label-success'
                        ]
                    ),
                    'options' => [
                        'colspan' => count($debitCategories),
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
                        'colspan' => count($creditCategories),
                        'style' => 'text-align:center',
                        'class' => 'label-danger'
                    ]
                ],

                [
                    'content' => 'TOTAL'
                ]
            ]
        ]
    ],
    'afterFooter' => [
        [
            'columns' => [
                [],


                [
                    'content' => '',
                    'options' => [
                        'colspan' => count($debitCategories),

                    ]
                ],
                [
                    'content' => '',
                    'options' => [
                        'colspan' => count($creditCategories),

                    ]
                ],

                [
                    'content' => $model->getBalances()->debit()->sum('amount') - $model->getBalances()->credit()->sum('amount')
                ]
            ]
        ]
    ],
    'columns' => $columns
])?>


<?php  Box::end()?>
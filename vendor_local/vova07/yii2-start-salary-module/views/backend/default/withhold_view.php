<?php

use vova07\salary\Module;
use yii\grid\GridView;

use yii\helpers\Html;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\salary\models\Salary
 */
$this->title = Module::t("default","SALARY_WITHHOLD_TITLE");
$this->params['subtitle'] = $model->issue->at;
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
];
?>
<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{update}{delete}'
    ]
);?>
<?php
    $attributes =  [
        [
            'attribute' => 'issue.at',
            'format' => 'date'
        ],
        'officer.person.fio',
        'officer.company.title',
        'officer.division.title',

    ];
    $attributeNames = ['amount_pension', 'amount_income_tax', 'amount_execution_list',
        'amount_labor_union','amount_sick_list','total',
        //'balance.amount'
        ];
    foreach ($attributeNames as $attributeName){
        $attributes[] = $attributeName;
    }
?>

<?php echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => $attributes
])?>


<?php \vova07\themes\adminlte2\widgets\Box::end(); ?>


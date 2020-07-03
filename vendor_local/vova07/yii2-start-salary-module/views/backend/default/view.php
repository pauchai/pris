<?php

use vova07\salary\Module;
use yii\grid\GridView;

use yii\helpers\Html;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\salary\models\Salary
 */
$this->title = Module::t("default","SALARY_CHARGE_TITLE");
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
        'issue.at',
        'officer.person.fio',
        'officer.company.title',
        'officer.division.title',
        'post.title',
        'officer.rank.title',
        'work_days'
    ];
    $attributeNames = ['amount_rate','amount_rank_rate','amount_conditions', 'amount_advance',
        'amount_optional','amount_diff_sallary','amount_additional','amount_maleficence', 'amount_vacation',
        'amount_sick_list', 'amount_bonus','total', 'balance.amount'
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


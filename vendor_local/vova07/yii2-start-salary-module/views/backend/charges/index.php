<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
use vova07\salary\Module;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;
use vova07\salary\models\SalaryCharge;
//$this->title = Module::t("default","EVENTS_TITLE");
$this->params['subtitle'] = Module::t("default","SALARY_CHARGES");
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        //      'url' => ['index'],
    ],
    // $this->params['subtitle']
];
?>


<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{create}'
    ]

);?>
<?=$this->render('_search',['model' => $searchModel])?>

<?php $form = \kartik\form\ActiveForm::begin()?>
<?php echo GridView::widget(['dataProvider' => $dataProvider,
    'filterModel' => $searchModel,

    'columns' => [
        ['class' => yii\grid\SerialColumn::class],

        [
            'attribute' => 'person.fio',
            'content' =>  function($model,$index,$key)use($searchModel, $form){
                $content = $model->person->fio;
                $salaryCharge = createOrReturnSalaryChargeForOfficer($model, $searchModel->year, $searchModel->month_no);
                $content .= $form->field($salaryCharge, '[' . $key. ']year')->hiddenInput()->label(false);
                $content .= $form->field($salaryCharge, '[' . $key. ']month_no')->hiddenInput()->label(false);
                return $content;
            }
        ],


        [
            'header' => 'post_id',
            'content' => function($model,$index,$key)use($searchModel, $form){
                $salaryCharge = createOrReturnSalaryChargeForOfficer($model, $searchModel->year, $searchModel->month_no);
                return $form->field($salaryCharge, '[' . $key. ']post_id')->label(false);

            }
        ],
        [
            'header' => 'rank_id',
            'content' => function($model,$index,$key)use($searchModel, $form){
                $salaryCharge = createOrReturnSalaryChargeForOfficer($model, $searchModel->year, $searchModel->month_no);
                return $form->field($salaryCharge, '[' . $key. ']rank_id')->label(false);

            }
        ],
        [
            'header' => 'amount_rate',
            'content' => function($model,$index,$key)use($searchModel, $form){
                $salaryCharge = createOrReturnSalaryChargeForOfficer($model, $searchModel->year, $searchModel->month_no);
                return $form->field($salaryCharge, '[' . $key. ']amount_rate')->label(false);

            }
        ],
        [
        'header' => 'amount_rank_rate',
        'content' => function($model,$index,$key)use($searchModel, $form){
            $salaryCharge = createOrReturnSalaryChargeForOfficer($model, $searchModel->year, $searchModel->month_no);
            return $form->field($salaryCharge, '[' . $key. ']amount_rank_rate')->label(false);

        }
         ],
        [
    'header' => 'amount_conditions',
    'content' => function($model,$index,$key)use($searchModel, $form){
        $salaryCharge = createOrReturnSalaryChargeForOfficer($model, $searchModel->year, $searchModel->month_no);
        return $form->field($salaryCharge, '[' . $key. ']amount_conditions')->label(false);

    }
    ],
        [
            'header' => 'amount_advance',
            'content' => function($model,$index,$key)use($searchModel, $form){
                $salaryCharge = createOrReturnSalaryChargeForOfficer($model, $searchModel->year, $searchModel->month_no);
                return $form->field($salaryCharge, '[' . $key. ']amount_advance')->label(false);

            }
        ],
        [
            'header' => 'amount_optional',
            'content' => function($model,$index,$key)use($searchModel, $form){
                $salaryCharge = createOrReturnSalaryChargeForOfficer($model, $searchModel->year, $searchModel->month_no);
                return $form->field($salaryCharge, '[' . $key. ']amount_optional')->label(false);

            }
        ],
        [
            'header' => 'amount_diff_sallary',
            'content' => function($model,$index,$key)use($searchModel, $form){
                $salaryCharge = createOrReturnSalaryChargeForOfficer($model, $searchModel->year, $searchModel->month_no);
                return $form->field($salaryCharge, '[' . $key. ']amount_diff_sallary')->label(false);

            }
        ],
        [
            'header' => 'amount_additional',
            'content' => function($model,$index,$key)use($searchModel, $form){
                $salaryCharge = createOrReturnSalaryChargeForOfficer($model, $searchModel->year, $searchModel->month_no);
                return $form->field($salaryCharge, '[' . $key. ']amount_additional')->label(false);

            }
        ],
        [
            'header' => 'amount_maleficence',
            'content' => function($model,$index,$key)use($searchModel, $form){
                $salaryCharge = createOrReturnSalaryChargeForOfficer($model, $searchModel->year, $searchModel->month_no);
                return $form->field($salaryCharge, '[' . $key. ']amount_maleficence')->label(false);

            }
        ],
        [
            'header' => 'amount_vacation',
            'content' => function($model,$index,$key)use($searchModel, $form){
                $salaryCharge = createOrReturnSalaryChargeForOfficer($model, $searchModel->year, $searchModel->month_no);
                return $form->field($salaryCharge, '[' . $key. ']amount_vacation')->label(false);

            }
        ],
        [
            'header' => 'amount_sick_list',
            'content' => function($model,$index,$key)use($searchModel, $form){
                $salaryCharge = createOrReturnSalaryChargeForOfficer($model, $searchModel->year, $searchModel->month_no);
                return $form->field($salaryCharge, '[' . $key. ']amount_sick_list')->label(false);

            }
        ],
        [
            'header' => 'amount_bonus',
            'content' => function($model,$index,$key)use($searchModel, $form){
                $salaryCharge = createOrReturnSalaryChargeForOfficer($model, $searchModel->year, $searchModel->month_no);
                return $form->field($salaryCharge, '[' . $key. ']amount_bonus')->label(false);

            }
        ],



    ]


])?>

<?php \kartik\form\ActiveForm::end()?>

<?php  \vova07\themes\adminlte2\widgets\Box::end()?>


<?php
    function createOrReturnSalaryChargeForOfficer($officer, $year, $month_no){
        static $newCharges;

        if (!isset($newCharges[$officer->primaryKey])){
            $newCharges[$officer->primaryKey] = new SalaryCharge([
               'officer_id' => $officer->primaryKey,
                'post_id' => $officer->post_id,
                'rank_id' => $officer->rank_id,
                'year' => $year,
                'month_no' => $month_no

            ]);
        }
        return $newCharges[$officer->primaryKey];
    }
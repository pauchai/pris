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
use vova07\salary\models\Salary;
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
            'attribute' => 'officer.person.fio',
            'format' => 'html',
            'content' =>  function($model,$index,$key)use($searchModel, $form){
                $content = \yii\bootstrap\Html::a(
                    $model->officer->person->fio . ' (' . $model->postDict->title . ' ' . ($model->full_time?'полная':'полставки') . ')',
                    ['/users/officers/view',
                        'id' => $model->officer_id,

                    ]);
                ;
                $salaryCharge = createOrReturnSalaryForOfficerPost($model, $searchModel->year, $searchModel->month_no, $key);
                $content .= $form->field($salaryCharge, '[' . $key. ']year')->hiddenInput()->label(false);
                $content .= $form->field($salaryCharge, '[' . $key. ']month_no')->hiddenInput()->label(false);
                $content .= $form->field($salaryCharge, '[' . $key. ']officer_id')->hiddenInput()->label(false);
                $content .= $form->field($salaryCharge, '[' . $key. ']company_id')->hiddenInput()->label(false);
                $content .= $form->field($salaryCharge, '[' . $key. ']division_id')->hiddenInput()->label(false);
                $content .= $form->field($salaryCharge, '[' . $key. ']postdict_id')->hiddenInput()->label(false);

                return $content;
            }
        ],

        [
            'attribute' => 'work_days',
            'content' =>  function($model,$index,$key)use($searchModel, $form){
                $salaryCharge = createOrReturnSalaryForOfficerPost($model, $searchModel->year, $searchModel->month_no, $key);
                $content = $form->field($salaryCharge, '[' . $key. ']work_days')->label(false);
                return $content;
            }
        ],
        [
            'attribute' => 'amount_rate',
            'content' =>  function($model,$index,$key)use($searchModel, $form){
                $salaryCharge = createOrReturnSalaryForOfficerPost($model, $searchModel->year, $searchModel->month_no, $key);
                $content = $form->field($salaryCharge, '[' . $key. ']amount_rate')->label(false);
                return $content;
            }
        ],
        [
            'attribute' => 'amount_rank_rate',
            'content' =>  function($model,$index,$key)use($searchModel, $form){
                $salaryCharge = createOrReturnSalaryForOfficerPost($model, $searchModel->year, $searchModel->month_no, $key);
                $content = $form->field($salaryCharge, '[' . $key. ']amount_rank_rate')->label(false);
                return $content;
            }
        ],
        [
            'attribute' => 'amount_conditions',
            'content' =>  function($model,$index,$key)use($searchModel, $form){
                $salaryCharge = createOrReturnSalaryForOfficerPost($model, $searchModel->year, $searchModel->month_no, $key);
                $content = $form->field($salaryCharge, '[' . $key. ']amount_conditions')->label(false);
                return $content;
            }
        ],
        [
            'attribute' => 'amount_advance',
            'content' =>  function($model,$index,$key)use($searchModel, $form){
                $salaryCharge = createOrReturnSalaryForOfficerPost($model, $searchModel->year, $searchModel->month_no, $key);
                $content = $form->field($salaryCharge, '[' . $key. ']amount_advance')->label(false);
                return $content;
            }
        ],
    ]


])?>

<?php \kartik\form\ActiveForm::end()?>

<?php  \vova07\themes\adminlte2\widgets\Box::end()?>


<?php
    function createOrReturnSalaryForOfficerPost($officerPost, $year, $month_no, $key){
        static $newSalaries;

        if (!isset($newSalaries[$key])){
            $monthDays = \vova07\jobs\helpers\Calendar::getMonthDaysNumber((new \DateTime())->setDate($year, $month_no, '1'));
             $salary = new Salary([
               'officer_id' => $officerPost->officer_id,
                'company_id' => $officerPost->company_id,
                'division_id' => $officerPost->division_id,
                'postdict_id' => $officerPost->postdict_id,


                'rank_id' => $officerPost->officer->rank_id,
                'year' => $year,
                'month_no' => $month_no,
                'work_days' => $monthDays,
                'amount_rank_rate' => $officerPost->officer->rank->rate


            ]);
             $salary->amount_rate = $salary->calculateAmountRate();
            $salary->amount_conditions = $salary->calculateAmountCondition();
            $salary->amount_advance = $salary->calculateAmountAdvance();



            $newSalaries[$key] = $salary;
        }
        return $newSalaries[$key];
    }
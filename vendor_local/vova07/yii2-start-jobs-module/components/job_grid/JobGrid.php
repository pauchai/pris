<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 22.10.2019
 * Time: 13:41
 */

namespace vova07\jobs\components\job_grid;


use kartik\form\ActiveForm;
use vova07\jobs\helpers\Calendar;
use vova07\jobs\models\Holiday;
use vova07\jobs\Module;
use vova07\prisons\widgets\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

class JobGrid extends GridView
{
   // public $searchModel;
    public $showOnEmpty = false;
    public $showCreateWhenEmpty = true;
    public $showActionButton = false;
    public $fixedColumnsWidth = [];


    public $form;

    public function init()
    {
        if ($this->showCreateWhenEmpty){
        $this->emptyText = Html::a(
            Module::t('default','CREATE_TABULAR_FOR_MONTH'),
            ['create-tabular',
                'prison_id'=>$this->filterModel->prison_id,
                'year'=>$this->filterModel->year,
                'month_no'=>$this->filterModel->month_no
            ],
            ['class'=>'btn btn-default']
        );
        }
        $this->insertDaysColumns();
        if ($this->showActionButton)
            $this->columns[] =   ['class' => ActionColumn::class];

        parent::init();



    }

    public function insertDaysColumns()
    {


        $firstFixColumnsCount = count ($this->columns);
        $url = \yii\helpers\Url::to(['toggle-date']);

        for($i=1;$i<=Calendar::getMonthDaysNumber($this->filterModel->getDateTime());$i++){
            $htmlOptions = [];
            $attributeName = $i . 'd';
            $btnDateTime = (new \DateTime())->setDate($this->filterModel->year,$this->filterModel->month_no,$i);
            $btnDate = $btnDateTime->format("Y-m-d");

       /*     if (in_array($btnDateTime->format('N'),[6,7]) ){
                if (Holiday::findOne($btnDate) === null){
                    $holiday = new Holiday();
                    $holiday->day_date = $btnDate;
                    $holiday->save();
                }
            }*/
            $htmlOptions['style'] = "padding:0px";
            if (Calendar::isWeekEnd($btnDate) || Calendar::isHoliday($btnDate) ){
                $btnClass = 'btn-danger';

                if ($this->filterModel->ignoreHolidayWeekDays === true)
                    $htmlOptions['disabled'] = 'disabled';


            } else {
                $btnClass = 'btn-default';
            }
            $htmlOptions['class'] = $btnClass .  ' form-control';



            $buttonContent = '<span class="btn-ajax-wrap">' . Html::a($i, $url, [
                    'class'                 => 'btn btn-xs  ' . $btnClass. '   btn-ajax',
                    //  'action'                => 'toggle-date',
                    'data'                  => [

                        //'toggle'            => 'confirmation',
                        'params' => ['day_date' => $btnDate],
                        'popout'            => true,
                        'singleton'         => true,
                        'placement'         => 'left',
                        //'title'             => Module::t('default','Are you sure you want to restore this item?'),
                        'method'            => 'post',
                        //'btn-ok-label'      => Module::t('default','Yes'),
                        //'btn-ok-class'      => 'btn-xs btn-success',
                        //'btn-cancel'        => Module::t('default','No'),
                        //'btn-cancel-class'  => 'btn-xs btn-warning',
                    ],
                    'before-send-title'     => Module::t('default','Request sent'),
                    //   'before-send-message'   => Module::t('default','Please, wait...'),
                    'success-title'         => Module::t('default','Server Response'),
                    'success-message'       => Module::t('default','Message successfully deleted.'),
                ]) . '</span>';
            $this->columns[] = [
                'contentOptions' => ['class' => 'days'],
                'headerOptions' => ['class' => 'days'],
                'header' => $buttonContent,
                //'attribute' => $attributeName
                'content' => function($model) use($attributeName,$btnClass, $btnDateTime, $htmlOptions){
                    return   $this->form->field($model,'[' . $model->primaryKey .']'. $attributeName)->input('text',$htmlOptions)->label(false);
                }
            ];


        }
        $this->columns[] = [
            'header' => Module::t('labels','TOTAL_HOURS_LABEL'),
            'content' => function($model){
                $retValue = 0;
                for ($i=1;$i<=31;$i++){
                    $attributeName = $i . 'd';
                    $retValue +=$model->$attributeName;
                }
                return $retValue;
            }];
        $this->columns[] = [
            'header' => Module::t('labels','TOTAL_DAYS_LABEL'),
            'content' => function($model){
                $retValue = 0;
                for ($i=1;$i<=31;$i++){
                    $attributeName = $i . 'd';
                    if ($model->$attributeName)
                        $retValue++;
                }
                return $retValue;
            }];
    }

    public function run()
    {
        echo $this->render("_search",['model'=>$this->filterModel]);
        $this->form = ActiveForm::begin(['method'=>'post']);
        echo Html::submitButton('submit',['class'=>'btn btn-submit']);

        parent::run();

        echo Html::submitButton('submit',['class'=>'btn btn-submit']);
         ActiveForm::end();
         $this->fixedColumnsAsserts();
    }

    public function fixedColumnsAsserts()
    {

if ($this->dataProvider->query->count()){
    $fixedColumnWidth = $this->fixedColumnsWidth;
} else {
    $fixedColumnWidth = [];
}

    $fixedColumnsCss = '';
    $cssLeft = 0;
    $fixedColumnscssContent = '';
    foreach ($fixedColumnWidth as $index=>$width){

        $fixedColumnscssContent .='.grid-view table thead tr th:nth-child(' . ($index+1) . '),' . "\n";
        $fixedColumnscssContent .='.grid-view  table tbody tr td:nth-child(' .($index+1) . ') {' . "\n";
        $fixedColumnscssContent .='background:#eff1f7;'. "\n";
        $fixedColumnscssContent .='top: auto;left:' . $cssLeft . 'em' . ';position: absolute;width:' . $width . 'em' . ';'. "\n";
        $fixedColumnscssContent .= '}' . "\n";
        $cssLeft += $width;
    }



   $fixedColumnscssContent .='.grid-view  table thead tr th:nth-child(' . (count($fixedColumnWidth)+1) . '),'. "\n";
   $fixedColumnscssContent .='.grid-view  table tbody tr td:nth-child(' . (count($fixedColumnWidth)+1) . '){'. "\n";
   $fixedColumnscssContent .='padding-left: ' . ($cssLeft + 0.5 ) . 'em;'. "\n";
   $fixedColumnscssContent .='}' . "\n";


     $this->view->registerCss(
    <<<CSS

         .grid-view {
            overflow-x: scroll;
           }


.grid-view table {
  
  width: 1300px;
  
}
.grid-view  table thead tr .days{padding:0px;}
.grid-view  table thead tr .days .btn {width:2em;}
.grid-view  table tbody tr .days{padding:0px;}
.grid-view  table tbody tr .days input {width:2em;}




CSS


 . $fixedColumnscssContent);
    }

}
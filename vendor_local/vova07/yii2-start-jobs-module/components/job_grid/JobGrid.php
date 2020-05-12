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
use vova07\jobs\models\JobNotPaid;
use vova07\jobs\Module;
use vova07\prisons\widgets\ActionColumn;
use yii\db\ActiveRecord;
use yii\grid\GridView;
use yii\helpers\Html;

class JobGrid extends GridView
{
   // public $searchModel;

    public $showOnEmpty = false;
    public $showSyncButton = true;
    public $showActionButton = false;
    public $fixedColumnsWidth = [];
    public $enableControlls = true;
    public $filterPosition  = self::FILTER_POS_FOOTER;



    public $form;

    public function init()
    {

        $this->insertDaysColumns();
        if ($this->showActionButton)
            $this->columns[] =   ['class' => ActionColumn::class,'template' => "{delete}"];

        parent::init();



    }

    public function insertDaysColumns()
    {


        $firstFixColumnsCount = count ($this->columns);
        $url = \yii\helpers\Url::to(['toggle-date']);

        $modelDateTime = Calendar::getDateTime($this->filterModel->year, $this->filterModel->month_no);
        for($i=1;$i<=Calendar::getMonthDaysNumber($modelDateTime);$i++){
            $htmlOptions = [];
            $attributeName = $i . 'd';
          //  $btnDateTime = (new \DateTime())->setDate($this->filterModel->year,$this->filterModel->month_no,$i);
            $btnDateTime = Calendar::getDateTime($this->filterModel->year, $this->filterModel->month_no,$i);
            $btnDate = $btnDateTime->format("Y-m-d");

       /*     if (in_array($btnDateTime->format('N'),[6,7]) ){
                if (Holiday::findOne($btnDate) === null){
                    $holiday = new Holiday();
                    $holiday->day_date = $btnDate;
                    $holiday->save();
                }
            }*/
            $htmlOptions['style'] = "padding:0px; text-align:center;";
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
                        //$htmlOptions['style'] = 'text-align:center;';
                            if (!($model instanceof JobNotPaid) && Calendar::checkDateInPenaltyForPrisoner($model->prisoner,$btnDateTime ))
                                $htmlOptions['style'] .= 'color:green;';


                        return   $this->form->field($model,'[' . $model->primaryKey .']'. $attributeName)->input('text',$htmlOptions)->label(false);

                }
            ];


        }
        $this->columns[] = [
            'header' => Module::t('labels','TOTAL_HOURS_LABEL'),
            'content' => function($model){
                if ( $model->getHours(false) != $model->getHours(true))
                    return $model->getHours(false) . '/' . Html::tag('span', $model->getHours(true),['class' => 'no-print', 'style' => 'color:green']);
                else
                    return $model->getHours(false);
            }];
        $this->columns[] = [
            'header' => Module::t('labels','TOTAL_DAYS_LABEL'),
            'content' => function($model){
                if ($model->getDays(false) != $model->getDays(true))
                    return $model->getDays(false)  . '/' . Html::tag('span', $model->getDays(true),['class' => 'no-print', 'style' => 'color:green']);
                else
                    return $model->getDays(false);
            }];
    }

    public function run()
    {

        if ($this->enableControlls){
            echo $this->render("_search",['model'=>$this->filterModel]);
        }

        if ($this->showSyncButton && $this->enableControlls) {
            echo Html::a(
                Module::t('default', 'SYNC_TABULAR_FOR_MONTH'),
                ['create-tabular',
                    'prison_id' => $this->filterModel->prison_id,
                    'year' => $this->filterModel->year,
                    'month_no' => $this->filterModel->month_no
                ],
                ['class' => 'btn btn-info']
            );
        }
        $this->form = ActiveForm::begin(['method'=>'post']);

        if ($this->dataProvider->count && $this->enableControlls ){
            echo Html::submitButton(Module::t('tabular','TABULAR_SAVE'),['class'=>'btn btn-success']);
        }


        parent::run();

        if ($this->dataProvider->count && $this->enableControlls ){
            echo Html::submitButton(Module::t('tabular','TABULAR_SAVE'),['class'=>'btn btn-success']);
        }
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
        //$fixedColumnscssContent .='top: auto;left:' . $cssLeft . 'em' . ';position: absolute;width:' . $width . 'em' . ';'. "\n";
        $fixedColumnscssContent .='width:' . $width . 'em' . ';'. "\n";
        $fixedColumnscssContent .= '}' . "\n";
       // $cssLeft += $width;
    }



  // $fixedColumnscssContent .='.grid-view  table thead tr th:nth-child(' . (count($fixedColumnWidth)+1) . '),'. "\n";
  // $fixedColumnscssContent .='.grid-view  table tbody tr td:nth-child(' . (count($fixedColumnWidth)+1) . '){'. "\n";
   //$fixedColumnscssContent .='padding-left: ' . ($cssLeft + 0.5 ) . 'em;'. "\n";
  // $fixedColumnscssContent .='}' . "\n";

     if ($this->view->context->isPrintVersion)
        $overflowX = 'none';
     else
        $overflowX = 'scroll';

     $this->view->registerCss(
    <<<CSS

         .grid-view {
            overflow-x: $overflowX;
           }


.grid-view table {
  
 /* width: 1300px; */
  
}
.grid-view  table thead tr .days{padding:0px;}
.grid-view  table thead tr .days .btn {width:2em;}
.grid-view  table tbody tr .days{padding:0px;}
.grid-view  table tbody tr .days input {width:2em;}




CSS


 . $fixedColumnscssContent
     );
    }

}
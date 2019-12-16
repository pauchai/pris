<?php
/**
 * @var $prisoner \vova07\users\models\Prisoner
 * @var $this \yii\web\View
 * @var $prisonerPrograms
 * @var $prisonerRequirements
 */
use vova07\plans\Module;
use yii\bootstrap\ActiveForm;
use \kartik\grid\GridView;
//use yii\grid\GridView;
use kartik\grid\SerialColumn;
use yii\helpers\Html;

$this->title =  Module::t('default', 'PLANUL INDIVIDUAL DE EXECUTAREA PEDEPSEI');
$this->params['subtitle'] = '';
$this->params['breadcrumbs'][] = [
        'label'=>$prisoner->person->fio,
    'url' => ['/users/prisoner/view','id'=>$prisoner->primaryKey]

];
$this->params['breadcrumbs'][] = Module::t('default', 'PLANUL INDIVIDUAL ');;

?>
<?php $this->registerCss(
        <<<CSS
        
#print-container {font-size:25px;}
  
#print-container h1{font-size:40px;}
#print-container h3 {font-size:30px;}
#print-container h5 {font-weight:bold; font-size:30px;}
#anexa li   {text-align:justify; font-size: 14px}
#print-container p {line-height: 100%}
p.requirements, p.programs {padding-left: 2em;}

CSS

)?>

<div id="print-container">

<table>
    <tr>
        <td width="60%">

        </td>
        <td width="40%">
            <p align="center">Anexa nr.1.</p>
            <p class="justified">la Metodologia privind procedura de elaborare şi implementare a Planului individual de resocializare pentru condamnatul adult, aprobată prin ordinul DIP nr. <u>34</u> din <u>31 ianuarie 2018</u></p>

        </td>
    </tr>
</table>


     <h1 class="bg-black" align="center">Planul Individual de resocializare a condamnatului adult</h1>


    <h3 align="center"><b>N.P.P.</b> <u><?=$prisoner->person->fio?> <?=$prisoner->person->birth_year?></u></h3>


<?php  $cnt = 0;foreach($programsGroupedByRole as $role=>$programs):?>

    <h5><?=++$cnt?>. <?=Module::getPlanLabels($role)?>.</h5>
<h5><?=Module::PLAN_LABELS_REQUIREMENTS?>:</h5>
<?php if(isset($programs['req'])) foreach($programs['req'] as $requirement):?>

<p class="requirements">
    <i class=" fa fa-pen-square"> <?=$requirement->content?> </i>
</p>


<?php endforeach;?>
    <p>
        __________________________________________________________________________________________________
    </p>
   <h5><?=Module::PLAN_LABELS_PROGRAMS_REQUIREMENT?>:</h5>
    <?php if(isset($programs['prog'])) foreach($programs['prog'] as $program):?>

    <p class="programs">
        <i  class=" fa fa-pen-square"><?=$program->programDict->title?> (<?=$program->date_plan?>)</i>
    </p>
    <?php endforeach;?>
    <p>
        __________________________________________________________________________________________________
    </p>
<br/>

    <p> A_întocmit:__________________________________________________________________________________________
    </p>
<br/>
<?php endforeach;?>
<br/>
    <p><b>Data</b> întocmirii Planului: ________________</p>
    <p ><b>Data</b> revizuirii Planului (când este nevoie): ________________</p>
<br/>
<div id="anexa">

    <ol >
        <li class="justified" >Planul individual de resocializare şi angajamentul se anexează, în original, la dosarul personal al condamnatului şi,în copie, la Programul individual, cu privire la planificarea executării pedepsei penale, Responsabilitatea anexării revine specialistului responsabil, conform atribuţiilor stabilite de şeful de serviciu.</li>
        <li class="justified">Pentru fiecare palier (educativ, psihologic, social), se consemnează doar programele şi activităţile obligatorii.</li>
        <li class="justified">În cazul revizuririi Planului individual de resocializare, se consemnează data la care se realizează revizuirea şi, după caz, se completează noile programe sau activităţi necesar a fi desfăşurate de către condamnat/condamnată</li>
    </ol>
<div>

</div>
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

<div class="container">
    <div class="row">

        <div class="col-md-6" ></div>
        <div class="col-md-6">
            <table>
                <tr><td align="center" colspan="2">„APROB”</td></tr>
                <tr><td>Directorul al Penitenciarului nr. 1 Taraclia</td></tr>
                <tr><td>Comisar-sef de justicie</td><td>Andrei Bușmachiu</td></tr>
                <tr><td colspan="2">„____” ________________ 2019</td></tr>
            </table>

        </div>
    </div>


<div class="row">

    <div class="col-lg-12" >
     <h1>Planul Individual de resocializare a condamnatului adult</h1>
    </div>

</div>
<div class="row">

    <div class="col-lg-12" >
        N.P.P <u><?=$prisoner->person->fio?></u>
    </div>

</div>

<?php  $cnt = 0;foreach($programsGroupedByRole as $role=>$programs):?>

<h2><?=++$cnt?></h2>
<h3>Nevoi:</h3>
<?php if(isset($programs['req'])) foreach($programs['req'] as $requirement):?>

        <div class="row">

            <div class="col-lg-12" >
                <u> <?=$requirement->content?></u>
            </div>

        </div>
<?php endforeach;?>

    <h3>Programs:</h3>
    <?php if(isset($programs['prog'])) foreach($programs['prog'] as $program):?>

        <div class="row">

            <div class="col-lg-12" >
                <u><?=$program->programDict->title?></u>
            </div>

        </div>
    <?php endforeach;?>

    <div class="row">

        <div class="col-lg-12" >
            <?=$role?>______
        </div>

    </div>
<?php endforeach;?>

</div>
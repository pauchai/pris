<?php
use vova07\jobs\Module;
use vova07\site\models\Setting;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\jobs\models\JobPaid
 */
?>

<table>
    <tr>
        <td width="60%"></td>
        <td>
            <p style="margin:0px;padding:0px" class="anexa" align="center">Anexa nr.2</p>
            <p class="anexa">
La instrucţiunea cu privire la procedura compensării privilegiată a zilelor de muncă din contul duratei pedepsei
            </p>
        </td>
    </tr>
</table>
<br/>
<p  style="text-align:center;" ><u><b>CERTIFICAT</b></u></p>
        <p>
            <label>Condamnatul</label>
<span class="field" style="width:26em;"> <?=$model->prisoner->fullTitle?></span>

        </p>

        <div style='text-align:justify'>

            în luna
            <i><u><?php echo \vova07\site\Module::t('calendar', 'MONTH_' . $model->month_no)?></u></i>
<?=$model->year?> a fost antrenat la executarea următoarelor lucrări:
</div>
<br/>
<p ><span class="field" style="width:100%" value="" ></span>
</p>

<p >

   în calitate de <span class="field"  style="font-weight:normal;width:82%;text-align:center">deservire gospodărească / <b><?=$model->type->title?> </b></span>
</p>

<p  style="text-align: center"><span style="line-height: 30%" ><i>
  (specialitatea)</i></span></p>
<p >în decurs de
    <span style="font-weight: bolder; text-align:center;width:15em" class="field" >
        <?=$model->getDays(false)?>
        <?php if ($model->half_time):?>
         / 0.5
        <?php endif;?>
        zile.
    </span>


</p>
<p >
    Sarcina de producere execută, regimul de detenţie nu încalcă. Se certifică pentru prezentare în vederea aplicării compensării privilegiate a zilelor de muncă din contul duratei pedepsei, reieşind din calcul:
</p>
<p>
    <span class="field" style="width:100%"  ><?=$model->type->compensationTitle?></span>
</p>


        <?php
            $value = $model->getWorkDaysWithCompensation(false);
            $floorValue = floor($value);
            $fractionValue = floor(($value - $floorValue) * 100);
        ?>

    <p>
    în număr de: <span class="field" style="width:82%"  ><?=Module::t('default','{n,number},{f,number} ({n,spellout}, {f,number} ) ',['v' => $value, 'n'=>$floorValue,'f'=>$fractionValue ])?> zile</span>
    </p>
<br/>


<table width="100%">
    <tr>
        <td width="50%" style="padding-right:10px;">
            <p style="margin:0px"><b><?=$sefSLA->post->title?></b></p>
             <p style="margin:0px"><?=$sefSLA->rank->title?></p>
            <br/>
            <p >
                <span class="field" style="width:100%" value="" ></span>
            </p>
            <p style="margin:0px"><?=$sefSLA->person->fio?></p>
            <p style="margin:0px"><?=date('d.m.Y')?></p>
        </td>

        <td width="50%"  style="padding-left:10px;" >
            <p style="margin:0px"><b>Secţia Reintegrare Socială</b></p>
            <p style="margin:0px">_____________________________________________</p>
            <br/>
            <p >
                <span class="field" style="width:100%" value="" ></span>
            </p>
            <p style="margin:0px">_____________________________________________</p>
            <p style="margin:0px"><?=date('d.m.Y')?></p>

        </td>
    </tr>
</table>

<br/>

<p ><b><?=$sefSF->post->title?></b></p>

<table>
    <tr>
        <td style="text-align:right"><?=$sefSF->rank->title?> /</td>
        <td>_________________</td>
        <td style="text-align:left">/ <?=$sefSF->person->fio?></td>
    </tr>
</table>
<p >
    <?=date('d.m.Y')?>
</p>


<?php if ($penalty = $model->getActualPenalty()):?>
<br/>
<table style="width:100%">
    <tr>
        <td style="width:20%; vertical-align:top"><b> Nota: </b></td>
        <td style="width:80%">Sancţiunea disciplinară activă în perioada <?=date('d.m.Y',$penalty->date_start)?> - <?=date('d.m.Y', $penalty->date_finish)?>
            <p>_________________________________________________________</p>

        </td>
    </tr>
</table>

<?php  endif;?>

<div style="page-break-after: always"></div>
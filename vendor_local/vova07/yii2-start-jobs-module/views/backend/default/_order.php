<?php
use vova07\jobs\Module;
use vova07\site\models\Setting;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\jobs\models\JobPaid
 */
?>


<p  style="text-align:center;" ><u><b>ORDONANŢĂ</b></u></p>
<p style="text-align:center;line-height:50%" ><span ><b>privind compensarea privilegiată a zilelor de muncă</b></span></p>
<p style="text-align:center;line-height:50%" >

    <span ><b>din contul termenului de pedeapsă</b></span></p>
    <br/>

        <p>

            <label>Condamnatul</label>
<span class="field" style="width:25em;"> <?=$model->prisoner->fullTitle?></span>

        </p>

        <div style='text-align:justify'>

            în luna
            <i><u><?php echo \vova07\site\Module::t('calendar', 'MONTH_' . $model->month_no)?></u></i>
<?=$model->year?> a fost antrenat la executarea următoarelor lucrări:
</div>


<p >

    <span class="field"  style="font-weight:normal;width:100%;text-align:center">deservire gospodărească / <b><?=$model->type->title?> </b></span>
</p>

<p  style="text-align: center"><span style="line-height: 30%" ><i>
  (specialitatea)</i></span></p>
<p >În
decurs de
    <span style="font-weight: bolder; text-align:center;width:15em" class="field" >
        <?=$model->days?>
        <?php if ($model->half_time):?>
         / 0.5
        <?php endif;?>
        zile.
    </span>


</p>
<p ><span >Sarcina
de producer execut</span><span >ă, sancţiuni
disciplinare nu are.</span></p>
<p ><span >În
urma deciziei Comisiei penitenciarului din „____”
_____________<?=date('Y')?></span>
privind
compensarea privilegiată a zilelor de muncă în contul duratei
pedepsei  reişind din calcul
    <span class="field" style="width:20em;"  ><?=$model->type->compensationTitle?></span>
</p>
<p >Conducîndu-mă
de prevederile articolului 238 Cod de Executare al R.M.,</p>
<br/>
<p align="center" >
    <b>DISPUN:</b>
</p>
<p>
    <label>Condamnatului</label>
    <span class="field" style="width:25em;" ><?=$model->prisoner->fullTitle?></span>
</p>

<p >
    Se efectuează compensarea privilegiată
   <span class="field" style="width:10em">
    <?=$model->days?>
    <?php if ($model->half_time):?>
        / 0.5
    <?php endif;?>
   </span>
    zile de muncă
    <?php
    $value = $model->getWorkDaysWithCompensation();
    $floorValue = floor($value);
    $fractionValue = floor(($value - $floorValue) * 100);
    ?>

<p>
    prin <span class="field" style="width:30em"  ><?=Module::t('default','{n,number},{f,number} ({n,spellout}, {f,number} ) ',['v' => $value, 'n'=>$floorValue,'f'=>$fractionValue ])?> zile</span>
    </p>
<br/>
<p ><b><?=$director->post?></b></p>
<p><?=$director->rank?> ____________________________ <?=$director->person->getFio(true,false)?></p>
<p >«___»____________<?=date('Y')?>
</p>
<br/>
<p >
    Cu ordonanţa am luat cunoştinţă la data de «___» ___________<?=date('Y')?>
</p>
<br/>
<div style="width:50%" ">

<span class="field" style="width:100%"></span>
    <p style="text-align: center">(semnătura condamnatului)</p>
</div>




<div style="page-break-after: always"></div>
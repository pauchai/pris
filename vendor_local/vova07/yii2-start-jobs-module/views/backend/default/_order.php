<?php
use vova07\jobs\Module;
use vova07\site\models\Setting;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\jobs\models\JobPaid
 */
?>
<p align="center" ><u><b>ORDANANŢÂ</b></u></p>
<p align="center" ><span ><b>Privind
compensarea privilegiată a zilelor de muncă</b></span></p>
<p align="center" ><span ><b>Din
contul termenului de pedeapsă</b></span></p>
<p >Condamnatul <u><?=$model->prisoner->person->fio?></u></p>
<p ><span >În
luna </span><i><u><?=(new DateTime())->setDate($model->year,$model->month_no,1)->format('M')?></u></i><span >
<?=$model->year?> a fost antrenat la executarea următoarelor lucrări</span><span >:
</span>
</p>
<p ><u>
</u><i><u>deservire gospodărească</u></i><span ><i><u>
/<?=$model->type->title?></u></i></span></p>
<p ><font size="2" ><span ><i>
  (specialitatea)</i></span></font></p>
<p ><span >În
decurs de	<u><?=$model->days?> <?=$model->half_time?'/0.5':''?></u>zile.</span></p>
<p ><span >Sarcina
de producer execut</span><span >ă, sancţiuni
disciplinare nu are.</span></p>
<p ><span >În
urma deciziei Comisiei penitenciarului din „____”
_____________<?=date('Y')?></span></p>
<p ><span >Privind
compensarea privilegiată a yilelor de muncă în contul duratei
pedepsei  reişind din calcul
</span><u><?=$model->type->compensationTitle?> </u></p>
<p >Conduc<span >înd-mă
de prevederile articolului 238 Cod de Executare al R.M.,</span></p>
<p align="center" ><b>DISPUN</b><b>:</b></p>
<p ><span >Condamnatului
</span><u><?=$model->prisoner->person->fio?></u></p>
<p >
<font size="2" ><span ><i>(NPP,
anul na</i></span></font><font size="2" ><i>şterii)</i></font></p>
<p ><span >Se
        efectuează compensarea privilegiată  </span><u><?=$model->days?> <?=$model->half_time?'/0.5':''?></u> zile</p>
<p >
<font size="2" ><i>(num</i></font><font size="2" ><span ><i>ărul
zilelor lucrate)</i></span></font></p>
<p ><span >De
        <?php
            $value = $model->getWorkDaysWithCompensation();
            $floorValue = floor($value);
            $fractionValue = round($value,2) - $floorValue;
        ?>
muncă prin </span><u><?=Module::t('default','{v,number}({n,spellout} {f,number} zile) ',['v' => $value, 'n'=>$floorValue,'f'=>$fractionValue])?> ?></u></p>
<p ><span ><b>Director
Penitenciarului nr. 1</b></span><b>-Taraclia</b></p>
<p >Comisar-<span >şef
de justiţie	</span> <u><?=Setting::getInstance()->directorOfficer->person->fio?></u></p>
<p ><span >«___»
____________<?=date('d-m-Y')?> </span>
</p>
<p ><span >Cuţa am luat cunoştinţă</span></p>
<p ><span >La data de </span><span >«___» ___________<?=date('Y')?>
	_______________________________</span></p>
<p >
<font size="2" ><i>(</i></font><font size="2" ><i>semn</i></font><font size="2" ><span ><i>ătura
condamnatului</i></span></font><font size="2" ><i>)</i></font></p>
<div style="page-break-after: always"></div>
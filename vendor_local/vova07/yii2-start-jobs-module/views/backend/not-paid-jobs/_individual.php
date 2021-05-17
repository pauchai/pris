<?php
use vova07\jobs\Module;
use vova07\site\models\Setting;
/**
 * @var $this \yii\web\View
 * @var $model \vova07\jobs\models\JobPaid
 */
?>
<?php
    $this->registerCss(<<<CSS
    body {
        font-family: "Times New Roman";
        font-size:14px;
    }
    .field {
        display:inline-block;
        border:none;
        border-bottom-color: black;
        border-bottom-width: 1px;
        border-bottom-style: solid;
        text-align: center;
        font-weight: bolder;
    }
CSS
)
?>
<table width="100%">
    <tr>
        <td width="50%">

        </td>
        <td width="50%">
            <table><tr>
                    <td width="40%">

                    </td>
                    <td width="60%">
                        <p >
                            Anexă la Programul informativ privind modul şi condiţiile de încadrare în câmpul muncii a persoanelor private de libertate
                        </p>
                    </td></tr></table>

            <br><br>
            <p style="text-align: center;font-size:120%">
                "APROB"
            </p>
            <p style="text-align: center;font-size:120%">
                <?=$director->post->title?>
            </p>
            <p style="text-align: center;font-size:120%">
                <?=$director->rank->title?>_______________<?=$director->person->getFio(true,false)?>
            </p>


        </td>
    </tr>
</table>
<br/>


<p  style="text-align:center;" ><b>Certificat</b></p>
<p style="text-align:center;line-height:50%" >privind evidenţa orelor de muncă neremunerată a persoanei private de libertate ce se deţine în Penitenciarul nr.1 - Taraclia</p>

<p style="text-align:center;line-height:50%" >
    pentru luna <i><u><?php echo \vova07\site\Module::t('calendar', 'MONTH_' . $model->month_no)?></u></i>
    <?=$model->year?>
</p>
<p style="text-align:center;line-height:50%" ><b><?=$model->prisoner->fullTitle?></b></p>

<?php

    //$dataProviderIndividual  = clone $dataProvider;
    //$dataProviderIndividual->query->andWhere(['prisoner_id' => $model->prisoner_id]);
    $dataProviderIndividual =  new \yii\data\ActiveDataProvider();
    $dataProviderIndividual->query = \vova07\jobs\models\JobNotPaid::find();
    $dataProviderIndividual->query->andWhere(
            [

            'prison_id' => $model->prison_id,
            'month_no' => $model->month_no,
            'year' => $model->year,
                'prisoner_id' => $model->prisoner_id

            ]
    );

?>

<?php echo \vova07\jobs\components\job_grid\JobGrid::widget(
        [
         'dataProvider' => $dataProviderIndividual,
            'filterModel' => $searchModel,
            'showSyncButton' => false,
            'enableControlls' => false,
            'summary' => false,

            'columns' => [
                //'prisoner.fullTitle',
            ]

        ])
?>

<!--<table style="width:100%" border=1>
    <tr>
        <th></th>
        <th>1</th>
        <th>2</th>
    </tr>
    <tr>
        <td># de ore</td>
        <td>2</td>
        <td>5</td>
    </tr>
</table>-->


<p ><b><u>Întocmit</u></b>:<?=$officer->post->title?> <?=$officer->rank->title?> _________________  <?=$officer->person->fio?></p>

<p><b><u>Coordonat</u></b>:</p>
<p><b>Şef al SRS</b> _______________________________________________________________________________________</p>
<p><b>Şef al SLA</b> _______________________________________________________________________________________</p>
<p><b>Şef al SSRP</b> ______________________________________________________________________________________</p>
<br>
<p><b>Am făcut cunoştinţă</b>:______________________________________________________________________________</p>

<div style="page-break-after: always"></div>
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
            <p style="text-align: center;font-size:120%">
                "APROB"
            </p>
            <p style="text-align: center;font-size:120%">
                <?=$director->post?>
            </p>
            <p style="text-align: center;font-size:120%">
                <?=$director->rank?>_______________<?=$director->person->getFio(true,false)?>
            </p>


        </td>
    </tr>
</table>
<br/>
<br/>


<p  style="text-align:center;" ><u><b>TABELUL</b></u></p>
<p style="text-align:center;line-height:50%" >de evidenţa a orelor neremunerate a condamnatului</p>
<p style="text-align:center;line-height:50%" >
    <span ><b> <?=$model->prisoner->fullTitle?></b></span>
</p>
<p style="text-align:center;line-height:50%" >
    pe luna <i><u><?php echo \vova07\site\Module::t('calendar', 'MONTH_' . $model->month_no)?></u></i>
    <?=$model->year?>
</p>

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
            //    'prisoner.person.fio',
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


<p ><b><?=$officer->post?></b></p>

<table>
    <tr>
        <td style="text-align:right"><?=$officer->rank?> /</td>
        <td>_________________</td>
        <td style="text-align:left">/ <?=$officer->person->fio?></td>
    </tr>
</table>
<p >«___»____________<?=date('Y')?>
</p>

<br/>
<p >
    Cu tabelul am luat cunoştinţă la data de «___» ___________<?=date('Y')?>
</p>
<br/>
<div style="width:50%" ">

<span class="field" style="width:100%"></span>
<p style="text-align: center">(semnătura condamnatului)</p>
</div>

<p>
  <span style=" font-weight: bolder"> Notă:</span>  <span class="field" style="width:100%; "> <i> <?=$model->type->title?></i></span>
</p>

<div style="page-break-after: always"></div>
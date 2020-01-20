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

<p ><b>Secţia Reintegrare Socială</b></p>
<br/>
<p ><span class="field" style="width:100%" value="" ></span>
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

<div style="page-break-after: always"></div>
<?php
use vova07\electricity\Module;
use yii\bootstrap\Html;
use vova07\electricity\models\DeviceAccounting;

?>


<?php foreach ($deviceAccountings as $deviceAccounting):?>
<?php
    if ($deviceAccounting->status_id == DeviceAccounting::STATUS_PROCESSED)
        $sumHtmlOptions = ['class' => 'text-green'];
    else
        $sumHtmlOptions = ['class' => 'text-red'];

    ?>
<table>
    <tr>
        <td style="padding:5px;">
            <b><?=Module::t('default','KWT_LABEL')?>: </b><?=Html::tag('span', $deviceAccounting->value )?>
        </td>
        <td style="padding:5px;">
            <b><?=Module::t('default','SUM_LABEL')?>:</b> <?=Html::tag('span', $deviceAccounting->getPrice(), $sumHtmlOptions )?>
        </td>
    </tr>
</table>

<table>
    <?php foreach ($deviceAccounting->getBalances()->all() as $balance):?>
    <tr>
        <td style="padding-left:5px;"><?=Html::a($balance->prisoner->person->getFio()
                ,['/finances/default/view', 'id'=>$balance->prisoner_id]
            )?></td>
        <td style="padding-left:5px;text-align: right"><?=$balance->amount?></td>
    </tr>
    <?php endforeach ?>
</table>

<?php endforeach;?>
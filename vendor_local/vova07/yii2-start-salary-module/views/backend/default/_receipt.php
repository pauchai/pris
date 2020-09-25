<?php
/**
* @var $model \vova07\salary\models\SalaryWithHold
*/

?>
<table width=100%>
    <tr><th align="center">
            <?=$model->officer->person->fio?>
            <?php $salaries = $model->getSalaries()->all()?>
        </th>
    </tr>
    <tr>
        <td>
        <table width=100%>
            <thead>
            <tr>
                <th align="center">НАЧИСЛЕНО</th>
                <?php foreach($salaries as $salary):?>
                   <th><?=$salary->work_days?></th>
                <?php endforeach;?>
            </tr>
            </thead>
            <tbody>
            <tr>
                <?php $attributeName = 'amount_rate'?>
                <td><?=$salary->getAttributeLabel($attributeName)?></td>
                <?php foreach($salaries as $salary):?>
                    <td><?=$salary->getAttribute($attributeName)?></td>
                <?php endforeach;?>

            </tr>
            <tr>
                <?php $attributeName = 'amount_rank_rate'?>
                <td><?=$salary->getAttributeLabel($attributeName)?></td>
                <?php foreach($salaries as $salary):?>
                    <td><?=$salary->getAttribute($attributeName)?></td>
                <?php endforeach;?>

            </tr>
            <tr>
                <?php $attributeName = 'amount_conditions'?>
                <td><?=$salary->getAttributeLabel($attributeName)?></td>
                <?php foreach($salaries as $salary):?>
                    <td><?=$salary->getAttribute($attributeName)?></td>
                <?php endforeach;?>

            </tr>
            <tr>
                <?php $attributeName = 'amount_advance'?>
                <td><?=$salary->getAttributeLabel($attributeName)?></td>
                <?php foreach($salaries as $salary):?>
                    <td><?=$salary->getAttribute($attributeName)?></td>
                <?php endforeach;?>
            </tr>
            <tr>
                <?php $attributeName = 'amount_diff_sallary'?>
                <td><?=$salary->getAttributeLabel($attributeName)?></td>
                <?php foreach($salaries as $salary):?>
                    <td><?=$salary->getAttribute($attributeName)?></td>
                <?php endforeach;?>
            </tr>
            <tr>
                <?php $attributeName = 'amount_bonus'?>
                <td><?=$salary->getAttributeLabel($attributeName)?></td>
                <?php foreach($salaries as $salary):?>
                    <td><?=$salary->getAttribute($attributeName)?></td>
                <?php endforeach;?>
            </tr>
            <tr>
                <?php $attributeName = 'amount_vacation'?>
                <td><?=$salary->getAttributeLabel($attributeName)?></td>
                <?php foreach($salaries as $salary):?>
                    <td><?=$salary->getAttribute($attributeName)?></td>
                <?php endforeach;?>
            </tr>
            <tr>
                <?php $attributeName = 'amount_sick_list'?>
                <td><?=$salary->getAttributeLabel($attributeName)?></td>
                <?php foreach($salaries as $salary):?>
                    <td><?=$salary->getAttribute($attributeName)?></td>
                <?php endforeach;?>
            </tr>
            </tbody>
            <tfoot>
            <?php if (count($salaries)>1):?>
                <tr>
                    <td></td>
                    <?php foreach($salaries as $salary):?>
                        <td><?=$salary->total?></td>
                    <?php endforeach;?>
                </tr>
                <?php endif?>
                <tr>
                <td>Начисленно за <?=DateTime::createFromFormat('Y-m-d', $model->issue->at)->format('M Y')?></td>
                <td colspan=<?php echo count($salaries)?> >
                    <?=$model->getSalaries()->totalAmount()?>
                </td>
                </tr>
            </tfoot>
        </table>
        </td>
    </tr>


    <tr><td>

        <table width=100%>
            <thead>
            <tr><th align="center" colspan=2 >УДЕРЖАНИЕ </th></tr>
            </thead>
            <tbody>
            <?php $attributeName = 'amount_pension';?>
            <tr>
                <td><?=$model->getAttributeLabel($attributeName)?></td>
                <td><?=$model->getAttribute($attributeName)?></td>
            </tr>
            <?php $attributeName = 'amount_income_tax';?>

            <tr>
                <td><?=$model->getAttributeLabel($attributeName)?></td>
                <td><?=$model->getAttribute($attributeName)?></td>
            </tr>
            <?php $attributeName = 'amount_execution_list';?>

            <tr>
                <td><?=$model->getAttributeLabel($attributeName)?></td>
                <td><?=$model->getAttribute($attributeName)?></td>
            </tr>
            <?php $attributeName = 'amount_labor_union';?>

            <tr>
                <td><?=$model->getAttributeLabel($attributeName)?></td>
                <td><?=$model->getAttribute($attributeName)?></td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td>Всего удержано</td>
                <td><?=$model->total?></td>
            </tr>
            <tr>
                <td>На карточку</td>
                <td><?=$model->amount_card?></td>
            </tr>
<!--            <tr>
                <td>Остаток на счёте</td>
                <td><?/*=\yii\helpers\ArrayHelper::getValue($model,'officer.balance.remain', 0)*/?></td>
            </tr>-->
            </tfoot>
        </table>
        </td>
    </tr>
</table>
<div style="page-break-after: always"></div>
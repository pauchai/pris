<table width="100%" cellpadding="4" cellspacing="0" border=1>
    <col width="256*">
    <tr>
        <td width="100%" valign="top" >
            <p><b>КВИТАНЦИЯ</b> № 1 Тараклия</p>
        </td>
    </tr>
    <tr>
        <td width="100%" valign="top" >
            <b>1006601002064</b>
        </td>
    </tr>
    <tr>
        <td width="100%" valign="top" >
            <?=$model->prisoner->person->fio?>
        </td>
    </tr>
    <tr>
        <td width="100%" valign="top" >
            <p>Фио</p>
        </td>
    </tr>
    <tr>
        <td width="100%" valign="top" >
            <?=$model->reason?>
        </td>
    </tr>
    <tr>
        <td width="100%" valign="top" >
            <p>(reason)</p>
        </td>
    </tr>
    <tr>
        <td width="100%" valign="top" >
            <table width="100%" cellpadding="4" cellspacing="0" border="1">
                <col width="85*">
                <col width="85*">
                <col width="85*">
                <tr valign="top">
                    <td width="33%" >
                        <b>Вид перевода</b>
                    </td>
                    <td width="33%" >
                        <b>Дата</b>
                    </td>
                    <td width="33%" >
                        <b>(Amount)</b>
                    </td>
                </tr>
                <tr valign="top">
                    <td width="33%" >
                        <?=$model->category->title?>
                    </td>
                    <td width="33%" >
                        <?=$model->atJui?>
                    </td>
                    <td width="33%" >
                        <?=$model->amount?>
                    </td>
                </tr>
            </table>
            <p><br/>

            </p>
        </td>
    </tr>
    <tr>
        <td width="100%" valign="top" >
            <p><br/>

            </p>
        </td>
    </tr>
    <tr>
        <td width="100%" valign="top" >
            <p><br/>

            </p>
        </td>
    </tr>
    <tr>
        <td width="100%" valign="top" >
            <p>(остаток)<?php echo $model->prisoner->getBalances()->debit()->sum('amount') -$model->prisoner->getBalances()->credit()->sum('amount') ?></p>
        </td>
    </tr>
</table>
<div style="page-break-after: always"> </div>
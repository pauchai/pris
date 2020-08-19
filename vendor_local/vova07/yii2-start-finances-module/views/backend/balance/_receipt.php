<table width="100%" cellpadding="4" cellspacing="0" border=1>
    <col width="256*">
    <tr>
        <td width="100%" valign="top" align="center" colspan="2" >
            <p><b>КВИТАНЦИЯ</b> ПУ №1-Тараклия</p>
        </td>
    </tr>
    <tr>
        <td width="100%" valign="top" colspan="2" >
           <br/>
        </td>
    </tr>
    <tr>
        <td width="100%" valign="top" colspan="2" >
            <b>Фискальный код: 1006601002064</b>
        </td>
    </tr>
    <tr>
        <td width="100%" valign="top" colspan="2">
            <br/>

        </td>
    </tr>
    <tr>
        <td width="100%" valign="top" colspan="2" >
            <b>кому</b>: <?=$model->prisoner->fullTitle?>
        </td>
    </tr>
    <tr>
        <td width="100%" valign="top"  colspan="2">
            <br/>

        </td>
    </tr>
    <tr>
        <td width="100%" valign="top"  colspan="2">
            <b>причина</b>: <?=$model->reason?>
        </td>
    </tr>
    <tr>
        <td width="100%" valign="top"  colspan="2">
            <br/>
        </td>
    </tr>
    <tr>
        <td width="100%" valign="top" colspan="2" >
            <table width="100%" cellpadding="4" cellspacing="0" border="1">
                <col width="85*">
                <col width="85*">
                <col width="85*">
                <tr valign="top">
                    <th width="33%" >
                        Вид перевода
                    </th>
                    <th width="33%" >
                        Дата
                    </th>
                    <th width="33%" >
                        Сумма, лей
                    </th>
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
        <td width="40%" style="padding:3px;" >



        </td>
        <td width="60%"  style="padding:3px;" >

                <b>Подпись</b>:


        </td>

    </tr>
</table>
<div style="page-break-after: always"> </div>
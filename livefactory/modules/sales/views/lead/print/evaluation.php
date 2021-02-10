<?php
$title = 'HOJA DE EVALUACIÓN';

if ($model->office_id == 5) {
    $title .= ' - ANEXO C';
}
?>
<table border="0" cellpadding="0" cellspacing="0" class="row">
    <tbody>
    <tr>
        <td style="height: 50pt">&nbsp;</td>
    </tr>
    <tr>
        <td class="text-right">
            <strong><?=$title?></strong><br>
            Marque con un circulo su respuesta<br><br><br><br><br>
        </td>
    </tr>
    <tr>
        <td class="text-justify">
            <ol>
                <li>¿Su asesor fue cortés y claro en su presentación?<span class="yesorno"><span>SI</span><span>NO</span></span></li>
                <li>¿Considera que son adecuadas las instalaciones para brindarle el servicio de gestión?<span class="yesorno"><span>SI</span><span>NO</span></span></li>
                <li>¿Una vez obtenidos los recursos, serán destinados para?
                    <ul>
                        <li>__ PAGO DE PASIVOS</li>
                        <li>__ ADQUIRIR BIENES MUEBLES o INMUEBLES</li>
                        <li>__ INVERSIÓN</li>
                        <li>__ OTRA</li>
                    </ul>
                </li>
                <li>En el caso de la cancelación de su contrato, ¿le explicaron sobre las deducciones?<span class="yesorno"><span>SI</span><span>NO</span></span></li>
                <li>¿Le comentaron que en caso de ser necesario deberá proporcionar alguno de los requisitos marcados en el Check List?<span class="yesorno"><span>SI</span><span>NO</span></span></li>
                <li>Los honorarios que está cubriendo, corresponden al servicio de gestión crediticia ¿Le queda claro?<span class="yesorno"><span>SI</span><span>NO</span></span></li>
                <li>¿Está consciente que la tasa de interés final será la determinada por la institución que otorgue el recurso, y que ésta puede variar a partir del 6.33%?<span class="yesorno"><span>SI</span><span>NO</span></span></li>
                <li>¿Le informaron que el costo de los gastos notariales y avalúo puede ser previo a la adjudicación de su crédito?<span class="yesorno"><span>SI</span><span>NO</span></span></li>
                <li>¿Sabía usted que contamos con servicio inmobiliario y asesoría legal? <span class="yesorno"><span>SI</span><span>NO</span></span></li>
                <li>¿Nos recomendaría?<span class="yesorno"><span>SI</span><span>NO</span></span></li>
            </ol>
        </td>
    </tr>
    </tbody>
</table>
<?php include 'signature.php'; ?>
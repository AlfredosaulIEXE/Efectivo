<?php
    $title = 'CHECK LIST';

    if ($model->office_id == 5) {
        $title .= ' - ANEXO B';
    }
?>
<table border="0" cellpadding="0" cellspacing="0" class="row"><tbody>
    <tr>
        <td style="height: 50pt">&nbsp;</td>
    </tr>
    <tr>
        <td class="text-center">
            <strong><?=$title?></strong><br><br><br><br>
        </td>
    </tr>
    <tr>
        <td>
            <ul>
                <li>OBLIGADO SOLIDARIO</li>
                <li>ESCRITURA DEL BIEN INMUEBLE</li>
                <li>CARTA DE LIBERACIÓN DE GRAVAMEN AVALÚO BANCARIO</li>
                <li>CONSTANCIA DE NO ADEUDO PREDIAL</li>
                <li>CONSTANCIA DE NO ADEUDO AGUA</li>
                <li>IDENTIFICACION OFICIAL</li>
                <li>ACTA DE NACIMIENTO</li>
                <li>ACTA DE MATRIMONIO</li>
                <li>COMPROBANTES DE INGRESOS DE LOS 6 MESES EN RELACIÓN 4 A 1 SEGÚN PAGO MENSUAL.</li>
                <li>SI ADQUIRIO EL TERRENO Y DESPUES CONSTRUYO O SE AMPLIO LA CONSTRUCCION REQUIERE:
                    <ul>
                        <li>CONSTANCIA DE ALINEAMIENTO Y NUMERO OFICIAL LICENCIA DE CONSTRUCCIÓN O CONSTANCIA DE REGULARIZACION, SEGÚN SEA EL CASO.</li>
                        <li>AVISO O MANIFIESTO DE TERMINACIÓN DE OBRA.</li>
                        <li>CONTRATACIÓN DE SEGURO</li>
                    </ul>
                </li>
                <li>PYME: SE SOLICITARÁ DOCUMENTOS DEPENDIENDO DEL PROYECTO</li>
                <li>SI CUALQUIERA DE LAS PARTES ES EXTRANJERA:
                    <ul>
                        <li>PRESENTAR SU FORMA MIGRATORIA QUE ACREDITE SU LEGAL ESTANCIA EN EL PAÍS.</li>
                    </ul>
                </li>
                <li>OTROS:
                    <ul>
                        <li>AUTORIZACIÓN DE SOLICITUID FIRMADA DE CONSULTA DE BURO DE CRÉDITO DE LA INSTITUCION FINANCIERA ANTE QUIEN SE ESTÉ TRAMITANDO, EN CASO DE SER UNA EMPRESA, DEBERÁ PRESENTAR LA SOLICITUD FIRMADA DE LA PERSONA MORAL Y DE LOS REPRESENTANTES LEGALES.</li>
                    </ul>
                </li>
            </ul>
        </td>
    </tr>
    </tbody>
</table>
<?php include 'signature.php'; ?>
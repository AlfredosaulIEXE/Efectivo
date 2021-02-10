<?php
use livefactory\modules\sales\includes\NumeroALetras;
$office = $model->office;
$temp3  =  \livefactory\models\Office::find()->where('id = 14')->all();
$temp4 = \livefactory\models\Office::find('id = 19')->all();
if ($office->id == 2 and $model->added_at < 1553666400) {
    $office = $temp3[0];
    $office->code = 'CC';
}
if ($office->id == 13 and $model->added_at < 1561266000){
    $office = $temp4[17];
    $office->code = 'M2';
}
$logo = ($office->code == 'CC' || $office->code == 'C1') ? ($model->added_at < 1553666400 ? 'tuefectivo2' : 'tuefectivo') : ($office->code == 'G3' ? 'metropoli' : ($office->code == 'M1' ? 'apoyofinanciero' : ($office->code == 'MX' ? 'efectivida' : ($office->code == 'L1' ? 'efectivida' : ($office->code == 'D1' ? 'business' :  ($office->code == 'G4' ? 'multi-servicios' : ($office->code == 'M2' ? ($model->added_at < 1561352400 ? 'avantti' : null) : ($office->code == 'G5' ? ($model->added_at >= 1579672800 ? 'one-capital': null ) : ($office->code == 'P1' ? 'impulsa' : ($office->code == 'P3' ? 'logo_gplaneacion' : null)))  )))))));
$file = '../office/'.$office->id.'.png?v'.$office->updated_at;

?>

    <table border="0" cellpadding="0" cellspacing="0" class="cover">
        <tbody>
        <tr>
            <td style="text-align: right">Folio de Solicitud: <?=$model->c_control?></td>
        </tr>
        <?php if (file_exists($file)){?>
            <tr>
                <td style="text-align: left"><img alt="image" src="<?=$file?>" height="<?= $office->height_document?>" /></td>
            </tr>
        <?php } else {
            if ($logo) {?>
                <tr>
                <td style="text-align: left"><img alt="image" src="../logo/<?=$logo?>.jpg?v<?=$office->updated_at?>" height="<?= $office->height_document?>" /></td>
            </tr>
        <?php }}  ?>
        <tr>
            <td style="text-align: left">
                <p>Asunto: Autorización de Viabilidad</p>
            <p>Estimado(a) : <?= $model->lead_name?></p>
                <p>&nbsp;</p>
            </td>
        </tr>
        <tr>
            <td style="text-align: left">
                <p>Agradecemos su preferencia respondiendo con compromiso y confianza.</p><br>
                <p>Le informamos que de acuerdo al estudio realizado basándonos en su capacidad de pago y endeudamiento, se le notifica que se obtuvo un resultado EXITOSO para el tramite de gestión de su crédito por la cantidad máxima de $ <?=$model->loan_amount?> (PESOS 00/100M/N).</p>
                <p>Por motivo de viabilidad de crédito se emite la clave de cliente <?=$model->c_control?></p>
                <p>El crédito se estará tramitando bajo el siguiente esquema:</p>
                <table style="width: 100%">
                    <tr>
                        <td style="text-align: left;width: 60%"> <ol >
                                <ul>

                                    <li> Tasa de interés anual fija desde (entre 7 y 14%)  </li>
                                    <li>Interés sobre saldos insolutos</li>
                                    <li>Pagos anticipados sin penalización</li>
                                </ul>
                            </ol></td>
                        <td style="width: 40%">
                            <img alt="image" src=<?= $model->bureau_status == null ? "../logo/viability.jpg": ($model->bureau_status == null ? "../logo/viability.jpg": ($model->bureau_status == 4 ? "../logo/viability.jpg": ($model->bureau_status == 3 ? "../logo/viability.jpg": ($model->bureau_status == 2 ? "../logo/viability1.jpg": ($model->bureau_status == 1 ? "../logo/viability2.jpg": "../logo/viability.jpg"))))) ?> height="140"  border="0">
                        </td>
                    </tr>
                </table>

                <p>&nbsp;</p>
                <p>NIVEL DE RIESGO</p>
                <p>AVAL: Identificación oficial, una propiedad con el valor equivalente al monto del crédito solicitado (Escritura de la propiedad). Última boleta predial. Avaluó bancario.</p>
                <p>Esperamos su pronta llamada para culminar con los trámites correspondientes.</p>
            </td>
        </tr>

        </tbody>
    </table>
<div id="pie"></div>
<?php include 'signature2.php'; ?>
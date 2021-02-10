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
$logo = ($office->code == 'CC' || $office->code == 'C1') ? ($model->added_at < 1553666400 ? 'tuefectivo2' : 'tuefectivo') : ($office->code == 'G3' ? 'metropoli' : ($office->code == 'M1' ? 'apoyofinanciero' : ($office->code == 'MX' ? 'efectivida' : ($office->code == 'L1' ? 'efectivida' : ($office->code == 'D1' ? 'business' :  ($office->code == 'G4' ? 'multi-servicios' : ($office->code == 'M2' ? ($model->added_at < 1561352400 ? 'avantti' : null) : ($office->code == 'G5' ? ($model->added_at >= 1579672800 ? 'one-capital': null ) : ($office->code == 'P1' ? 'impulsa' : ($office->code == 'P3' ? 'logo_gplaneacion' : null))) )))))));
$payments = \livefactory\models\Payment::find()->where('entity_id = ' . $model->id)->all();
$payment=$payments[0]['amount'] != null ? $payments[0]['amount'] : '0';
$total = $model->loan_commission - $payment;
$file = '../office/'.$office->id.'.png?v'.$office->updated_at;
?>

    <table border="0" cellpadding="0" cellspacing="0" class="cover" style="line-height: 200%;">
        <tbody>
        <tr>
            <td style="text-align: right">FECHA: <?=date("d-M-Y");?></td>
        </tr>
        <?php if (file_exists($file)){?>
            <tr>
                <td style="text-align: center"><img alt="image" src="<?=$file?>" height="<?= $office->height_document?>" /></td>
            </tr>
        <?php } else {
            if ($logo ) {?>
                <tr>
                <td style="text-align: center"><img alt="image" src="../logo/<?=$logo?>.jpg?v<?=$office->updated_at?>" height="<?= $office->height_document?>" /></td>
            </tr>
        <?php } }?>
        <tr>
            <td class="text-center">
                <strong>CONVENION ANEXO A CONTRATO DE PRESTACIÓN DE SERVICIOS</strong><br><br><br>
            </td>
        </tr>
        <tr>
            <td class="text-justify text-center ">
                <p>
                    POR ESTE CONDUCTO <strong><?=$office->business_name;?></strong> A QUIEN<br> DENOMINAMOS “EL PRESTADOR DE SERVICIOS”, Y POR LA OTRA,<br> EL DENOMINADO “EL CLIENTE” <strong><?=strtoupper($model->lead_name)?></strong>,<br> CELEBRANDO EL CONTRATO NUMERO <strong><?=strtoupper($model->c_control)?></strong> PARA LLEVAR A<br> CABO LA GESTIÓN DE UN CRÉDITO POR UN IMPORTE DE <strong>$<?=number_format($model->loan_amount, 2)?>M.N.</strong>
                </p>
                <p>
                    CON BASE A ESTA RELACIÓN <u>SE AUTORIZA EL CONVENIO DE PAGO</u><br>  POR <strong>$<?=number_format($payment, 2)?>M.N.</strong> BAJO EL CONCEPTO DE <u>ANTICIPO</u>; QUEDANDO A SU CUENTA Y CARGO<br> <strong>$<?=number_format($total, 2)?>M.N.</strong>; DEBIENDO CUBRIR DICHO IMPORTE EN UN PLAZO <strong>NO<br> MAYOR A 2 DÍAS HÁBILES,</strong> UNA VEZ CONCLUIDA LA GESTION CREDITICIA.
                </p>
            </td>
        </tr>
        </tbody>
    </table>
<?php include 'signature.php'; ?>
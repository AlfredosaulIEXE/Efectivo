<?php
use livefactory\modules\sales\includes\NumeroALetras;
use livefactory\models\State;
use livefactory\models\City;

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
$office_address = $office->address;
$office_state = State::findOne(['id' => $office_address->state_id])->state;
$office_city = City::findOne(['id' => $office_address->city_id])->city;
$logo = ($office->code == 'CC' || $office->code == 'C1') ? ($model->added_at < 1553666400 ? 'tuefectivo2' : 'tuefectivo') : ($office->code == 'G3' ? 'metropoli' : ($office->code == 'M1' ? 'apoyofinanciero' : ($office->code == 'MX' ? 'efectivida' : ($office->code == 'L1' ? 'efectivida' : ($office->code == 'D1' ? 'business' :  ($office->code == 'G4' ? 'multi-servicios' : ($office->code == 'M2' ? ($model->added_at < 1561352400 ? 'avantti' : null) : ($office->code == 'G5' ? ($model->added_at >= 1579672800 ? 'one-capital': null ) : ($office->code == 'P1' ? 'impulsa' : ($office->code == 'P3' ? 'logo_gplaneacion' : null)))  )))))));
$months = [null, 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
$logo2 = $office->code == 'CC' ? ($model->added_at < 1553666400 ? 'tuefectivo2' : 'tuefectivo?v') : ($office->code == 'G3' ? 'metropoli' : ($office->code == 'M1' ? 'apoyofinanciero' : ($office->code == 'MX' ? 'efectivida' : null)));
$file = '../office/'.$office->id.'.png?v'.$office->updated_at;
?>

    <table border="0" cellpadding="0" cellspacing="0" class="cover">
        <tbody>
        <?php if (file_exists($file)){?>
            <tr>
                <td style="text-align: left"><img alt="image" src="<?=$file?>" height="<?= $office->height_document?>" /></td>
            </tr>
        <?php  } else{
            if ($logo ) {?>
            <tr>
                <td style="text-align: left"><img alt="image" src="../logo/<?=$logo?>.jpg?v<?=$office->updated_at?>" height="<?= $office->height_document?>" /></td>
            </tr>
        <?php } } ?>

        <tr>
            <td class="text-justify">
                <p>&nbsp;</p>
                <p>Por este medio la empresa <strong><?=$office->business_name;?></strong>, de acuerdo al contrato con número de folio <strong><?= $model->c_control?></strong>, se compromete a gestionar y a finalizar el procedimiento para la obtención de una linea de crédito a nombre de <strong><?=$model->lead_name?></strong> por un monto total <strong>$<?=number_format($model->loan_amount, 2)?>M.N.</strong>, en un lapso no mayor de 5 a 12 días hábiles, a partir del depósito total de <strong>$<?=number_format($model->loan_commission, 2)?>M.N.</strong>, mismos que cubren los gastos de contractuales. </p>
                <p>&nbsp;</p>
                <p>Se hace constar que <strong><?=$model->lead_name?></strong> ya cumplió con la entrega a satisfacción todos los documentos y requisitos validados de forma legal y que además, ya están completos y revisados por <strong><?=$office->business_name;?></strong> para dicho fin.</p>
                <p>&nbsp;</p>
                <p>El prestador de servicio <strong><?=$office->business_name;?></strong>, se compromete que en caso de  NO REALIZAR la gestión y finalización del procedimiento del trámite para la obtención de la línea de crédito solicitada por <strong><?=$model->lead_name?></strong>, se compromete a gestionar la devolución inmediata del pago de gastos correspondiente a los <strong>$<?=number_format($model->loan_commission, 2)?>M.N.</strong>.</p>
                <p>&nbsp;</p>
                <p>Se extiende la presente para los fines legales a que haya lugar en <?=$office_state ?> , <?= $office_address->delegation?>. A <strong><?=strtoupper($months[date('n')])?> <?=date('d')?> DEL <?=date('Y')?></strong></p>
            </td>
        </tr>
        </tbody>
    </table>
<?php include 'signature.php'; ?>
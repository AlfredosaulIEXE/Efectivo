<?php
use livefactory\models\LeadStatus;
use livefactory\models\Payment;
use livefactory\models\Contact;
use livefactory\models\State;
use livefactory\models\City;
use livefactory\models\Lead;
use livefactory\modules\sales\includes\NumeroALetras;

$lead = Lead::findOne($model->entity_id);
$contact = Contact::findOne(['entity_id' => $lead->id, 'entity_type' => 'lead']);
$folio = ($lead->lead_status_id == LeadStatus::_CONVERTED ? $lead->c_contract : $lead->c_control) . '-P' . $model->folio;
$office = $lead->office;
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
$office_contact = $office->contact;
$office_state = State::findOne(['id' => $office_address->state_id])->state;
$office_city = City::findOne(['id' => $office_address->city_id])->city;
$office_address_str = ($office_address->address_1 ? $office_address->address_1 . ', ' : '') . $office_address->num_ext . ' '.(!empty($office_address->num_int) ? $office_address->num_int . ', ': ''). ($office_address->block ? 'Col. ' . $office_address->block . ', ' : '') . ($office_address->delegation ? $office_address->delegation. ', ' : '') . ($office_city ? $office_city. ', ' : '') . ($office_state ? $office_state. ', ' : '') . ($office_address->zipcode ? 'C.P. ' . $office_address->zipcode: '');
$months = [null, 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
$payments = Payment::find()->where(['entity_id' => $lead->id])->all();
$total = 0;
foreach ($payment as $pay){
    $total += $pay->amount;
}
$rest = $lead->loan_commission - $total;
$paymentOrigins = [
    1 => 'Efectivo',
    2 => 'Transferencia',
    3 => 'Depósito Bancario',
    4 => 'Tarjeta de Crédito/Débito',
    5 => 'Cheque',
];
$is_cc = $office->code ;
$logo = ($office->code == 'CC' || $office->code == 'C1')  ? ($lead->added_at < 1553666400 ? 'tuefectivo2' : 'tuefectivo') : ($office->code == 'G3' ? 'metropoli' : ($office->code == 'M1' ? 'apoyofinanciero' : ($office->code == 'MX' ? 'efectivida' : ($office->code == 'L1' ? 'efectivida' : ($office->code == 'D1' ? 'business' :  ($office->code == 'G4' ? 'multi-servicios' : ($office->code == 'M2' ? ($contact->added_at < 1561352400 ? 'avantti' : null) : ($office->code == 'G5' ? ($lead->added_at >= 1579672800 ? 'one-capital': null ) : ($office->code == 'P1' ? 'impulsa' : ($office->code == 'P3' ? 'logo_gplaneacion' : null)))  )))))));
$file = '../office/'.$office->id.'.png?v'.$office->updated_at;
$logo_height = ($office->code == 'CC' || $office->code == 'C1') || $office->code == 'MX'  ? '90' : ($office->code == 'G3' ? '160' : ($office->code == 'M1' ? '163' : ($office->code == 'D1' ? '163' : ($office->code == 'G4' ? '163' : null))));
$title = '';
if ($lead->office_id == 5) {
    $title .= '<h3>ANEXO D</h3>';
}

if ($rest < 0)
{
    echo 'not print';
}
else
{
?>
<table border="0" cellpadding="0" cellspacing="0" class="row receipt">
    <tbody>
    <tr>
        <td style="height: 30pt">&nbsp;</td>
    </tr>
    <tr class="top-box">
        <td rowspan="5" colspan="2" valign="top">
            <?php if (file_exists($file)){?>
                <img alt="image" src="<?=$file?>" height="<?= $office->height_custom?>" />
    <?php } else {
        if ($logo){?>
            <img alt="image" src="../logo/<?=$logo?>.jpg?v<?=$office->updated_at?>" height="<?= $office->height_custom?>" />
    <?php } } ?>
        </td>
        <td><?=$title.$office_address_str?></td>
    </tr>
    <tr class="top-box">
        <td><?=$office->website?></td>
    </tr>
    <tr class="top-box">
        <td><?=$office_contact->email?></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="left-box b-t-n">FECHA:</td>
        <td colspan="2" class="right-box b-t-n"><strong><?=strtoupper($months[date('n')])?> <?=date('d')?> DEL <?=date('Y')?></strong></td>
    </tr>
    <tr>
        <td class="left-box">FOLIO:</td>
        <td colspan="2" class="right-box"><strong><?=$folio?></strong></td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="title text-center">RECIBO DE PAGO</td>
    </tr>
    <tr class="label">
        <td colspan="3">RECIBIMOS DEL C.</td>
    </tr>
    <tr class="value">
        <td colspan="3"><?=$contact->first_name. ' ' . $contact->middle_name  . ' ' . $contact->last_name?></td>
    </tr>
    <tr class="label">
        <td colspan="3">La Cantidad de:</td>
    </tr>
    <tr class="value">
        <td>$<?=number_format($model->amount, 2)?></td>
        <td colspan="2">(<?=NumeroALetras::convertir($model->amount)?>, Pesos 00/100 M.N.)</td>
    </tr>
    <tr class="label">
        <td>Forma de Pago</td>
        <td colspan="2">Fecha de Pago</td>
    </tr>
    <tr class="value">
        <td><?=$paymentOrigins[$model->origin]?></td>
        <td colspan="2"><strong><?=strtoupper($months[date('n', strtotime($model->date))])?> <?=date('d', strtotime($model->date))?> DEL <?=date('Y', strtotime($model->date))?></strong></td>
    </tr>
    <tr class="label">
        <td colspan="3">Por la gestión de un crédito de:</td>
    </tr>
    <tr class="value">
        <td>$<?=number_format($lead->loan_amount, 2)?></td>
        <td colspan="2">(<?=NumeroALetras::convertir($lead->loan_amount)?>, Pesos 00/100 M.N.)</td>
    </tr>
    <?php if ($model->type == 1 && $model->total_due != null && $model->total_due>0): ?>
    <tr class="label">
        <td colspan="3">Quedando pendiente la cantidad de:</td>
    </tr>
    <tr class="value">
        <td>$<?=number_format($model->total_due, 2)?></td>
        <td colspan="2">(<?=NumeroALetras::convertir($model->total_due)?>, Pesos 00/100 M.N.)</td>
    </tr>
    <?php endif; ?>
    <tr class="value divider">
        <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    </tbody>
</table>
<?php
$model = $lead;
?>
<?php include 'signature.php'; }?>
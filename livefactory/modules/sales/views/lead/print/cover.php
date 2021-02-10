<?php
use livefactory\models\LeadStatus;
use livefactory\models\Address;
use livefactory\models\Contact;
use livefactory\models\State;
use livefactory\models\City;
use yii\helpers\ArrayHelper;


$office = $model->office;
//Office CC in double office for date
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
$job = Contact::findOne(['entity_id' => $model->id, 'entity_type' => 'lead.job']);
$months = [null, 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
$leadCivilStatus = [
    1 => 'Soltero/a',
    2 => 'Comprometido/a',
    3 => 'Casado/a',
    4 => 'Divorciado/a',
    5 => 'Viudo/a'
];
$leadCivilStatusRegime = [
    1 => 'Separación de bienes',
    2 => 'Sociedad Conyugal'
];
$contact = Contact::findOne(['entity_id' => $model->id, 'entity_type' => 'lead']);
$address = Address::findOne(['entity_id' => $model->id, 'entity_type' => 'lead']);
$state = State::findOne(['id' => $address->state_id])->state;
$city = City::findOne(['id' => $address->city_id])->city;
$address_str = ($address->address_1 ? $address->address_1 . ', ' : '') . $address->num_ext . ' '.(!empty($address->num_int) ? $address->num_int . ', ': ''). ($address->block ? 'Col. ' . $address->block . ', ' : '') . ($address->delegation ? $address->delegation. ', ' : '') . ($city ? $city. ', ' : '') . ($state ? $state. ', ' : '') . ($address->zipcode ? 'C.P. ' . $address->zipcode: '');
$spouse = Contact::findOne(['entity_id' => $model->id, 'entity_type' => 'lead.spouse']);
$job = Contact::findOne(['entity_id' => $model->id, 'entity_type' => 'lead.job']);
$job_address = Address::findOne(['entity_id' => $model->id, 'entity_type' => 'lead.job']);
$job_state = State::findOne(['id' => $address->state_id])->state;
$job_city = City::findOne(['id' => $address->city_id])->city;
$job_address_str = ($job_address->address_1 ? $job_address->address_1 . ', ' : '') . $job_address->num_ext . ' '.(!empty($job_address->num_int) ? $job_address->num_int . ', ': ''). ($job_address->block ? 'Col. ' . $job_address->block . ', ' : '') . ($job_address->delegation ? $job_address->delegation. ', ' : '') . ($job_city ? $job_city. ', ' : '') . ($job_state ? $job_state. ', ' : '') . ($job_address->zipcode ? 'C.P. ' . $job_address->zipcode: '');
// References
$ref1c = Contact::findOne(['entity_id' => $model->id, 'entity_type' => 'lead.ref.1']);
$ref2c = Contact::findOne(['entity_id' => $model->id, 'entity_type' => 'lead.ref.2']);
$ref1a = Address::findOne(['entity_id' => $model->id, 'entity_type' => 'lead.ref.1']);
$ref2a = Address::findOne(['entity_id' => $model->id, 'entity_type' => 'lead.ref.2']);
$ref1s = State::findOne(['id' => $ref1a->state_id])->state;
$ref1y = City::findOne(['id' => $ref1a->city_id])->city;
$ref2s = State::findOne(['id' => $ref2a->state_id])->state;
$ref2y = City::findOne(['id' => $ref2a->city_id])->city;
$ref_count = 1;
//$folio = $model->lead_status_id == LeadStatus::_CONVERTED ? $model->c_contract : $model->c_control;
$is_cc = $office->code == 'CC';
$logo = ($office->code == 'CC' || $office->code == 'C1') ? ($model->added_at < 1553666400 ? 'tuefectivo2' : 'tuefectivo') : ($office->code == 'G3' ? 'metropoli' : ($office->code == 'M1' ? 'apoyofinanciero' :($office->code == 'MX' ? 'efectivida' : ($office->code == 'L1' ? 'efectivida' : ($office->code == 'D1' ? 'business' :  ($office->code == 'G4' ? 'multi-servicios' : ($office->code == 'M2' ? ($model->added_at < 1561352400 ? 'avantti' : null) : ($office->code == 'G5' ? ($model->added_at >= 1579672800 ? 'one-capital': null ) : ($office->code == 'P1' ? 'impulsa' : ($office->code == 'P3' ? 'logo_gplaneacion' : null)))  )))))));
$logo_height = ($office->code == 'CC' || $office->code == 'C1') || $office->code == 'MX'  ? '90' : ($office->code == 'G3' ? '160' : ($office->code == 'M1' ? '163' : ($office->code == 'D1' ? '163' : ($office->code == 'G4' ? '163' : null))));

$title = '';
if ($model->office_id == 5) {
    $title .= '<h3>ANEXO A</h3>';
}
$temp = strtok($model->user->alias," ");
$count=0;
while($temp !== false) {
    // En los tokens subsecuentes no se include el string $cadena
    $temp1=$temp1.substr($temp,0,1);
    $temp = strtok(" \n\t");
    $count++;
}
$file = '../office/'.$office->id.'.png?v'.$office->updated_at;
$payment =  \livefactory\models\Payment::findBySql("SELECT *  FROM tbl_payment WHERE entity_id = " . $model->id ."  ORDER BY `id`  DESC")->all();
//var_dump($payment[0]->total_due, substr($payment[0]->total_due , 0 ,1));
$total = 0;
foreach ($payment as $pay){
    $total += $pay->amount;
}
$rest = $model->loan_commission - $total;

if ($rest < 0)
{
    echo 'not print';
}
else
{
?>
<table border="0" cellpadding="0" cellspacing="0" class="cover">
    <tbody>
    <tr>
        <td style="height: 30pt">&nbsp;</td>
    </tr>
    <tr class="top-box">
        <td rowspan="7" colspan="2" valign="top">

            <?php if (  file_exists($file)){?>
                <img alt="image" src="<?=$file?>" height="<?= $office->height_custom?>" />
            <?php  } else{
                if ($logo){?>
                    <img alt="image" src="../logo/<?=$logo?>.jpg?v<?=$office->updated_at?>" height="<?= $office->height_custom?>" />
                <?php } } ?>
        </td>
        <td></td>
        <td colspan="2"><?=$title.$office_address_str?></td>
    </tr>
    <tr class="top-box">
        <td></td>
        <td colspan="2"><?=$office->website?></td>
    </tr>
    <tr class="top-box">
        <td></td>
        <td colspan="2"><?=$office_contact->email?></td>
    </tr>
    <!--<tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>-->
    <tr>
        <td colspan="3" style="height: 15pt">&nbsp;</td>
    </tr>
    <tr>
        <td></td>
        <td class="left-box b-t-n">FECHA:</td>
        <td class="right-box b-t-n"><strong><?=strtoupper($months[date('n')])?> <?=date('d')?> DEL <?=date('Y')?></strong></td>
    </tr>
    <tr>
        <td></td>
        <td class="left-box b-t-n">Folio:</td>
        <td class="right-box b-t-n"><strong><?=$model->c_control?></strong></td>
    </tr>
    <tr>
        <td></td>
        <td class="left-box">Contrato:</td>
        <td class="right-box"><strong><?=$model->c_contract?></strong></td>
    </tr>
    <tr>
        <td colspan="5" class="title">Datos generales del crédito</td>
    </tr>
    <tr class="label">
        <td>Apellido Paterno</td>
        <td>Apellido Materno</td>
        <td>Nombre (s)</td>
        <td>Fecha de Nacimiento</td>
        <td>Lugar de Nacimiento</td>
    </tr>
    <tr class="value">
        <td><?=$contact->middle_name?></td>
        <td><?=$contact->last_name?></td>
        <td><?=$contact->first_name?></td>
        <td><?=$model->birthdate?></td>
        <td><?=$model->place_of_birth?></td>
    </tr>
    <tr class="label">
        <td>CURP</td>
        <td>RFC</td>
        <td colspan="2">Estado Civil</td>
        <td>Dependientes</td>
    </tr>
    <tr class="value">
        <td><?=$model->curp?></td>
        <td><?=$model->rfc?></td>
        <td colspan="2"><?=$leadCivilStatus[$model->civil_status]?><?=($model->civil_status == 3 ? ', ' . $leadCivilStatusRegime[$model->civil_status_regime] : '')?></td>
        <td><?=$model->economic_dep?></td>
    </tr>
    <tr class="label">
        <td colspan="5">Domicilio</td>
    </tr>
    <tr class="value">
        <td colspan="5">
            <?=$address_str?>
        </td>
    </tr>
    <tr class="label">
        <td>Teléfono Casa</td>
        <td>Teléfono Oficina</td>
        <td>Teléfono Celular</td>
        <td colspan="2">Correo electrónico</td>
    </tr>
    <tr class="value">
        <td><?=$contact->phone?></td>
        <td><?=$job->phone?><?=($job->phone_ext ? ' EXT.' . $job->phone_ext : '')?></td>
        <td><?=$contact->mobile?></td>
        <td colspan="2"><?=$contact->email?></td>
    </tr>
    <?php if ($model->civil_status == 3): ?>
    <tr class="value divider">
        <td colspan="5"></td>
    </tr>
    <tr>
        <td colspan="5" class="title">Cónyuge</td>
    </tr>
    <tr class="label">
        <td colspan="2">Nombre Completo</td>
        <td>Teléfono celular</td>
        <td colspan="2">Correo electrónico</td>
    </tr>
    <tr class="value">
        <td colspan="2"><?=$spouse->first_name. ' ' . $spouse->last_name . ' ' . $spouse->middle_name?></td>
        <td><?=$spouse->mobile?></td>
        <td colspan="2"><?=$spouse->email?></td>
    </tr>
    <?php endif; ?>
    <tr class="value divider">
        <td colspan="5"></td>
    </tr>
    <?php if ($ref1c || $ref2c): ?>
    <tr>
        <td colspan="5" class="title">Referencias</td>
    </tr>
    <?php endif; ?>
    <?php if ($ref1c && $ref1a): ?>
    <?php
        $ref1_address = $ref1a->address_1 . ', ' . $ref1a->num_ext . ' '.(!empty($ref1a->num_int) ? $ref1a->num_int: ''). ', Col. ' . $ref1a->block . ', ' . $ref1a->delegation . ', ' . $city . ', ' . $state . ', C.P. ' . $ref1a->zipcode;
        ?>
    <tr class="label">
        <td colspan="2">Referencia <?=$ref_count++?></td>
        <td>Teléfono de casa</td>
        <td>Teléfono celular</td>
        <td>Correo electrónico</td>
    </tr>
    <tr class="value">
        <td colspan="2"><?=$ref1c->first_name. ' ' . $ref1c->last_name . ' ' . $ref1c->middle_name?></td>
        <td><?=$ref1c->phone?></td>
        <td><?=$ref1c->mobile?></td>
        <td><?=$ref1c->email?></td>
    </tr>
    <?php if (strlen($ref1_address) > 50): ?>
    <tr class="label">
        <td colspan="5">Domicilio</td>
    </tr>
    <tr class="value">
        <td colspan="5"><?=$ref1_address?></td>
    </tr>
    <?php endif; ?>
    <?php endif; ?>
    <?php if ($ref2c && $ref2a): ?>
        <?php
        $ref2_address = $ref2a->address_1 . ', ' . $ref2a->num_ext . ' '.(!empty($ref2a->num_int) ? $ref2a->num_int: ''). ', Col. ' . $ref2a->block . ', ' . $ref2a->delegation . ', ' . $city . ', ' . $state . ', C.P. ' . $ref2a->zipcode;
        ?>
        <tr class="label">
            <td colspan="2">Referencia <?=$ref_count++?></td>
            <td>Teléfono de casa</td>
            <td>Teléfono celular</td>
            <td>Correo electrónico</td>
        </tr>
        <tr class="value">
            <td colspan="2"><?=$ref2c->first_name. ' ' . $ref2c->last_name . ' ' . $ref2c->middle_name?></td>
            <td><?=$ref2c->phone?></td>
            <td><?=$ref2c->mobile?></td>
            <td><?=$ref2c->email?></td>
        </tr>
    <?php if (strlen($ref2_address) > 50): ?>
        <tr class="label">
            <td colspan="5">Domicilio</td>
        </tr>
        <tr class="value">
            <td colspan="5"><?=$ref2_address?></td>
        </tr>
    <?php endif; ?>
    <?php endif; ?>
    <tr class="value divider">
        <td colspan="5"></td>
    </tr>
    <tr>
        <td colspan="5" class="title">Datos Laborales</td>
    </tr>
    <tr class="label">
        <td colspan="2">Empresa / Negocio</td>
        <td>Puesto</td>
        <td>AntigÜedad</td>
        <td>Ingreso Mensual</td>
    </tr>
    <tr class="value">
        <td colspan="2"><?=$model->company_name?></td>
        <td><?=$model->job?></td>
        <td><?=$model->labor_old?> meses</td>
        <td>$<?=number_format(($model->monthly_income + $model->monthly_income2), 2)?></td>
    </tr>
    <?php if (strlen($job_address_str) > 50): ?>
    <tr class="label">
        <td colspan="5">Domicilio</td>
    </tr>
    <tr class="value">
        <td colspan="5"><?=$job_address_str?></td>
    </tr>
    <?php endif; ?>
    <tr class="value divider">
        <td colspan="5"></td>
    </tr>
    <tr>
        <td colspan="5" class="title">CONDICIONES DE LA PRESTACION DE SERVICIOS</td>
    </tr>
    <tr class="label">
        <td>Monto Solicitado</td>
        <td>Monto Autorizado</td>
        <td>Honorarios $</td>
        <td>Honorarios %</td>
        <td>Asesor Financiero</td>
    </tr>
    <tr class="value">
        <td>$<?=number_format($model->loan_amount, 2)?></td>
        <td>$<?=number_format($model->loan_amount, 2)?></td>
        <td>$<?=number_format($model->loan_commission, 2)?></td>
        <td><?=$model->loan_interest?>%</td>
        <td><?=$temp1?></td>
    </tr>
    <tr class="value divider">
        <td colspan="5">&nbsp;</td>
    </tr>
    </tbody>
</table>
<?php include 'signature.php'; }?>
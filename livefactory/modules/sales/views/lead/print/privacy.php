<?php
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
$address = $office->address;
$state = State::findOne(['id' => $address->state_id])->state;
$city = City::findOne(['id' => $address->city_id])->city;
$office_address = $address->address_1 . ', ' . $address->num_ext . ' '.(!empty($address->num_int) ? $address->num_int: ''). ', Col. ' . $address->block . ', ' . $address->delegation . ', ' . $city . ', ' . $state . ', C.P. ' . $address->zipcode;
$months = [null, 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
?>
<table border="0" cellpadding="0" cellspacing="0" class="cover">
    <tbody>
    <tr>
        <td style="height: 30pt">&nbsp;</td>
    </tr>
    <tr>
        <td class="text-right">
            <strong>AVISO DE PRIVACIDAD</strong>
        </td>
    </tr>
    <tr>
        <td class="text-justify">
            <p>A fin de dar cumplimiento a lo establecido en la Ley Federal de Protección de Datos Personales en Posesión de Particulares, <strong><?=$office->business_name;?></strong> con domicilio para efectos convencionales en <strong><?=$office_address?></strong> hace constar la responsabilidad frente al tratamiento de datos personales observando los principios de licitud, consentimiento, información y lealtad.</p>
            <p>Los datos que serán revelados para el Titular serán todos aquellos necesarios para que la Responsable le pueda ofrecer la gestión para la tramitación de un crédito, ante alguna Institución de Crédito o Financiera. El tratamiento de datos que realizará la Responsable incluye una evaluación técnica, referente a la capacidad de endeudamiento para posteriormente desarrollar un proyecto individual que refleje su capacidad de pago y tope máximo de endeudamiento, análisis, uso, manejo, aprovechamiento, transferencia, disposición, almacenamiento, acceso, y cualquier otro análogo, que sea necesario para que la Responsable pueda ofrecerle al Titular un servicio de gestoría, obligándola a asumir el deber de confidencialidad impuesto a la entidad que se le hubiere revelado la información inicialmente.</p>
            <p>Por otra parte, el Titular acepta y autoriza a la Responsable para que transmita a cualquier tercero con los que la Responsable tenga celebrada, o celebre posteriormente, una relación jurídica o de negocios, los datos e información que le haya entregado. La transferencia de datos a los citados terceros se limitará a que estos realicen al Titular ofrecimientos relativos a los productos y servicios que ofrecen en virtud de su objeto social. A dichos terceros se les notificará el contenido del presente aviso de privacidad, por lo que quedarán obligados a respetar sus términos y limitantes.<br>
                La Responsable se obliga a tratar los datos revelados por el Titular con estricta confidencialidad en apego a lo estipulado en la Ley Federal de Protección de Datos Personales en Posesión de los Particulares y su reglamento, aplicando las medidas físicas y tecnológicas necesarias a fin de salvaguardar la integridad de los datos revelados.</p>
            <p>En el supuesto que el Titular quiera limitar el uso o divulgación de sus datos o bien ejercer los derechos de acceso, rectificación, cancelación u oposición, y los cambios que se le realicen, podrá hacerlo, consultando el procedimiento establecido por, <strong><?=$office->business_name;?></strong></p>
            <p>Por su parte, el Titular podrá revocar en cualquier momento el consentimiento aquí otorgado, siguiendo el procedimiento establecido por, <strong><?=$office->business_name;?></strong> Lo anterior en el procedimiento que no se darán efectos retroactivos a la revocación solicitada por el Titular.</p>
        </td>
    </tr>
    <tr>
        <td class="text-right">
            <strong><?=strtoupper($city . ', ' . $state)?> A <?=strtoupper($months[date('n')])?> <?=date('d')?> DEL <?=date('Y')?></strong>
        </td>
    </tr>
    </tbody>
</table>
<?php include 'signature.php'; ?>
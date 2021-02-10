<?php
use livefactory\modules\sales\includes\NumeroALetras;

use livefactory\models\Address;
use livefactory\models\Contact;
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
$office_contact = $office->contact;
$office_state = State::findOne(['id' => $office_address->state_id])->state;
$office_city = City::findOne(['id' => $office_address->city_id])->city;
$office_address_str = ($office_address->address_1 ? $office_address->address_1 . ', ' : '') . 'número ' . $office_address->num_ext . ' '.(!empty($office_address->num_int) ? $office_address->num_int . ', ': ''). ($office_address->block ? 'Col. ' . $office_address->block . ', ' : '') . 'localidad ' . ($office_address->delegation ? $office_address->delegation. ', ' : '')   . 'código postal' . ($office_address->zipcode ? 'C.P. ' . $office_address->zipcode: '') . ' en el estado de ' . ($office_state ? $office_state. ', ' : '');
$months = [null, 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
$logo = $office->code == 'CC' ? ($model->added_at < 1553666400 ? 'tuefectivo2' : 'tuefectivo') : ($office->code == 'G3' ? 'metropoli' : ($office->code == 'M1' ? 'apoyofinanciero' : ($office->code == 'MX' ? 'efectivida' : ($office->code == 'L1' ? 'efectivida' : ($office->code == 'D1' ? 'business' :  ($office->code == 'G4' ? 'multi-servicios' : ($office->code == 'M2' ? ($model->added_at < 1561352400 ? 'avantti' : null) : ($office->code == 'G5' ? ($model->added_at >= 1579672800 ? 'one-capital': null ) : ($office->code == 'P1' ? 'impulsa' : ($office->code == 'P3' ? 'logo_gplaneacion' : null)))  )))))));
$address = Address::findOne(['entity_id' => $model->id, 'entity_type' => 'lead']);
$state = State::findOne(['id' => $address->state_id])->state;
$city = City::findOne(['id' => $address->city_id])->city;
$address_str = ($address->address_1 ? $address->address_1 . ', ' : '') . $address->num_ext . ' '.(!empty($address->num_int) ? $address->num_int . ', ': ''). ($address->block ? 'Col. ' . $address->block . ', ' : '') . ($address->delegation ? $address->delegation. ', ' : '') . ($city ? $city. ', ' : '') . ($state ? $state. ', ' : '') . ($address->zipcode ? 'C.P. ' . $address->zipcode: '');
$file = '../office/'.$office->id.'.png?v'.$office->updated_at;

?>

    <table border="0" cellpadding="0" cellspacing="0" class="cover">
        <tbody>
        <?php if (file_exists($file)){?>
            <tr>
                <td style="text-align: center"><img alt="image" src="<?=$file?>" height="220" /></td>
            </tr>
        <?php } else {
            if ($logo ){?>
                <tr>
                <td style="text-align: center"><img alt="image" src="../logo/<?=$logo?>.jpg?v<?=$office->updated_at?>" height="220" /></td>
            </tr>
        <?php } } ?>
        <tr>
            <td class="text-center">
                <strong>AVISO DE PROPIEDAD INTELECTUAL Y PRIVACIDAD.</strong><br><br><br>
            </td>
        </tr>
        <tr>
            <td class="text-justify">
                <p>En cumplimiento a lo dispuesto en la Ley Federal de Protección de Datos Personales en Posesión de los Particulares, <strong><?=$office->business_name;?></strong>, le hace del conocimiento al particular del aviso de privacidad, así como del procedimiento para ejercer los derechos de acceso, rectificación, cancelación y oposición al tratamiento de sus datos personales, haciendo constar la responsabilidad frente al tratamiento de datos personales observando los principios de licitud, consentimiento, formalidad y lealtad, señalando como domicilio para efectos convencionales el ubicado en <strong><?= $office_address_str?></strong>.</p>
                <p>Derivado de la presente, el particular está en contacto con un conjunto de documentos, capacidades y conocimientos (know how) que le permiten <strong><?=$office->business_name;?></strong>, desarrollar su actividad, por lo que el particular se obliga a mantener toda la información y documentación que fuere objeto del presente negocio en estricta confidencialidad, no quedando permitida en ningún caso su reproducción, cesión, venta, alquiler o préstamo comprometiéndose el usuario, a título enunciativo y no limitativo, a no ceder su uso parcial o total de ninguna forma, así como a no divulgarlo, publicarlo, ni ponerlo de ninguna otra manera a disposición de otras personas, ni enviar publicidad sobre los bienes y servicios, salvo que conste la autorización expresa del contratante en el presente contrato.</p>
                <p>Por lo anterior, se le hace de su conocimiento que el presente documento, contratos, sus anexos, formularios, publicidad, textos, logotipos, diseños, gráficos y en general, todos los objetos y documentos relacionados de forma directa o indirecta con <strong><?=$office->business_name;?></strong>, son propiedad de éste, quedando prohibida su reproducción total o parcial sin autorización previa, así como su uso o utilización.</p>
                <p>De igual forma, el prestador se obliga a mantener los datos del cliente con carácter de confidencial y únicamente podrá ser enviada, utilizada, compartida, revelada, transferida, cedida y usada la información por <strong><?=$office->business_name;?></strong>, y terceros relacionados directa o indirectamente con ésta, con el fin de que se lleve a cabo la consultoría, intermediación y gestión crediticia ante algún ente crediticio.</p>
                <p>El tratamiento de datos que realizará <strong><?=$office->business_name;?></strong>, incluye una evaluación técnica, referente a la capacidad de pago y nivel de endeudamiento, así como análisis, uso, manejo, aprovechamiento, transferencia, dispersión, almacenamiento, acceso y cualquier análogo, que sea necesario para que la responsable pueda ofrecer el servicio de gestoría, intermediación y consultoría crediticia, obligándola a asumir el deber de confidencialidad impuesto a la entidad que se le hubiera revelado la información inicialmente.</p>
                <p>La moral <strong><?=$office->business_name;?></strong>, se obliga a tratar los datos revelados por el titular con estricta confidencialidad en apego a lo estipulado en la Ley Federal de Protección de Datos Personales en Posesión de los Particulares y su reglamento, aplicando las medidas físicas y tecnológicas necesarias a fin de salvaguardar la integridad de los datos revelados.</p>
                <p>En el supuesto que el titular quiera limitar el uso o divulgación de sus datos o bien ejercer los derechos de acceso, rectificación, cancelación u oposición, y los cambios que se le realicen, podrá hacerlo consultando el procedimiento establecido por la empresa. Por parte, el titular podrá revocar en cualquier momento el consentimiento aquí otorgado, siguiendo el procedimiento establecido. Lo anterior, en el procesamiento que no se darán efectos retroactivos a la revocación solicitada.</p>
            </td>
        </tr>
        </tbody>
    </table>
<?php include 'signature2.php'; ?>
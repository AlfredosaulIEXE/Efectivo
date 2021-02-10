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
$logo = ($office->code == 'CC' || $office->code == 'C1') ? ($model->added_at < 1553666400 ? 'tuefectivo2' : 'tuefectivo') : ($office->code == 'G3' ? 'metropoli' : ($office->code == 'M1' ? 'apoyofinanciero' : ($office->code == 'MX' ? 'efectivida' : ($office->code == 'L1' ? 'efectivida' : ($office->code == 'D1' ? 'business' :  ($office->code == 'G4' ? 'multi-servicios' : ($office->code == 'M2' ? ($model->added_at < 1561352400 ? 'avantti' : null) : ($office->code == 'G5' ? ($model->added_at >= 1579672800 ? 'one-capital': null ) : ($office->code == 'P1' ? 'impulsa' : ($office->code == 'P3' ? 'logo_gplaneacion' : null))) )))))));
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
                <td style="text-align: center"><img alt="image" src="<?=$file?>" height="<?= $office->height_document?>" /></td>
            </tr>
        <?php } else {
            if ($logo ){?>
                <tr>
                <td style="text-align: center"><img alt="image" src="../logo/<?=$logo?>.jpg?v<?=$office->updated_at?>" height="<?= $office->height_document?>" /></td>
            </tr>
        <?php } } ?>
        <tr>
            <td class="text-center">
                <strong>CONTRATO DE PRESTACIÓN DE SERVICIOS</strong><br><br><br>
            </td>
        </tr>
        <tr>
            <td class="text-justify">
                <p>CONTRATO DE PRESTACIÓN DE SERVICIOS DE INTERMEDIACIÓN, CONSULTORÍA Y GESTIÓN CREDITICIA QUE CELEBRAN POR UNA PARTE <strong><?=$office->business_name;?></strong> A QUIEN EN LO SUCESIVO SE LE DENOMINARÁ EL PRESTADOR, Y POR LA OTRA <strong><?=strtoupper($model->lead_name)?></strong> A QUIEN EN LO SUCESIVO SE LE DENOMINARÁ EL CLIENTE, QUIENES SE SOMETEN AL TENOR DE LO SIGUIENTE:</p>
                <p>&nbsp;</p>
                <p class="text-center"><strong>D E C L A R A C I O N E S</strong></p>
                <p>&nbsp;</p>
                <p><strong>I.	Declara el prestador que: </strong></p>
                <ol type="A">
                    <li>Es una persona moral legalmente constituida conforme a las leyes mexicanas, cuyo objeto es la realización de prestación de servicios de gestión, intermediación y consultoría crediticia a nombre de terceros, quien con los elementos adecuados y con el personal con experiencia y conocimiento suficiente para obligarse a lo estipulado.</li>
                    <li>El domicilio del prestador se encuentra ubicado en la calle <strong><?= $office_address_str?></strong>, el cual señala como domicilio para todos los efectos legales del presente contrato, y como aquel donde se celebra el presente contrato.</li>
                    <li>Informó y explicó al cliente el objeto del contrato y sus alcances en lenguaje claro y comprensible.</li>
                </ol>
                <p><strong>II.  DECLARA EL CLIENTE:</strong></p>
                <ol type="A">
                    <li>Es una persona que responde al nombre <strong><?= $model->lead_name?></strong>, y que es su deseo obligarse en los términos y condiciones del presente contrato.</li>
                    <li>Su domicilio se encuentra ubicado en la calle <?= $address_str ?>, así como señala el teléfono <?= $model->mobile?> y correo electrónico <?= $model->email?>, mismos que señala como medios de notificación.</li>
                    <li>Recibió del prestador toda la información relativa al servicio objeto del presente contrato, así como le fueron debidamente detallados y explicados los procedimientos y actividades del prestador del servicio, cotización de  precios, tarifas y variaciones del servicio, formularios y documentos necesarios para la prestación del servicio, todo ello en lenguaje claro y comprensible, y los cuales entendió y comprendió, por lo que decidió contratar de forma libre, total y en su entera satisfacción, por así convenir sus intereses.</li>
                    <li>Que tuvo la oportunidad de examinar la información y documentación relativa al servicio, y que fue puesta a su disposición para asesoramiento con terceros independientes al prestador; y que, por tanto, conoce la operación y ha realizado su propia estimación sobre ésta.</li>
                </ol>
                <p>Las partes convienen en obligarse conforme a las siguientes:</p>
                <p class="text-center"><strong>CLÁUSULAS</strong></p>
                <p></p>
                <p><strong>PRIMERA. CONSENTIMIENTO DE LAS PARTES. </strong> Las partes manifiestan su voluntad para consentir que la naturaleza jurídica del presente contrato es la prestación del servicio de intermediación, consultoría y gestión crediticia; por lo que el prestador se obliga a prestar el servicio a favor del cliente, y en consecuencia, el cliente pagará un precio cierto y determinado por la prestación, autorizando el cliente al prestador que éste preste el servicio y accesorios de manera directa o por terceros, con el fin de cumplir el objeto del presente contrato. La prestación se limita a las siguientes actividades y alcances:</p>
                <ol type="N">
                    <li>La realización de consultoría sobre la capacidad de pago y nivel de endeudamiento del cliente por medio de una opinión técnica del perfil transaccional y crediticio del cliente, con el fin de determinar la viabilidad de gestiones crediticias a su favor.</li>
                    <li>La realización de intermediación y gestión ante los medios de crédito o financiamiento al alcance del prestador, en favor del cliente, en caso de resultar, conforme a la opinión técnica del perfil transaccional y crediticio del cliente, viable la realización de gestiones crediticias; lo anterior, únicamente si el cliente manifiesta a la prestadora su voluntad, de que se realice a su nombre tal actividad, para lo cual, se recibirán los documentos que deben aportarse para tal caso.</li>
                    <li>Realización de asesoría sobre la liquidación, reducción o reestructuración de adeudos crediticios, frente a cualquier ente de crédito,lo anterior, únicamente si el cliente manifiesta a la prestadora su voluntad, de que se realice a su nombre tal actividad, para lo cual, se recibirán los documentos que deben aportarse para tal caso.</li>
                </ol>
                <p><strong>SEGUNDA. DOCUMENTACIÓN DEL CLIENTE.</strong>El Cliente se compromete a entregar al prestador la documentación e información veraz, adecuada y oportuna que le sea requerida para la prestación a favor del cliente, misma que está sujeta a la evaluación de la situación financiera, jurídica, procesal y crediticia del cliente que realice el ente crediticio.</p>
                <p>&nbsp;</p>
                <p><strong>TERCERA. CONTRAPRESTACIÓN. </strong>. Para el cálculo de la prestación del servicio objeto del presente contrato, se establece que el prestador cobrará por honorarios una cantidad desde el diez por ciento (10 %) de la cantidad total de la suma de dinero que el cliente ordene al prestador a gestionar en crédito, o en su caso, reestructurar en su favor.</p>
                <p>&nbsp;</p>
                <p>El cliente reconoce que para la prestación del servicio es necesaria la erogación de gastos accesorios, tales como gastos administrativos, de investigación, de operación, entre otros que corren a cargo del cliente, mismos gastos que se exceptúan al costo de la prestación, y que deberán ser pagadas por el cliente al prestador, por lo que le deberán ser requisitados por el prestador para su pago, según resulte su procedencia. </p>
                <p>&nbsp;</p>
                <p>El prestador no podrá hacer el cobro de contraprestación que no se encuentre contemplada en el presente contrato y sus anexos; salvo aquellos que sean solicitados por escrito por el contratante y no se hallen contemplados en el presente contrato. </p>
                <p>Las cantidades antes descritas deberán ser cubiertas al momento del requerimiento de éstas por el prestador, mismas cantidades a las cuales deberá cubrir el cliente el impuesto al valor agregado (IVA), correspondiente que se genere por la operación.</p>
                <p>&nbsp;</p>
                <p>Todo pago deberá efectuarse en las instalaciones del prestador o en las instituciones financieras que el prestador designe, mismo que una vez verificado será debidamente acreditado con recibo de pago, previa entrega de ficha de depósito.</p>
                <p>&nbsp;</p>
                <p>Se dará por nulo cualquier pago realizado a persona o institución diferente o ajena a la designada por el prestador, ya sea pago en efectivo, depósito o transferencias bancarias, interbancarias, realizadas de forma electrónica o por internet.</p>
                <p>&nbsp;</p>
                <p>En caso que el cliente no liquidé dentro del plazo señalado el servicio ofertado, se rescindirá el presente contrato de forma unilateral por el prestador, sin necesidad de consentimiento alguno por parte del cliente, eximiendo de cualquier responsabilidad al prestador, precisándose que en caso de existir la realización de pagos parciales realizados por el cliente, por el incumplimiento del cliente, el prestador se adjudicará, por concepto de penalización por incumplimiento contractual, las sumas entregadas como pago en parcialidades del servicio, así como los gastos accesorios erogados hasta ese momento para la prestación del servicio, todas esas cantidades más el Impuesto al Valor Agregado (IVA) que legalmente se generé por tal operación.</p>
                <p>&nbsp;</p>
                <p>Si el crédito obtenido por el cliente fuera mayor al solicitado inicialmente por éste, el cliente se obliga con el prestador a pagarle una compensación por contraprestación, consistente en una bonificación, la cual será el pago de la diferencia del equivalente al diez por ciento total obtenido del crédito otorgado.</p>
                <p>&nbsp;</p>
                <p><strong>CUARTA. PROPIEDAD INTELECTUAL Y AVISO DE PRIVACIDAD. </strong>A fin de dar cumplimiento a la Ley Federal de Protección de Datos Personales en Posesión de los Particulares, así como al Secreto bancario, previo a la firma del presente contrato y en cumplimiento, el prestador hizo del conocimiento al contratante del aviso de privacidad, así como del procedimiento para ejercer los derechos de acceso, rectificación, cancelación y oposición al tratamiento de sus datos personales, por lo que se someten a lo estipulado en tal documento. Por otra parte, es obligación de las partes mantener toda la información, imagen, documentación y cualquier objeto que fuere parte del presente contrato o negociación en estricta confidencialidad, no quedando permitida en ningún caso su reproducción, cesión, venta, alquiler o préstamo comprometiéndose el usuario, a título enunciativo y no limitativo, a no ceder su uso parcial o total de ninguna forma, así como a no divulgarlo, publicarlo, ni ponerlo de ninguna otra manera a disposición de otras personas, ni enviar publicidad o información sobre los bienes y servicios, salvo que conste la autorización expresa por escrito del prestador.</p>
                <p>&nbsp;</p>
                <p><strong>QUINTA. OBLIGACIONES DEL CLIENTE. </strong>Son obligaciones del cliente, persona física o moral que adquiere el servicio de otorgado por el prestador, las siguientes:</p>
                <ol type="N">
                    <li>Entregar la documentación requerida, así como servirse a signar formularios y documentos necesarios para la prestación del servicio.</li>
                    <li>Cumplir con el pago de los montos establecidos como contraprestación en este contrato.</li>
                    <li>Colaborar con el prestador y terceros relacionados en las actividades que éste realice en beneficio del servicio.</li>
                    <li>Guardar secreto sobre los asuntos e información que el prestador le confíe.</li>
                    <li>Cumplir con las obligaciones establecidas en los anexos del contrato.</li>
                </ol>
                <p>&nbsp;</p>
                <p><strong>SEXTA. OBLIGACIONES DEL PRESTADOR: </strong> Son obligaciones del prestador del servicio, la persona que ofrece el servicio mediante el cobro de un precio cierto y determinado, las siguientes:</p>
                <ol type="N">
                    <li>Prestar el servicio convenido, poniendo todos sus conocimientos de intermediación, consultoría y gestoría crediticia al servicio del cliente.</li>
                    <li>Erogar las expensas o gastos que sean necesarios para el desempeño del servicio, con excepción a las expensas que se compromete a pagar por su cuenta el cliente, cuando el prestador erogue tales expensas tendrá derecho a que se le reembolsen por el cliente con el rédito legal desde el día en que se hicieron.</li>
                    <li>Guardar secreto sobre los asuntos e información que el cliente le confíe, salvo los informes que deba proporcionar conforme a las leyes respectivas.</li>
                </ol>
                <p>&nbsp;</p>
                <p><strong>SÉPTIMA. DESARROLLO DEL SERVICIO. </strong>El prestador informa al cliente que su servicio se desarrollará de la siguiente forma:</p>
                <ol type="N">
                    <li><strong>CONSULTORÍA CREDITICIA. </strong>La prestadora realizará un análisis sobre la capacidad de pago y nivel de endeudamiento del cliente por medio de una opinión técnica del perfil transaccional y crediticio del cliente, con el fin de determinar la viabilidad de gestiones crediticias a su favor.</li>
                    <li><strong>GESTIÓN CREDITICIA. </strong>Realización de gestión crediticia, en favor del cliente, en caso de resultar viable la realización de gestiones crediticias conforme a la opinión técnica del perfil transaccional y crediticio del cliente, misma que se realizará en las oficinas del área de gestión de crédito de la prestadora, y para lo cual se le requerirá al cliente su colaboración para la firma de documentación para tal fin, así como la entrega de documentación complementaria, misma que está sujeta a los términos y condiciones del ente crediticio.</li>
                    <li><strong>ANÁLISIS DEL CRÉDITO. </strong>Una vez cubiertas las solicitudes anteriores, el ente crediticio determinará la viabilidad del crédito solicitado en gestión, según el resultado que arroje la evaluación jurídica, investigación y análisis de la condición financiera, jurídica, procesal y crediticia del cliente que realice el ente crediticio, cuyo resultado de tal evaluación es responsabilidad del cliente.</li>
                    <li><strong>OTORGAMIENTO Y FORMALIZACIÓN DEL CRÉDITO. </strong>En caso de resultar procedente, el cliente firmará, de conformidad, ante el ente crediticio, la línea de crédito gestionada, quedando abierta la posibilidad de que ésta sea mayor o menor al crédito solicitado.</li>

                </ol>
                <p>&nbsp;</p>
                <p><strong>OCTAVA. PRODUCTO O SERVICIO CREDITICIO GESTIONADO.</strong>La intermediación, consultoría, asesoría y gestión realizada por la prestadora está sujeta a los términos y condiciones del ente de crédito, su resultado está determinado por la evaluación jurídica, investigación y análisis de la condición financiera, jurídica, procesal y crediticia que éste realice del cliente, siendo el cliente el único responsable por el resultado.</p>
                <p>&nbsp;</p>
                <p><strong>NOVENA. VIGENCIA. </strong>La vigencia será de 45 (cuarenta y cinco) días hábiles contados a partir de la firma del mismo. Agotado el plazo ya mencionado, la prorroga será voluntaria para cualquiera de las partes, a fin de dar continuidad al servicio.</p>
                <p>&nbsp;</p>
                <p><strong>DÉCIMA. TERMINACIÓN ANTICIPADA Y DEVOLUCIÓN DE PAGOS.</strong>En caso de devoluciones, el prestador deberá devolver los montos procedentes en un lapso no mayor a 05 (cinco) días hábiles a partir de la resolución de la devolución, cuyo procedimiento, inicia al momento en que el cliente hace su solicitud de cancelación de la prestación, misma que se enviará tal solicitud al área de cancelación y devolución de pagos de la prestadora, en un término no mayor a diez días hábiles para proceder a su dictaminación respecto de su procedencia, conforme al presente contrato y los elementos con los que cuente respecto de la prestación, misma que en un término no mayor de quince días hábiles emitirá tal resolución, y la cuál se hará de su conocimiento al cliente, en caso de resultar procedente. </p>
                <p>Si el cliente manifiesta su deseo de dar por terminada la relación contractual, una vez iniciado la prestación del servicio, éste deberá solicitar al prestador la baja de su trámite, y si así lo ameritará el caso, iniciar con el procedimiento de devolución del pago realizado por el cliente. Por lo anterior, el cliente rescindirá el presente contrato de forma unilateral, eximiendo de cualquier responsabilidad al prestador, por lo que el prestador se adjudicará, por concepto de penalización los montos pagados, salvo en los siguientes casos:</p>
                <ol type="a">
                    <li>El prestador no realice sus trámites de consultoría, intermediación y gestión. </li>
                    <li>El prestador pierda los permisos y licencias otorgadas por las autoridades competentes</li>
                </ol>
                <p>De igual forma, son causas de terminación anticipada del presente contrato que el cliente antes de que se haya iniciado la prestación del servicio avise al prestador que no requiere del servicio, por lo que el prestador podrá adjudicarse los montos pagados, salvo que el prestador no realice el trámite de consultoría, intermediación y gestión.

                    No habrá devolución de obligaciones contractuales bajo ninguna circunstancia que no se detalle en la presente cláusula.
                </p>
                <p>&nbsp;</p>
                <p><strong>DÉCIMA PRIMERA. CAUSAS DE RESCISIÓN. </strong>Serán causa de rescisión inmediata del presente contrato, las siguientes: </p>
                <ol type="a">
                    <li>El incumplimiento a cualquiera de las obligaciones establecidas en el presente contrato.</li>
                    <li>El incumplimiento con la obligación de pago de la contraprestación, así como de los gastos que se originan con motivo de la prestación, que corren directamente a cargo del cliente.</li>
                    <li>El cobro forzoso por conceptos adicionales que no correspondan a la prestación del servicio, y que no se hallen contemplados en el contrato, así como en sus anexos y adendas, mismos que forman parte del presente contrato.</li>
                    <li>En caso de que el prestador pierda los permisos y licencias otorgadas por las autoridades competentes, así como si es declarado en quiebra por sentencia ejecutoriada. </li>
                </ol>
                <p>&nbsp;</p>
                <p><strong>DÉCIMA SEGUNDA. CASO FORTUITO Y FUERZA MAYOR: </strong>Las partes no serán responsables de cualquier atraso o incumplimiento del presente contrato, cuando el mismo sea resultado de caso fortuito o fuerza mayor. En caso de que alguna de las partes se encuentre imposibilitada para cumplir con el presente contrato, deberá de hacerlo del conocimiento de la otra parte por escrito.</p>
                <p>&nbsp;</p>
                <p><strong>DÉCIMA TERCERA. PROCEDIMIENTO PARA DUDAS, QUEJAS Y RECLAMACIONES.</strong> El cliente podrá interponer duda, queja o reclamación vía telefónica al número de atención de clientes del prestador, así como acudir directamente a las oficinas de atención a clientes.</p>
                <p>&nbsp;</p>
                <p><strong>DÉCIMA CUARTA. MODIFICACIONES.</strong>Cualquier modificación realizada al presente contrato, se dará por nula, si no se ratificare por escrito y firmada por ambas partes. Por lo que cualquier convenio, acuerdo u oferta realizada de forma verbal con cualquier asesor, empleado, representante, comisionista y/o personal directo e indirecto del prestador quedará sin efectos jurídicos.</p>
                <p>&nbsp;</p>
                <p><strong>DÉCIMA QUINTA. VICIOS DEL CONSENTIMIENTO.</strong>Ambas partes acuerdan que es su libre voluntad llevar a cabo la celebración del presente contrato, y se manifiesta que a la firma del mismo no existe error, dolo, violencia, mala fe, o cualquier vicio del consentimiento, por lo que ambas partes renuncian a demandar su nulidad por cualquier causa.</p>
                <p>&nbsp;</p>
                <p><strong>DÉCIMA SEXTA. COMPETENCIA. </strong>Para todo lo relativo a la interpretación, aplicación y cumplimiento del presente contrato, las partes acuerdan sujetarse a la jurisdicción de los tribunales y autoridades del domicilio del prestador.</p>
                <p>&nbsp;</p>
                <p>Leído que fue y una vez hecha la explicación de su alcance legal y contenido, este contrato se firma por duplicado en cada una de sus hojas y al calce, en el domicilio del prestador, a  <strong><?=strtoupper($months[date('n')])?> <?=date('d')?> DEL <?=date('Y')?></strong>, entregándosele una copia del mismo a el contratante. </p>
                <p>&nbsp;</p>
            </td>
        </tr>
        </tbody>
    </table>
<?php include 'signature.php'; ?>
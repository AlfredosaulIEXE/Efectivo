<?php
use livefactory\models\Contact;
use livefactory\modules\sales\includes\NumeroALetras;

$signature = './img/signature.png';
$height = 90;
$office = $model->office;
if ($model->office_id == 2 || $model->office_id == 10 || $model->office_id == 15) {
    $signature = './img/signature-mau.png';
} else if ($model->office_id == 8) {
    $signature = './img/signature-mm.jpg';
} else if ($model->office_id == 5 || $model->office_id == 7) {
    $signature = './img/signature-p2.png';
    $height = 40;
} else if ($model->office_id == 9 || $model->office == 18) {
    $signature = './img/signature-sol.jpg';
}else if ($model->office_id == 12 ){
    $signature = './img/signature-pue3.jpg';
}else if($model->office_id == 11 || $model->office_id == 16){
    $signature = './img/signature-mauricio.jpg';
}
else if($model->office_id == 13 || $model->office_id == 19){
    $signature = './img/firma-salvador.jpg';
}
else if ($model->office_id == 17){
    $signature = './img/firma-irma.jpg';
}
//$contact = Contact::findOne(['entity_id' => $model->id, 'entity_type' => 'lead']);
?>
<table border="0" cellpadding="0" cellspacing="0" class="contractp2">
    <tbody>
    <!--<tr>
        <td style="height: 30pt">&nbsp;</td>
    </tr>-->
    <tr>
        <td class="text-center">
            <strong>CONTRATO DE PRESTACIÓN DE SERVICIOS</strong><br><br><br>
        </td>
    </tr>
    <tr>
        <td class="text-justify">
            <p>CONTRATO DE PRESTACIÓN DE SERVICIOS PROFESIONALES DE GESTORÍA, CONSULTORÍA E INTERMEDIACIÓN QUE CELEBRAN POR UNA PARTE, <strong><?=$office->business_name;?></strong>,  A QUIEN EN LO SUCESIVO SE LE DENOMINARÁ “EL PRESTADOR DE SERVICIO” Y POR OTRA PARTE LA PERSONA FÍSICA Y/O MORAL CUYOS DATOS SE SEÑALAN EN LA CARÁTULA DEL PRESENTE CONTRATO A QUIEN EN LO SUCESIVO SE LE DENOMINARÁ “EL CLIENTE” AL TENOR DE LAS SIGUIENTES DECLARACIONES Y CLÁUSULAS: </p>
            <p><strong>ANTECEDENTES:</strong></p>
            <p>I.-DECLARA “EL PRESTADOR DE SERVICIO” POR CONDUCTO DE SU REPRESENTANTE LO SIGUIENTE:</p>
            <p>A) SER UNA PERSONA MORAL, DEBIDAMENTE CONFORMADA POR LAS LEYES MEXICANAS, según lo acredita con la escritura pública número sesenta y nueve mil doscientos setenta y cinco, otorgada ante la fe del <?= $office->notary?>, titular de la notaría <?=is_numeric($office->notary_number) ? NumeroALetras::convertir($office->notary_number) : strtoupper($office->notary_number)?> de la Ciudad de <?=strtoupper($office->notary_state)?>, con Registro Federal de Contribuyentes  <?= $office->rfc?>.</p>
            <p>B) SE ENCUENTRA COMPRENDIDA LA PRESTACIÓN DE SERVICIOS PROFESIONALES EN ASUNTOS DE GESTIÓN DE CRÉDITOS  A PERSONAS FÍSICAS O MORALES, AJUSTANDO EL SERVICIO A LAS DIFERENTES LINEAS DE CRÉDITO APLICABLES EN EL MERCADO.</p>
            <p>II.- DECLARA “EL CLIENTE” </p>
            <p>A) SER UNA PERSONA CON CAPACIDAD LEGAL PARA CELEBRAR EL PRESENTE CONTRATO.<br>
            B) QUE ES SU LIBRE VOLUNTAD CONTRATAR LOS SERVICIOS DE “EL PRESENTADOR DEL SERVICIO”</p>
            <p>HECHAS LAS DECLARACIONES QUE ANTECEDEN LAS PARTES, CONVIENEN EN LAS SIGUIENTES: </p>
            <p><strong>CLÁUSULAS:</strong></p>
            <p><strong>PRIMERA</strong></p>
            <p><strong>OBJETO DEL CONTRATO</strong>. Convienen las partes expresamente que el objeto del presente contrato se limita a que “EL PRESTADOR DEL SERVICIO” realice las siguientes actividades, las que en lo sucesivo se denominarán como “LOS SERVICIOS”, obligándose el cliente a exhibir los documentos consignados en el Anexo ”B” que debidamente firmado por las partes forma parte del integrante del presente contrato.</p>
            <p>El plazo para la entrega de la documentación será de 5 días hábiles contados a partir de la firma del presente contrato.</p>
            <p><strong>ALCANCE</strong>. “EL PRESTADOR DEL SERVICIO”, elaborará a “EL CLIENTE” una evaluación técnica, referente a la capacidad de endeudamiento del cliente para desarrollar un proyecto que refleje su capacidad de pago y el tope  máximo de endeudamiento, mismo que se realizará en un plazo máximo de 5 días hábiles de la recepción de los citados documentos.</p>
            <p><strong>GESTION</strong>. Concluida la evaluación técnica y obteniendo la factibilidad  de “EL CLIENTE”, como una persona solvente, desde el punto de vista moral, legal y económico, podrá optar éste último, sólo si es su deseo y manifestándolo por escrito, hacer uso de la gestión que le ofrece “EL PRESTADOR DEL SERVICIO”  para la gestión de un crédito ante instituciones dadas de alta en la Comisión Nacional Bancaria de Valores (CNBV), tales como SOFOM, SOFOL, BANCOS, SOFIPO, Uniones de Crédito, etc.</p>
            <p>Está de acuerdo en celebrar el presente Contrato, con el fin de presentar sus servicios para ayudar al Cliente a liquidar, reducir o reestructurar en su caso sus adeudos en tarjetas de crédito, frente a cualquier Institución de Crédito, o cualesquiera otras Sociedades que otorguen crédito de consumo o créditos a través de tarjetas de crédito, siempre por orden y cuenta del Cliente, en los términos y condiciones establecidos en éste Contrato.</p>
            <p><strong>SEGUNDA. DE LA INFORMACIÓN.</strong></p>
            <p><strong>2.1. PRIVACIDAD</strong>.  “EL CLIENTE” autoriza a “EL PRESTADOR DE SERVICIO” para compartir a terceros cualquier documentación o información que le haya sido entregada por “EL CLIENTE”, así como éste último autoriza al primero de los nombrados a indagar o investigar la veracidad de la información proporcionada, así como obtener por sus medios toda la información necesaria para los fines que sean objeto del presente contrato.</p>
            <p><strong>2.2. REVELACIÓN DE INFORMACIÓN</strong>.  “EL CLIENTE” se obliga a mantener  toda la información derivada del objeto del presente contrato en estricta confidencialidad quedando prohibida la divulgación por cualquier medio a terceros de la información, en caso de no cumplir esta cláusula el cliente, se tramitara por la vía penal la difamación y la divulgación que ocasionare el cliente a la prestadora de servicio,  reproducida por cualquier medio, por lo que expresamente el primero autoriza al segundo para enviar o compartir por cualquier medio con la “INSTITUCIÓN FINANCIERA” la documentación personal o financiera proporcionada con la finalidad de conseguir la debida y oportuna  gestión del crédito.</p>
            <p><strong>TERCERA. CONTRAPRESTACIÓN.</strong></p>
            <p><strong>3.1 HONORARIOS</strong>. Una vez que “EL CLIENTE” esté de acuerdo y lo exprese con la firma del presente contrato, las partes acuerdan que por la prestación de “LOS SERVICIOS” “EL CLIENTE” pagará a “LA PRESTADORA DE SERVICIO” la cantidad consignada en el anexo  “D”, que debidamente firmado por las partes forma parte integrante del presente contrato.</p>
            <p><strong>3.2. EL PAGO</strong>. El pago de los honorarios deberá sujetarse a lo siguiente:</p>
            <p><strong>A)</strong> El pago descrito será únicamente cubierto en las instituciones financieras que “EL PRESTADOR DE SERVICIO” designe, el cual será acreditado mediante la exhibición por parte de “EL CLIENTE” de la ficha de depósito o comprobante con número de referencia asignado por aquel.</p>
            <p><strong>B)</strong> Será nulo todo pago o depósito hecho a persona o institución diferente o ajena a la designada por “EL PRESTADOR DE SERVICIO”. Así como cargos a cuentas bancarias ya sea de forma electrónica o por internet.</p>
            <p><strong>3.3. RECIBO</strong>. Por la cantidad que pague “EL CLIENTE” a “EL PRESTADOR DEL SERVICIO”, éste último se obliga a expedir  al primero el comprobante respectivo que se requiera para el cumplimiento del presente contrato. En caso de no tener comprobante de pago completo y en forma, se tendrá por no efectuado el pago.  </p>
            <p><strong>CUARTA. EVOLUCION</strong></p>
            <p><strong>4.1. PROCESO DE VIABILIDAD</strong>.  Éste se sujetará a lo siguiente: </p>
            <p><strong>A)</strong> Cubrir en tiempo y forma el pago por honorarios descrito en este contrato y sus anexos.</p>
            <p><strong>B)</strong> Cumplir oportunamente con todas las garantías y todos los requisitos que se soliciten a “EL CLIENTE”, en términos de los anexos “B”,  “C” y “D” que le solicite el "PRESTADOR DE SERVICIOS"</p>
            <p><strong>C)</strong> Firmar “EL CLIENTE”, en su oportunidad, de conformidad la línea de crédito obtenida y las condiciones de la misma.</p>
            <p><strong>4.2. AUTORIZACIÓN DE LA “INSTITUCIÓN FINANCIERA”</strong>.  En esta etapa “EL CLIENTE” cubrió todos y cada uno de los requisitos solicitados en este instrumento, sus anexos y la Institución Financiera; se determina la viabilidad del crédito gestionado para los efectos legales correspondientes. </p>
            <p><strong>QUINTA. DE LA TERMINACIÓN ANTICIPADA  </strong></p>
            <p>El presente contrato podrá terminar anticipadamente por las siguientes causas: </p>
            <br>
            <p><strong>5.1. CANCELACIÓN</strong>.  Éste contrato se podrá cancelar únicamente si no se hizo disposición del crédito autorizado por la “INSTITUCIÓN FINANCIERA”  y gestionado por el “PRESTADOR DE SERVICIOS”, en el  entendido de que “EL CLIENTE” deberá  dar aviso por escrito y personalmente a “EL PRESTADOR DE SERVICIOS”, debiendo de estar al corriente en sus obligaciones de este contrato al momento de solicitar la cancelación al Departamento  de Atención a Clientes.</p>
            <p>Por ese motivo se aplicará una pena convencional del 10% (diez por ciento) calculada sobre el monto del crédito que solicitó  “EL CLIENTE”, más el 16 % correspondiente  de IVA, así como las cantidades erogadas por otros conceptos.</p>
            <p><strong>5.2. RESCISIÓN</strong>.  En caso de que “EL CLIENTE” proporcione información falsa o equivoca, este contrato se rescindirá de pleno derecho y sin necesidad de declaración previa, a lo cual no recibirá reembolso alguno del pago realizado, por concepto de daños y perjuicios.</p>
            <p>Para el caso de que “EL CLIENTE” se abstenga de informar a “EL PRESTADOR DE SERVICIO” de cualquier circunstancia que modifique su situación crediticia, y aquel no la reporte oportunamente a “EL PRESTADOR DE SERVICIO”  se entiende que “EL CLIENTE” asume bajo su responsabilidad las consecuencias que se generen en el proceso de trámite de su crédito y al mismo tiempo se configurará en una causal de rescisión del presente contrato, sin responsabilidad para “EL PRESTADOR DE SERVICIO”.</p>
            <p><strong>5.3 INCUMPLIMIENTO DE CONTRATO</strong>.  Por el incumplimiento del presente contrato en cualquiera de sus cláusulas por parte de “EL CLIENTE” se aplicará una pena convencional equivalente al 10% (diez por ciento), calculado sobre el monto solicitado, establecido en el anexo “A” del presente contrato que debidamente firmado por las partes, forma parte integral del presente contrato.</p>
            <p><strong>SEXTA. DEL PRODUCTO. </strong></p>
            <p><strong>6.1 DEL PRODUCTO</strong>.  Los términos y condiciones del producto financiero serán determinados por el resultado que arroje el estudio sobre la capacidad económica de “EL CLIENTE”, así como de la oferta  de la “INSTITUCIÓN FINANCIERA”</p>
            <p><strong>6.2. DEVOLUCIONES</strong>.  No habrá devolución de Honorarios bajo ninguna circunstancia, en caso de que la Institución Financiera otorgue un crédito menor a lo solicitado por “EL CLIENTE”; no recibirá reembolso alguno del pago realizado,  en caso de que el crédito otorgado por la Institución Financiera sea mayor al solicitado por “EL CLIENTE”, éste deberá cubrir la diferencia de los Honorarios que se cita en el Anexo “D”.</p>
            <p><strong>SEPTIMA. DE LA DOCUMENTACIÓN.</strong></p>
            <p><strong>7.1. ARCHIVO</strong>.  Constituyen todos y cada uno de los documentos idóneos, según el criterio de cada “INSTITUCIÓN FINANCIERA” que “EL CLIENTE” deberá otorgar a “EL PRESTADOR  DE SERVICIO” dentro del plazo de veinte días naturales después de haber sido solicitados, en términos de lo contenido en el anexo “B” que debidamente firmado por las partes forma parte integrante del presente contrato, mismos que son indispensables para la gestoría y la realización exitosa de “LOS SERVICIOS” contratados. Si dentro  de dicho plazo “EL CLIENTE” se abstiene, por cualquier causa, de proporcionar dicha documentación a “EL PRESTADOR DE SERVICIO”, ésta constituirá una causal de rescisión del presente contrato, sin responsabilidad para esta última. Por otra parte si “EL CLIENTE” desea recoger su documentación anticipadamente a la obtención del crédito será causa de rescisión del presente contrato.</p>
            <p><strong>OCTAVA. DE LAS COSTAS</strong></p>
            <p><strong>8.1. GASTOS</strong>.  “EL CLIENTE” se obliga a pagar todos los derechos, gastos, honorarios e impuestos que se originen con motivo de la celebración del presente contrato que se lleve a cabo con la “INSTITUCIÓN FINANCIERA”, de su formalización y en su caso de su inscripción en el Registro Público de la Propiedad y del Comercio, los de su cancelación, así como los efectuados por cobranza extrajudicial o judicial.</p>
            <p><strong>NOVENA. DE LOS DOMICILIOS. </strong></p>
            <p><strong>9.1. DOMICILIOS</strong>. “EL CLIENTE” señala como su domicilio el indicado en la carátula de éste contrato. Cualquier cambio de domicilio de las partes deberá ser notificado por escrito, o de lo contrario todas las notificaciones practicadas en dichos domicilios se tendrán por legalmente efectuadas.</p>
            <br>
            <p><strong>DECIMA. DE LA VIGENCIA.</strong></p>
            <p><strong>10.1. VIGENCIA DEL CONTRATO</strong>. El presente contrato tendrá de 60 (sesenta) días naturales contados a partir de la firma del mismo. Agotado este plazo mencionado, la prórroga será voluntaria para ambas partes, pudiendo cualquiera de éstas darlo por terminado con por lo menos 15 (quince)  días naturales de anticipación a la autorización del crédito por la “INSTITUCIÓN FINANCIERA” mediante aviso por escrito a la parte interesada. La empresa podrá realizar una gestión secundaria con alguna Institución, quedando sujetos a la resolución y a las condiciones que esta última determine.</p>
            <p><strong>DÉCIMA PRIMERA. DE LAS MODIFICACIONES. </strong></p>
            <p><strong>11.1. ALTERACION</strong>.  Cualquier alteración realizada al presente contrato deberá hacerse por escrito y firmada por ambas partes, por lo que cualquier convenio o acuerdo verbal, así como cantidades diferentes a los conceptos y montos estipulados en el presente contrato celebrado por “EL CLIENTE” con cualquier asesor y/o equipo jurídico, no serán responsabilidad de “EL PRESTADOR DE SERVICIO” y no tendrán valor alguno.</p>
            <p><strong>DÉCIMA SEGUNDA. DE LOS VICIOS. </strong></p>
            <p><strong>12.1. DE LA VOLUNTAD</strong>.  Ambas partes manifiestan que en la celebración del presente contrato no existe error, dolo, violencia, mala fe o cualquier vicio de consentimiento, por lo que las partes renuncian expresamente a demandar su nulidad por cualquiera de estas causas.</p>
            <p><strong>DÉCIMA TERCERA. DE LA COMPETENCIA. </strong></p>
            <p><strong>13.1. COMPETENCIA</strong>.  La Procuraduría Federal del Consumidor (PROFECO) es competente en la vía administrativa para resolver cualquier controversia que se suscite sobre la interpretación o cumplimiento del presente contrato.</p>
        </td>
    </tr>
    <!--<tr>
        <td class="text-center" width="50%">
            <div style="height: 90px;display: flex;align-items: flex-end;justify-content: center;">
                <img src="<?=$signature?>" height="<?=$height?>">
            </div>
            <hr>
            <div style="height: 40px">
                <strong><?=strtoupper($model->office->business_name)?></strong><br>"EL PRESTADOR DE SERVICIOS"
            </div>
        </td>
        <td class="text-center" width="50%">
            <div style="height: 90px">&nbsp;</div>
            <hr>
            <div style="height: 40px;"><strong><?=strtoupper($model->lead_name)?></strong><br>"EL CLIENTE"</div>
        </td>
    </tr>-->
    <tr>
        <td class="text-center">
            <table>
                <tr>
                    <td class="text-center" width="50%">
                            <img src="<?=$signature?>" height="90">
                            <hr style="width: 100%">
                        <strong><?=$office->business_name;?></strong>
                    </td>
                    <td class="text-center" width="50%">
                        <br><br><br><br><br>
                            <hr style="width: 100%">
                            <strong><?=strtoupper($model->lead_name)?></strong>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>

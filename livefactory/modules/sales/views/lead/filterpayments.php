<?php
/**
 * Created by PhpStorm.
 * User: DEV02
 * Date: 26/04/2019
 * Time: 06:01 PM
 */
use yii\helpers\ArrayHelper;
use livefactory\models\User;
use livefactory\models\Office;
use livefactory\models\LeadStatus;
$paymentTypes = [
    1 => 'Cobro contrato nuevo',
    2 => 'Cobro anticipo',
    3 => 'Cobro addendums',
    4 => 'Cobro incremento',
    5 => 'Cobro de seguro'
    /*1 => 'Todos los pagos(excepto seguro)',
    2 => 'Pagos seguro'*/

];
$filterpayment = [
    1 => 'Todos los pagos(excepto seguro)',
    2 => 'Pagos seguro'
];
$request = Yii::$app->request->getQueryParams();
$payment_type = $request['payment_type'];

$filterstatus = [
        1 => 'Todos los pagos en Validación',
        2 => 'Todos los pagos Aprobados',
        3 => 'Todos los pagos declinados'
];

$payment_status = $request['payment_state'];
?>

            <div class="row">
                <?php if (Yii::$app->user->can('Payment.Insurance')) { ?>
                <div class="col-md-4">
                    <select name="payment_type" class="form-control">
                        <option value="">Tipo de Pago</option>
                        <option value="">Todos los pagos</option>
                        <?php foreach ($filterpayment as $value => $text): ?>
                            <option value="<?=$value?>"<?php if($value == $payment_type): ?> selected<?php endif; ?> ><?=$text?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php } ?>
                <div class="col-md-4">
                    <select name="payment_state" class="form-control">
                        <option value="">Estado de Pago</option>
                        <option value="">Todos los pagos</option>
                        <?php foreach ($filterstatus as $value => $text): ?>
                            <option value="<?=$value?>"<?php if($value == $payment_status): ?> selected<?php endif; ?> ><?=$text?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>


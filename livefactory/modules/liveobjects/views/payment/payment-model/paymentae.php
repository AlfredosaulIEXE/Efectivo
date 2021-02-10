<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use livefactory\models\User;
use livefactory\models\Payment;
?>
<?php
$fecha_actual = date("d-m-Y");
$payment = Payment::findOne([
        'entity_id' => $_REQUEST['id'],
        'type' => 1
        ]);
$paymentTypes = Payment::getTypes();
$payment_ncu = [];
$paymentOrigins = [
    1 => 'Efectivo',
    2 => 'Transferencia',
    3 => 'Depósito Bancario',
    4 => 'Tarjeta de Crédito/Débito',
    5 => 'Cheque',
];
$payment_new = [
        1 => 'Cobro contrato nuevo',
        2 => 'Cobro anticipo'
        ];

//agents off but have a one or more payments actives
$agentspayments = User::find()->where('id = ' . (int) $payment->generator_id  . ' AND active = 0')->asArray()->all();

$coagentspayments = User::find()->where('id = ' . (int) $payment->co_generator_id . ' AND active = 0' )->asArray()->all();

//query where only user with sales manager or sales person
$query = User::find()->where('office_id = ' . $model->office_id . ' and active = 1')->orderBy('first_name');
$query->join('LEFT JOIN', 'auth_assignment', 'tbl_user.id = auth_assignment.user_id');
$query->where(" auth_assignment.item_name = 'Sales Manager' or auth_assignment.item_name = 'Sales Person' " )
//$query->where(" auth_assignment.item_name = 'Sales Manager' or auth_assignment.item_name = 'Sales Person' or auth_assignment.item_name = 'Customer.Director' or auth_assignment.item_name = 'Customer.Service'" )
->andWhere("tbl_user.active = 1 ")
->andWhere("tbl_user.office_id = " . $model->office_id);
$agents = $query->asArray()->all();
//merge agents off with one or more payment active with agents sales manager or sales person
    $agents = array_merge($agents,$agentspayments,$coagentspayments);

$generator = isset($payment_model) ? User::findOne($payment_model->generator_id) : User::findOne($model->lead_owner_id);
$can_change_generator = Yii::$app->user->can('Payment.Update') && $payment_model;
//$insurance = array();
//$query = User::find();
//$query->join('LEFT JOIN', 'auth_assignment', 'tbl_user.id = auth_assignment.user_id');
//$query->where("auth_assignment.item_name = 'Insurance' or auth_assignment.item_name = 'Insurance.Director' or auth_assignment.item_name = 'Insurance.Customer'");
//foreach ($query->asArray()->all() as $i)
//{
//    $insurance[] = $i;
//}
//$agents = array_merge($agents , $insurance);

//insurance users where the selection type is insurance
$query_insurance = User::find()->where(' active = 1');
$query_insurance->join('LEFT JOIN', 'auth_assignment', 'tbl_user.id = auth_assignment.user_id');
$query_insurance->where("auth_assignment.item_name = 'Insurance' and tbl_user.active = 1" );
$insurance = $query_insurance->asArray()->all();

//Customer.Commercial
$query = User::find()->where(' active = 1');
$users_add = array();
$query->join('LEFT JOIN', 'auth_assignment', 'tbl_user.id = auth_assignment.user_id');
$query->where("auth_assignment.item_name = 'Commercial.Manager' and tbl_user.active = 1" );
//var_dump($query);
foreach ($query->asArray()->all() as $u)
{
    $users_add[] = $u;

}
//Customer.Service
$query = User::find();
$users_add2 = array();
$query->join('LEFT JOIN', 'auth_assignment', 'tbl_user.id = auth_assignment.user_id');
$query->where("(auth_assignment.item_name = 'Customer.Director' or auth_assignment.item_name = 'Customer.Service'  or auth_assignment.item_name = 'Customer.Service2') and active = 1" );
//var_dump($query);
foreach ($query->asArray()->all() as $u)
{
    $users_add2[] = $u;

}
//merge with customer commercial manager
$agents = array_merge($agents, $users_add, $users_add2);
?>
<?php
if($model->lead_status_id == \livefactory\models\LeadStatus::_NEW || $model->lead_status_id == \livefactory\models\LeadStatus::_INPROCESS || $model->lead_status_id == \livefactory\models\LeadStatus::_OPPORTUNITY){
    $payment_ncu = $payment_new;
}
else if($model->lead_status_id == \livefactory\models\LeadStatus::_CONVERTED && empty($payment_model))
{
    if($payment->date == $fecha_actual){
        $payment_ncu = $paymentTypes;
    }
    else
    $payment_ncu = [
            3 => 'Cobro addendums',
            4 => 'Cobro incremento',
            5 => 'Cobro de seguro'
            ];
}
else
{
    $payment_ncu = [
        1 => 'Cobro contrato nuevo',
        2 => 'Cobro anticipo',
        3 => 'Cobro addendums',
        4 => 'Cobro incremento',
        5 => 'Cobro de seguro'
    ];
}
///added payment repayment and refused
if ( ! isset($_GET['payment_edit']) )
$payment_ncu += [
        6 => 'Devolución',
        7=> 'Pago rechazado'
];

//if (Yii::$app->user->can('Role.Manager') || Yii::$app->user->can('Role.Admin')) {
    //$payment_ncu += [
      //      5 => 'Cobro de seguro'
    //];
//}

?>
<style>
    #generator_id_ select{
        color: white !important;
    }


    #generator_id_ option {
        color: black;
    }
    #generator_id_ select:hover {
        color: black !important;
    }
</style>
<div class="modal fade bs-example-modal-lg paymentae">
    <?php if (empty($payment_model)) { ?>
    <form method="post" id="paymentform" action="index.php?r=sales/lead/paymentinsert&id=<?php echo $_REQUEST['id'];?>" enctype="multipart/form-data">
        <?php
        } else { ?>
        <form method="post" id="paymentform" action="index.php?r=sales/lead/paymentupdate&id=<?php echo $_REQUEST['id'];?>"  enctype="multipart/form-data">
            <?php } ?>

            <?php Yii::$app->request->enableCsrfValidation = true; ?>

            <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">

            <input type="hidden" name="payment_id" value="<?=$payment_model->id?>">

            <input type="hidden" name="payment" value="true">

            <input type="hidden" name="lead_id" value="<?=$model->id?>">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header">

                        <a class="close"  href="index.php?r=sales/lead/view&id=<?php echo $_REQUEST['id'] ?>">&times;</a> <!--check-->

                        <h4 class="modal-title"><?=Yii::t('app', 'Payment')?></h4>

                    </div>

                    <div class="modal-body">

                        <div class="row">

                            <div class="col-sm-4">

                                <div class="form-group">

                                    <label class="control-label"><?=Yii::t('app', 'Amount')?></label>

                                    <input name="amount" step="any" data-validation="required" class="form-control currency" value="<?=$payment_model->amount?>" required>

                                </div>

                            </div>



                            <div class="col-sm-4">

                                <div class="form-group">
                                    <label class="control-label"><?=Yii::t('app', 'Payment Type')?></label>
                                        <select id="type" name="type" class="form-control" required  onchange="insurance()">
                                            <option><?='--' . Yii::t('app', 'Payment Type') . '--'?></option>
                                            <?php
                                            if ($payment_model->type == 6 or $payment_model->type == 7)
                                                $payment_ncu = [
                                                        6 => 'Devolución',
                                                        7=> 'Pago rechazado'
                                                ];
                                            foreach ($payment_ncu as $type_id => $type) {
                                                if ($type_id == $payment_model->type ){
                                                    echo "<option value='$type_id' selected>$type</option>";
                                                }else {
                                                    echo "<option value='$type_id'>$type</option>";
                                                }
                                            }?>
                                        </select>
                                </div>

                            </div>

                            <div class="col-sm-4">

                                <div class="form-group">

                                    <label class="control-label"><?=Yii::t('app', 'Date')?></label>
                                    <input type="date" name="date" class="form-control" value="<?= empty($payment_model->date) ? '' : (date('Y-m-d',strtotime($payment_model->date)))?>" max="<?=date('Y-m-d')?>" required>

                                </div>

                            </div>

                            <div class="col-sm-4">

                                <div class="form-group">

                                    <label class="control-label"><?=Yii::t('app', 'Note')?></label>

                                    <input type="text" name="note" data-validation="required" class="form-control" value="<?=$payment_model->note?>">

                                </div>

                            </div>

                            <div class="col-sm-4">

                                <div class="form-group">

                                    <label class="control-label"><?=Yii::t('app', 'Code')?></label>
                                    <input type="text" name="code" class="form-control" value="<?=$payment_model->code?>">

                                </div>

                            </div>

                            <div class="col-sm-4">

                                <div class="form-group" id="generator_id_">

                                    <label class="control-label">Generó</label>
                                    <select  id="generator_id" name="generator_id" class="form-control" required>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($agents as $a_id ):  ?>
                                            <option value="<?= $a_id['id'] ?>"<?php if($a_id['id'] == $generator->id): ?> selected<?php endif; ?>><?=$a_id['first_name'] . ' '. substr($a_id['last_name'],0,1) . ' '. substr($a_id['middle_name'],0,1) ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                </div>

                            </div>

                            <div class="col-sm-4">

                                <div class="form-group">

                                    <label class="control-label"><?=Yii::t('app', 'Payment Origin')?></label>
                                    <select name="origin" class="form-control" required>
                                        <option value=""><?='--' . Yii::t('app', 'Payment Origin') . '--'?></option>
                                        <?php
                                        for ($i=1; $i<5; $i++) {
                                            if ($i == $payment_model->origin){
                                                echo "<option value='$i' selected>$paymentOrigins[$i]</option>";

                                            }else {
                                                echo "<option value='$i'>$paymentOrigins[$i]</option>";
                                            }
                                        }?>
                                    </select>
                                </div>

                            </div>

                            <div class="col-sm-4">

                                <div class="form-group">

                                    <?php
                                    if(Yii::$app->params['FILE_SIZE']=="0")
                                        $limit = "No Limit";
                                    else
                                        $limit = Yii::$app->params['FILE_SIZE']."MB";
                                    ?>
                                    <label><?=Yii::t('app', 'Attachment (Max allowed size:').' '.$limit.')'?></label>
                                    <input type="file" name="attach" class="form-control" value="">

                                </div>

                            </div>

                            <div class="col-sm-4">

                                <div class="form-group">

                                    <label class="control-label">Co-generador</label>
                                    <select name="co_generator_id" class="form-control" id="co_generator_id" <?php /*if ( ! $can_change_generator) echo ' disabled="disabled"'*/?>>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($agents as $a_id ): ?>
                                            <option value="<?= $a_id['id'] ?>"<?php  if(isset($payment_model) && $a_id['id'] == $payment_model->co_generator_id): ?> selected<?php endif;   ?>><?=$a_id['first_name'] . ' '. substr($a_id['last_name'],0,1) . ' '. substr($a_id['middle_name'],0,1)?></option>
                                        <?php endforeach; ?>
                                    </select>

                                </div>

                            </div>

                        </div>

                        <?php if(isset($payment_model) && Yii::$app->user->can('Payment.Validate') && $payment_model->origin != 1): ?>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">Estado</label>
                                    <select name="payment_status" class="form-control" required>
                                        <option value="<?=Payment::UNVALIDATED?>"<?php if ($payment_model->status == Payment::UNVALIDATED):?> selected<?php endif;?>>Por validar</option>
                                        <option value="<?=Payment::VALIDATED?>"<?php if ($payment_model->status == Payment::VALIDATED):?> selected<?php endif;?>>Validado</option>
                                        <option value="<?=Payment::DECLINED?>"<?php if ($payment_model->status == Payment::DECLINED):?> selected<?php endif;?>>Declinado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                    </div>

                    <div class="modal-footer">

                        <button type="submit" class="btn btn-primary add_appointmentae btn-sm">

                            <i class="fa fa-road"></i><?=Yii::t('app', 'Save')?> </button>

                        <a class="btn btn-default btn-sm" href="index.php?r=sales/lead/view&id=<?php echo $_REQUEST['id'] ?>">Cerrar</a> <!--check -->
                    </div>

                </div><!-- /.modal-content -->

            </div><!-- /.modal-dialog -->

        </form>

</div><!-- /.modal -->
<script>
    function insurance() {
        var type = document.getElementById("type").value;
        if (type == 5)
        {
            document.getElementById("generator_id").innerHTML = "<select  id=\"generator_id\" name=\"generator_id\" class=\"form-control\" required>\n" +
                "                                        <option value=\"\">Seleccionar...</option>\n" +
                "                                        <?php foreach ($insurance as $a_id ):  ?>\n" +
                "                                            <option value=\"<?= $a_id['id'] ?>\"<?php if($a_id['id'] == $generator->id): ?> selected<?php endif; ?>><?=$a_id['first_name'] . ' ' . substr($a_id['last_name'], 0, 1) . ' ' . substr($a_id['middle_name'], 0, 1) ?></option>\n" +
                "                                        <?php endforeach; ?>\n" +
                "                                    </select>";
        }
        else
        {
            document.getElementById("generator_id").innerHTML = "<select  id=\"generator_id\" name=\"generator_id\" class=\"form-control\" required>\n" +
                "                                        <option value=\"\">Seleccionar...</option>\n" +
                "                                        <?php foreach ($agents as $a_id ):  ?>\n" +
                "                                            <option value=\"<?= $a_id['id'] ?>\"<?php if($a_id['id'] == $generator->id): ?> selected<?php endif; ?>><?=$a_id['first_name'] . ' ' . substr($a_id['last_name'], 0, 1) . ' ' . substr($a_id['middle_name'], 0, 1) ?></option>\n" +
                "                                        <?php endforeach; ?>\n" +
                "                                    </select>";
        }

    }
</script>



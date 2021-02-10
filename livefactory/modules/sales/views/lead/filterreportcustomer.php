<?php
use yii\helpers\ArrayHelper;
use livefactory\models\User;
use livefactory\models\Office;
use livefactory\models\LeadStatus;
//Customer Director
$customermanager = [];
foreach (User::find()->join('LEFT JOIN', 'auth_assignment', 'auth_assignment.user_id = tbl_user.id')
             ->where(" auth_assignment.item_name = 'Customer.Director' ")
             ->andWhere('tbl_user.active = 1')
             ->asArray()->all() as $key => $item) {
    $customermanager[$item['id']] = $item['alias'] . ' ('. $item['username'] .')';
}
// Managers
if (isset($model->service_owner_id))
    $managers = [$model->service_owner_id];
else
{
    $managers = [];
}

foreach (User::find()->join('LEFT JOIN', 'auth_assignment', 'auth_assignment.user_id = tbl_user.id')
             ->where("auth_assignment.item_name = 'Customer.Service' OR auth_assignment.item_name = 'Customer.Service2' ")

             ->andWhere('tbl_user.active = 1')
             ->asArray()->all() as $key => $item) {
    $managers[$item['id']] = $item['alias'] . ' ('. $item['username'] .')';
}
$manager = $managers + $customermanager;
$request = Yii::$app->request->getQueryParams();
$managerget = $request['customerowner'];
var_dump($request);
$customerservicestatus = LeadStatus::$_customer_service_status;
var_dump(LeadStatus::$_customer_service_status);
?>

<div class="row">
    <div class="col-md-3">
        <h5>Propietario Atención a clientes</h5>
        <select name="customerowner" class="form-control">
            <option>Seleccione el propietario</option>
            <?php foreach ($manager as $key => $manager) { ?>

                <option value="<?= $key?>" <?php if($key == $managerget): ?>selected <?php endif;?>><?= $manager?></option>
            <?php } ?>
        </select>

    </div>
    <div class="col-md-3">
        <h5>Estatus en Atención a clientes</h5>
        <select name="statuscustomer" class="form-control">
            <option>Seleccione el estatus del lead en atención a clientes</option>
            <?php foreach ($customerservicestatus as $key => $status) {?>
                <option value="<?=$key?>"><?= $status?></option>
            <?php } ?>
        </select>
    </div>
</div>
<?php
use yii\helpers\ArrayHelper;
use livefactory\models\User;
use livefactory\models\Office;
use livefactory\models\LeadStatus;

$this->title = Yii::t ( 'app', 'Leads Export' );

$this->params ['breadcrumbs'] [] = $this->title;
$pageView = 'List View';
$request = Yii::$app->request->getQueryParams();
$status_appointment = isset($request['status_appointment']) && $request['status_appointment'] != '' ? (int) $request['status_appointment'] : '';
$type_selected = isset($request['type_appointment']) && $request['type_appointment'] != '' ? (int) $request['type_appointment'] : '';
$office_user_filter = $lead['office_id'] != null ? ' AND office_id = ' . $lead['office_id'] : '';
$lead_status_id = $request['lead_status'];
$start = $request['start'];
$end = $request['end'];
$statuses = [
    -1 => 'Vencida',
    0 => 'Vigente',
    1 => 'Concretado',
    2 => 'No Concretado'
];
$type_appointment = [
    0 => 'En Oficina' ,
    1 => 'En llamada'
];
$lead_status=ArrayHelper::map(LeadStatus::find()->where("active=1")->orderBy('sort_order')->asArray()->all(), 'id', 'label');


//$office=ArrayHelper::map(Office::find()->where("active=1")->orderBy('code')->asArray()->all(), 'id', 'description');
// Sales Report
$request = Yii::$app->request->getQueryParams();
$office_id = $request['office_id'];

$offices = ArrayHelper::map(Office::find()->where("active=1 and reports=1")->orderBy('description')->asArray()->all(), 'id', 'description');
$agents_office_id = $office_id == null ? ( ! Yii::$app->user->can('Office.NoLimit') && Yii::$app->user->can('Reports.ByUser') ? Yii::$app->user->identity->office_id : null ) : $office_id;
$agents = $agents_office_id == null ? [] : ArrayHelper::map(User::find()->where('office_id = ' . $agents_office_id)->orderBy('alias')->asArray()->all(), 'id', 'alias');


$capturist=ArrayHelper::map(User::find()->where("id IN (SELECT user_id FROM tbl_lead WHERE user_id IS NOT NULL GROUP BY user_id) and active=1".$office_user_filter)->asArray()->all(),'id',function($user){
    return $user['alias'].' ('.$user['username'].')';
});


///
$owner=ArrayHelper::map(User::find()->orderBy('first_name')
    ->where("active=1" . (Yii::$app->user->can('Office.NoLimit') ? '' : " and office_id = " . Yii::$app->user->identity->office_id))
    ->asArray()
    ->all(), 'id', function ($user, $defaultValue) {
    $username = $user['username'] ? $user['username'] : $user['email'];
    return $user['alias'] .  ' (' . $username . ')';
});
?>

<div class="ibox">
    <div class="ibox-title"><h5>Filtros</h5></div>
    <div class="ibox-content">
        <form id="report_form" action="index.php">
            <input type="hidden" name="r" value="sales/lead/exportleads">
            <div class="row">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-control-static"><h4 class="m-n">Periodo:</h4></div>
                        <div id="datepick" class="col-md-4 col-lg-4 text-right form-group">
                            <div class="input-daterange input-group">
                                <span class="input-group-addon"><strong>Semana:</strong></span>
                                <input type="date" name="start" class="form-control" autocomplete="off" value="<?=$start?>" data-date-format="YYYY MMMM  DD">
                                <span class="input-group-addon">a</span>
                                <input type="date" name="end" class="form-control" autocomplete="off" value="<?=$end?>" data-date-format="YYYY MMMM  DD">
                                <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary" value="export" name="export-lead" id="export-lead">Aplicar</button>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (Yii::$app->user->can('Office.NoLimit')): ?>
                    <div class="col-md-4">
                        <div class="form-control-static"><h4 class="m-n">Sucursal:</h4></div>
                    </div>
                <?php endif; ?>

            </div>
            <div class="row">

                <div class="col-md-3 form-group">
                    <?php if (Yii::$app->user->can('Office.NoLimit')): ?>
                        <select name="office_id" class="form-control">
                            <option value="">Todas las sucursales...</option>
                            <?php foreach ($offices as $o_id => $name): ?>
                                <option value="<?= $o_id ?>"<?php if($o_id == $office_id): ?> selected<?php endif; ?>><?=$name?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>

            </div>

        </form>
    </div>
</div>
<?php
use yii\helpers\ArrayHelper;
use livefactory\models\User;
use livefactory\models\Office;
use livefactory\models\LeadStatus;


$request = Yii::$app->request->getQueryParams();
$status_appointment = isset($request['status_appointment']) && $request['status_appointment'] != '' ? (int) $request['status_appointment'] : '';
$type_selected = isset($request['type_appointment']) && $request['type_appointment'] != '' ? (int) $request['type_appointment'] : '';
$office_user_filter = $lead['office_id'] != null ? ' AND office_id = ' . $lead['office_id'] : '';
$lead_status_id = $request['lead_status'];
$start = isset($request['start']) && $request['start'] != '' ? $request['start'] : date('Y-m-d');
$end = isset($request['end']) && $request['end'] != '' ? $request['end'] : date('Y-m-d');

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
$agent_id = $request['agent_id'];

//$office=ArrayHelper::map(Office::find()->where("active=1")->orderBy('code')->asArray()->all(), 'id', 'description');
// Sales Report
$request = Yii::$app->request->getQueryParams();
$office_id = $request['office_id'];

$offices = ArrayHelper::map(Office::find()->where("active=1 and reports=1")->orderBy('description')->asArray()->all(), 'id', 'description');
$agents_office_id = $office_id == null ? ( ! Yii::$app->user->can('Office.NoLimit') && Yii::$app->user->can('Reports.ByUser') ? Yii::$app->user->identity->office_id : null ) : $office_id;
$agents = $agents_office_id == null ? [] : User::find()->where('active = 1 and office_id = ' . $agents_office_id)->orderBy('alias')->asArray()->all();

$capturist=ArrayHelper::map(User::find()->where("id IN (SELECT user_id FROM tbl_lead WHERE user_id IS NOT NULL GROUP BY user_id) and active=1".$office_user_filter)->asArray()->all(),'id',function($user){
return $user['alias'].' ('.$user['username'].')';
});

/////link
$link_filter = Yii::$app->request->getQueryParams();
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
            <input type="hidden" name="r" value="<?= $link_filter['r']?>">
            <div class="row">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-control-static"><h4 class="m-n">Periodo:</h4></div>
                        <div id="datepick" class="col-md-4 col-lg-4 text-right form-group">
                            <div class="input-daterange input-group">
                                <span class="input-group-addon"><strong>Semana:</strong></span>
                                <input type="date" name="start" class="form-control" autocomplete="off" value="<?=$start?>" data-date-format="YYYY MMMM  DD">
                                <span class="input-group-addon">a</span>
                                <input type="date" name="end" class="form-control" autocomplete="off" value="<?=$end?>" data-date-format="YYYY MMMM  DD">
                                <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary">Aplicar</button>
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
                <?php if (Yii::$app->user->can('Reports.ByUser')): ?>
                    <div class="col-md-3">
                        <ul id="filter-tabs" class="nav nav-tabs">
                            <li<?=$mean_id ? '' : ' class="active"'?>><a href="#filter-agents" data-toggle="tab" style="padding: 3px 10px">Propietario</a></li>
                        </ul>
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
                <?php if (Yii::$app->user->can('Reports.ByUser')): ?>
                    <div class="col-md-4 form-group">
                        <div class="tab-content">
                            <div class="tab-pane<?=$mean_id ? '' : ' active'?>" id="filter-agents">
                                <select name="agent_id" class="form-control">
                                    <option value="">Todos los asesores</option>
                                    <?php foreach ($agents as $a_id): ?>
                                        <option value="<?= $a_id['id'] ?>"<?php if($a_id['id'] == $agent_id): ?> selected<?php endif; ?>><?=$a_id['first_name'] . " " . $a_id['last_name'] . " " . $a_id['middle_name'] . "(" . $a_id['username'] . ')'?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php// else: ?>
                                <!--<input type="hidden" name="office_id" value="<?=$office_id?>">
                                <div class="input-daterange input-group">
                                    <span class="input-group-addon">Estadísticas de:</span>
                                    <span class="input-group-btn">
                                    <button type="button" class="btn btn-default js-switch <?=$agent_id != null ? ' active' : ''?>" data-type="agent"><?=Yii::$app->user->identity->first_name?></button>
                                    <button type="button" class="btn btn-default js-switch <?=$office_id != null ? ' active' : ''?>" data-type="office"><?=$office->description?></button>
                                </span>
                                </div>-->

                            </div>

                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>
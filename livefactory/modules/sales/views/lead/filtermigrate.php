<?php
use yii\helpers\ArrayHelper;
use livefactory\models\User;
use livefactory\models\Office;
use livefactory\models\LeadStatus;
$request = Yii::$app->request->getQueryParams();
$start = $request['start'];
$end = $request['end'];
if (! isset($request['start']))
{

    $start = date('d-m-Y');
    $end = date('d-m-Y');
}
$agent_id = $request['agent_id'];
$office_id = $request['office_id'];
$lead_status_id = $request['lead_status'];
$mean_id = $request['mean_id'];
$offices = ArrayHelper::map(Office::find()->where("active=1 and reports=1")->orderBy('description')->asArray()->all(), 'id', 'description');
$agents_office_id = $office_id == null ? ( ! Yii::$app->user->can('Office.NoLimit') && Yii::$app->user->can('Reports.ByUser') ? Yii::$app->user->identity->office_id : null ) : $office_id;
$agents = $agents_office_id == null ? [] : ArrayHelper::map(User::find()->where('office_id = ' . $agents_office_id)->orderBy('alias')->asArray()->all(), 'id', function ($user){
    return $user['username'].' ('.$user['first_name'] . ' ' . $user['last_name'] . ' ' . $user['middle_name'] .')';
});
$capturist=ArrayHelper::map(User::find()->where("id IN (SELECT user_id FROM tbl_lead WHERE user_id IS NOT NULL GROUP BY user_id) and active=1".$office_user_filter)->asArray()->all(),'id',function($user){
    return $user['alias'].' ('.$user['username'].')';
});
$lead_status=ArrayHelper::map(LeadStatus::find()->where("active=1")->orderBy('sort_order')->asArray()->all(), 'id', 'label');
if (isset($request['leads-migrates']))
{
    $leads =  $request['leads-migrates'];
    $aux = $leads;
}

?>
<div class="ibox">
    <div class="ibox-title">Opciones de Migración</div>
    <div class="ibox-content">

        <div class="container">
            <div class="row">
                <form id="report_form" action="index.php">
                    <input type="hidden" name="r" value="sales/lead/migrateleads">
                    <div class="form-group">
                        <label>Leads Migrate</label>
                        <input id="leads-migrates" name="leads-migrates" placeholder="Número de control" class="form-control" value="<?= $leads?>">
                    </div>
                    <label>Periodo</label>
                    <div id="datepicker" class="col-md-12 col-lg-12 text-right form-group">
                        <div class="input-daterange input-group">
                            <span class="input-group-addon"><strong>Semana:</strong></span>
                            <input type="text" name="start" class="form-control" autocomplete="off" value="<?=$start?>">
                            <span class="input-group-addon">a</span>
                            <input type="text" name="end" class="form-control" autocomplete="off" value="<?=$end?>">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary">Aplicar</button>
                            </span>
                        </div>
                    </div>
                        <div class="col-md-4 form-group">
                            <label>Sucursales</label>
                            <select name="office_id" class="form-control">
                                <option value="">Todas las sucursales...</option>
                                <?php foreach ($offices as $o_id => $name): ?>
                                    <option value="<?= $o_id ?>"<?php if($o_id == $office_id): ?> selected<?php endif; ?>><?=$name?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>




                        <div class="col-md-4 form-group">
                            <label>Propietario</label>
                            <select name="agent_id" class="form-control">
                                <option value="">Todos los asesores</option>
                                <?php foreach ($agents as $a_id => $name): ?>
                                    <option value="<?= $a_id ?>"<?php if($a_id == $agent_id): ?> selected<?php endif; ?>><?=$name?></option>
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
                        <div class="col-md-4 form-group">
                            <label>Capturista</label>
                            <select name="mean_id" class="form-control">
                                <option value="">Todos los asesores</option>
                                <?php foreach ($capturist as $value => $text): ?>
                                    <option value="<?=$value?>"<?php if($value == $mean_id): ?> selected<?php endif; ?> ><?=$text?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Estatus de Lead</label>
                            <select name="lead_status" class="form-control">
                                <option value="">Estado de lead</option>
                                <?php foreach ($lead_status as $value => $text): ?>
                                    <option value="<?=$value?>"<?php if($value == $lead_status_id): ?> selected<?php endif; ?> ><?=$text?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>
                </form>
            </div>

        </div>


</div>

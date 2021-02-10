<?php

use livefactory\models\UnitGenerate;
use yii\helpers\ArrayHelper;
use livefactory\models\User;
use livefactory\models\Office;
use livefactory\models\LeadStatus;

$request = Yii::$app->request->getQueryParams();
$status_appointment = isset($request['status_appointment']) && $request['status_appointment'] != '' ? (int) $request['status_appointment'] : '';
$type_selected = isset($request['type_appointment']) && $request['type_appointment'] != '' ? (int) $request['type_appointment'] : '';
$select_period = isset($request['type_period_check']) && $request['type_period_check'] != '' ? (int) $request['type_period_check'] : $type_period_check;;
$office_user_filter = $lead['office_id'] != null ? ' and office_id = ' . $lead['office_id'] : '';
$lead_status_id = $request['lead_status'];
$unit_post = isset($request['unitGenerate']) && $request['unitGenerate'] ? (int) $request['unitGenerate'] : '';
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
$type_period = [
  0 => 'Fecha de cita',
  1 => 'Fecha de creación'
];
$lead_status=ArrayHelper::map(LeadStatus::find()->where("active=1")->orderBy('sort_order')->asArray()->all(), 'id', 'label');


//$office=ArrayHelper::map(Office::find()->where("active=1")->orderBy('code')->asArray()->all(), 'id', 'description');
// Sales Report
$request = Yii::$app->request->getQueryParams();

$office_id = $request['office_id'];
//filter capturist with unit generate
   $unit_post != '' ? $filter_unit_generate = ' and tbl_user.unit_generate = ' . $unit_post :$filter_unit_generate = '';


$offices = ArrayHelper::map(Office::find()->where("active=1 and reports=1")->orderBy('description')->asArray()->all(), 'id', 'description');
$agents_office_id = $office_id == null ? ( ! Yii::$app->user->can('Office.NoLimit') && Yii::$app->user->can('Reports.ByUser') ? Yii::$app->user->identity->office_id : null ) : $office_id;
$agents = $agents_office_id == null ? [] : ArrayHelper::map(User::find()->where('active= 1 and office_id = ' . $agents_office_id )->orderBy('alias')->asArray()->all(), 'id', 'alias');

$capturist=ArrayHelper::map(User::find()->where("tbl_user.id IN (SELECT user_id FROM tbl_appointment WHERE user_id IS NOT NULL GROUP BY user_id) and tbl_user.active=1".$office_user_filter . $filter_unit_generate)->asArray()->all(),'id',function($user){
    $unitGenerate = UnitGenerate::findOne($user['unit_generate']);

    return $user['alias'] . ' (' . $user['username'] . ')(unit: ' . $unitGenerate['name'] . ')';
});


///
$owner=ArrayHelper::map(User::find()->orderBy('first_name')
    ->where("active=1" . (Yii::$app->user->can('Office.NoLimit') ? '' : " and office_id = " . Yii::$app->user->identity->office_id))
    ->asArray()
    ->all(), 'id', function ($user, $defaultValue) {
    $username = $user['username'] ? $user['username'] : $user['email'];
    return $user['alias'] .  ' (' . $username . ')';
});
foreach ($dataProvider->query->all() as $row){
    $promises_count += $row['amount'];
}
?>
<?php foreach (Yii::$app->request->getQueryParams() as $key=>$params)
{

    if ($key == 'r'){
        $page_appointment = $params;
    }
}
?>
<div class="ibox">
    <div class="ibox-title"><h5>Filtros</h5></div>
    <div class="ibox-content">
        <form id="report_form" action="index.php">
            <input type="hidden" name="r" value=<?= $page_appointment?>>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-control-static"><h4 class="m-n">Periodo:</h4></div>
                </div>


            </div>
            <div class="row">
                <div id="datepicker" class="col-md-4 col-lg-4 text-right form-group">
                    <div class="col-md-12 text-center">
                        <h4><strong>Tipo de Periodo de cita</strong></h4>
                        <div style="text-align: left"><span>Selección de periodo entre la fecha de creación de la cita y el dia de la cita</span></div>
                        <?php foreach ($type_period as $value => $text): ?>
                            <input type="radio"  name="type_period_check" value="<?=$value ?>" <?php if($value === $select_period): ?> checked="checked" <?php endif; ?> <?=$text?>> <label> <?=$text?>&nbsp; </label>
                        <?php endforeach;?>
                    </div>
                    <div class="input-daterange input-group">

                        <span class="input-group-addon"><strong>Semana:</strong></span>
                        <input type="text" name="start" class="form-control" autocomplete="off" value="<?=date('d/m/Y', strtotime($start))?>">
                        <span class="input-group-addon">a</span>
                        <input type="text" name="end" class="form-control" autocomplete="off" value="<?=date('d/m/Y', strtotime($end))?>">
                        <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary">Aplicar</button>
                            </span>
                    </div>

                </div>
                <div class="col-md-4 form-group">

                    <?php if (Yii::$app->user->can('Office.NoLimit')): ?>
                        <div  style="margin-bottom: 3vh">
                            <div class="form-control-static"><h4 class="m-n">Sucursal:</h4></div>
                        </div>
                    <?php endif; ?>
                    <div>
                        <span>Selección de la sucursal a filtrar</span>
                    </div>
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
                <div class="col-md-4 form-group" >
                    <?php if (Yii::$app->user->can('Reports.ByUser')): ?>
                        <div>
                            <ul id="filter-tabs" class="nav nav-tabs">
                                <li<?=$agents ? '' : ' class="active"'?>><a href="#filter-agents" data-toggle="tab" style="padding: 3px 10px">Propietario</a></li>
                                <li<?=$mean_id ? ' class="active"' : ''?>><a href="#filter-means" data-toggle="tab" style="padding: 3px 10px;">Generador de Cita</a></li>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="tab-content" style="margin-top: 2vh">
                        <div><span>Selección entre el propietario del lead y el generador de cita del lead</span></div>
                        <div class="tab-pane<?=$mean_id ? '' : ' active'?>" id="filter-agents">
                                <select name="agent_id" class="form-control">
                                    <option value="">Todos los asesores propietarios de lead</option>
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
                        <div class="tab-pane<?=$mean_id ? ' active' : ''?>" id="filter-means">
                            <select name="mean_id" class="form-control">
                                <option value="">Todos los asesores generadores de cita</option>
                                <?php foreach ($capturist as $value => $text): ?>
                                    <option value="<?=$value?>"<?php if($value == $mean_id): ?> selected<?php endif; ?> ><?=$text?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                </div>
                <?php endif; ?>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div>
                        <label>Estatus del lead</label>
                    </div>
                    <div>
                        <span>Selección del estatus en el que se encuentra en el lead (nuevo,cita,venta, etc .. )</span>
                    </div>
                    <select name="lead_status" class="form-control">
                        <option value="">Seleccionar estado de lead</option>
                        <?php foreach ($lead_status as $value => $text): ?>
                            <option value="<?=$value?>"<?php if($value == $lead_status_id): ?> selected<?php endif; ?> ><?=$text?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
                <div class="col-md-4">
                    <div>
                        <label>Estatus de la cita</label>
                    </div>
                    <div>
                        <span>Selección del estado de la cita del lead (vigente, vencida, concretado, no concretado)</span>
                    </div>
                    <select name="status_appointment" class="form-control">
                        <option value="" >Seleccionar estatus de Cita</option>
                        <?php foreach ($statuses as $value => $text): ?>
                            <option value="<?=$value?>"<?php if($value === $status_appointment): ?> selected<?php endif; ?> ><?=$text?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
                <div class="col-md-4">
                    <div>
                        <label>Unidad Generadora</label>
                    </div>
                    <div>
                        <span>
                            Selección de la unidad generadora en la que se encuentra el capturista de la cita
                        </span>
                    </div>
                    <select name="unitGenerate" class="form-control">
                        <option value="">Unidad Generadora</option>
                        <?php foreach ($unitGenerate as $value => $unit): ?>
                        <option value="<?= $unit->id?>" <?php if ($unit->id === $unit_post) :?> selected <?php endif;?>> <?= $unit->name ?></option>
                        <?php endforeach; ?>
                    </select>


                </div>
                <div class="col-md-4 text-center">
                    <div>
                        <h4><strong>Tipo de cita</strong></h4>
                    </div>

                    <?php foreach ($type_appointment as $value => $text): ?>
                        <input type="radio"  name="type_appointment" value="<?=$value ?>" <?php if($value === $type_selected): ?> checked="checked" <?php endif; ?> <?=$text?>> <label> <?=$text?>&nbsp; </label>
                    <?php endforeach;?>
                </div>

            </div>
                <div class="row" style="margin-top: 15px">
                    <div class="col-md-4">
                        <h2>Promesas totales: <strong>$<?=number_format($promises_count, 2)?></strong></h2>
                    </div>
                </div>
        </form>
    </div>
</div>
<?php

use livefactory\models\Office;
use livefactory\models\User;
use yii\helpers\ArrayHelper;

$offices = ArrayHelper::map(Office::find()->where("active=1 and reports=1")->orderBy('description')->asArray()->all(), 'id', 'description');
$office = $office_id ? Office::findOne($office_id) : null;
$agents = $office_id ? User::find()->where('active=1 and office_id = ' . $office_id)->orderBy('first_name')->asArray()->all() : [];
$request =$_POST;
?>
<div class="row">
    <div id="datepicker" class="col-sm-4 text-right">
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
    <div class="col-sm-4">
        <?php if (Yii::$app->user->can('Office.NoLimit')): ?>
            <select name="office_id" class="form-control">
                <option value="">Todas las sucursales...</option>
                <?php foreach ($offices as $o_id => $name): ?>
                    <option value="<?= $o_id ?>"<?php if($o_id == $office_id): ?> selected<?php endif; ?>><?=$name?></option>
                <?php endforeach; ?>
            </select>
        <?php endif ?>
    </div>
    <div class="col-sm-4<?php echo count($agents) > 0 ? '' : ' hide'; ?>">
        <?php if (Yii::$app->user->can('Reports.ByUser')): ?>
            <select name="agent_id" class="form-control">
                <option value="">Todos los asesores</option>
                <?php foreach ($agents as $a_id ): ?>
                    <option value="<?= $a_id['id'] ?>"<?php if($a_id['id'] == $request['user_id']): ?> selected<?php endif; ?>><?=$a_id['first_name'] . ' '. substr($a_id['last_name'],0,1) . ' '. substr($a_id['middle_name'],0,1)?></option>
                <?php endforeach; ?>
            </select>
        <?php endif ?>
    </div>
</div>

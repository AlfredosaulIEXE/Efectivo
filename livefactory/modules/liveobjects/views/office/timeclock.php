<?php

/**
 * @var yii\web\View $this
 * @var livefactory\models\Office $model
 */

$this->title = 'Horarios de oficina';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Offices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Horarios de oficina';

$days = [
    1 => 'Lunes',
    2 => 'Martes',
    3 => 'Miércoles',
    4 => 'Jueves',
    5 => 'Viernes',
    6 => 'Sábado',
    7 => 'Domingo'
];

$office_times_array = [];

if ($office_times) {
    foreach ($office_times as $time) {
        $office_times_array[$time->week_day] = [
            'start_time' => substr($time->start_time, 0, 5),
            'end_time' => substr($time->end_time, 0, 5),
            'denied' => $time->denied
        ];
    }
}

?>
<form action="" method="post">
<div class="ibox">
    <div class="ibox-title">
        <h5><?=$office->description?> - Horarios de oficina</h5>
    </div>
    <div class="ibox-content">
        <div class="alert alert-info">
            En esta tabla se pueden configurar los horarios en que los empleados pueden iniciar sesión dentro del sistema.
        </div>
        <div class="form-inline alert alert-warning">
            <label for="role_id">Estos horarios aplican para:</label>
            <select name="role_id" class="form-control" onchange="window.location.href = 'index.php?r=liveobjects/office/timeclock&id=<?=$office->id?>&role_id=' + this.value">
                <option value="">Todos los roles</option>
                <?php foreach ($roles as $role): ?>
                    <option value="<?=$role['name']?>"<?php if($role['name'] == $role_id): ?> selected<?php endif; ?> ><?=$role['description']?></option>
                <?php endforeach; ?>
            </select>
        </div>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <?php foreach ($days as $day_no => $day_name): ?>
                    <th><?=$day_name?></th>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php foreach ($days as $day_no => $day_name): ?>
                        <td<?php if(isset($office_times_array[$day_no]['denied']) && $office_times_array[$day_no]['denied'] == 1): ?> class="danger"<?php endif; ?>>
                            <div class="row row-no-gutters">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Entrada:</label>
                                        <input type="time" class="form-control" name="times[<?=$day_no?>][start_time]" value="<?=isset($office_times_array[$day_no]['start_time']) ? $office_times_array[$day_no]['start_time'] : '09:00'?>">
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Salida:</label>
                                        <input type="time" class="form-control" name="times[<?=$day_no?>][end_time]" value="<?=isset($office_times_array[$day_no]['end_time']) ? $office_times_array[$day_no]['end_time'] : '19:00'?>">
                                    </div>
                                </div>
                            </div>
                            <label class="checkbox-inline"><input type="checkbox" name="times[<?=$day_no?>][denied]" class="time-block" value="1" <?php if(isset($office_times_array[$day_no]['denied']) && $office_times_array[$day_no]['denied'] == 1): ?> checked<?php endif; ?>> Bloquear ingreso este día</label>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
    </div>
    <div class="ibox-footer">
        <button class="btn btn-primary" type="submit">Guardar</button>
    </div>
</div>
</form>
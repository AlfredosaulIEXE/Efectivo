<?php
    $request = Yii::$app->request->getQueryParams();
    $start_date = $request['start_date'];
    $end_date = $request['end_date'];
    $fecha = new \DateTime();
    if($start_date == $end_date)
    {

        if ($request['start_time'] == null)
        {
            $start_time='00:00:00';

        }
        if ($request['end_time'] == null)
        {
            $end_time='23:59:59';
        }
        else {
            $start_time = $request['start_time'];
            $end_time = $request['end_time'];
        }

    }
    else
    {
        $start_time = $request['start_time'];
        $end_time = $request['end_time'];
    }

    $lead_departments = [
        'Todos',
        'Ventas',
        'Seguros',
        'Atención a clientes'
    ];
    $master_status_id = isset($request['master_status_id']) ? $request['master_status_id'] : '';
?>
<div class="row">
    <div class="col-sm-12">
        <form id="report_form" action="index.php">
            <input type="hidden" name="r" value="<?=$request['r']?>">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Periodo</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-inline" style="margin-bottom: 10px">
                                <div class="form-group">
                                    <div class="form-control-static">Inicio:</div>
                                </div>
                                <div class="form-group">
                                    <input type="date" name="start_date" class="form-control" autocomplete="off" value="<?=$start_date?>">
                                </div>
                                <div class="form-group">
                                    <input type="time" name="start_time" class="form-control" autocomplete="off" value="<?=$start_time?>">
                                </div>
                                <div class="form-group">
                                    Fin:
                                </div>
                                <div class="form-group">
                                    <input type="date" name="end_date" class="form-control" autocomplete="off" value="<?=$end_date?>">
                                </div>
                                <div class="form-group">
                                    <input type="time" name="end_time" class="form-control" autocomplete="off" value="<?=$end_time?>">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Aplicar</button>
                                </div>
<!--                                --><?php //if ((Yii::$app->user->can('Admin')) || (Yii::$app->user->id == 202)) :?>
<!--                                <div class="form-group" style="align-items: right">-->
<!--                                    <a href="/livecrm/web/index.php?r=sales/lead/exportleads" class="btn btn-success">Export</a>-->
<!--                                </div>-->
<!--                                --><?php //endif; ?>
                            </div>
                            <div class="text-muted">Escoger el periodo antes de cualquier filtro.</div>
                        </div>
                        <?php if (Yii::$app->user->can('Role.Insurance') || Yii::$app->user->can('Role.Manager')): ?>
                        <div class="col-md-4">
                            <div class="form-inline">
                                <div class="form-group">
                                    <label for="master_status_id">Departamento:</label>
                                </div>
                                <div class="form-group">
                                    <select id="master_status_id" name="master_status_id" class="form-control">
                                        <?php foreach ($lead_departments as $value => $text): ?>
                                            <option value="<?=$value?>"<?php if($value == $master_status_id): ?> selected<?php endif; ?> ><?=$text?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
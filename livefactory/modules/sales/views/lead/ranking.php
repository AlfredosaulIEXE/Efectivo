<?php

use yii\helpers\ArrayHelper;
use livefactory\models\Office;
/**

 *

 * @var yii\web\View $this

 * @var yii\data\ActiveDataProvider $dataProvider

 * @var common\models\search\Lead $searchModel
 *

 */

$this->title = Yii::t ( 'app', 'Ranking' );

$this->params ['breadcrumbs'] [] = $this->title;
$pageView = 'List View';

$request = Yii::$app->request->getQueryParams();
$start_date = $request['start'];
$end_date = $request['end'];
//
$offices = ArrayHelper::map(Office::find()->where("active=1 and reports=1")->orderBy('description')->asArray()->all(), 'code', 'description');
//var_dump($data);exit;
$total_count = 0;
$total_count1 = 0;
$total_import = 0;
$total_amount = 0;
$total_payments = 0;
$total_charged = 0;
$total_amount_office = 0;

function days_elapsed($date_i,$date_f)
{
    $week_start = date("W",strtotime($date_i));
    $week_end = date("W",strtotime($date_f));
    $number_week = $week_end - $week_start + 1 ;
    $days = (((strtotime($date_f)-strtotime($date_i))/86400)+1);
    $days = $days / 7;
    return  (int) round($days);
}
$value = 42000 * days_elapsed($start,$end);
//var_dump(Yii::$app->request->get('r'));
$link_ranking =  Yii::$app->request->get('r');
?>
<style>
    @media print {
        * {
            display: none;
        }
        #printableTable {
            display: block;
        }
    }
</style>
<script>
    function printTable() {
        window.frames["print_frame"].document.body.innerHTML =
            document.getElementById("table-ranking").innerHTML;
            window.frames["print_frame"].window.focus();
            window.frames["print_frame"].window.print();
    }
</script>
<div class="row">

    <form id="report_form" action="index.php">
        <input type="hidden" name="r" value="<?= $link_ranking?>">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Filtrar reporte</h5>
            </div>
            <div class="ibox-content">
                <div class="form-inline" style="margin-bottom: 10px">
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
                    <div class="form-group">
                        <input type="hidden" name="ranking_type" value="<?=$ranking_type?>">
                        <div class="input-daterange input-group">
                            <span class="input-group-addon">Estadísticas por:</span>
                            <span class="input-group-btn">
                                    <button type="button" class="btn btn-default js-switch<?=($ranking_type == 'agent' ? ' active' : '')?>" data-type="agent">Asesores</button>
                                    <button type="button" class="btn btn-default js-switch<?=($ranking_type == 'office' ? ' active' : '')?>" data-type="office">Oficinas</button>
                                </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div align="right">
        <button class="btn btn-primary" onclick="printTable()">Imprimir</button>
    </div>
    <div class="ibox" id="table-ranking">
        <div class="ibox-content">
            <?php if ($ranking_type == 'agent'): ?>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Posición</th>
                    <th>Unidad</th>
                    <th>Nombre</th>
                    <th>ID Empleado</th>
                    <th>Cobros</th>
                    <th>CCN</th>
                    <th>Importe</th>
                    <th>Monto al 7%</th>
                    <th>Roles</th>
                </tr>
                </thead>
                <tbody>
                <?php $position = 1; ?>
                <?php foreach ($data as $row): ?>
                <tr  <?= (($row['total'] < $value) /*AND ($row['payments'] < 6)*/)   ? ($row['active'] == 1 ? 'class= "alert alert-danger"' : 'style= "background-color: #DDDDDD"') :  ($row['active'] == 1 ? 'class="text-secondary"': 'style= "background-color: #DDDDDD"')?>>
                    <td><?=$position?></td>
                    <td><?=$row['office']?></td>
                    <td><?=$row['name']?></td>
                    <td><?=$row['username']?></td>
                    <td><a href="index.php?r=sales/lead/payments&start=<?=$start_date?>&end=<?=$end_date?>&office_id=<?=$row['office_id']?>&agent_id=<?=$row['user_id']?>"><?=$row['payments']?></a></td>
                    <td><?=$row['ccin']?></td>
                    <td>$<?=number_format($row['total'], 2)?></td>
                    <td>$<?=number_format(($row['total'] / .07), 2)?></td>
                    <td><?=$row['role']?></td>
                </tr>
                <?php
                    $position++;
                    $total_count += $row['payments'];
                    $total_count1 += $row['ccin'];
                    $total_amount += ($row['total'] / .07);
                    $total_import += $row['total'];
                    ?>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td><strong>Total del Grupo</strong></td>
                    <td></td>
                    <td><strong><?=$total_count?></strong></td>
                    <td><strong><?=$total_count1?></strong></td>
                    <td><strong>$<?=number_format($total_import, 2)?></strong></td>
                    <td><strong>$<?=number_format($total_amount, 2)?></strong></td>
                    <td></td>
                </tr>
                </tfoot>
            </table>
            <?php else: ?>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="width: 5%"></th>
                    <th>UN / Asesor</th>
                    <th style="width: 7%">Pagos</th>
                    <th style="width: 7%">CCN</th>
                    <th style="width: 20%">Cobrado</th>
                    <th style="width: 20%">Monto al 7%</th>
                </tr>
                </thead>
            </table>
                <?php
                    $office_data = [];
                    $office_amount = [];
                    foreach ($data as $row) {
                        $office_data[$row['office']][] = $row;
                        $office_amount[$row['office']] += $row['total'];
                    }
                    arsort($office_amount);
                ?>
            <?php $no = 1; ?>
            <?php foreach ($office_amount as $office => $amount): ?>
                <?php $data_row = $office_data[$office]; ?>
                <table class="table table-bordered">
                    <tr >
                        <td><strong>#<?=$no?></strong></td>
                        <td><strong><?=$offices[$office]?></strong></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php
                        $position = 1;
                        $total_office_count = 0;
                        $total_office_count1 = 0;
                        $total_office_amount = 0;
                        $total_office_import = 0;
                    ?>
                    <?php foreach ($data_row as $row): ?>
                        <tr <?= $row['total'] < $value   ? ($row['active'] == 1 ? 'class= "alert alert-danger"' : 'style= "background-color: #DDDDDD"') :  ($row['active'] == 1 ? 'class="text-secondary"': 'style= "background-color: #DDDDDD"')?>>
                            <td style="width: 5%"><?=$position?></td>
                            <td><?=$row['name']?></td>
                            <td style="width: 7%"><a href="index.php?r=sales/lead/payments&start=<?=$start_date?>&end=<?=$end_date?>&office_id=<?=$row['office_id']?>&agent_id=<?=$row['user_id']?>"><?=$row['payments']?></a></td>
                            <td style="width: 7%"><?=$row['ccin']?></td>
                            <td style="width: 20%">$<?=number_format($row['total'], 2)?></td>
                            <td style="width: 20%">$<?=number_format(($row['total'] / .07), 2)?></td>
                        </tr>
                        <?php
                        $position++;
                        $total_office_count += $row['payments'];
                        $total_office_amount += ($row['total'] / .07);
                        $total_office_import += $row['total'];
                        $total_office_count1 += $row['ccin'];
                        ?>
                    <?php endforeach; ?>
                    <tr>
                        <td style="width: 5%"></td>
                        <td></td>
                        <td style="width: 7%"><strong><a href="index.php?r=sales/lead/payments&start=<?=$start_date?>&end=<?=$end_date?>&office_id=<?=$row['office_id']?>"><?=$total_office_count?></strong></a></td>
                        <td style="width: 7%"><strong><?=$total_office_count1?></strong></td>
                        <td style="width: 20%"><strong>$<?=number_format($total_office_import, 2)?></strong></td>
                        <td style="width: 20%"><strong>$<?=number_format($total_office_amount, 2)?></strong></td>
                        <?php
                        $total_payments += $total_office_count;
                        $total_payments1 += $total_office_count1;
                        $total_charged += $total_office_import;
                        $total_amount_office += $total_office_amount;
                        ?>
                    </tr>
                </table>
                    <?php $no++; ?>
            <?php endforeach; ?>
            <table class="table table-bordered">
                <tr>
                    <td><strong>#<?=$no?></strong></td>
                    <td><strong>Total de oficinas</strong></td>
                    <td><strong>Total de Pagos</strong></td>
                    <td><strong>Total CCN</strong></td>
                    <td><strong>Total Cobrado</strong></td>
                    <td><strong>Total Monto al 7%</strong></td>
                </tr>
                <tr>
                    <td style="width: 5%"></td>
                    <td style="text-align: center"><strong><?=$no-1?></strong></td>
                    <td style="width: 7%"><strong><a href="index.php?r=sales/lead/payments&start=<?=$start_date?>&end=<?=$end_date?>"><?=$total_payments?></strong></a></td>
                    <td style="width: 7%"><strong><?=$total_payments1?></strong></td>
                    <td style="width: 20%"><strong>$<?= number_format($total_charged, 2)?></strong></td>
                    <td style="width: 20%"><strong>$<?=number_format($total_amount_office, 2)?></strong></td>
                </tr>
            </table>
            <?php endif; ?>
        </div>
    </div>
    <iframe name="print_frame" width="0" height="0" frameborder="0" src="about:blank"></iframe>
</div>

<script>
    document.querySelectorAll('.js-switch').forEach(function (switcher) {
        switcher.onclick = function () {

            //
            document.querySelector('input[name="ranking_type"]').value = switcher.getAttribute('data-type');

            //
            document.getElementById('report_form').submit();
        }
    })
</script>
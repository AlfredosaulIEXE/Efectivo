<?php



use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\Office;
use livefactory\models\SalesReport;
use yii\helpers\ArrayHelper;

use livefactory\models\User;

/**

 *

 * @var yii\web\View $this

 * @var yii\data\ActiveDataProvider $dataProvider

 * @var common\models\search\Lead $searchModel
 *

 */

$this->title = 'Reporte de efectividad';

$this->params ['breadcrumbs'] [] = $this->title;
$pageView = 'List View';

$by_office = Yii::$app->user->can('Office.NoLimit') == true;
$filter = Yii::$app->request->getQueryParam('Lead');
$request = Yii::$app->request->getQueryParams();
$office_id = $by_office ? Yii::$app->request->getQueryParam('office_id') : Yii::$app->user->identity->office_id;
list($start, $end) = SalesReport::getPeriodFromRequest(Yii::$app->request->getQueryParams());

$statuses = [
    1 => 'Nuevo',
    2 => 'Cita',
    3 => 'UPS',
    4 => 'Venta',
    6 => 'Muerto',
    8 => 'No Contesta',
    9 => 'Seguimiento'
];

// Counting
$amounts = [
    1 => 0, // Nuevo
    9 => 0, //Seguimiento
    2 => 0, // Cita
    3 => 0, // UPS
    4 => 0, // Venta
    8 => 0, // No Contesta
    6 => 0, // Muerto

];

$office_amounts = [];
$office_not_work = [];
$office_total_amount = [];
$amounts_empty = $amounts;

//
$total_amount = 0;
$not_work_amount = 0;

// All leads
foreach ($data as $row) {
    $amounts[$row->lead_status_id]++;
    $total_amount++;

    // leads trabajados
    if ($row->lead_status_id != 6 && $row->lead_status_id != 4) {
        $not_work_amount++;

        if ( ! isset($office_not_work[$row->office_id]))
            $office_not_work[$row->office_id] = 0;

        $office_not_work[$row->office_id]++;
    }

    if ( ! isset($office_amounts[$row->office_id])) {
        $office_total_amount[$row->office_id] = 0;
        $office_amounts[$row->office_id] = $amounts_empty;
    }

    // Count
    $office_amounts[$row->office_id][$row->lead_status_id]++;
    $office_total_amount[$row->office_id]++;
}

// Only sales
foreach ($data_sales as $row) {
    $amounts[$row->lead_status_id]++;
    $total_amount++;

    // leads trabajados
    if ($row->lead_status_id != 6 && $row->lead_status_id != 4) {
        $not_work_amount++;

        if ( ! isset($office_not_work[$row->office_id]))
            $office_not_work[$row->office_id] = 0;

        $office_not_work[$row->office_id]++;
    }

    if ( ! isset($office_amounts[$row->office_id])) {
        $office_total_amount[$row->office_id] = 0;
        $office_amounts[$row->office_id] = $amounts_empty;
    }

    // Count
    $office_amounts[$row->office_id][$row->lead_status_id]++;
    $office_total_amount[$row->office_id]++;
}

?>
<div class="row">

    <form id="report_form" action="index.php">
        <input type="hidden" name="r" value="sales/lead/effectiveness">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Filtrar reporte de efectividad</h5>
            </div>
            <div class="ibox-content">
                <?php require 'reports/filters.php'; ?>
            </div>
        </div>
    </form>

    <div class="ibox">
        <div class="ibox-content">
            <?php if ($office_id): ?>
            <table class="table" id="data_table">
                <thead>
                <tr>
                    <th>Estado</th>
                    <th>Cantidad</th>
                    <th class="text-right">%</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($amounts as $status_id => $amount): ?>
                <tr class="<?php echo ($status_id != 6 && $status_id != 4) ? 'warning' : ($status_id == 6 ? 'danger' : 'success');?>">
                    <td><?=strtoupper($statuses[$status_id]);?></td>
                    <td>
                        <?php if ($status_id != 4): ?>
                            <a href="index.php?Lead[office_id]=<?=$office_id?>&Lead[lead_status_id]=<?=$status_id?>&Lead[lead_owner_id]=<?=$agent_id?>&r=sales/lead/&start_date=<?=$start?>&end_date=<?=$end?>"><?=$amount; ?></a>
                        <?php else: ?>
                            <a href="index.php?r=sales/lead/list&start=<?=$request['start']?>&end=<?=$request['end']?>&office_id=<?=$office_id?>&type=converted"><?=$office_amounts[$office_id][$status_id]; ?></a>
                        <?php endif; ?>
                    </td>
                    <td class="text-right"><?=round(($amount / $total_amount) * 100, 2)?>%</td>
                </tr>
                <?php endforeach; ?>
                </tbody>
                <tfooot>
                    <tr>
                        <td><strong>TOTAL DE LEADS</strong></td>
                        <td><strong><?=$total_amount;?></strong></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr style="font-size: 120%">
                        <td><strong>FALTA LABOR</strong></td>
                        <td><strong><?=$not_work_amount?></strong></td>
                        <td class="text-right"><?=round(($not_work_amount / $total_amount) * 100, 2)?>%</td>
                    </tr>
                </tfooot>
            </table>
            <?php else: ?>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th></th>
                        <?php foreach ($offices as $o_id => $name): ?>
                        <th colspan="2" class="text-center"><?=$name?></th>
                        <?php endforeach; ?>
                        <th colspan="2" class="text-center">TOTALES</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($amounts as $status_id => $amount): ?>
                        <tr class="<?php echo ($status_id != 6 && $status_id != 4) ? 'warning' : ($status_id == 6 ? 'danger' : 'success');?>">
                            <td><?=strtoupper($statuses[$status_id]);?></td>
                            <?php foreach ($offices as $o_id => $name): ?>
                                <td style="border-right: 0">
                                    <?php if ($status_id != 4): ?>
                                        <a href="index.php?Lead[office_id]=<?=$o_id?>&Lead[lead_status_id]=<?=$status_id?>&r=sales/lead/&start_date=<?=$start?>&end_date=<?=$end?>"><?=empty($office_amounts[$o_id][$status_id]) ? 0 : $office_amounts[$o_id][$status_id]; ?></a>
                                    <?php else: ?>
                                        <a href="index.php?r=sales/lead/list&start=<?=$request['start']?>&end=<?=$request['end']?>&office_id=<?=$o_id?>&type=converted"><?=$office_amounts[$o_id][$status_id]; ?></a>
                                    <?php endif; ?>
                                </td>
                            <td style="border-left: 0" class="text-right"><?=empty($office_total_amount[$o_id]) ? 0 : round(($office_amounts[$o_id][$status_id] / $office_total_amount[$o_id]) * 100, 2)?>%</td>
                            <?php endforeach; ?>
                            <td><?=$amount; ?></td>
                            <td class="text-right"><?=round(($amount / $total_amount) * 100, 2)?>%</td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfooot>
                        <tr>
                            <td><strong>TOTAL DE LEADS</strong></td>
                            <?php foreach ($offices as $o_id => $name): ?>
                            <td><strong><?=$office_total_amount[$o_id];?></strong></td>
                            <td></td>
                            <?php endforeach; ?>
                            <td><strong><?=$total_amount;?></strong></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                        <tr style="font-size: 120%">
                            <td><strong>FALTA LABOR</strong></td>
                            <?php foreach ($offices as $o_id => $name): ?>
                            <td><strong><?=$office_not_work[$o_id];?></strong></td>
                            <td><?=round(($office_not_work[$o_id] / $office_total_amount[$o_id]) * 100, 2)?>%</td>
                            <?php endforeach; ?>
                            <td><strong><?=$not_work_amount?></strong></td>
                            <td><?=round(($not_work_amount / $total_amount) * 100, 2)?>%</td>
                        </tr>
                    </tfooot>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

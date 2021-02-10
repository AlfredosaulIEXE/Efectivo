<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use livefactory\models\search\CommonModel;
use livefactory\models\LeadReport;
use livefactory\models\Payment;
use livefactory\models\SalesReport;
use livefactory\models\Office;
use livefactory\models\User;
use livefactory\models\TaskReports;
use livefactory\models\DefectReports;
use livefactory\models\CustomerReport;
use livefactory\models\TaskStatus;
use livefactory\models\LeadSource;

date_default_timezone_set(Yii::$app->params['TIME_ZONE']);

/* @var $this yii\web\View */
$this->title = \Yii::t('app', 'My Dashboard');
$this->registerCssFile('css/plugins/datapicker/datepicker3.css');
$days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));

// Sales Report
$offices = ArrayHelper::map(Office::find()->where("active=1 and reports=1")->orderBy('description')->asArray()->all(), 'id', 'description');
$agents_office_id = $office_id == null ? ( ! Yii::$app->user->can('Office.NoLimit') && Yii::$app->user->can('Reports.ByUser') ? Yii::$app->user->identity->office_id : null ) : $office_id;
$agents = $agents_office_id == null ? [] : ArrayHelper::map(User::find()->leftJoin('auth_assignment' , 'auth_assignment.user_id = tbl_user.id')->where('tbl_user.office_id = ' . $agents_office_id . ' and tbl_user.active = 1 and auth_assignment.item_name != "Receptionist" ')->orderBy('alias')->asArray()->all(), 'id', 'alias');
$means = ArrayHelper::map(LeadSource::find()->where("active=1")->orderBy('label')->asArray()->all(), 'id', 'label');

//
$office = Office::findOne($office_id ? $office_id : Yii::$app->user->identity->office_id);
$agent = User::findOne($agent_id);

//
$all_leads = CommonModel::getAllLeads($start, $end, $office_id, $agent_id, $mean_id);
$all_appointments = CommonModel::getAllAppointments($start, $end, $office_id, $agent_id, $mean_id);
$all_ups = CommonModel::getAllOpportunities($start, $end, $office_id, $agent_id, $mean_id);
$all_converted = CommonModel::getAllConverted($start, $end, $office_id, $agent_id, $mean_id);

$placement_amount = CommonModel::getPlacement($start, $end, $office_id, $agent_id, $mean_id);
$charged_amount = CommonModel::getTotalSales($start, $end, $office_id, $agent_id, $mean_id);
$charged_amount1  = CommonModel::getTotalSales1($start,$end,$office_id, $agent_id, $mean_id);
$charged_amount2 = CommonModel::getTotalSales2($start,$end,$office_id, $agent_id, $mean_id);
$total_amount = CommonModel::getAmount($start, $end, $office_id, $agent_id, $mean_id);

// New contracts
$new_contract_amount = CommonModel::getSales($start, $end, $office_id, $agent_id, $mean_id, Payment::_NEW_CONTRACT);
$new_contract_percent = round(($new_contract_amount / $charged_amount) * 100);

//
$advance_amount = CommonModel::getSales($start, $end, $office_id, $agent_id, $mean_id, Payment::_ADVANCE);
$advance_percent = round(($advance_amount / $charged_amount) * 100);

//
$addendums_amount = CommonModel::getSales($start, $end, $office_id, $agent_id, $mean_id, Payment::_ADDENDUMS);
$addendums_percent = round(($addendums_amount / $charged_amount) * 100);

//
$increase_amount = CommonModel::getSales($start, $end, $office_id, $agent_id, $mean_id, Payment::_INCREASE);
$increase_percent = round(($increase_amount / $charged_amount ) * 100);

//
$insurance_amount = CommonModel::getSales($start, $end, $office_id, $agent_id, $mean_id, Payment::_INSURANCE);
$insurance_percent = round(($insurance_amount / $charged_amount ) * 100);
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<link rel="stylesheet" href="../include/jPages.css">
<!-- announcement display code added by deepak -->
<?php $msg = livefactory\models\Announcement::find()->andWhere(['is_status' => 0])->one(); ?>
<?php if (!empty($msg))    { ?>
<script>
    $(function (e) {
        setTimeout(function () {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                onClick: null,
                positionClass: 'toast-top-center',

                showMethod: 'fadeIn',
                timeOut: 4000
            };
            toastr.info('<?= $msg->message ?>');

        }, 1300);
    });
</script>
<?php } ?><!-- announcement display code end (by deepak) -->
<script>
    $(document).ready(function (e) {
        $("div.holder").jPages({
            containerID: "todolist",
            perPage: 15,
            delay: 20
        });
        $("div.holder1").jPages({
            containerID: "timeline",
            perPage: 15,
            delay: 20
        });
        $("div.holder2").jPages({
            containerID: "customers",
            perPage: 6,
            delay: 20
        });
        $('#project_box').change(function () {
            window.location.href = 'index.php?r=site/index&entity_id=' + $(this).val() + '#timeline';
        });
    });
</script>
<style>
    .timeline-item .content {
        min-height: 50px
    }

    .timeline-item .date {
        width: 35px
    }
</style>
<div class="site-index">

    <div class="ibox">
        <div class="ibox-title"><h5>Filtros</h5></div>
        <div class="ibox-content">
            <form id="report_form" action="index.php">
                <input type="hidden" name="r" value="site/index">
                <?php if (Yii::$app->user->can('Reports.ByMeans')): ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-control-static"><h4 class="m-n">Periodo:</h4></div>
                    </div>
                    <?php if (Yii::$app->user->can('Office.NoLimit')): ?>
                    <div class="col-md-4">
                        <div class="form-control-static"><h4 class="m-n">Sucursal:</h4></div>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-4">
                        <ul id="filter-tabs" class="nav nav-tabs">
                            <li<?=$mean_id ? '' : ' class="active"'?>><a href="#filter-agents" data-toggle="tab" style="padding: 3px 10px">Asesores</a></li>
                            <li<?=$mean_id ? ' class="active"' : ''?>><a href="#filter-means" data-toggle="tab" style="padding: 3px 10px;">Medios</a></li>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
                <div class="row">
                    <div id="datepicker" class="col-md-4 col-lg-4 text-right">
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

                    <?php if (Yii::$app->user->can('Office.NoLimit')): ?>
                    <div class="col-md-4">
                        <select name="office_id" class="form-control">
                            <option value="">Todas las sucursales...</option>
                            <?php foreach ($offices as $o_id => $name): ?>
                                <option value="<?= $o_id ?>"<?php if($o_id == $office_id): ?> selected<?php endif; ?>><?=$name?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                    <?php endif; ?>
                    <div class="col-md-4">
                        <div class="tab-content">
                            <div class="tab-pane<?=$mean_id ? '' : ' active'?>" id="filter-agents">
                                <?php if (Yii::$app->user->can('Reports.ByUser')): ?>
                                    <select name="agent_id" class="form-control">
                                        <option value="">Todos los asesores</option>
                                        <?php foreach ($agents as $a_id => $name): ?>
                                            <option value="<?= $a_id ?>"<?php if($a_id == $agent_id): ?> selected<?php endif; ?>><?=$name?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <input type="hidden" name="office_id" value="<?=$office_id?>">
                                    <div class="input-daterange input-group">
                                        <span class="input-group-addon">Estadísticas de:</span>
                                        <span class="input-group-btn">
                                    <button   type="button" class="btn btn-default js-switch <?=$agent_id != null ? ' active user' : 'user-off'?>" data-type="agent"><?=Yii::$app->user->identity->first_name?></button>
                                    <button type="button" class="btn btn-default js-switch <?=$office_id != null ? ' active ' : ''?>" data-type="office"><?=$office->description?></button>
                                </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="tab-pane<?=$mean_id ? ' active' : ''?>" id="filter-means">
                                <select name="mean_id" class="form-control">
                                    <option value="">Todos los medios</option>
                                    <?php foreach ($means as $m_id => $name): ?>
                                        <option value="<?= $m_id ?>"<?php if($m_id == $mean_id): ?> selected<?php endif; ?>><?=$name?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php
    if (Yii::$app->user->identity->userType->type != "Customer" || Yii::$app->params['user_role'] == 'admin') {
        ?>
        <script src="../include/jsapi.js"></script>
    <?php

    if (in_array('sales', Yii::$app->params['modules']))
    {
    $obj = new LeadReport;
    ?>
    <div class="row">
        <?php if (Yii::$app->params['SHOW_SALES_FUNNEL_ON_DASHBOARD'] == 'Yes') { ?>
            <div class="<?= Yii::$app->params['SHOW_LEAD_STATISTICS_ON_DASHBOARD'] == 'No' ? 'col-lg-12' : 'col-lg-6' ?>">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <div class="btn-group btn-group-xs pull-right" role="group" aria-label="...">
                            <button type="button" class="btn btn-default hide"><?php echo Yii::t('app', 'today'); ?></button>
                            <button type="button" class="btn btn-default hide"><?php echo Yii::t('app', 'week'); ?></button>
                            <button type="button" class="btn btn-warning hide"><?php echo Yii::t('app', 'month'); ?></button>
                            <button type="button"
                                    class="btn btn-danger hide"><?php echo Yii::t('app', 'last month'); ?></button>
                        </div>
                        <h5><?php echo Yii::t('app', 'Sales Funnel'); ?></h5>
                    </div>

                    <div class="ibox-content">
                        <div id="sales-funnel" style="width:100%;height:495px;"></div>
                    </div>
                </div>
            </div>

            <?php
            $obj->salesFunnelChart3($all_leads, $all_appointments, $all_ups, $all_converted);
            ?>
        <?php }
    if (Yii::$app->params['SHOW_LEAD_STATISTICS_ON_DASHBOARD'] == 'Yes'){
        ?>
        <div class="<?= Yii::$app->params['SHOW_SALES_FUNNEL_ON_DASHBOARD'] == 'No' ? 'col-lg-4' : 'col-lg-3' ?>">
            <div class="ibox float-e-margins">
                <div class="ibox-title">

                    <h5><?php echo Yii::t('app', 'Leads'); ?></h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><a href="<?= CommonModel::getStatsLink($start, $end, $office_id, $agent_id, $mean_id, 'leads') ?>"><?= $all_leads ?></a></h1>
                    <small><?php echo Yii::t('app', 'Total Leads'); ?></small>
                </div>
            </div>
        </div>
        <div class="<?= Yii::$app->params['SHOW_SALES_FUNNEL_ON_DASHBOARD'] == 'No' ? 'col-lg-4' : 'col-lg-3' ?>">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?php echo Yii::t('app', 'Appointments'); ?></h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><a href="<?= CommonModel::getStatsLink($start, $end, $office_id, $agent_id, $mean_id, 'appointments')?>"><?= $all_appointments ?></a></h1>
                    <small>Total de Citas</small>
                </div>
            </div>
        </div>
        <div class="<?= Yii::$app->params['SHOW_SALES_FUNNEL_ON_DASHBOARD'] == 'No' ? 'col-lg-4' : 'col-lg-3' ?>">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?php echo Yii::t('app', 'UPS'); ?></h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><a href="<?= CommonModel::getStatsLink($start, $end, $office_id, $agent_id, $mean_id, 'ups') ?>"><?= $all_ups ?></a></h1>
                    <small><?php echo Yii::t('app', 'Total UPS'); ?></small>
                </div>
            </div>
        </div>
        <div class="<?= Yii::$app->params['SHOW_SALES_FUNNEL_ON_DASHBOARD'] == 'No' ? 'col-lg-4' : 'col-lg-3' ?>">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?php echo Yii::t('app', 'Sales'); ?></h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><a href="<?= CommonModel::getStatsLink($start, $end, $office_id, $agent_id, $mean_id, 'converted') ?>"><?= $all_converted ?></a></h1>
                    <small><?php echo Yii::t('app', 'Total Sales'); ?></small>
                </div>
            </div>
        </div>
        <div class="<?= Yii::$app->params['SHOW_SALES_FUNNEL_ON_DASHBOARD'] == 'No' ? 'col-lg-4' : 'col-lg-6' ?>">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Monto (Colocado)</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><a href="<?= CommonModel::getStatsLink($start, $end, $office_id, $agent_id, $mean_id, 'amount') ?>"><?= '$' . number_format($total_amount, 2) ?></a></h1>
                </div>
            </div>
        </div>
        <div class="<?= Yii::$app->params['SHOW_SALES_FUNNEL_ON_DASHBOARD'] == 'No' ? 'col-lg-4' : 'col-lg-6' ?>">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Colocación al 7%</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?= '$' . number_format(($charged_amount + $charged_amount1) / 0.07, 2) ?></h1>
                </div>
            </div>
        </div>
        <div class="row col-lg-12">
            <div class="<?= Yii::$app->params['SHOW_SALES_FUNNEL_ON_DASHBOARD'] == 'No' ? 'col-lg-4' : 'col-lg-3' ?>">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Total en Firme y Validados</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><a href="<?=  CommonModel::getStatsLink($start, $end, $office_id, $agent_id, $mean_id, 'charged') ?>"><?= '$' . number_format($charged_amount, 2) ?></a></h1>
                    </div>
                </div>
            </div>
            <div class="<?= Yii::$app->params['SHOW_SALES_FUNNEL_ON_DASHBOARD'] == 'No' ? 'col-lg-4' : 'col-lg-3' ?>">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Total en Validación</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><a href="<?= CommonModel::getStatsLink($start, $end, $office_id, $agent_id, $mean_id, 'validate') ?>"><?= '$' . number_format($charged_amount1, 2) ?></a></h1>
                    </div>
                </div>
            </div>
            <div class="<?= Yii::$app->params['SHOW_SALES_FUNNEL_ON_DASHBOARD'] == 'No' ? 'col-lg-4' : 'col-lg-3' ?>">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Total de Declinados</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?= '$' . number_format($charged_amount2, 2) ?></h1>
                    </div>
                </div>
            </div>
            <div class="<?= Yii::$app->params['SHOW_SALES_FUNNEL_ON_DASHBOARD'] == 'No' ? 'col-lg-4' : 'col-lg-3' ?>">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Total en validación y declinados</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?= '$' . number_format($charged_amount1 + $charged_amount2, 2) ?></h1>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="ibox">
            <div class="ibox-title"><h5>Desglose de cobros</h5></div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="m-md">
                                    <h1 class="no-margins"><a href="<?=  CommonModel::getStatsLink($start, $end, $office_id, $agent_id, $mean_id, 'payment', Payment::_NEW_CONTRACT)?>"><?= '$' . number_format($new_contract_amount, 2) ?></a></h1>
                                    <h3 class="no-margins text-info"><?=$new_contract_percent?>%</h3>
                                    <span class="text-muted font-bold">Contratos nuevos</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="m-md">
                                    <h1 class="no-margins"><a href="<?= CommonModel::getStatsLink($start, $end, $office_id, $agent_id, $mean_id, 'payment', Payment::_ADVANCE) ?>"><?= '$' . number_format($advance_amount, 2) ?></a></h1>
                                    <h3 class="no-margins text-info"><?=$advance_percent?>%</h3>
                                    <span class="text-muted font-bold">Anticipos</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="m-md">
                                    <h1 class="no-margins"><a href="<?= CommonModel::getStatsLink($start, $end, $office_id, $agent_id, $mean_id, 'payment', Payment::_ADDENDUMS) ?>"><?= '$' . number_format($addendums_amount, 2) ?></a></h1>
                                    <h3 class="no-margins text-info"><?=$addendums_percent?>%</h3>
                                    <span class="text-muted font-bold">Addendums</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="m-md">
                                    <h1 class="no-margins"><a href="<?= CommonModel::getStatsLink($start, $end, $office_id, $agent_id, $mean_id, 'payment', Payment::_INCREASE)?>"><?= '$' . number_format($increase_amount, 2) ?></a></h1>
                                    <h3 class="no-margins text-info"><?=$increase_percent?>%</h3>
                                    <span class="text-muted font-bold">Incrementos</span>
                                </div>
                            </div>
                        </div>
                        <?php if (Yii::$app->user->can('Payment.Insurance')) { ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="m-md">
                                    <h1 class="no-margins"><a href="<?= CommonModel::getStatsLink($start, $end, $office_id, $agent_id, $mean_id, 'payment', Payment::_INSURANCE) ?>"><?= '$' . number_format($insurance_amount, 2) ?></a></h1>
                                    <!--<h3 class="no-margins text-info"><?=$insurance_percent?>%</h3>-->
                                    <span class="text-muted font-bold">Seguros</span>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col-md-6">
                        <div id="amount-chart" style="width:100%;height:250px;" ></div>
                        <?php $ctype = "[['Customer Type', 'Customer Type']";
                        $ctype.=",['Contratos nuevos', ".intval($new_contract_amount)."]";
                        $ctype.=",['Anticipos', ".intval($advance_amount)."]";
                        $ctype.=",['Addendums', ".intval($addendums_amount)."]";
                        $ctype.=",['Incrementos', ".intval($increase_amount)."]";
                        $ctype.="]";
                        ?>
                        <script type="text/javascript">
                            google.load("visualization", "1", {packages:["corechart"]});
                            google.setOnLoadCallback(drawChart);
                            function drawChart() {
                                var data = google.visualization.arrayToDataTable(<?=$ctype?>);

                                var options = {
                                    title: '',
                                    is3D: true,
                                    // pieHole: 0.3,
                                    colors: ['#1ab394','#d1dade', '#1c84c6', '#ed5565'],
                                };

                                var chart = new google.visualization.PieChart(document.getElementById('amount-chart'));
                                chart.draw(data, options);
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $quality_levels = [
            6 => [
                'label' => 'Más de 6 millones',
                'color' => 'rgb(152,53,152)'
            ],
            5 => [
                'label' => 'De 3 a 6 millones',
                'color' => 'rgb(0,102,204)'
            ],
            4 => [
                'label' => 'De 1.2 a 3 millones',
                'color' => 'rgb(24,210,234)'
            ],
            3 => [
                'label' => 'De 600 mil a 1.2 millones',
                'color' => 'rgb(104,203,59)'
            ],
            2 => [
                'label' => 'De 120 a 600 mil',
                'color' => 'rgb(255,255,121)'
            ],
            1 => [
                'label' => 'De 60 a 120 mil',
                'color' => 'rgb(255, 158, 0)'
            ],
            /*0 => [
                'label' => 'Menos de 60 mil',
                'color' => 'rgb(255,69,62)'
            ],*/
        ];
        ?>
        <?php list($quality, $_offices) = CommonModel::getQuality($start, $end, $office_id, $agent_id, $mean_id); ?>
        <div class="ibox">
            <div class="ibox-title"><h5>Calidad de Leads</h5></div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-6">
                        <div style="position: relative; height:600px;">
                            <canvas id="canvas"></canvas>
                        </div>
                        <script type="text/javascript">
                            var barChartData = {
                                labels: ['<?=implode("', '", $_offices)?>'],
                                datasets: [
                                        <?php foreach ($quality as $key => $item):?>{
                                        label: '<?=$quality_levels[$key]['label']?>',
                                        backgroundColor: '<?=$quality_levels[$key]['color']?>',
                                        data: [<?php foreach ($item as $num): ?> <?=$num?>, <?php endforeach; ?>],
                                    },
                                    <?php endforeach; ?>]
                            };
                        </script>
                    </div>
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <td></td>
                                    <?php foreach ($_offices as $office): ?>
                                        <td class="text-center"><?=$office?></td>
                                    <?php endforeach; ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $total_quality = []; ?>
                                <?php foreach ($quality as $key => $item): ?>
                                    <tr>
                                        <td><?=$quality_levels[$key]['label']?></td>
                                        <?php foreach ($item as $_oid => $num): ?>
                                            <td class="text-right"><?=$num?></td>
                                            <?php $total_quality[$_oid] += $num; ?>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <?php foreach ($total_quality as $_t): ?>
                                        <td class="text-right"><strong><?=$_t?></strong></td>
                                    <?php endforeach; ?>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
    } //end check for sales module
}
?>
</div>
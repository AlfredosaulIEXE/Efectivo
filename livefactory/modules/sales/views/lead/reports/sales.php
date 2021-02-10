<?php


use livefactory\models\SalesReport;
use livefactory\models\LeadSource;
use yii\helpers\ArrayHelper;

$this->title =Yii::t('app','Sales Report');
$this->registerCssFile('css/plugins/datapicker/datepicker3.css');

// Sales Report
list($start, $end) = SalesReport::getPeriodFromRequest(Yii::$app->request->getQueryParams());
//$period = SalesReport::getPeriod($start, $end);
list($days, $stats) = SalesReport::getStats($start, $end);

// Means
$leadSource = array();
foreach (ArrayHelper::map(LeadSource::find()->where("active=1")->orderBy('sort_order')->asArray()->all(), 'id', 'label') as $key => $ld) {
    $leadSource[$key] = $ld;
}

$paymentTypes = [
    'new_contract' => 'Cobro contrato nuevo',
    'advance' => 'Cobro anticipo',
    'addemdums' => 'Cobro ademdums',
    'increase' => 'Cobro incremento',
];

$week = ['', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
$total = 0;
foreach ($stats as $date) {
    $total++;
}
if (count($days) > 6) {
    $total = 1;
}
$total += 3;
// Counters
$total_ups = [];
$acum_leads = 0;
$acum_appointments = 0;
$acum_ups = 0;
$acum_re_appointments = 0;
$acum_increments = 0;
$acum_contracts = 0;
$acum_income = 0;
$acum_sales = 0;
$advance_amount = 0;
//$advance_sales = 0;
$advance_news = 0;
$optim_appointments = 90;
$optim_ups = 65;
$optim_contracts = 45;
$office_goal = $office->weekly_goal ? $office->weekly_goal : 0;
?>
<form id="report_form" action="index.php">
    <input type="hidden" name="r" value="sales/lead/report">
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Reporte de ventas</h5>
            </div>
            <div class="ibox-content">
                <?php include 'filters.php' ?>
                <?php if (count($days) > 7): ?>
                <?php include 'total.php'; ?>
                <?php else: ?>
                <?php include 'week.php'; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</form>
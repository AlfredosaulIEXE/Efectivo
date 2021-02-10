<?php
use yii\helpers\ArrayHelper;
use livefactory\models\User;
use livefactory\models\Office;
use livefactory\models\LeadStatus;


$request = Yii::$app->request->getQueryParams();

$start = isset($request['start']) && $request['start'] != '' ? $request['start'] : date('Y-m-d');
$end = isset($request['end']) && $request['end'] != '' ? $request['end'] : date('Y-m-d');
var_dump($start);


/////link
$link_filter = Yii::$app->request->getQueryParams();
var_dump($link_filter['r']);
///

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

            </div>


        </form>
    </div>
</div>
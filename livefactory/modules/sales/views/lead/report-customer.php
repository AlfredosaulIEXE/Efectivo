<?php
$this->title = Yii::t ( 'app', 'Reporte Atención a Clientes' );

$this->params ['breadcrumbs'] [] = $this->title;
var_dump($stats);
?>
<div class="row">
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5>Total </h5>
            </div>
            <div class="panel-body">
                <h4><?= $stats['total']?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5>Colocación</h5>
            </div>
            <div class="panel-body">

            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5>Cancelaciones</h5>
            </div>
            <div class="panel-body">

            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="ali">
        <h5>Devoluciones</h5>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5>Devoluciones en proceso </h5>
                </div>
                <div class="panel-body">

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5>Devoluciones declinadas</h5>
                </div>
                <div class="panel-body">

                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <form id="report_form" action="index.php">
        <input type="hidden" name="r" value="sales/lead/report_customer">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Filtro Reporte Atención a Clientes</h5>
            </div>
            <div class="ibox-content">
                <?php require 'filterreportcustomer.php';?>
            </div>
        </div>

    </form>

</div>

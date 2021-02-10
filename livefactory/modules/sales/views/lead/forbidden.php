<?php

$this->title = Yii::t ( 'app', 'Leads' );

$office = $model->office;

?>
<div class="alert alert-warning">
    <h4 class="text-uppercase">Lo sentimos no puedes acceder a este Lead</h4>
    <p>ESTE LEAD ES ADMINISTRADO POR OTRA UNIDAD DE NEGOCIOS (<strong><?=$office->code?></strong>) SI DESEA ALGÚN DATO CONSULTE POR FAVOR CON SU GERENCIA!</p>
</div>
<p>
    <a href="index.php?r=sales/lead/index" class="btn btn-default"><i class="fa fa-arrow-left"></i> Regresar</a>
</p>
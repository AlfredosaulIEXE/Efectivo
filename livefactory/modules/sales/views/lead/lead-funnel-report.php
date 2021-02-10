<?php

use livefactory\models\LeadReport;

$obj = new LeadReport;

$this->title =Yii::t('app','Sales Funnel');

?>

<div class="row">

	<div class="col-sm-12">

  		<div class="panel panel-info">

            <div class="panel-heading">

                <strong><?=Yii::t('app','Sales Funnel')?></strong>

            </div>

            <div class="panel-body">

                <div id="sales-funnel" style="width:50%;height:500px;" ></div>

            </div>

        </div>

	</div>

</div>


 <?php

	$obj->salesFunnelChart2('sales-funnel');

 ?>
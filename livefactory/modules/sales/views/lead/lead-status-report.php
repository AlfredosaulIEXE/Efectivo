<?php

use livefactory\models\LeadReport;

$obj = new LeadReport;

$this->title =Yii::t('app','Lead Status Reports');

?>

<div class="row">

	<div class="col-sm-12">

  		<div class="panel panel-info">

            <div class="panel-heading">

                <strong><?=Yii::t('app','Lead Status')?></strong>

            </div>

            <div class="panel-body">

                <div id="lead-status" style="width:100%;height:500px"></div>

            </div>

        </div>

	</div>

</div>







 <script src="../include/jsapi.js"></script>

 <?php

	$obj->leadStatusChart('lead-status');

 ?>
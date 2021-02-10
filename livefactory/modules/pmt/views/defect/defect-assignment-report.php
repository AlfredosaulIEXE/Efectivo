<?php 

$this->title =Yii::t ( 'app','Defect Assignment Report');

?>

<script src="../include/jsapi.js"></script>

<!--<div class="panel panel-primary">

	<div class="panel-heading">

    	<h3 class="panel-title">Defect Status</h3>

    </div>

    <div class="panel-body">

    	<div id="defectStatus" style="height:350px"></div>

    </div>

</div>-->

<div class="panel panel-info">

	<div class="panel-heading">

    	<h3 class="panel-title"><?=Yii::t ( 'app','Defect Assignment')?> </h3>

    </div>

    <div class="panel-body">

    	<div id="defectAssignment" style="height:350px"></div>

    </div>

</div>



<?php  

use livefactory\models\DefectReports;

//DefectReports::myDefectStatusChart('defectStatus');

$defectObj = new DefectReports();

$defectObj->getDefectAssignmentChart('defectAssignment');

?>
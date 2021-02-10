<?php 

$this->title =Yii::t ( 'app','Task Assignment Report');

?>

<script src="../include/jsapi.js"></script>

<!--<div class="panel panel-primary">

	<div class="panel-heading">

    	<h3 class="panel-title">Task Status</h3>

    </div>

    <div class="panel-body">

    	<div id="taskStatus" style="height:350px"></div>

    </div>

</div>-->

<div class="panel panel-info">

	<div class="panel-heading">

    	<h3 class="panel-title"><?=Yii::t ( 'app','Task Assignment')?> </h3>

    </div>

    <div class="panel-body">

    	<div id="taskAssignment" style="height:350px"></div>

    </div>

</div>



<?php  

use livefactory\models\TaskReports;

//TaskReports::myTaskStatusChart('taskStatus');

$taskObj = new TaskReports();

$taskObj->getTaskAssignmentChart('taskAssignment');

?>
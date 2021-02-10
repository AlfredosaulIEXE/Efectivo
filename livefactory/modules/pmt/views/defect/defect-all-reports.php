<?php 

$this->title =Yii::t ( 'app','Defect All Reports');

?>

<script src="../include/jsapi.js"></script>

<div class="col-sm-12">

	<div class="row">

    	<div class="col-sm-6">

            	<div class="panel panel-info">

                	<div class="panel-heading">

                    	<h3 class="panel-title"><?=Yii::t ( 'app','Defect Status')?></h3>

                    </div>

                    <div class="panel-body">

                    	 <div id="defectStatus"></div>

                    </div>

            </div>

        </div>

        <div class="col-sm-6">

            <div class="panel panel-info">

                <div class="panel-heading">

                    <h3 class="panel-title"><?=Yii::t ( 'app','Defect Assignment')?></h3>

                </div>

                <div class="panel-body">

                      <div id="defectAssignment"></div>

                </div>

            </div>

        </div>

    </div>

</div>



<?php  

use livefactory\models\DefectReports;

DefectReports::myDefectStatusChart('defectStatus');

$defectObj = new DefectReports();

$defectObj->getDefectAssignmentChart('defectAssignment');

?>
<?php 

$this->title =Yii::t('app','User Type Report');

?>

<script src="../include/jsapi.js"></script>

<div class="row">

	<div class="col-sm-12">

      <div class="panel panel-info">

        <div class="panel-heading">

            <strong><?=Yii::t('app','User Types')?></strong>

        </div>

        <div class="panel-body">

            <div id="user-type" style="width:100%;height:500px"></div>

        </div>

    </div>

    </div>

</div>

<?php  

use livefactory\models\UserReports;

$userObj = new UserReports();

$userObj->userTypeChart('user-type');

?>
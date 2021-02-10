<?php 

$this->title =Yii::t('app','All User Reports');

?>

<script src="../include/jsapi.js"></script>

<div class="col-sm-12">

	<div class="row">

    	<div class="col-sm-6">

            	<div class="panel panel-info">

                	<div class="panel-heading">

                    	<h3 class="panel-title"><?=Yii::t('app','User Types')?></h3>

                    </div>

                    <div class="panel-body">

                    	 <div id="user-type"></div>

                    </div>

            </div>

        </div>

        <div class="col-sm-6">

            <div class="panel panel-info">

                <div class="panel-heading">

                    <h3 class="panel-title"><?=Yii::t('app','User Activate / Deactivate')?></h3>

                </div>

                <div class="panel-body">

                      <div id="user-active"></div>

                </div>

            </div>

        </div>

    </div>

    <div class="row">

    	<div class="col-sm-6">

            	<div class="panel panel-info">

                	<div class="panel-heading">

                    	<h3 class="panel-title"><?=Yii::t('app','New User Last 12 Months')?></h3>

                    </div>

                    <div class="panel-body">

                    	  <div id="user-months"></div>

                    </div>

            </div>

        </div>

    </div>

</div>





<?php  

use livefactory\models\UserReports;

$userObj = new UserReports();

$userObj->userTypeChart('user-type');

$userObj->userStatusChart('user-active');

$userObj->newUserChart('user-months');

//$userObj->userLocationChart('user-location');

?>
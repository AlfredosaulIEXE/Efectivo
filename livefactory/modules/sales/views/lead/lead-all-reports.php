<?php

use livefactory\models\LeadReport;

$obj = new LeadReport;

$this->title =Yii::t('app','All Sales Reports');

?>

<div class="col-sm-12">

	<div class="row">

	    <div class="col-sm-6">

            	<div class="panel panel-info">

                	<div class="panel-heading">

                    	<h3 class="panel-title"><?=Yii::t('app','Sales Funnel')?></h3>

                    </div>

                    <div class="panel-body">

                    	 <div id="sales-funnel" style="width:100%;height:487px;"></div>

                    </div>

            </div>

        </div>

		<div class="col-sm-6">

            	<div class="panel panel-info">

                	<div class="panel-heading">

                    	<h3 class="panel-title"><?=Yii::t('app','Lead Status')?></h3>

                    </div>

                    <div class="panel-body">

                    	 <div id="lead-status"></div>

                    </div>

            </div>

        </div>

		  	<div class="col-sm-6">

            	<div class="panel panel-info">

                	<div class="panel-heading">

                    	<h3 class="panel-title"><?=Yii::t('app','Lead Type')?></h3>

                    </div>

                    <div class="panel-body">

                    	 <div id="lead-type"></div>

                    </div>

            </div>

        </div>

		</div>

		<div class="row">

  

        <div class="col-sm-6">

            <div class="panel panel-info">

                <div class="panel-heading">

                    <h3 class="panel-title"><?=Yii::t('app','Lead Country')?></h3>

                </div>

                <div class="panel-body">

                      <div id="lead-country"></div>

                </div>

            </div>

        </div>
		

		  	<div class="col-sm-6">

            	<div class="panel panel-info">

                	<div class="panel-heading">

                    	<h3 class="panel-title"><?=Yii::t('app','New Lead Last 12 Months')?></h3>

                    </div>

                    <div class="panel-body">

                    	  <div id="lead-months"></div>

                    </div>

            </div>

        </div>

    </div>

    <div class="row">

  

        <div class="col-sm-6">

            	<div class="panel panel-info">

                	<div class="panel-heading">

                    	<h3 class="panel-title"><?=Yii::t('app','New Lead Last 12 Months with Type')?></h3>

                    </div>

                    <div class="panel-body">

                    	  <div id="newLeadWithTypeChat"></div>

                    </div>

            </div>

        </div>

    </div>

</div>

 <script src="../include/jsapi.js"></script>

 <?php

 	$obj->newLeadChart('lead-months');

	$obj->leadTypeChart('lead-type');

	$obj->leadStatusChart('lead-status');

	$obj->salesFunnelChart2('sales-funnel');

	$obj->leadCountryChart('lead-country');

	$obj->getNewLeadWithTypeChat('newLeadWithTypeChat')

 ?>
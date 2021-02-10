<?php

use livefactory\models\CustomerReport;

$obj = new CustomerReport;

$this->title =Yii::t('app','Customer All Reports');

?>

<div class="col-sm-12">

	<div class="row">

    	<div class="col-sm-6">

            	<div class="panel panel-info">

                	<div class="panel-heading">

                    	<h3 class="panel-title"><?=Yii::t('app','Customer Type')?></h3>

                    </div>

                    <div class="panel-body">

                    	 <div id="customer-type"></div>

                    </div>

            </div>

        </div>

        <div class="col-sm-6">

            <div class="panel panel-info">

                <div class="panel-heading">

                    <h3 class="panel-title"><?=Yii::t('app','Customer Country')?></h3>

                </div>

                <div class="panel-body">

                      <div id="customer-country"></div>

                </div>

            </div>

        </div>

    </div>

    <div class="row">

    	<div class="col-sm-6">

            	<div class="panel panel-info">

                	<div class="panel-heading">

                    	<h3 class="panel-title"><?=Yii::t('app','New Customer Last 12 Months')?></h3>

                    </div>

                    <div class="panel-body">

                    	  <div id="customer-months"></div>

                    </div>

            </div>

        </div>

        <div class="col-sm-6">

            	<div class="panel panel-info">

                	<div class="panel-heading">

                    	<h3 class="panel-title"><?=Yii::t('app','New Customer Last 12 Months with Type')?></h3>

                    </div>

                    <div class="panel-body">

                    	  <div id="newCustomerWithTypeChat"></div>

                    </div>

            </div>

        </div>

    </div>

</div>

 <script src="../include/jsapi.js"></script>

 <?php

 	$obj->newCustomerChart('customer-months');

	$obj->customerTypeChart('customer-type');

	$obj->customerCountryChart('customer-country');

	$obj->getNewCustomerWithTypeChat('newCustomerWithTypeChat')

 ?>
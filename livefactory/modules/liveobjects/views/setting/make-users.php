<?php

use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\helpers\Html;
use kartik\builder\Form;
use kartik\widgets\ActiveForm;

use kartik\grid\GridView;

use yii\widgets\Pjax;

use yii\helpers\ArrayHelper;


use livefactory\models\Country;
use livefactory\models\State;
use livefactory\models\City;


/**

 *

 * @var yii\web\View $this

 * @var yii\data\ActiveDataProvider $dataProvider

 * @var livefactory\models\search\Address $searchModel

 */



$this->title = Yii::t ( 'app', 'Create User Logins for Customers' );

$this->params ['breadcrumbs'] [] = $this->title;



?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<style>	
.cke_contents{max-height:250px}
.slider .tooltip.top {
    margin-top: -36px;
    z-index: 100;
}
.close {
    color: #000000;
    float: right;
    font-size: 18px;
    font-weight: bold;
    line-height: 1;
    opacity: 0.2;
    text-shadow: 0 1px 0 #ffffff;
}
</style>
<script type="text/javascript">
function Add_Error(obj,msg){
	 $(obj).parents('.form-group').addClass('has-error');
	 $(obj).parents('.form-group').append('<div style="color:#D16E6C; clear:both" class="error"><i class="icon-remove-sign"></i> '+msg+'</div>');
	 return true;
}
function Remove_Error(obj){
	$(obj).parents('.form-group').removeClass('has-error');
	$(obj).parents('.form-group').children('.error').remove();
	return false;
}
function Add_ErrorTag(obj,msg){
	obj.css({'border':'1px solid #D16E6C'});
	
	obj.after('<div style="color:#D16E6C; clear:both" class="error"><i class="icon-remove-sign"></i> '+msg+'</div>');
	 return true;
}
function Remove_ErrorTag(obj){
	obj.removeAttr('style').next('.error').remove();
	return false;
}
$(document).ready(function(e) {
    $('.action').each(function(index, element) {
        var id = $(this).attr('id');
		$(this).load('index.php?r=liveobjects/setting/ajax-make-user&id='+id);
    });
});
</script>
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5><?php echo Yii::t ( 'app', 'Customers' ); ?> </h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        
            <a class="close-link">
                <i class="fa fa-times"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content">
    	
            <?php
			if(count($customers) > 0){?>
            <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th><?php echo Yii::t ( 'app', 'Name' ); ?>Name</th>
                    <th><?php echo Yii::t ( 'app', 'Email' ); ?></th>
                    <th><?php echo Yii::t ( 'app', 'Action' ); ?></th>
                </tr>
            </thead>
            <tbody>
			<?php 	foreach($customers as $customer){
			?>
            <tr>
            	<td><?= $customer['customer_name']?></td>
                <td><?= $customer['email']?></td>
                <td class="action" id="<?= $customer['id']?>">Processing...............</td>
            </tr>
            <?php
				}?>
              </tbody>
        </table>
			<?php }else  echo "<h1>".Yii::t ( 'app', 'All customers have user logins created already' )."</h1>";
			?>
       	
    </div>
</div>
          
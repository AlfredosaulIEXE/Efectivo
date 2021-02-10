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
use livefactory\models\search\CommonModel;
use livefactory\models\TicketPriority;
use livefactory\models\TicketImpact;
use livefactory\models\Department;
use livefactory\models\Queue;
use livefactory\models\TicketCategory;


/**

 *

 * @var yii\web\View $this

 * @var yii\data\ActiveDataProvider $dataProvider

 * @var livefactory\models\search\Address $searchModel

 */



$this->title = Yii::t ( 'app', 'System Settings' );

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
 function loadState(){
$('#state_id').load('<?=isset($baseUrl)?$baseUrl:''?>?r=liveobjects/address/ajax-load-states&country_id='+escape('<?=$addressModel->country_id?>')+'&state_id='+escape('<?=$addressModel->state_id?>'));
		
}
function loadCity(){
			$('#city_id').load('<?=isset($baseUrl)?$baseUrl:''?>?r=liveobjects/address/ajax-load-cities&state_id=<?=$addressModel->state_id?>&city_id=<?=$addressModel->city_id?>')	
}

function loadQueue(){
			$('#queue_id').load('index.php?r=support/ticket/ajax-department-queue&department_id=<?=Yii::$app->params['DEFAULT_TICKET_DEPARTMENT']?>&queue_id=<?=Yii::$app->params['DEFAULT_TICKET_QUEUE']?>')	
}

function loadCategory(){
	$('#ticket_category_id_1').load('index.php?r=support/ticket/ajax-ticket-category&department_id=<?=Yii::$app->params['DEFAULT_TICKET_DEPARTMENT']?>&ticket_category_id_1=<?=Yii::$app->params['DEFAULT_TICKET_CATEGORY']?>');
}
   
$(document).ready(function(){
	
	
	$('#country_id').change(function(){
    $.post('<?= isset($baseUrl)?$baseUrl:'' ?>?r=liveobjects/address/ajax-load-states&country_id='+$(this).val(),function(result){
					$('#state_id').html(result);
					$('#city_id').html('<option value=""> --Select City--</option>');
				})
	})
	$('#state_id').change(function(){
    $.post('<?= isset($baseUrl)?$baseUrl:'' ?>?r=liveobjects/address/ajax-load-cities&state_id='+$(this).val(),function(result){
					$('#city_id').html(result);
				})
	})
	//Auto Load
	loadState();
	loadCity();
	loadCategory();

	$('#department_id').change(function(){
	 $.post('index.php?r=support/ticket/ajax-department-queue&department_id='+$(this).val(),function(r){
		$('#queue_id').html(r) ;
	 });

	 $.post('index.php?r=support/ticket/ajax-ticket-category&department_id='+$(this).val(),function(r){
		$('#ticket_category_id_1').html(r) ;
	 });

	});

	loadQueue();

	$('#w0').submit(function(event){
		var error='';
		$('[data-validation="required"]').each(function(index, element) {
			//alert($(this).attr('id'));
			Remove_Error($(this));
			if($(this).val() == ''){
				error+=Add_Error($(this),'This Field is Required!');
			}else{
					Remove_Error($(this));							
			}
			if(error !=''){
				event.preventDefault();
			}else{
				return true;
			}
		});
	});

	$('.update_button').click(function(event){
		var error='';
		$('[data-validation="required"]').each(function(index, element) {
			//alert($(this).attr('id'));
			Remove_ErrorTag($(this));
			if($(this).val() == ''){
				error+=Add_ErrorTag($(this),'This Field is Required!');
			}else{
					Remove_ErrorTag($(this));							
			}
			if(error !=''){
				event.preventDefault();
			}else{
				return true;
			}
		});
	});

	function readLogoURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			
			reader.onload = function (e) {
				$('.upload_logo').attr('src', e.target.result);
			}
			
			reader.readAsDataURL(input.files[0]);
		}
	}

	function readSealURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			
			reader.onload = function (e) {
				$('.upload_seal').attr('src', e.target.result);
			}
			
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	
	$(".inp_company_logo").change(function(){
		readLogoURL(this);
		ajaxLogoFileUpload(this);
		//$('#w0').submit();
	});
	$('.upload_logo').click(function(){
		$('.inp_company_logo').click();
	})
	function ajaxLogoFileUpload(upload_field)
	{
	// Checking file type
		/*var re_text = /\.jpg|\.gif|\.jpeg/i;
		var filename = upload_field.value;
			if (filename.search(re_text) == -1) {
				alert("File should be either jpg or gif or jpeg");
				upload_field.form.reset();
				return false;
			}*/
		document.getElementById('company_logo_preview').innerHTML = '<div><img src="http://i.hizliresim.com/xAmY7B.gif" width="100%" border="0" /></div>';
		upload_field.form.action = 'index.php?r=liveobjects/setting';
		upload_field.form.target = 'upload_logo_iframe';
		upload_field.form.submit();
		upload_field.form.action = '';
		upload_field.form.target = '';
		setTimeout(function(){
		document.getElementById('company_logo_preview').innerHTML = '';
		},2500)
		return true;
	}


	$(".inp_company_seal").change(function(){
		readSealURL(this);
		ajaxSealFileUpload(this);
		//$('#w0').submit();
	});
	$('.upload_seal').click(function(){
		$('.inp_company_seal').click();
	})
	function ajaxSealFileUpload(upload_field)
	{
	// Checking file type
		/*var re_text = /\.jpg|\.gif|\.jpeg/i;
		var filename = upload_field.value;
			if (filename.search(re_text) == -1) {
				alert("File should be either jpg or gif or jpeg");
				upload_field.form.reset();
				return false;
			}*/
		document.getElementById('company_seal_preview').innerHTML = '<div><img src="http://i.hizliresim.com/xAmY7B.gif" width="100%" border="0" /></div>';
		upload_field.form.action = 'index.php?r=liveobjects/setting';
		upload_field.form.target = 'upload_seal_iframe';
		upload_field.form.submit();
		upload_field.form.action = '';
		upload_field.form.target = '';
		setTimeout(function(){
		document.getElementById('company_seal_preview').innerHTML = '';
		},2500)
		return true;
	}

});
</script>
<script type="text/javascript">
   
$(document).ready(function(){
	CKEDITOR.config.readOnly=true;
	$('.COLLAPSE_MENU,.FIXED_SIDEBAR,.TOP_NAVBAR,.BOXED_LAYOUT,FIXED_FOOTER').click(function(){
		if($(this).attr('class')=='COLLAPSE_MENU'){
			if($(this).val() =='1'){
				$('body').addClass('mini-navbar');
			}else{
				$('body').removeClass('mini-navbar');
			}
		}
		if($(this).attr('class')=='FIXED_SIDEBAR'){
			if($(this).val() =='1'){
				$('body').addClass('fixed-sidebar');
				$('.sidemenu').css({'position': 'relative', 'overflow': 'hidden', 'width': 'auto', 'height': '100%'});
				$('.sidemenu').addClass('slimScrollDiv');
			}else{
				$('body').removeClass('fixed-sidebar');
				$('.sidemenu').removeAttr('style');
				$('.sidemenu').removeClass('slimScrollDiv');
				$('.sidemenu').addClass('sidebar-collapse');
			}
		}
		if($(this).attr('class')=='BOXED_LAYOUT'){
			if($(this).val() =='1'){
				$('body').addClass('boxed-layout');
				$('.navbar.white-bg').addClass('navbar-static-top');
				$('.navbar.white-bg').removeClass('navbar-fixed-top');
				$('body').removeClass('fixed-nav');
			}else{
				$('body').removeClass('boxed-layout');
			}
		}
		if($(this).attr('class')=='TOP_NAVBAR'){
			if($(this).val() =='1'){				
				$('body').addClass('fixed-nav');
				$('body').removeClass('boxed-layout');
				$('.navbar.white-bg').addClass('navbar-fixed-top');
			}else{
				$('body').removeClass('fixed-nav');
				$('.navbar.white-bg').addClass('navbar-static-top');
				$('.navbar.white-bg').removeClass('navbar-fixed-top');
			}
		} 
		if($(this).attr('class')=='FIXED_FOOTER'){
			if($(this).val() =='1'){
				$('.footer').addClass('fixed');
			}else{
				$('.footer').removeClass('fixed');
			}
		}
	})
	$('.theme_color').change(function(){
		if($(this).val()=='11'){
			$('body').addClass('skin-1');
		}else if($(this).val()=='12'){
			$('body').removeClass('skin-1');
			$('body').addClass('skin-3');
		}else{
			$('body').removeClass('skin-1');
			$('body').removeClass('skin-3');
		}
	})
})
function reloadPage(){
	document.location.href='index.php?r=liveobjects/setting&reload=true'	
}

	if('<?=!empty($reload)?$reload:''?>'=='yes' && '<?=$_REQUEST['reload']?>' != 'true'){
		reloadPage()
	}
	
	$(function () {
	  $('.form-control').hover(function(){
		$(this).next('.tooltip_box').toggle(500);  
	  })
		if('<?=isset($sent_email)?$sent_email:''?>' !=''){
			setTimeout(function(){
				document.location.href='index.php?r=liveobjects/setting/index';
			},1500);
		}
	 
	})
</script>

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
<style>	

.cke_contents{max-height:250px}

</style>
<iframe name="upload_logo_iframe" id="upload_logo_iframe" style="display:none;"></iframe>
<iframe name="upload_seal_iframe" id="upload_seal_iframe" style="display:none;"></iframe>
<script src="../../vendor/ckeditor/ckeditor/ckeditor.js"></script>
<div class="logo-index">
	<!--
	<div class="page-header">
		<h1><?= Html::encode($this->title) ?></h1>
	</div>
	-->
    <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo Yii::t ( 'app', 'System Settings' ); ?> <small class="m-l-sm"><?php echo Yii::t ( 'app', 'Changes will be at application level' ); ?></small></h5>
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
                    <div class="tabbable">
                        <ul class="nav nav-tabs">
                        <li class="active">
                        	<a href="#general" class="general" role="tab" data-toggle="tab"><?php echo Yii::t ( 'app', 'General Settings' ); ?></a>
                        </li>
                        <li><a href="#smtp" class="smtp" role="tab" data-toggle="tab"><?php echo Yii::t ( 'app', 'SMTP Settings' ); ?></a></li>
                        <li><a href="#theme" class="theme" role="tab" data-toggle="tab"><?php echo Yii::t ( 'app', 'Theme Settings' ); ?></a></li>
                        <li><a href="#email_config" class="email_config" role="tab" data-toggle="tab"><?php echo Yii::t ( 'app', 'Email Settings' ); ?></a></li>
                        <li><a href="#logo" class="logo" role="tab" data-toggle="tab"><?php echo Yii::t ( 'app', 'Logo Settings' ); ?></a></li>
						<?php
						if(in_array('invoice',yii::$app->params['modules']))
						{
						?>
							<li><a href="#payment" class="payment" role="tab" data-toggle="tab"><?php echo Yii::t ( 'app', 'Payment Settings' ); ?></a></li>
						<?php
						}
						?>
                        <li><a href="#company" class="company" role="tab" data-toggle="tab"><?php echo Yii::t ( 'app', 'Company Settings' ); ?></a></li>
                        <!--<li><a href="#license" class="license" role="tab" data-toggle="tab"><?php echo Yii::t ( 'app', 'License' ); ?></a></li>-->
                       <!-- <li><a href="#cron" class="cron" role="tab" data-toggle="tab"><?php echo Yii::t ( 'app', 'Cron Jobs' ); ?></a></li>-->
                        </ul>
                    
                    <div class="tab-content">
                        <div class="tab-pane active" id="general"> 
                             <br/>
                             
                             <div class="row">
                             <div class="col-sm-12">
                             <form method="post" class="form-horizontal" action="index.php?r=liveobjects/setting/update" enctype="multipart/form-data">
                                <?php Yii::$app->request->enableCsrfValidation = true; ?>
                                <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                                	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                              <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingOne">
                                                  <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                    <?php echo Yii::t ( 'app', 'General' ); ?> 
                                                    </a>
                                                  </h4>
                                                </div>
                                                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                                  <div class="panel-body">
                                                  <div class="form-group">
                                                                                    <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['APPLICATION_NAME'.'_description']) ?>">
                                                                                        <label><?php echo Yii::t ( 'app', 'Application Name' ); ?></label>
                                                                                        <input type="text" class="form-control  tooltip_btn" required name="application_name" value="<?=Yii::$app->params['APPLICATION_NAME'] ?>">

                                                                                    </div>
                                                                                    <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['LOCALE'.'_description']) ?>">
                                                                                    <label><?php echo Yii::t ( 'app', 'System Language' ); ?></label>
                                                                                    <select class="form-control   tooltip_btn" name="LOCALE">
                                                                                        <?php
                                                                                            foreach($languages as $lang){
                                                                                        ?>
                                                                                        <option value="<?php echo $lang['locale']?>" <?=$_SESSION['LOCALE'] !=$lang['locale']?'':'selected' ?>><?php echo $lang['language']; ?></option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                    </div>
                                                                                    <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['APPLICATION_VERSION'.'_description']) ?>">
                                                                                    <label><?php echo Yii::t ( 'app', 'Application Version' ); ?></label>
                                                                                    <input type="text" name="APPLICATION_VERSION" class="form-control" readonly value="<?=Yii::$app->params['APPLICATION_VERSION'] ?>">
                                                                                    
                                                                                    </div>
<div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['TIME_ZONE'.'_description']) ?>">
                                                                                    <label><?php echo Yii::t ( 'app', 'Time Zone' ); ?></label>
																						<select class="form-control   tooltip_btn" name="TIME_ZONE">
																							<?php
																								foreach(CommonModel::getTimezoneList() as $key=>$value){
																							?>
																							<option value="<?php echo $key?>" <?=Yii::$app->params['TIME_ZONE'] !=$key?'':'selected' ?>><?php echo $value; ?></option>
																							<?php } ?>
																						</select>                                                                                    
                                                                                    </div>                                                                                    
                                                                                    
                                                                                </div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingTwo">
                                                  <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                     <?php echo Yii::t ( 'app', 'Display' ); ?>  
                                                    </a>
                                                  </h4>
                                                </div>
                                                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                                  <div class="panel-body">
                                                   <div class="form-group">
                                                                                     
                                                                                    <div class="col-sm-4" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['DEFAULT_SEARCH_PAGE_VIEW'.'_description'] )?>">
                                                                                <label><?php echo Yii::t ( 'app', 'Search Page View' ); ?></label>
                                                                                    <select class="form-control" name="DEFAULT_SEARCH_PAGE_VIEW">
                                                                                        <option  <?=Yii::$app->params['DEFAULT_SEARCH_PAGE_VIEW'] =='List View'?'selected':'' ?>>List View</option>
                                                                                        <option  <?=Yii::$app->params['DEFAULT_SEARCH_PAGE_VIEW'] =='Tile View'?'selected':'' ?>>Tile View</option>
                                                                                    </select>
                                                                                    </div>
                                                                                    <div class="col-sm-4" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['USER_IMAGE'.'_description']) ?>">
                                                                                     <label><?php echo Yii::t ( 'app', 'Show User Images' ); ?></label>
                                                                                    <select class="form-control" name="USER_IMAGE">
                                                                                        <option value="No" <?=Yii::$app->params['USER_IMAGE'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                                                                                        <option value="Yes" <?=Yii::$app->params['USER_IMAGE'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
                                                                                    </select>
                                                                                    
                                                                                    </div>
                                                                                    <div class="col-sm-4" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['RTL_THEME'.'_description'] )?>">
                                                                                     <label><?php echo Yii::t ( 'app', 'RTL Active' ); ?></label>
                                                                                    <select class="form-control" name="RTL_THEME">
                                                                                        <option value="No" <?=Yii::$app->params['RTL_THEME'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                                                                                        <option  <?=Yii::$app->params['RTL_THEME'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
                                                                                    </select>
                                                                                    
                                                                                    </div>
                                                                                    
                                                                               </div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="Communication1">
                                                  <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#Communication" aria-expanded="false" aria-controls="Communication">
                                                   <?php echo Yii::t ( 'app', 'Communication' ); ?>    
                                                    </a>
                                                  </h4>
                                                </div>
                                                <div id="Communication" class="panel-collapse collapse" role="tabpanel" aria-labelledby="Communication1">
                                                  <div class="panel-body">
                                                   <div class="form-group">
                                                                                    <div class="col-sm-6" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SYSTEM_EMAIL'.'_description'] )?>">
                                                                                    <label><?php echo Yii::t ( 'app', 'System Email' ); ?></label>
                                                                                    <input type="text" class="form-control" required name="system_email" value="<?=Yii::$app->params['SYSTEM_EMAIL'] ?>"></div>
                                                                                    <div class="col-sm-6" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['CHAT'.'_description']) ?>">
                                                                                <label><?php echo Yii::t ( 'app', 'System Chat' ); ?></label>
                                                                                    <select class="form-control" name="chat">
                                                                                        <option value="0" <?=Yii::$app->params['CHAT']!='1'?'':'selected' ?>><?php echo Yii::t ( 'app', 'No' ); ?></option>
                                                                                        <option value="1" <?=Yii::$app->params['CHAT']=='1'?'selected':'' ?>><?php echo Yii::t ( 'app', 'Yes' ); ?></option>
                                                                                    </select>
                                                                                    </div>
                                                                                    
                                                                               </div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="Credential">
                                                  <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#Credentialtab" aria-expanded="false" aria-controls="Credentialtab">
                                                    <?php echo Yii::t ( 'app', 'Credential/Security' ); ?>      
                                                    </a>
                                                  </h4>
                                                </div>
                                                <div id="Credentialtab" class="panel-collapse collapse" role="tabpanel" aria-labelledby="Credential">
                                                  <div class="panel-body">
                                                   <div class="form-group">
                                                                                    <div class="col-sm-4" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['AUTO_PASSWORD'.'_description']) ?>">
                                                                                     <label><?php echo Yii::t ( 'app', 'Auto Password' ); ?></label>
                                                                                    <select class="form-control" name="AUTO_PASSWORD">
                                                                                        <option value="No" <?=Yii::$app->params['AUTO_PASSWORD'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                                                                                        <option  <?=Yii::$app->params['AUTO_PASSWORD'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
                                                                                    </select>
                                                                                    
                                                                                    </div>
                                                                                    <div class="col-sm-4" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['ALLOW_CUSTOMER_LOGIN_TO_BACKEND'.'_description']) ?>">
                                                                                     <label><?php echo Yii::t ( 'app', 'Allow Customer Login to Backend' ); ?>
                                                                                     
                                                                                     </label>
                                                                                    <select class="form-control" name="ALLOW_CUSTOMER_LOGIN_TO_BACKEND">
                                                                                        <option value="No" <?=Yii::$app->params['ALLOW_CUSTOMER_LOGIN_TO_BACKEND'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                                                                                        <option  <?=Yii::$app->params['ALLOW_CUSTOMER_LOGIN_TO_BACKEND'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
                                                                                    </select>
                                                                                    
                                                                                    </div>
                                                                                    <div class="col-sm-4" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['ALLOW_MULTIPLE_USER_ACCOUNTS_FOR_CUSTOMER'.'_description']) ?>">
                                                                                     <label><?php echo Yii::t ( 'app', 'Allow Multiple User Accounts For Customer' ); ?>
                                                                                     
                                                                                     </label>
                                                                                    <select class="form-control" name="ALLOW_MULTIPLE_USER_ACCOUNTS_FOR_CUSTOMER">
                                                                                        <option value="No" <?=Yii::$app->params['ALLOW_MULTIPLE_USER_ACCOUNTS_FOR_CUSTOMER'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                                                                                        <option  <?=Yii::$app->params['ALLOW_MULTIPLE_USER_ACCOUNTS_FOR_CUSTOMER'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
                                                                                    </select>
                                                                                    
                                                                                    </div>
                                                                                  
<div class="col-sm-12"><br/><br/>
<h3 style="color:#1ab394"> <?php echo Yii::t ( 'app', 'Customer Create/Delete accounts' ); ?></h3>
<div><?php
							if(Yii::$app->params['ALLOW_CUSTOMER_LOGIN_TO_BACKEND'] =='Yes'){
								?>
								<a href="index.php?r=liveobjects/setting/make-users" onClick="return confirm('<?php echo Yii::t ( 'app', 'Are you Sure!' ); ?>')" class="btn btn-primary"><?php echo Yii::t ( 'app', 'Create Customer Accounts' ); ?></a>
                                
                                <a href="index.php?r=liveobjects/setting/delete-users" onClick="return confirm('<?php echo Yii::t ( 'app', 'Are you Sure!' ); ?>')" class="btn btn-danger"><?php echo Yii::t ( 'app', 'Delete Customer Accounts' ); ?></a>
							<?php
							} ?> &nbsp; &nbsp; </div>
</div>
                                                                                    
                                                                               </div>
                                                  </div>
                                                </div>
                                              </div>
<!-- show/hide only for pmt n support -->  <?php if(in_array('pmt',yii::$app->params['modules']) || in_array('support',yii::$app->params['modules']) ){ ?>
                                              <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="Show_hide">
                                                  <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#Show_hide1" aria-expanded="false" aria-controls="Show_hide1">
                                                  <?php echo Yii::t ( 'app', 'Show/Hide' ); ?>          
                                                    </a>
                                                  </h4>
                                                </div>
                                                <div id="Show_hide1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="Show_hide">
                                                  <div class="panel-body">
                                                   <div class="form-group">
                                                    <div class="col-sm-12">
                                                                                        <h3 style="color:#1ab394"> <?php echo Yii::t ( 'app', 'Show Attachment Page' ); ?></h3>
                                                                                        <div class="row">
																						<?php if(in_array('pmt',yii::$app->params['modules'])){ ?>
                                                                                            <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_ADD_ATTACHMENT_PAGE_NEW_PROJECT'.'_description']) ?>">
                                                                                    <label><?php echo Yii::t ( 'app', 'In Create Project' ); ?></label>
                                                                                        <select class="form-control" name="SHOW_ADD_ATTACHMENT_PAGE_NEW_PROJECT">
                                                                                            <option value="No" <?=Yii::$app->params['SHOW_ADD_ATTACHMENT_PAGE_NEW_PROJECT'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                                                                                            <option  <?=Yii::$app->params['SHOW_ADD_ATTACHMENT_PAGE_NEW_PROJECT'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
                                                                                        </select>
                                                                                        
                                                                                        </div>
                                                                                            <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_ADD_ATTACHMENT_PAGE_NEW_TASK'.'_description']) ?>">
                                                                                        <label><?php echo Yii::t ( 'app', 'In Create Task' ); ?></label>
                                                                                        <select class="form-control" name="SHOW_ADD_ATTACHMENT_PAGE_NEW_TASK">
                                                                                            <option value="No" <?=Yii::$app->params['SHOW_ADD_ATTACHMENT_PAGE_NEW_TASK'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                                                                                            <option  <?=Yii::$app->params['SHOW_ADD_ATTACHMENT_PAGE_NEW_TASK'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
                                                                                        </select>
                                                                                        
                                                                                        </div>
                                                                                            <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_ADD_ATTACHMENT_PAGE_NEW_DEFECT'.'_description']) ?>">
                                                                                      <label><?php echo Yii::t ( 'app', 'In Create Defect' ); ?></label>
                                                                                        <select class="form-control" name="SHOW_ADD_ATTACHMENT_PAGE_NEW_DEFECT">
                                                                                            <option value="No" <?=Yii::$app->params['SHOW_ADD_ATTACHMENT_PAGE_NEW_DEFECT'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                                                                                            <option  <?=Yii::$app->params['SHOW_ADD_ATTACHMENT_PAGE_NEW_DEFECT'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
                                                                                        </select>
                                                                                        
                                                                                        </div>
																						<?php } 
																						 if(in_array('support',yii::$app->params['modules'])){ ?>
                                                                                        <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_ADD_ATTACHMENT_PAGE_NEW_TICKET'.'_description']) ?>">
                                                                                      <label><?php echo Yii::t ( 'app', 'In Create Ticket' ); ?></label>
                                                                                        <select class="form-control" name="SHOW_ADD_ATTACHMENT_PAGE_NEW_TICKET">
                                                                                            <option value="No" <?=Yii::$app->params['SHOW_ADD_ATTACHMENT_PAGE_NEW_TICKET'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                                                                                            <option  <?=Yii::$app->params['SHOW_ADD_ATTACHMENT_PAGE_NEW_TICKET'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
                                                                                        </select>
                                                                                        
                                                                                        </div>
																						 <?php } ?>
                                                                                        </div><hr/>
 <h3 style="color:#1ab394"><?php echo Yii::t ( 'app', 'Hide Completed Items By Default' ); ?></h3>
 <div class="row">
 <?php if(in_array('pmt',yii::$app->params['modules'])){ ?>
 <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['HIDE_COMPLETED_PROJECTS_BY_DEFAULT'.'_description'] )?>">
                                                                                    <label><?php echo Yii::t ( 'app', 'Completed Projects' ); ?></label>
                                                                                        <select class="form-control" name="HIDE_COMPLETED_PROJECTS_BY_DEFAULT">
                                                                                            <option value="No" <?=Yii::$app->params['HIDE_COMPLETED_PROJECTS_BY_DEFAULT'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                                                                                            <option  <?=Yii::$app->params['HIDE_COMPLETED_PROJECTS_BY_DEFAULT'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
                                                                                        </select>
                                                                                        
                                                                                        </div>
                                                                                    <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['HIDE_COMPLETED_TASKS_BY_DEFAULT'.'_description']) ?>">
                                                                                        <label><?php echo Yii::t ( 'app', 'Completed Tasks' ); ?></label>
                                                                                        <select class="form-control" name="HIDE_COMPLETED_TASKS_BY_DEFAULT">
                                                                                            <option value="No" <?=Yii::$app->params['HIDE_COMPLETED_TASKS_BY_DEFAULT'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                                                                                            <option  <?=Yii::$app->params['HIDE_COMPLETED_TASKS_BY_DEFAULT'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
                                                                                        </select>
                                                                                        
                                                                                        </div>
                                                                                    <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['HIDE_COMPLETED_DEFECTS_BY_DEFAULT'.'_description']) ?>">
                                                                                     <label><?php echo Yii::t ( 'app', 'Completed Defects' ); ?></label>
                                                                                    <select class="form-control" name="HIDE_COMPLETED_DEFECTS_BY_DEFAULT">
                                                                                        <option value="No" <?=Yii::$app->params['HIDE_COMPLETED_DEFECTS_BY_DEFAULT'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                                                                                        <option  <?=Yii::$app->params['HIDE_COMPLETED_DEFECTS_BY_DEFAULT'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
                                                                                    </select>
                                                                                    
                                                                                    </div>
																					<?php }
																					 if(in_array('support',yii::$app->params['modules'])){ ?>
																					
                                                                                    <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['HIDE_COMPLETED_TICKET_BY_DEFAULT'.'_description']) ?>">
                                                                                     <label><?php echo Yii::t ( 'app', 'Completed Ticket' ); ?></label>
                                                                                    <select class="form-control" name="HIDE_COMPLETED_TICKET_BY_DEFAULT">
                                                                                        <option value="No" <?=Yii::$app->params['HIDE_COMPLETED_TICKET_BY_DEFAULT'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                                                                                        <option  <?=Yii::$app->params['HIDE_COMPLETED_TICKET_BY_DEFAULT'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
                                                                                    </select>
                                                                                    
                                                                                    </div>
																					 <?php } ?>
																						</div>   <hr/>                                                                                    
                                                                                        <div class="row">
																						<?php if(in_array('support',yii::$app->params['modules'])  ||  in_array('pmt',yii::$app->params['modules']) ){ ?>
                                                                                    
                                                                                            <div class="col-sm-3" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_ADD_NOTES_POPUP_ON_COMPLETION'.'_description']) ?>">
																					<h3 style="color:#1ab394"><?php echo Yii::t ( 'app', 'Show Notes Popup' ); ?></h3>
                                                                                    <label><?php echo Yii::t ( 'app', 'Notes Popup on Completion' ); ?></label>
                                                                                        <select class="form-control" name="SHOW_ADD_NOTES_POPUP_ON_COMPLETION">
                                                                                            <option value="No" <?=Yii::$app->params['SHOW_ADD_NOTES_POPUP_ON_COMPLETION'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                                                                                            <option value="Yes" <?=Yii::$app->params['SHOW_ADD_NOTES_POPUP_ON_COMPLETION'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
                                                                                        </select>
                                                                                        
                                                                                        </div>
																						<?php } ?>
   <!-- <div class="col-sm-3" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['PROJECT_FILE_MANAGER'.'_description']) ?>">
<h3 style="color:#1ab394"><?php echo Yii::t ( 'app', 'Project File Manager' ); ?></h3>
                                                                                    <label><?php echo Yii::t ( 'app', 'Project File Manager Show' ); ?></label>
                                                                                        <select class="form-control" name="PROJECT_FILE_MANAGER">
                                                                                            <option value="No" <?=Yii::$app->params['PROJECT_FILE_MANAGER'] =='No'?'selected':'' ?>>No</option>
                                                                                            <option value="Yes" <?=Yii::$app->params['PROJECT_FILE_MANAGER'] =='Yes'?'selected':'' ?>>Yes</option>
                                                                                        </select>
                                                                                        
                                                                                        </div>
                                                                                        <div class="col-sm-3" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['AUTO_PROJECT_ID'.'_description']) ?>">
<h3 style="color:#1ab394"><?php echo Yii::t ( 'app', 'Auto Generated Project Id' ); ?></h3>
                                                                                    <label><?php echo Yii::t ( 'app', 'Auto Generated Project Id' ); ?></label>
                                                                                        <select class="form-control" name="AUTO_PROJECT_ID">
                                                                                            <option value="No" <?=Yii::$app->params['AUTO_PROJECT_ID'] =='No'?'selected':'' ?>>No</option>
                                                                                            <option value="Yes" <?=Yii::$app->params['AUTO_PROJECT_ID'] =='Yes'?'selected':'' ?>>Yes</option>
                                                                                        </select>
                                                                                        
                                                                                        </div>   -->                                                                              
                                                                                        </div>
                                                                                
                                                                                    
                                                                             </div>  </div>
                                                  </div>
                                                </div>
                                              </div>
											   <?php } ?>  <!-- complete show/hide only on pmt and support module  -->
											   
											   <!-- customise dashboard -->
												<div class="panel panel-default">
  <div class="panel-heading" role="tab" id="customize_dashboard">
    <h4 class="panel-title">
      <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#customize_dashboard1" aria-expanded="false" aria-controls="customize_dashboard1">
      <?php echo Yii::t ( 'app', 'Customize Dashboard' ); ?>          
      </a>
    </h4>
  </div>
  <div id="customize_dashboard1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="customize_dashboard">
    <div class="panel-body">
      <div class="form-group">
        <div class="col-sm-12">
		 <div class="row">
            <?php if(in_array('customer',yii::$app->params['modules']) ){ ?>
            <div class="col-sm-3" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_CUSTOMERS_WORLDWIDE'.'_description']) ?>">
              <h3 style="color:#1ab394"><?php echo Yii::t ( 'app', 'Show Customers Worldwide' ); ?></h3>
              <label><?php echo Yii::t ( 'app', 'Show Customers Worldwide' ); ?></label>
              <select class="form-control" name="SHOW_CUSTOMERS_WORLDWIDE">
                <option value="No" <?=Yii::$app->params['SHOW_CUSTOMERS_WORLDWIDE'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                <option value="Yes" <?=Yii::$app->params['SHOW_CUSTOMERS_WORLDWIDE'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
              </select>
            </div>
			<div class="col-sm-3" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_CUSTOMERS_TYPE'.'_description']) ?>">
              <h3 style="color:#1ab394"><?php echo Yii::t ( 'app', 'Show Customers Type' ); ?></h3>
              <label><?php echo Yii::t ( 'app', 'Show Customers Type' ); ?></label>
              <select class="form-control" name="SHOW_CUSTOMERS_TYPE">
                <option value="No" <?=Yii::$app->params['SHOW_CUSTOMERS_TYPE'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                <option value="Yes" <?=Yii::$app->params['SHOW_CUSTOMERS_TYPE'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
              </select>
            </div>
			<div class="col-sm-3" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_NEW_CUSTOMERS'.'_description']) ?>">
              <h3 style="color:#1ab394"><?php echo Yii::t ( 'app', 'Show New Customers' ); ?></h3>
              <label><?php echo Yii::t ( 'app', 'Show New Customers' ); ?></label>
              <select class="form-control" name="SHOW_NEW_CUSTOMERS">
                <option value="No" <?=Yii::$app->params['SHOW_NEW_CUSTOMERS'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                <option value="Yes" <?=Yii::$app->params['SHOW_NEW_CUSTOMERS'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
              </select>
            </div>
            <?php } ?>
                   <?php if(in_array('user',yii::$app->params['modules']) ){ ?>
            <div class="col-sm-3" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_QUICK_LINKS_ON_DASHBOARD'.'_description']) ?>">
              <h3 style="color:#1ab394"><?php echo Yii::t ( 'app', 'Show Quick Links' ); ?></h3>
              <label><?php echo Yii::t ( 'app', 'Show Quick Links On Dashboard' ); ?></label>
              <select class="form-control" name="SHOW_QUICK_LINKS_ON_DASHBOARD">
                <option value="No" <?=Yii::$app->params['SHOW_QUICK_LINKS_ON_DASHBOARD'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                <option  value="Yes" <?=Yii::$app->params['SHOW_QUICK_LINKS_ON_DASHBOARD'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
              </select>
            </div>
            <?php } ?>                                                                       
          </div>
		   <?php if(in_array('sales',yii::$app->params['modules'])){ ?>
		  <hr>
          <h3 style="color:#1ab394"> <?php echo Yii::t ( 'app', 'LiveSales' ); ?></h3>
          <div class="row">
           
            <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_SALES_FUNNEL_ON_DASHBOARD'.'_description']) ?>">
              <label><?php echo Yii::t ( 'app', 'Show Sales Funnel On Dashboard' ); ?></label>
              <select class="form-control" name="SHOW_SALES_FUNNEL_ON_DASHBOARD">
                <option value="No" <?=Yii::$app->params['SHOW_SALES_FUNNEL_ON_DASHBOARD'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                <option value="Yes" <?=Yii::$app->params['SHOW_SALES_FUNNEL_ON_DASHBOARD'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
              </select>
            </div>
            <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_NEW_LEAD_WITH_TYPE_ON_DASHBOARD'.'_description']) ?>">
              <label><?php echo Yii::t ( 'app', 'Show New Sales Leads With Type' ); ?></label>
              <select class="form-control" name="SHOW_NEW_LEAD_WITH_TYPE_ON_DASHBOARD">
                <option value="No" <?=Yii::$app->params['SHOW_NEW_LEAD_WITH_TYPE_ON_DASHBOARD'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                <option value="Yes" <?=Yii::$app->params['SHOW_NEW_LEAD_WITH_TYPE_ON_DASHBOARD'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
              </select>
            </div>
			<div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_LEADS_WORLDWIDE_ON_DASHBOARD'.'_description']) ?>">
              <label><?php echo Yii::t ( 'app', 'Show Sales Leads Worldwide' ); ?></label>
              <select class="form-control" name="SHOW_LEADS_WORLDWIDE_ON_DASHBOARD">
                <option value="No" <?=Yii::$app->params['SHOW_LEADS_WORLDWIDE_ON_DASHBOARD'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                <option  <?=Yii::$app->params['SHOW_LEADS_WORLDWIDE_ON_DASHBOARD'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
              </select>
            </div>
			<div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_LEAD_STATISTICS_ON_DASHBOARD'.'_description']) ?>">
              <label><?php echo Yii::t ( 'app', 'Show Lead Statistics On Dashboard' ); ?></label>
              <select class="form-control" name="SHOW_LEAD_STATISTICS_ON_DASHBOARD">
                <option value="No" <?=Yii::$app->params['SHOW_LEAD_STATISTICS_ON_DASHBOARD'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                <option value="Yes" <?=Yii::$app->params['SHOW_LEAD_STATISTICS_ON_DASHBOARD'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
              </select>
            </div>
            
          </div>
          <hr/><?php }  ?>
		   <?php if(in_array('pmt',yii::$app->params['modules'])){ ?>
          <h3 style="color:#1ab394"><?php echo Yii::t ( 'app', 'LiveProjects' ); ?></h3>
          <div class="row">
           
            <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_TASK_STATISTICS_ON_DASHBOARD'.'_description'] )?>">
              <label><?php echo Yii::t ( 'app', 'Show Task Statistics On Dashboard' ); ?></label>
              <select class="form-control" name="SHOW_TASK_STATISTICS_ON_DASHBOARD">
                <option value="No" <?=Yii::$app->params['SHOW_TASK_STATISTICS_ON_DASHBOARD'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                <option value="Yes" <?=Yii::$app->params['SHOW_TASK_STATISTICS_ON_DASHBOARD'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
              </select>
            </div>
            <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_TASKS_OPEN_CLOSED_ON_DASHBOARD'.'_description']) ?>">
              <label><?php echo Yii::t ( 'app', 'Show Tasks Open/Closed' ); ?></label>
              <select class="form-control" name="SHOW_TASKS_OPEN_CLOSED_ON_DASHBOARD">
                <option value="No" <?=Yii::$app->params['SHOW_TASKS_OPEN_CLOSED_ON_DASHBOARD'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                <option value="Yes" <?=Yii::$app->params['SHOW_TASKS_OPEN_CLOSED_ON_DASHBOARD'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
              </select>
            </div>
            <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_PROJECT_TIMELINE'.'_description']) ?>">
              <label><?php echo Yii::t ( 'app', 'Show Project Timeline' ); ?></label>
              <select class="form-control" name="SHOW_PROJECT_TIMELINE">
                <option value="No" <?=Yii::$app->params['SHOW_PROJECT_TIMELINE'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                <option value="Yes" <?=Yii::$app->params['SHOW_PROJECT_TIMELINE'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
              </select>
            </div>
            
            <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_TODO_LIST'.'_description']) ?>">
              <label><?php echo Yii::t ( 'app', 'Show Todo List' ); ?></label>
              <select class="form-control" name="SHOW_TODO_LIST">
                <option value="No" <?=Yii::$app->params['SHOW_TODO_LIST'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                <option value="Yes" <?=Yii::$app->params['SHOW_TODO_LIST'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
              </select>
            </div>
			 <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_TASK_STATUS_ON_DASHBOARD'.'_description'] )?>">
              <label><?php echo Yii::t ( 'app', 'Show Task Status' ); ?></label>
              <select class="form-control" name="SHOW_TASK_STATUS_ON_DASHBOARD">
                <option value="No" <?=Yii::$app->params['SHOW_TASK_STATUS_ON_DASHBOARD'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                <option value="Yes" <?=Yii::$app->params['SHOW_TASK_STATUS_ON_DASHBOARD'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
              </select>
            </div>
            <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_TASKS_ASSIGNMENT_ON_DASHBOARD'.'_description']) ?>">
              <label><?php echo Yii::t ( 'app', 'Show Task Assignment' ); ?></label>
              <select class="form-control" name="SHOW_TASKS_ASSIGNMENT_ON_DASHBOARD">
                <option value="No" <?=Yii::$app->params['SHOW_TASKS_ASSIGNMENT_ON_DASHBOARD'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                <option value="Yes" <?=Yii::$app->params['SHOW_TASKS_ASSIGNMENT_ON_DASHBOARD'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
              </select>
            </div>
            <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_DEFECT_STATUS_ON_DASHBOARD'.'_description']) ?>">
              <label><?php echo Yii::t ( 'app', 'Show Defect Status' ); ?></label>
              <select class="form-control" name="SHOW_DEFECT_STATUS_ON_DASHBOARD">
                <option value="No" <?=Yii::$app->params['SHOW_DEFECT_STATUS_ON_DASHBOARD'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                <option value="Yes" <?=Yii::$app->params['SHOW_DEFECT_STATUS_ON_DASHBOARD'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
              </select>
            </div>
            
            <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_DEFECT_ASSIGNMENT_ON_DASHBOARD'.'_description']) ?>">
              <label><?php echo Yii::t ( 'app', 'Show Defect Assignment' ); ?></label>
              <select class="form-control" name="SHOW_DEFECT_ASSIGNMENT_ON_DASHBOARD">
                <option value="No" <?=Yii::$app->params['SHOW_DEFECT_ASSIGNMENT_ON_DASHBOARD'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                <option value="Yes" <?=Yii::$app->params['SHOW_DEFECT_ASSIGNMENT_ON_DASHBOARD'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
              </select>
            </div>
        
          </div>
		  <hr/>    <?php } ?>
		  <?php if(in_array('support',yii::$app->params['modules'])){ ?>
          <h3 style="color:#1ab394"><?php echo Yii::t ( 'app', 'LiveSupport' ); ?></h3>
          <div class="row">
            
            <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_TICKET_STATISTICS_ON_DASHBOARD'.'_description'] )?>">
              <label><?php echo Yii::t ( 'app', 'Show Ticket Statistics' ); ?></label>
              <select class="form-control" name="SHOW_TICKET_STATISTICS_ON_DASHBOARD">
                <option value="No" <?=Yii::$app->params['SHOW_TICKET_STATISTICS_ON_DASHBOARD'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                <option value="Yes" <?=Yii::$app->params['SHOW_TICKET_STATISTICS_ON_DASHBOARD'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
              </select>
            </div>
            <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_TICKET_OPEN_CLOSED_ON_DASHBOARD'.'_description']) ?>">
              <label><?php echo Yii::t ( 'app', 'Show Tickets Open/Closed' ); ?></label>
              <select class="form-control" name="SHOW_TICKET_OPEN_CLOSED_ON_DASHBOARD">
                <option value="No" <?=Yii::$app->params['SHOW_TICKET_OPEN_CLOSED_ON_DASHBOARD'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                <option  value="Yes" <?=Yii::$app->params['SHOW_TICKET_OPEN_CLOSED_ON_DASHBOARD'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
              </select>
            </div>
          </div>
          <hr/><?php } ?>
		  
		   <?php if(in_array('invoice',yii::$app->params['modules'])){ ?>
          <h3 style="color:#1ab394"><?php echo Yii::t ( 'app', 'LiveInvoices' ); ?></h3>
          <div class="row">
           <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_INVOICE_STATISTICS_ON_DASHBOARD'.'_description'] )?>">
              <label><?php echo Yii::t ( 'app', 'Show Invoice Statistics' ); ?></label>
              <select class="form-control" name="SHOW_INVOICE_STATISTICS_ON_DASHBOARD">
                <option value="No" <?=Yii::$app->params['SHOW_INVOICE_STATISTICS_ON_DASHBOARD'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                <option value="Yes" <?=Yii::$app->params['SHOW_INVOICE_STATISTICS_ON_DASHBOARD'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
              </select>
            </div>
            <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_INVOICES_CREATED_PAID_ON_DASHBOARD'.'_description']) ?>">
              <label><?php echo Yii::t ( 'app', 'Show Invoices Created/Paid' ); ?></label>
              <select class="form-control" name="SHOW_INVOICES_CREATED_PAID_ON_DASHBOARD">
                <option value="No" <?=Yii::$app->params['SHOW_INVOICES_CREATED_PAID_ON_DASHBOARD'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                <option  value="Yes" <?=Yii::$app->params['SHOW_INVOICES_CREATED_PAID_ON_DASHBOARD'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
              </select>
            </div>
           
            
          </div><?php } ?>
         
        </div>
      </div>
    </div>
  </div>
</div>

											 <!-- customise dashboard ends -->  
											   
											<!--Made changes-->
											<?php if(in_array('invoice',yii::$app->params['modules'])){ ?>
											  	<div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="Invoice_alert_mail">
                                                  <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#Invoice_alert_mail1" aria-expanded="false" aria-controls="Misc1">
                                                     <?php echo Yii::t ( 'app', 'Invoice Settings' ); ?> 
                                                    </a>
                                                  </h4>
                                                </div>
                                                <div id="Invoice_alert_mail1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="Invoice_alert_mail">
                                                  <div class="panel-body">
                                                   <div class="form-group">
                                                       

														

														<div class="col-sm-3" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['COMPANY_LOGO_ON_INVOICE'.'_description']) ?>">
														  <label><?php echo Yii::t ( 'app', 'Show Company Logo on Invoice' ); ?></label>
														  <select class="form-control" name="COMPANY_LOGO_ON_INVOICE">
															<option value="No" <?=Yii::$app->params['COMPANY_LOGO_ON_INVOICE'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
															<option value="Yes" <?=Yii::$app->params['COMPANY_LOGO_ON_INVOICE'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
														  </select>
														</div>

														<div class="col-sm-3" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['COMPANY_SEAL_ON_INVOICE'.'_description']) ?>">
														  <label><?php echo Yii::t ( 'app', 'Show Company Seal on Invoice' ); ?></label>
														  <select class="form-control" name="COMPANY_SEAL_ON_INVOICE">
															<option value="No" <?=Yii::$app->params['COMPANY_SEAL_ON_INVOICE'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
															<option  value="Yes" <?=Yii::$app->params['COMPANY_SEAL_ON_INVOICE'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
														  </select>
														</div>

														<div class="col-sm-3" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['TAX_NUMBER_ON_INVOICE'.'_description']) ?>">
														  <label><?php echo Yii::t ( 'app', 'Show Tax Number on Invoice' ); ?></label>
														  <select class="form-control" name="TAX_NUMBER_ON_INVOICE">
															<option value="No" <?=Yii::$app->params['TAX_NUMBER_ON_INVOICE'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
															<option value="Yes"  <?=Yii::$app->params['TAX_NUMBER_ON_INVOICE'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
														  </select>
														</div>

														

														<div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['TAX_NUMBER_PREFIX_FORMAT'.'_description']) ?>">
															<label><?php echo Yii::t ( 'app', 'Tax Number Prefix Format' ); ?></label>
															<input type="text" class="form-control  tooltip_btn" name="TAX_NUMBER_PREFIX_FORMAT" value="<?=Yii::$app->params['TAX_NUMBER_PREFIX_FORMAT'] ?>">

														</div>


														 <div class="col-sm-3" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['DAYS_LEFT_IN_DUE_INVOICE'.'_description']) ?>">
                                                        <label><?php echo Yii::t ( 'app', 'Payment Alert (Due Invoice)' ); ?></label>
                                                        <select class="form-control" name="DAYS_LEFT_IN_DUE_INVOICE">
                                                            <option <?=Yii::$app->params['DAYS_LEFT_IN_DUE_INVOICE'] !='1'?'':'selected' ?> value="1">1 <?=Yii::t('app', 'Day')?></option>
                                                            <option <?=Yii::$app->params['DAYS_LEFT_IN_DUE_INVOICE'] !='2'?'':'selected' ?> value="2">2 <?=Yii::t('app', 'Day')?></option>
                                                            <option <?=Yii::$app->params['DAYS_LEFT_IN_DUE_INVOICE'] !='3'?'':'selected' ?> value="3">3 <?=Yii::t('app', 'Days')?></option>
															
                                                            <option <?=Yii::$app->params['DAYS_LEFT_IN_DUE_INVOICE'] !='4'?'':'selected' ?> value="4">4 <?=Yii::t('app', 'Days')?></option>
															
															<option <?=Yii::$app->params['DAYS_LEFT_IN_DUE_INVOICE'] !='5'?'':'selected' ?> value="5">5 <?=Yii::t('app', 'Days')?></option>
															
															<option <?=Yii::$app->params['DAYS_LEFT_IN_DUE_INVOICE'] !='6'?'':'selected' ?> value="6">6 <?=Yii::t('app', 'Days')?></option>
															
															<option <?=Yii::$app->params['DAYS_LEFT_IN_DUE_INVOICE'] !='7'?'':'selected' ?> value="7">7 <?=Yii::t('app', 'Days')?></option>
															
															<option <?=Yii::$app->params['DAYS_LEFT_IN_DUE_INVOICE'] !='8'?'':'selected' ?> value="8">8 <?=Yii::t('app', 'Days')?></option>
															
															<option <?=Yii::$app->params['DAYS_LEFT_IN_DUE_INVOICE'] !='9'?'':'selected' ?> value="9">9 <?=Yii::t('app', 'Days')?></option>
															
															<option <?=Yii::$app->params['DAYS_LEFT_IN_DUE_INVOICE'] !='10'?'':'selected' ?> value="10">10 <?=Yii::t('app', 'Days')?></option>
															
															<option <?=Yii::$app->params['DAYS_LEFT_IN_DUE_INVOICE'] !='11'?'':'selected' ?> value="11">11 <?=Yii::t('app', 'Days')?></option>
															
															<option <?=Yii::$app->params['DAYS_LEFT_IN_DUE_INVOICE'] !='12'?'':'selected' ?> value="12">12 <?=Yii::t('app', 'Days')?></option>
															
															<option <?=Yii::$app->params['DAYS_LEFT_IN_DUE_INVOICE'] !='13'?'':'selected' ?> value="13">13 <?=Yii::t('app', 'Days')?></option>
															
															<option <?=Yii::$app->params['DAYS_LEFT_IN_DUE_INVOICE'] !='14'?'':'selected' ?> value="14">14 <?=Yii::t('app', 'Days')?></option>
															
															<option <?=Yii::$app->params['DAYS_LEFT_IN_DUE_INVOICE'] !='15'?'':'selected' ?> value="15">15 <?=Yii::t('app', 'Days')?></option>
															
                                                        </select>
                                                        </div>
                          
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
											<?php } ?>
											  <!--End-->

											  <!--Made changes-->
											<?php if(in_array('support',yii::$app->params['modules'])){ ?>
											  	<div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="Support_Email_Settings">
                                                  <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#Support_Email_Settings1" aria-expanded="false" aria-controls="Misc1">
                                                     <?php echo Yii::t ( 'app', 'Automatic Tickets Settings' ); ?> 
                                                    </a>
                                                  </h4>
                                                </div>
                                                <div id="Support_Email_Settings1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="Support_Email_Settings">
                                                  <div class="panel-body">
                                                   <div class="form-group">

													 <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['INCOMING_EMAIL_SERVER_TYPE'.'_description']) ?>">
														<label><?php echo Yii::t ( 'app', 'Incoming Server Type' ); ?></label>
													  <select class="form-control" name="INCOMING_EMAIL_SERVER_TYPE">
																<option value="imap"  <?=Yii::$app->params['INCOMING_EMAIL_SERVER_TYPE'] =='imap'?'selected':'' ?>>IMAP</option>
																<option value="pop"  <?=Yii::$app->params['INCOMING_EMAIL_SERVER_TYPE'] =='pop'?'selected':'' ?>>POP</option>
															</select>
														
														</div>

														<div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['INCOMING_EMAIL_SERVER_HOST'.'_description']) ?>">
															<label><?php echo Yii::t ( 'app', 'Incoming Server Host' ); ?></label>
															<input type="text" class="form-control  tooltip_btn" name="INCOMING_EMAIL_SERVER_HOST" value="<?=Yii::$app->params['INCOMING_EMAIL_SERVER_HOST'] ?>">

														</div>

														<div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['INCOMING_EMAIL_SERVER_USERNAME'.'_description']) ?>">
															<label><?php echo Yii::t ( 'app', 'Username' ); ?></label>
															<input type="text" class="form-control  tooltip_btn" name="INCOMING_EMAIL_SERVER_USERNAME" value="<?=Yii::$app->params['INCOMING_EMAIL_SERVER_USERNAME'] ?>">

														</div>

													   
														<div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['INCOMING_EMAIL_SERVER_PASSWORD'.'_description']) ?>">
														<label><?php echo Yii::t ( 'app', 'Password' ); ?></label>
														<input type="password" name="INCOMING_EMAIL_SERVER_PASSWORD" class="form-control"  value="**********">
														
														</div>

														 <div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['INCOMING_EMAIL_SERVER_PORT'.'_description']) ?>">
														<label><?php echo Yii::t ( 'app', 'Port' ); ?></label>
														<input type="text" name="INCOMING_EMAIL_SERVER_PORT" class="form-control"  value="<?=Yii::$app->params['INCOMING_EMAIL_SERVER_PORT'] ?>">
														
														</div>

														<div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['DEFAULT_TICKET_PRIORITY'.'_description']) ?>">
														<label><?php echo Yii::t ( 'app', 'Default Ticket Priority' ); ?></label>
														<select class="form-control" name="DEFAULT_TICKET_PRIORITY">
															<?php
																foreach(TicketPriority::find()->all() as $row){
															?>
															<option value="<?php echo $row->id?>" <?=Yii::$app->params['DEFAULT_TICKET_PRIORITY'] !=$row->id?'':'selected' ?>><?php echo $row->label; ?></option>
															<?php } ?>
														</select>
														
														</div>

														<div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['DEFAULT_TICKET_IMPACT'.'_description']) ?>">
														<label><?php echo Yii::t ( 'app', 'Default Ticket Impact' ); ?></label>
														<select class="form-control" name="DEFAULT_TICKET_IMPACT">
															<?php
																foreach(TicketImpact::find()->all() as $row){
															?>
															<option value="<?php echo $row->id?>" <?=Yii::$app->params['DEFAULT_TICKET_IMPACT'] !=$row->id?'':'selected' ?>><?php echo $row->label; ?></option>
															<?php } ?>
														</select>
														
														</div>

														<div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['DEFAULT_TICKET_DEPARTMENT'.'_description']) ?>">
														<label><?php echo Yii::t ( 'app', 'Default Department' ); ?></label>
														
														<?=Html::dropDownList('DEFAULT_TICKET_DEPARTMENT',Yii::$app->params['DEFAULT_TICKET_DEPARTMENT'],
         ArrayHelper::map(Department::find()->orderBy('id')->asArray()->all(), 'id', 'label'), ['prompt' => '--Department--','class'=>'form-control','id'=>'department_id']  )?>
														
														</div>

														<div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['DEFAULT_TICKET_QUEUE'.'_description']) ?>">
														<label><?php echo Yii::t ( 'app', 'Default Queue' ); ?></label>
														
														
														<?=Html::dropDownList('DEFAULT_TICKET_QUEUE',Yii::$app->params['DEFAULT_TICKET_QUEUE'],
         ArrayHelper::map(Queue::find()->where('id=0')->asArray()->all(), 'id', 'queue_title'), ['prompt' => '--Queue--','class'=>'form-control','id'=>'queue_id']  )?>

														</div>

														<div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['DEFAULT_TICKET_CATEGORY'.'_description']) ?>">
														<label><?php echo Yii::t ( 'app', 'Default Ticket Category' ); ?></label>


														<?=Html::dropDownList('DEFAULT_TICKET_CATEGORY',Yii::$app->params['DEFAULT_TICKET_CATEGORY'],
         ArrayHelper::map(TicketCategory::find()->where('id=0')->asArray()->all(), 'id', 'label'), ['prompt' => '--Ticket Category 1--' , 'class'=>'form-control','id'=>'ticket_category_id_1']  )?>

														
														</div>

														<div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['PROCESSED_MAIL_FOLDER'.'_description']) ?>">
														<label><?php echo Yii::t ( 'app', 'Processed Mail Folder' ); ?></label>
														<input type="text" name="PROCESSED_MAIL_FOLDER" class="form-control"  value="<?=Yii::$app->params['PROCESSED_MAIL_FOLDER'] ?>">
														
														</div>
<!--
														<div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['PREPEND_TICKET_ID_IN_TITLE'.'_description']) ?>">
														<label><?php echo Yii::t ( 'app', 'Prepend Ticket_id in Ticket Title' ); ?></label>


														<select class="form-control" name="PREPEND_TICKET_ID_IN_TITLE">
                                                            <option <?=Yii::$app->params['PREPEND_TICKET_ID_IN_TITLE'] !='Yes'?'':'selected' ?> value="Yes"><?=Yii::t('app', 'Yes')?></option>
                                                            <option <?=Yii::$app->params['PREPEND_TICKET_ID_IN_TITLE'] !='No'?'':'selected' ?> value="No"><?=Yii::t('app', 'No')?></option>
                                                        </select>

														
														</div>
-->
														<div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['DELETE_MAIL_AFTER_PROCESSING'.'_description']) ?>">
														<label><?php echo Yii::t ( 'app', 'Delete Email After Processing' ); ?></label>


														<select class="form-control" name="DELETE_MAIL_AFTER_PROCESSING">
                                                            <option <?=Yii::$app->params['DELETE_MAIL_AFTER_PROCESSING'] !='Yes'?'':'selected' ?> value="Yes"><?=Yii::t('app', 'Yes')?></option>
                                                            <option <?=Yii::$app->params['DELETE_MAIL_AFTER_PROCESSING'] !='No'?'':'selected' ?> value="No"><?=Yii::t('app', 'No')?></option>
                                                        </select>

														
														</div>

														<div class="col-sm-3" data-container="body" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['DELETE_SPAM_MAIL'.'_description']) ?>">
														<label><?php echo Yii::t ( 'app', 'Delete SPAM Email' ); ?></label>


														<select class="form-control" name="DELETE_SPAM_MAIL">
                                                            <option <?=Yii::$app->params['DELETE_SPAM_MAIL'] !='Yes'?'':'selected' ?> value="Yes"><?=Yii::t('app', 'Yes')?></option>
                                                            <option <?=Yii::$app->params['DELETE_SPAM_MAIL'] !='No'?'':'selected' ?> value="No"><?=Yii::t('app', 'No')?></option>
                                                        </select>

														
														</div>

                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
											<?php } ?>


                                              <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="Misc">
                                                  <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#Misc1" aria-expanded="false" aria-controls="Misc1">
                                                     <?php echo Yii::t ( 'app', 'Misc' ); ?> 
                                                    </a>
                                                  </h4>
                                                </div>
                                                <div id="Misc1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="Misc">
                                                  <div class="panel-body">
                                                   <div class="form-group">
                                                        <div class="col-sm-3" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['FILE_SIZE'.'_description']) ?>">
                                                        <label><?php echo Yii::t ( 'app', 'Maximum Size (File Upload)' ); ?></label>
                                                        <select class="form-control" name="FILE_SIZE">
                                                            <option <?=Yii::$app->params['FILE_SIZE'] !='5'?'':'selected' ?> value="5">5MB</option>
                                                            <option <?=Yii::$app->params['FILE_SIZE'] !='20'?'':'selected' ?> value="20">20MB</option>
                                                            <option <?=Yii::$app->params['FILE_SIZE'] !='100'?'':'selected' ?> value="100">100MB</option>
                                                            <option <?=Yii::$app->params['FILE_SIZE'] !='0'?'':'selected' ?> value="0"><?php echo Yii::t ( 'app', 'No Limit' ); ?></option>
                                                        </select>
                                                        </div>
                                                        <div class="col-sm-3" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SHOW_DEBUG_TOOLBAR'.'_description']) ?>">
                                                        <label><?php echo Yii::t ( 'app', 'Show Debug Toolbar' ); ?></label>
                                                        <select class="form-control" name="SHOW_DEBUG_TOOLBAR">
                                                            <option <?=Yii::$app->params['SHOW_DEBUG_TOOLBAR'] !='Yes'?'':'selected' ?> value="Yes"><?=Yii::t('app', 'Yes')?></option>
                                                            <option <?=Yii::$app->params['SHOW_DEBUG_TOOLBAR'] !='No'?'':'selected' ?> value="No"><?=Yii::t('app', 'No')?></option>
                                                        </select>
                                                        </div>
														<div class="col-sm-3" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SESSION_TIMEOUT_PERIOD'.'_description']) ?>">
                                                        <label><?php echo Yii::t ( 'app', 'Session Timeout Period' ); ?></label>
                                                        <select class="form-control" name="SESSION_TIMEOUT_PERIOD">
                                                            <option <?=Yii::$app->params['SESSION_TIMEOUT_PERIOD'] !='86400'?'':'selected' ?> value="1">1 <?=Yii::t('app', 'Day')?></option>
                                                            <option <?=Yii::$app->params['SESSION_TIMEOUT_PERIOD'] !='172800'?'':'selected' ?> value="2">2 <?=Yii::t('app', 'Days')?></option>
                                                            <option <?=Yii::$app->params['SESSION_TIMEOUT_PERIOD'] !='432000'?'':'selected' ?> value="5">5 <?=Yii::t('app', 'Days')?></option>
															<option <?=Yii::$app->params['SESSION_TIMEOUT_PERIOD'] !='604800'?'':'selected' ?> value="7">7 <?=Yii::t('app', 'Days')?></option>
                                                            <option <?=Yii::$app->params['SESSION_TIMEOUT_PERIOD'] !='0'?'':'selected' ?> value="0"><?php echo Yii::t ( 'app', 'Never Timeout' ); ?></option>
                                                        </select>
                                                        </div>
														<div class="col-sm-3" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['ALLOW_NEW_REGISTRATION'.'_description']) ?>">
                                                        <label><?php echo Yii::t ( 'app', 'Allow New Registration' ); ?></label>
                                                        <select class="form-control" name="ALLOW_NEW_REGISTRATION">
                                                            <option <?=Yii::$app->params['ALLOW_NEW_REGISTRATION'] !='Yes'?'':'selected' ?> value="Yes"><?=Yii::t('app', 'Yes')?></option>
                                                            <option <?=Yii::$app->params['ALLOW_NEW_REGISTRATION'] !='No'?'':'selected' ?> value="No"><?=Yii::t('app', 'No')?></option>
                                                        </select>
                                                        </div>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                	
                                	
                                	
                                	
                                	
                                	<div class="form-group">
                                    <div class="col-sm-4"><input type="submit" value="<?php echo Yii::t ( 'app', 'Update' ); ?>" class="update_button btn btn-primary btn-sm"></div></div>
                                        
                            </form>
                            </div></div>
                        </div>
                        <div class="tab-pane" id="smtp"> 
					
                             <br/>
								<?php
								if(!empty($sent_email)){
								?>
                                	<div class="alert alert-success"><?=$sent_email?>	</div>
                                <?php } ?>
                             <div class="row">
                             <div class="col-sm-12">
                             <form method="post" class="form-horizontal" action="index.php?r=liveobjects/setting/update" enctype="multipart/form-data">
                                <?php Yii::$app->request->enableCsrfValidation = true; ?>
                                <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                                	<div class="form-group">
                                    <label class="col-sm-2"><?php echo Yii::t ( 'app', 'SMTP Enable' ); ?></label>
                                    <div class="col-sm-4" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SMTP_AUTH'.'_description']) ?>">
                                    	<select class="form-control" name="SMTP_AUTH">
                                            <option value="No" <?=Yii::$app->params['SMTP_AUTH'] =='No'?'selected':'' ?>><?=Yii::t('app', 'No')?></option>
                                        	<option value="Yes" <?=Yii::$app->params['SMTP_AUTH'] =='Yes'?'selected':'' ?>><?=Yii::t('app', 'Yes')?></option>
                                        </select>
                                         
                                        <em><?php echo Yii::t ( 'app', 'Notes: if using google SMTP follow these instructions' ); ?> <a href="https://support.google.com/a/answer/176600?hl=en" target="_blank"><?php echo Yii::t ( 'app', 'here' ); ?></a></em>
                                        
                                        </div>
                                   </div>
                                	<div class="form-group">
                                    <label class="col-sm-2"><?php echo Yii::t ( 'app', 'SMTP Host' ); ?></label>
                                    <div class="col-sm-4" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SMTP_HOST'.'_description']) ?>">
                                    	<input type="text" class="form-control"  name="SMTP_HOST" value="<?=Yii::$app->params['SMTP_HOST'] ?>" placeholder="smtp.gmail.com">
                                        </div>
                                   </div>
                                	<div class="form-group">
										<label class="col-sm-2"><?php echo Yii::t ( 'app', 'SMTP Username' ); ?></label>
										<div class="col-sm-4" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SMTP_USERNAME'.'_description']) ?>">
                                    	<input type="text" class="form-control" name="SMTP_USERNAME" value="<?=Yii::$app->params['SMTP_USERNAME'] ?>" placeholder="username@gmail.com">
                                        </div>
                                   </div>
                                	<div class="form-group">
                                    <label class="col-sm-2"><?php echo Yii::t ( 'app', 'SMTP Password' ); ?></label>
                                    <div class="col-sm-4" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SMTP_PASSWORD'.'_description']) ?>">
                                    	<!--<input type="password" class="form-control" name="SMTP_PASSWORD" value="<?= sha1(Yii::$app->params['SMTP_PASSWORD'],PASSWORD_DEFAULT) ?>" placeholder="Your password">-->
										<input type="password" class="form-control" name="SMTP_PASSWORD" value="**********" placeholder="Your password">
                                        </div>
                                   </div>
                                   <div class="form-group">
                                    <label class="col-sm-2"><?php echo Yii::t ( 'app', 'SMTP Port' ); ?></label>
                                    <div class="col-sm-4" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SMTP_PORT'.'_description']) ?>">
                                    	<input type="text" class="form-control" name="SMTP_PORT" value="<?=Yii::$app->params['SMTP_PORT'] ?>">
                                        </div>
                                   </div>
                                   <div class="form-group">
                                    <label class="col-sm-2"><?php echo Yii::t ( 'app', 'SMTP Encryption' ); ?></label>
                                    <div class="col-sm-4" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SMTP_ENCRYPTION'.'_description']) ?>">
                                    	<select class="form-control" name="SMTP_ENCRYPTION">
											<option value="no"  <?=Yii::$app->params['SMTP_ENCRYPTION'] =='no'?'selected':'' ?>>None</option>
                                            <option value="ssl"  <?=Yii::$app->params['SMTP_ENCRYPTION'] =='ssl'?'selected':'' ?>>SSL</option>
                                        	<option value="tls"  <?=Yii::$app->params['SMTP_ENCRYPTION'] =='tls'?'selected':'' ?>>TLS</option>
                                        </select>
                                        
                                        </div>
                                   </div>
                                	<div class="form-group">
                                    <label class="col-sm-2"></label>
                                    <div class="col-sm-2"><input type="submit" value="<?php echo Yii::t ( 'app', 'Update' ); ?>" class="btn btn-primary btn-sm"> </div>
                                   
                                    <div class="col-sm-2"><a href="index.php?r=liveobjects/setting/index&email_send=true" class="btn btn-primary "><?php echo Yii::t ( 'app', 'Send Test Email' ); ?></a> </div>
                                   
                                    </div>
                                        
                            </form>
                            </div></div>
                        </div>
                        <div class="tab-pane" id="theme"> 
                            <br/>
                            <form  action="" method="post" enctype="multipart/form-data" name="theme" id="theme">
							  <?php Yii::$app->request->enableCsrfValidation = true; ?>
									<input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
								<?php 
								if($dataProvider != NULL){
								foreach($dataProvider as $row){
									if($row['config_item_value'] !='Default'){
									?>
                        <div class="row">
                                <input type="hidden" value="<?=$row['id']?>" name="ids[]">
                                	<div class="form-group">
                                        <div class="col-sm-2">
                                        <label><?php echo Yii::t ( 'app', ucwords(strtolower(str_replace('_',' ',$row['config_item_name'])))); ?></label>
                                        </div>
                                        <div class="col-sm-2" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params[$row['config_item_name'].'_description']) ?>">
                                        : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                        <input type="radio" class="<?=$row['config_item_name']?>" name="active<?=$row['id']?>" value="1" <?=$row['active']=='1'?'checked':''?>>
                                        <?php echo Yii::t ( 'app', 'No' ); ?>:
                                        <input type="radio" class="<?=$row['config_item_name']?>" name="active<?=$row['id']?>" value="0" <?=$row['active']=='1'?'':'checked'?>>
                                    </div>
                                     </div>
                                      
                                </div>
                                <hr/>
                                <?php }
                                }
						}	
                                 ?>
                                 <div class="row">
                                 
                                 <div class="form-group">
                                    <div class="col-sm-2">
                                        <label><?php echo Yii::t ( 'app', 'Theme Color' ); ?></label>
                                    </div>
                                    <div class="col-sm-2" data-toggle="hover" data-placement="right" data-content="Theme Color">
                                        <select name="color" class="theme_color" class="form-control">
                                            <option value="">--<?php echo Yii::t ( 'app', 'Select' ); ?>--</option>
                                            <?php 
											if($dataProviderColor != NULL){
											foreach($dataProviderColor as $row1){?>
                                                <option value="<?=$row1['id']?>" <?=$row1['active']?'selected':''?>><?=ucwords(strtolower(str_replace('_',' ',$row1['config_item_name'])))?></option>
                                            <?php }
											
											}?>
                                        </select>
                                    </div>
                                    </div>
                                 </div>
                            <br/><br/>
                            <?= Html::submitButton(Yii::t ( 'app', 'Save Theme' ), ['class' => 'btn btn-primary btn-sm', 'name' => 'login-button']) ?>
                     </form>
                        </div>
                        <div class="tab-pane" id="email_config"> 
                            <br/>
                            <form  action="index.php?r=liveobjects/setting/update" method="post" enctype="multipart/form-data" name="email_config" id="email_config">
								  <?php Yii::$app->request->enableCsrfValidation = true; ?>
						<input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
						<input type="hidden" value="1" name="email_ids">
    			
    			<div class="panel-group" id="accordion1" role="tablist" aria-multiselectable="true">
                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="collapse_user">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion1" href="#collapse_user1" aria-expanded="true" aria-controls="collapse_user1">
                          <?php echo Yii::t ( 'app', 'User Email' ); ?> 
                        </a>
                      </h4>
                    </div>
                    <div id="collapse_user1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="collapse_user">
                      <div class="panel-body">
                        	<div class="form-group">
                                <div class="col-sm-2">
                                <label><?php echo Yii::t ( 'app','New User'); ?></label>
                                </div>
                                <div class="col-sm-2" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['NEW_USER_EMAIL'.'_description'])?>">
                                : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                <input type="radio"  name="NEW_USER_EMAIL" value="1" <?=Yii::$app->params['NEW_USER_EMAIL']=='1'?'checked':''?>>
                                <?php echo Yii::t ( 'app', 'No' ); ?>:
                                <input type="radio"  name="NEW_USER_EMAIL" value="0" <?=Yii::$app->params['NEW_USER_EMAIL']=='1'?'':'checked'?>>
                            </div>
                           </div>
                      </div>
                    </div>
                  </div>
				  
				  
                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="collapse_password">
                      <h4 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion1" href="#collapse_password1" aria-expanded="false" aria-controls="collapse_password1">
                          <?php echo Yii::t ( 'app', 'Password Email' ); ?> 
                        </a>
                      </h4>
                    </div>
                    <div id="collapse_password1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapse_password">
                      <div class="panel-body">
                       <div class="form-group">
                                <div class="col-sm-2">
                                <label><?php echo Yii::t ( 'app','Reset Password'); ?></label>
                                </div>
                                <div class="col-sm-2"  data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['RESET_PASSWORD_EMAIL'.'_description']) ?>">
                                : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                <input type="radio"  name="RESET_PASSWORD_EMAIL" value="1" <?=Yii::$app->params['RESET_PASSWORD_EMAIL']=='1'?'checked':''?>>
                                <?php echo Yii::t ( 'app', 'No' ); ?>:
                                <input type="radio"  name="RESET_PASSWORD_EMAIL" value="0" <?=Yii::$app->params['RESET_PASSWORD_EMAIL']=='1'?'':'checked'?>>
                            </div>
                           </div>
                      </div>
                    </div>
                  </div>
				  <?php if(in_array('pmt',yii::$app->params['modules'])){ ?>
                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="collapse_task">
                      <h4 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion1" href="#collapse_task1" aria-expanded="false" aria-controls="collapse_task1">
                        <?php echo Yii::t ( 'app', 'Task Email' ); ?>  
                        </a>
                      </h4>
                    </div>
                    <div id="collapse_task1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapse_task">
                      <div class="panel-body">
                      <div class="row">
                       	<div class="form-group">
                                <div class="col-sm-2">
                                <label><?php echo Yii::t ( 'app','Task Create'); ?></label>
                                </div>
                                <div class="col-sm-2"  data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['TASK_CREATE_EMAIL'.'_description']) ?>">
                                : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                <input type="radio"  name="TASK_CREATE_EMAIL" value="1" <?=Yii::$app->params['TASK_CREATE_EMAIL']=='1'?'checked':''?>>
                                <?php echo Yii::t ( 'app', 'No' ); ?>:
                                <input type="radio"  name="TASK_CREATE_EMAIL" value="0" <?=Yii::$app->params['TASK_CREATE_EMAIL']=='1'?'':'checked'?>>
                                 <div class="tooltip_box" style="display:none"><?=Yii::t ( 'app',Yii::$app->params['TASK_CREATE_EMAIL'.'_description']) ?></div>
                            </div>
                           </div>
                       </div>
                        <hr/>
                       <div class="row">
                         <div class="form-group">
                                <div class="col-sm-2">
                                <label><?php echo Yii::t ( 'app','User Changed'); ?></label>
                                </div>
                                <div class="col-sm-2"  data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['TASK_CHANGED_USER_EMAIL'.'_description']) ?>">
                                : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                <input type="radio"  name="TASK_CHANGED_USER_EMAIL" value="1" <?=Yii::$app->params['TASK_CHANGED_USER_EMAIL']=='1'?'checked':''?>>
                                <?php echo Yii::t ( 'app', 'No' ); ?>:
                                <input type="radio"  name="TASK_CHANGED_USER_EMAIL" value="0" <?=Yii::$app->params['TASK_CHANGED_USER_EMAIL']=='1'?'':'checked'?>>
                            </div>
                           </div>
                        </div>
                         <hr/>
                       <div class="row">
                         <div class="form-group">
                                <div class="col-sm-2">
                                <label><?php echo Yii::t ( 'app','Priority Changed'); ?></label>
                                </div>
                                <div class="col-sm-2"  data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['TASK_CHANGED_PRIORITY'.'_description']) ?>">
                                : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                <input type="radio"  name="TASK_CHANGED_PRIORITY" value="1" <?=Yii::$app->params['TASK_CHANGED_PRIORITY']=='1'?'checked':''?>>
                                <?php echo Yii::t ( 'app', 'No' ); ?>:
                                <input type="radio"  name="TASK_CHANGED_PRIORITY" value="0" <?=Yii::$app->params['TASK_CHANGED_PRIORITY']=='1'?'':'checked'?>>
                            </div>
                           </div>
                        </div>
                         <hr/>
                       <div class="row">
                        <div class="form-group">
                                <div class="col-sm-2">
                                <label><?php echo Yii::t ( 'app','Status Changed'); ?></label>
                                </div>
                                <div class="col-sm-2"  data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['TASK_CHANGED_STATUS_EMAIL'.'_description']) ?>">
                                : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                <input type="radio"  name="TASK_CHANGED_STATUS_EMAIL" value="1" <?=Yii::$app->params['TASK_CHANGED_STATUS_EMAIL']=='1'?'checked':''?>>
                                <?php echo Yii::t ( 'app', 'No' ); ?>:
                                <input type="radio"  name="TASK_CHANGED_STATUS_EMAIL" value="0" <?=Yii::$app->params['TASK_CHANGED_STATUS_EMAIL']=='1'?'':'checked'?>>
                            </div>
                           </div>
                           </div>
                      </div>
                      
                    </div>
                  </div>
				  <?php } 
				   if(in_array('support',yii::$app->params['modules'])){ 
				  ?>
                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="collapse_ticket">
                      <h4 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion1" href="#collapse_ticket1" aria-expanded="false" aria-controls="collapse_ticket1">
                        <?php echo Yii::t ( 'app', 'Ticket Email' ); ?>  
                        </a>
                      </h4>
                    </div>
                    <div id="collapse_ticket1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapse_ticket">
                      <div class="panel-body">
                      <div class="row">
                       	<div class="form-group">
                                <div class="col-sm-2">
                                <label><?php echo Yii::t ( 'app','Ticket Create'); ?></label>
                                </div>
                                <div class="col-sm-2"  data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['TICKET_CREATE_EMAIL'.'_description']) ?>">
                                : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                <input type="radio"  name="TICKET_CREATE_EMAIL" value="1" <?=Yii::$app->params['TICKET_CREATE_EMAIL']=='1'?'checked':''?>>
                                <?php echo Yii::t ( 'app', 'No' ); ?>:
                                <input type="radio"  name="TICKET_CREATE_EMAIL" value="0" <?=Yii::$app->params['TICKET_CREATE_EMAIL']=='1'?'':'checked'?>>
                                 <div class="tooltip_box" style="display:none"><?=Yii::t ( 'app',Yii::$app->params['TICKET_CREATE_EMAIL'.'_description']) ?></div>
                            </div>
                           </div>
                       </div>
                        <hr/>
                       <div class="row">
                         <div class="form-group">
                                <div class="col-sm-2">
                                <label><?php echo Yii::t ( 'app','User Changed'); ?></label>
                                </div>
                                <div class="col-sm-2"  data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['TICKET_CHANGED_USER_EMAIL'.'_description']) ?>">
                                : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                <input type="radio"  name="TICKET_CHANGED_USER_EMAIL" value="1" <?=Yii::$app->params['TICKET_CHANGED_USER_EMAIL']=='1'?'checked':''?>>
                                <?php echo Yii::t ( 'app', 'No' ); ?>:
                                <input type="radio"  name="TICKET_CHANGED_USER_EMAIL" value="0" <?=Yii::$app->params['TICKET_CHANGED_USER_EMAIL']=='1'?'':'checked'?>>
                            </div>
                           </div>
                        </div>
                         <hr/>
                       <div class="row">
                         <div class="form-group">
                                <div class="col-sm-2">
                                <label><?php echo Yii::t ( 'app','Priority Changed'); ?></label>
                                </div>
                                <div class="col-sm-2"  data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['TICKET_CHANGED_PRIORITY'.'_description']) ?>">
                                : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                <input type="radio"  name="TICKET_CHANGED_PRIORITY" value="1" <?=Yii::$app->params['TICKET_CHANGED_PRIORITY']=='1'?'checked':''?>>
                                <?php echo Yii::t ( 'app', 'No' ); ?>:
                                <input type="radio"  name="TICKET_CHANGED_PRIORITY" value="0" <?=Yii::$app->params['TICKET_CHANGED_PRIORITY']=='1'?'':'checked'?>>
                            </div>
                           </div>
                        </div>
                         <hr/>
                       <div class="row">
                        <div class="form-group">
                                <div class="col-sm-2">
                                <label><?php echo Yii::t ( 'app','Status Changed'); ?></label>
                                </div>
                                <div class="col-sm-2"  data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['TICKET_CHANGED_STATUS_EMAIL'.'_description']) ?>">
                                : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                <input type="radio"  name="TICKET_CHANGED_STATUS_EMAIL" value="1" <?=Yii::$app->params['TICKET_CHANGED_STATUS_EMAIL']=='1'?'checked':''?>>
                                <?php echo Yii::t ( 'app', 'No' ); ?>:
                                <input type="radio"  name="TICKET_CHANGED_STATUS_EMAIL" value="0" <?=Yii::$app->params['TICKET_CHANGED_STATUS_EMAIL']=='1'?'':'checked'?>>
                            </div>
                           </div>
                           </div>
						   <hr/>
						   <div class="row">
                        <div class="form-group">
                                <div class="col-sm-2">
                                <label><?php echo Yii::t ( 'app','Send Email To Customer On Ticket Creation & Status Update'); ?></label>
                                </div>
                                <div class="col-sm-2"  data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['SEND_EMAIL_TO_CUSTOMER_ON_TICKET_CREATION'.'_description']) ?>">
                                : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                <input type="radio"  name="SEND_EMAIL_TO_CUSTOMER_ON_TICKET_CREATION" value="1" <?=Yii::$app->params['SEND_EMAIL_TO_CUSTOMER_ON_TICKET_CREATION']=='1'?'checked':''?>>
                                <?php echo Yii::t ( 'app', 'No' ); ?>:
                                <input type="radio"  name="SEND_EMAIL_TO_CUSTOMER_ON_TICKET_CREATION" value="0" <?=Yii::$app->params['SEND_EMAIL_TO_CUSTOMER_ON_TICKET_CREATION']=='1'?'':'checked'?>>
                            </div>
                           </div>
                           </div>
                      </div>
                      
                    </div>
                  </div>
				   <?php }
				   if(in_array('pmt',yii::$app->params['modules'])){ ?>
                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="collapse_defect">
                      <h4 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion1" href="#collapse_defect1" aria-expanded="false" aria-controls="collapse_defect1">
                          <?php echo Yii::t ( 'app', 'Defect Email' ); ?>
                        </a>
                      </h4>
                    </div>
                    <div id="collapse_defect1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapse_defect">
                      <div class="panel-body">
                      	<div class="row">
                       	<div class="form-group">
                                <div class="col-sm-2">
                                <label><?php echo Yii::t ( 'app','Create Defect'); ?></label>
                                </div>
                                <div class="col-sm-2"  data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['DEFECT_CREATE_EMAIL'.'_description']) ?>">
                                : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                <input type="radio"  name="DEFECT_CREATE_EMAIL" value="1" <?=Yii::$app->params['DEFECT_CREATE_EMAIL']=='1'?'checked':''?>>
                                <?php echo Yii::t ( 'app', 'No' ); ?>:
                                <input type="radio"  name="DEFECT_CREATE_EMAIL" value="0" <?=Yii::$app->params['DEFECT_CREATE_EMAIL']=='1'?'':'checked'?>>
                            </div>
                           </div>
                         </div>  
                           <hr/>
                         <div class="row">
                         <div class="form-group">
                                <div class="col-sm-2">
                                <label><?php echo Yii::t ( 'app','User Changed'); ?></label>
                                </div>
                                <div class="col-sm-2"  data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['DEFECT_USER_CHANGED_EMAIL'.'_description']) ?>">
                                : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                <input type="radio"  name="DEFECT_USER_CHANGED_EMAIL" value="1" <?=Yii::$app->params['DEFECT_USER_CHANGED_EMAIL']=='1'?'checked':''?>>
                                <?php echo Yii::t ( 'app', 'No' ); ?>:
                                <input type="radio"  name="DEFECT_USER_CHANGED_EMAIL" value="0" <?=Yii::$app->params['DEFECT_USER_CHANGED_EMAIL']=='1'?'':'checked'?>>
                            </div>
                           </div>
                          </div>  
                           <hr/>
                         <div class="row">
                         <div class="form-group">
                                <div class="col-sm-2">
                                <label><?php echo Yii::t ( 'app','Priority Changed'); ?></label>
                                </div>
                                <div class="col-sm-2"  data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['DEFECT_CHANGED_PRIORITY_EMAIL'.'_description']) ?>">
                                : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                <input type="radio"  name="DEFECT_CHANGED_PRIORITY_EMAIL" value="1" <?=Yii::$app->params['DEFECT_CHANGED_PRIORITY_EMAIL']=='1'?'checked':''?>>
                                <?php echo Yii::t ( 'app', 'No' ); ?>:
                                <input type="radio"  name="DEFECT_CHANGED_PRIORITY_EMAIL" value="0" <?=Yii::$app->params['DEFECT_CHANGED_PRIORITY_EMAIL']=='1'?'':'checked'?>>
                            </div>
                           </div>
                         </div>  
                           <hr/>
                         <div class="row">
                        <div class="form-group">
                                <div class="col-sm-2">
                                <label><?php echo Yii::t ( 'app','Status Changed'); ?></label>
                                </div>
                                <div class="col-sm-2"  data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['DEFECT_CHANGED_STATUS_EMAIL'.'_description']) ?>">
                                : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                <input type="radio"  name="DEFECT_CHANGED_STATUS_EMAIL" value="1" <?=Yii::$app->params['DEFECT_CHANGED_STATUS_EMAIL']=='1'?'checked':''?>>
                                <?php echo Yii::t ( 'app', 'No' ); ?>:
                                <input type="radio"  name="DEFECT_CHANGED_STATUS_EMAIL" value="0" <?=Yii::$app->params['DEFECT_CHANGED_STATUS_EMAIL']=='1'?'':'checked'?>>
                            </div>
                           </div>
                        </div>
                      </div>
                    </div>
                  </div>
				   <?php } ?>
                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="collapse_attachment">
                      <h4 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion1" href="#collapse_attachment1" aria-expanded="false" aria-controls="collapse_attachment1">
                         <?php echo Yii::t ( 'app', 'Attachment Email' ); ?> 
                        </a>
                      </h4>
                    </div>
                    <div id="collapse_attachment1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapse_attachment">
                      <div class="panel-body">
                      	<div class="row">
                       	<div class="form-group">
                                <div class="col-sm-3">
                                <label><?php echo Yii::t ( 'app','Add Attachment'); ?></label>
                                </div>
                                <div class="col-sm-2"  data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['ADD_ATTACHMENT_EMAIL'.'_description']) ?>">
                                : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                <input type="radio"  name="ADD_ATTACHMENT_EMAIL" value="1" <?=Yii::$app->params['ADD_ATTACHMENT_EMAIL']=='1'?'checked':''?>>
                                <?php echo Yii::t ( 'app', 'No' ); ?>:
                                <input type="radio"  name="ADD_ATTACHMENT_EMAIL" value="0" <?=Yii::$app->params['ADD_ATTACHMENT_EMAIL']=='1'?'':'checked'?>>
                            </div>
                           </div>
                          </div>  
                         <!--  <hr/>
                         <div class="row">
                         <div class="form-group">
                                <div class="col-sm-3">
                                <label><?php echo Yii::t ( 'app','Update Attachment'); ?></label>
                                </div>
                                <div class="col-sm-2"  data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['UPDATE_ATTACHMENT_EMAIL'.'_description']) ?>">
                                : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                <input type="radio"  name="UPDATE_ATTACHMENT_EMAIL" value="1" <?=Yii::$app->params['UPDATE_ATTACHMENT_EMAIL']=='1'?'checked':''?>>
                                <?php echo Yii::t ( 'app', 'No' ); ?>:
                                <input type="radio"  name="UPDATE_ATTACHMENT_EMAIL" value="0" <?=Yii::$app->params['UPDATE_ATTACHMENT_EMAIL']=='1'?'':'checked'?>>
                            </div>
                           </div>
                           </div>-->
                      </div>
                    </div>
                  </div>
                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="collapse_notes">
                      <h4 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion1" href="#collapse_notes1" aria-expanded="false" aria-controls="collapse_notes1">
                         <?php echo Yii::t ( 'app', 'Notes Email' ); ?>  
                        </a>
                      </h4>
                    </div>
                    <div id="collapse_notes1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapse_notes">
                      <div class="panel-body">
                      	<div class="row">
                       	<div class="form-group">
                                <div class="col-sm-3">
                                <label><?php echo Yii::t ( 'app','Create Notes'); ?></label>
                                </div>
                                <div class="col-sm-2"  data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['NOTE_ADD_EMAIL'.'_description']) ?>">
                                : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                <input type="radio"  name="NOTE_ADD_EMAIL" value="1" <?=Yii::$app->params['NOTE_ADD_EMAIL']=='1'?'checked':''?>>
                                <?php echo Yii::t ( 'app', 'No' ); ?>:
                                <input type="radio"  name="NOTE_ADD_EMAIL" value="0" <?=Yii::$app->params['NOTE_ADD_EMAIL']=='1'?'':'checked'?>>
                            </div>
                           </div>
                           </div>  
                           <hr/>
                         <div class="row">
                         <div class="form-group">
                                <div class="col-sm-3">
                                <label><?php echo Yii::t ( 'app','Update Notes'); ?></label>
                                </div>
                                <div class="col-sm-2" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['UPDATE_NOTE_EMAIL'.'_description']) ?>">
                                : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                <input type="radio"  name="UPDATE_NOTE_EMAIL" value="1" <?=Yii::$app->params['UPDATE_NOTE_EMAIL']=='1'?'checked':''?>>
                                <?php echo Yii::t ( 'app', 'No' ); ?>:
                                <input type="radio"  name="UPDATE_NOTE_EMAIL" value="0" <?=Yii::$app->params['UPDATE_NOTE_EMAIL']=='1'?'':'checked'?>>
                            </div>
                           </div>
                           </div>
                      </div>
                    </div>
                  </div>
				  
                   <!--Changes done by Amita-->
				   <?php if(in_array('invoice',yii::$app->params['modules'])){ ?>
				  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="collapse_notification_mail">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion1" href="#collapse_notification_mail1" aria-expanded="false" aria-controls="collapse_notification_mail1">
                          <?php echo Yii::t ( 'app', 'Due Invoice Email' ); ?> 
                        </a>
                      </h4>
                    </div>
                    <div id="collapse_notification_mail1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapse_notification_mail">
                      <div class="panel-body">
                        	<div class="form-group">
                                <div class="col-sm-2">
                                <label><?php echo Yii::t ( 'app','Due Invoice Email'); ?></label>
                                </div>
                                <div class="col-sm-2" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['DUE_INVOICE_NOTIFICATION_EMAIL'.'_description'])?>">
                                : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                <input type="radio"  name="DUE_INVOICE_NOTIFICATION_EMAIL" value="1" <?=Yii::$app->params['DUE_INVOICE_NOTIFICATION_EMAIL']=='1'?'checked':''?>>
                                <?php echo Yii::t ( 'app', 'No' ); ?>:
                                <input type="radio"  name="DUE_INVOICE_NOTIFICATION_EMAIL" value="0" <?=Yii::$app->params['DUE_INVOICE_NOTIFICATION_EMAIL']=='1'?'':'checked'?>>
 
                            </div>
                           </div>
                      </div>
                    </div>
                  </div>
				   <?php } ?>
				  <!--End Here-->
				  
				  <!--Changes done by Deepak-->
				  <?php if(in_array('support',yii::$app->params['modules'])  || in_array('pmt',yii::$app->params['modules'])  ){ ?>
				  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="collapse_timesheet_email1">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion1" href="#collapse_timesheet_email" aria-expanded="false" aria-controls="collapse_timesheet_email">
                          <?php echo Yii::t ( 'app', 'Timesheet Email' ); ?> 
                        </a>
                      </h4>
                    </div>
                    <div id="collapse_timesheet_email" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapse_timesheet_email1">
                      <div class="panel-body">
                        	<div class="form-group">
                                <div class="col-sm-2">
                                <label><?php echo Yii::t ( 'app','New Timesheet Entry Email'); ?></label>
                                </div>
                                <div class="col-sm-2" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['NEW_TIMESHEET_ENTRY_EMAIL'.'_description'])?>">
                                : <?php echo Yii::t ( 'app', 'Yes' ); ?>:
                                <input type="radio"  name="NEW_TIMESHEET_ENTRY_EMAIL" value="1" <?=Yii::$app->params['NEW_TIMESHEET_ENTRY_EMAIL']=='1'?'checked':''?>>
                                <?php echo Yii::t ( 'app', 'No' ); ?>:
                                <input type="radio"  name="NEW_TIMESHEET_ENTRY_EMAIL" value="0" <?=Yii::$app->params['NEW_TIMESHEET_ENTRY_EMAIL']=='1'?'':'checked'?>>
 
                            </div>
                           </div>
                      </div>
                    </div>
                  </div>
				   <?php } ?>
				  <!--End Here-->
                </div>
                            <br/><br/>
                            <?= Html::submitButton(Yii::t ( 'app', 'Update' ), ['class' => 'btn btn-primary btn-sm']) ?>
                     </form>
                        </div>

<br/>
						

                        <div class="tab-pane fade" id="logo"> 

		
					<div class="wrapper wrapper-content animated fadeInRight">
							<div class="row">
								<div class="col-lg-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5>Company Logo</small></h5>
										
									</div>
									<div class="ibox-content">
											 <form method="post" id="frm_logo" enctype="multipart/form-data">
											<?php Yii::$app->request->enableCsrfValidation = true; ?>
											<input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
											<div class="row">
												<div class="col-sm-8">
													<div class="form-group">
														<input type="file" class="form-control inp_company_logo" name="company_logo">
													</div>
													<div class="form-group">
														<input type="submit" value="<?php echo Yii::t ( 'app', 'Upload Logo' ); ?>" class="btn btn-primary btn-medium"> 
													</div>
												</div>
												<div class="col-sm-4">
													<div id="company_logo_preview"></div>
													<img src="../logo/logo.png" class="img-responsive upload_logo">
												</div>
											</div>
										 <!--   <br/><br/>
												<?= Html::submitButton(Yii::t ( 'app', 'Update' ), ['class' => 'btn btn-primary btn-sm']) ?>
												-->
											 </form>     
									</div>
								</div>
							</div>
						</div>
						</div>


						<div class="wrapper wrapper-content animated fadeInRight">
							<div class="row">
								<div class="col-lg-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5>Company Seal</small></h5>
										
									</div>
									<div class="ibox-content">
											 <form method="post" id="frm_company_seal" enctype="multipart/form-data">
													<?php Yii::$app->request->enableCsrfValidation = true; ?>
													<input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
													<div class="row">
														<div class="col-sm-8">
															<div class="form-group">
																<input type="file" class="form-control inp_company_seal" name="company_seal">
															</div>
															<div class="form-group">
					                                        	<input type="submit" value="<?php echo Yii::t ( 'app', 'Upload Seal' ); ?>" class="btn btn-primary btn-medium"> 
															</div>
														</div>
														<div class="col-sm-4">
															<div id="company_seal_preview"></div>
															<img src="../logo/seal.png" class="img-responsive upload_seal">
														</div>
													</div>
													<!-- <br/><br/>
													<?= Html::submitButton(Yii::t ( 'app', 'Update' ), ['class' => 'btn btn-primary btn-sm']) ?>
													-->
										 </form>     
									</div>
								</div>
							</div>
						</div>
						</div>
			


                        
					
					
					 


                        </div>



                        <div class="tab-pane" id="cron">
                        	<br/>
                            <?php
							if(!empty($_GET['report'])){?>
								<div class="alert alert-success"><?=Yii::t('app', 'Report has been Sent!')?></div>
								<script>
									setTimeout(function(){
										window.location.href='index.php?r=liveobjects/setting';
									},2000);
								</script>	
						<?php	}
						?>
                           
                             <?php
                                
                                $searchModel = new livefactory\models\search\CronJobs();
                                $dataProvider1 = $searchModel->search( Yii::$app->request->getQueryParams ());
                                
                                echo Yii::$app->controller->renderPartial("cron-jobs-tab", [ 
                                        'dataProvider1' => $dataProvider1
                                ] );
                                
                                ?>
 <center>
                            	<a href="index.php?r=cron/cron" class="btn btn-lg btn-primary"><?php echo Yii::t ( 'app', 'Send Report' ); ?> </a>
                            </center>
                        </div>
                        <div class="tab-pane" id="license">
                        	<br/>
                             <form method="post" class="form-horizontal" action="index.php?r=liveobjects/setting/update" enctype="multipart/form-data">
                                <?php Yii::$app->request->enableCsrfValidation = true; ?>
                                <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">

                            
        
                                <div class="form-group">
        							<div class="col-sm-12">
                               			 <label class="control-label" for="lname"><?=Yii::t('app', 'License')?>
        
            
        
                                </label>
        
                               			 <div class="controls">
        
                                  <textarea class="form-control input-sm ckeditor" name="LICENSE" id="LICENSE" rows="8" readonly style="width:100%"><?=Yii::$app->params['LICENSE']?></textarea>
        
                                </div>
        						</div>
                            </div>
                                <div class="form-group">
                                	<div class="col-sm-12">
                                    <input type="submit" value="Update" class="btn btn-primary btn-sm">
                                    </div>
                                </div>
                </form>
                        </div>
						<div class="tab-pane" id="payment"> 
					         <br/>
								 <div class="row">
									<div class="col-sm-12">
										 <form method="post" class="form-horizontal" action="index.php?r=liveobjects/setting/update" enctype="multipart/form-data">
											<?php Yii::$app->request->enableCsrfValidation = true; ?>
											<input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                                			<div class="form-group">
												<label class="col-sm-2"><?php echo Yii::t ( 'app', 'Paypal Account ID' ); ?></label>
													<div class="col-sm-4" data-toggle="hover" data-placement="top" data-content="<?=Yii::t ( 'app', Yii::$app->params['PAYPAL_USER'.'_description']) ?>">
                                    					<input type="text" class="form-control"  name="PAYPAL_USER" value="<?=Yii::$app->params['PAYPAL_USER'] ?>" placeholder="">
													</div>
											</div>
                                	
                                			<div class="form-group">
												<label class="col-sm-2"></label>
													<div class="col-sm-2"><input type="submit" value="<?php echo Yii::t ( 'app', 'Update' ); ?>" class="btn btn-primary btn-sm"> </div>
											</div>
										</form>
									</div>
								</div>
                        </div>

                        <div class="tab-pane" id="company"> 
                            <br/>			
                             <div class="company-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]); 
	
	?>
    <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo Yii::t ( 'app', 'Company Detail' ); ?></h3>
                </div>
                <div class="panel-body">
    <?php
	echo Form::widget([

    'model' => $companyModel,
    'form' => $form,
    'columns' => 2,
    'attributes' => [

'company_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Company Name...', 'maxlength'=>255]], 

'company_email'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Company Email...', 'maxlength'=>255]], 

'phone'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Company Phone...', 'maxlength'=>255]], 

'mobile'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Company Mobile...', 'maxlength'=>255]], 

'fax'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Company Fax...', 'maxlength'=>255]], 

//'address_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Address ID...']], 

//'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Created At...']], 

//'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Updated At...']], 

    ]


    ]);?>
    		</div>
         </div>
         <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo Yii::t ( 'app', 'Address Detail' ); ?></h3>
                </div>
                <div class="panel-body">
                <div class="row">
                	<div class="col-sm-4">
                    	<div class="form-group">
                        	<label class="control-label"><?php echo Yii::t ( 'app', 'Address 1' ); ?></label>
                        	<input type="text" name="address_1" value="<?=$addressModel->address_1?>" data-validation="required" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-4">
                    	<div class="form-group">
                        	<label class="control-label"><?php echo Yii::t ( 'app', 'Address 2' ); ?></label>
                        	<input type="text" name="address_2" value="<?=$addressModel->address_2?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-4">
                    	<div class="form-group">
                        	<label class="control-label"><?php echo Yii::t ( 'app', 'Zipcode' ); ?></label>
                        	<input type="text" name="zipcode" data-validation="required" value="<?=$addressModel->zipcode?>" class="form-control">
                        </div>
                    </div>
                </div>
					<?php
                    echo '<div class="row">
                            <div class="col-sm-4">
                                <div class="form-group required">
                                    <label class="control-label">'.Yii::t ( 'app', 'Country' ).'</label>
                            '.Html::dropDownList('country_id',$addressModel->country_id,
         ArrayHelper::map(Country::find()->orderBy('country')->asArray()->all(), 'id', 'country'), ['prompt' => '--Select--','class'=>'form-control','id'=>'country_id','data-validation'=>'required' ]  ).'</div></div>
                            <div class="col-sm-4">
                            <div class="form-group required">
                                    <label class="control-label">'.Yii::t ( 'app', 'State' ).'</label>
                            '.Html::dropDownList('state_id',$addressModel->state_id,
         ArrayHelper::map(State::find()->where('id=0')->orderBy('state')->asArray()->all(), 'id', 'state'), ['prompt' => '--Select--','class'=>'form-control','id'=>'state_id','data-validation'=>'required' ]  ).'</div></div>
                        <div class="col-sm-4">
                            <div class="form-group required">
                                    <label class="control-label">'.Yii::t ( 'app', 'City' ).'</label>
                            '.Html::dropDownList('city_id',$addressModel->city_id,
         ArrayHelper::map(City::find()->where('id=0')->orderBy('city')->asArray()->all(), 'id', 'city'), ['prompt' => '--Select--','class'=>'form-control','id'=>'city_id' ]  ).'</div></div></div></div></div>';
        
        echo Html::submitButton($companyModel->isNewRecord ? Yii::t ( 'app', 'Create' ) : Yii::t ( 'app', 'Update' ), ['class' => $companyModel->isNewRecord ? 'btn btn-success btn-sm company_submit' : 'btn btn-primary company_submit btn-sm']);
        ActiveForm::end(); ?>
    
    </div>     
                        </div>
                    </div>
                    </div>
                    	
				</div>
            </div>
    

</div>

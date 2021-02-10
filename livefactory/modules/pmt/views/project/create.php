<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Project $model
 */

$this->title = Yii::t('app', 'Create Project');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
function getUserRoleCounts(){
	$connection = \Yii::$app->db;
	$id = Yii::$app->user->identity->id;
	$sql="select auth_item.* from auth_item,auth_assignment where auth_item.type=2 and auth_assignment.user_id=$id and auth_assignment.item_name=auth_item.name and auth_item.name='Customer'";
		$command=$connection->createCommand($sql);
		$dataReader=$command->queryAll();
		return count($dataReader);	
}
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<style>	
.cke_contents{max-height:250px}
</style>
<script>
$(document).ready(function(e) {
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

    $('#project-customer_id').after('<a href="index.php?r=customer/customer/create" class="btn btn-xs btn-info"><span class="glyphicon glyphicon-plus"></span> <?= Yii::t('app', "Add New") ?> </a>');
	$('#project-customer_id').css({'float':'left'});
	/*if('<?=!empty($_GET['customer_id'])?$_GET['customer_id']:''?>' !='')
	$('.field-project-customer_id').parent().hide();*/
	
	//if('<?=getUserRoleCounts()>0?'ss':''?>' !='')
	//$('#project-customer_id').parent().hide();

	//$('#project-project_status_id').attr('disabled','disabled');

	/*$('#project-actual_start_datetime').val('0000-00-00 00-00-00');
	$('#project-actual_end_datetime').val('0000-00-00 00-00-00');*/
	$('#w0').submit(function(event){
		error ='';
	var expected_actual_start = $('#project-expected_start_datetime').val();
	var expected_actual_end = $('#project-expected_end_datetime').val();
	if(expected_actual_start !='' && expected_actual_start !=''){
		var expected_startTime = new Date(expected_actual_start);
		var expected_endTime = new Date(expected_actual_end);
		Remove_Error($('#project-expected_end_datetime'));
		if(expected_startTime >= expected_endTime){
	
			 error+=Add_Error($('#project-expected_end_datetime'),'<?=Yii::t ('app','Expected Completion Date Should be more than Expected Start Date')?>');
	
		}else{
	
			Remove_Error($('#project-expected_end_datetime'));
	
		}
				if(error !=''){
					event.preventDefault();
					return false;
				}else{
					return true;
				}
	}
  
		$('#cke_1_contents').parent().parent().removeAttr('style').next('.error').remove();
		sageLength = CKEDITOR.instances['project-project_description'].getData().replace(/<[^>]*>/gi, '').length;
		if(sageLength==0){
			Add_ErrorTag($('#cke_1_contents').parent().parent(),'<?=Yii::t ('app','This Field is Required!')?>');
		event.preventDefault();
		}
		 })
		 
	})
</script>
<!--<style>
.field-project-project_id{width:200px}
<?php  
if(Yii::$app->params['AUTO_PROJECT_ID'] == 'Yes') { ?>
	.field-project-project_id{display:none;width:200px}
	<?php 	 }?>
</style>-->

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?= Yii::t('app', "Project Details") ?> <small class="m-l-sm"><?= Yii::t('app', "Enter Project Details & Description") ?></small></h5>
                        <div class="ibox-tools">
						    <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
							<!--
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-wrench"></i>
                            </a>
							
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="#">Config option 1</a>
                                </li>
                                <li><a href="#">Config option 2</a>
                                </li>
                            </ul>
							-->
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
										 <div class="project-create">
							<?= $this->render('_form', [
								'model' => $model,
							]) ?>
						
						</div>
                    </div>
                </div>

<!--
<div class="project-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
</div>
-->

<!--
<div class="panel panel-primary">
	<div class="panel-heading">
    	<h3 class="panel-title"><?= Html::encode($this->title) ?>
        </h3>
    </div>
    <div class="panel-body">
        <div class="project-create">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        
        </div>
    </div>
</div>
-->
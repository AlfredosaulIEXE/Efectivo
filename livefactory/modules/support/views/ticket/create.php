<?php
use yii\helpers\Html;
/**
 * @var yii\web\View $this
 * @var livefactory\models\Ticket $model
 */
$this->title = Yii::t('app', 'Create Ticket');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tickets'), 'url' => ['index']];
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
<style>	
.cke_contents{max-height:250px}
</style>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script>
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
	//$('.field-ticket-added_at').hide();
	//if('<?=getUserRoleCounts()>0?'ss':''?>' !='')
	//$('#ticket-ticket_customer_id').parent().hide();
	/*if('<?= isset($_GET['customer_id'])?$_GET['customer_id']:'' !=''?'ss':''?>' !='')
	$('#ticket-ticket_customer_id').parent().hide();*/

	
	$('#ticket-ticket_impact_id').change(function(){
		
		var priority = $('#ticket-ticket_priority_id :selected').val();
		var impact = $('#ticket-ticket_impact_id :selected').val();
	 if($('#ticket-ticket_priority_id').val()=='' || $('#ticket-ticket_impact_id').val()==''){
		 
	 }else{
	 $.post('index.php?r=support/ticket/ajax-ticket-sla&ticket_priority_id=' + 
              priority + '&ticket_impact_id=' + impact, 
                function(data)
				{
					if(data)
						alert(data);
				});
	 }
	})
	
	$('#ticket-ticket_priority_id').change(function(){
		
		var priority = $('#ticket-ticket_priority_id :selected').val();
		var impact = $('#ticket-ticket_impact_id :selected').val();
	if($('#ticket-ticket_priority_id').val()=='' || $('#ticket-ticket_impact_id').val()==''){
		
	}else{
	$.post('index.php?r=support/ticket/ajax-ticket-sla&ticket_priority_id=' + 
              priority + '&ticket_impact_id=' + impact, 
                  function(data)
				{
					if(data)
						alert(data);
				});
	 }
	})
	
	$('#ticket-department_id').change(function(){
	 $.post('index.php?r=support/ticket/ajax-department-queue&department_id='+$(this).val(),function(r){
		$('#ticket-queue_id').html(r) ;
	 });
			
	 $.post('index.php?r=support/ticket/ajax-ticket-category&department_id='+$(this).val(),function(r){
		$('#ticket-ticket_category_id_1').html(r) ;
	 })
   })

	 $('#ticket-queue_id').change(function(){
	  //alert('index.php?r=pmt/task/ajax-project-users&project_id='+$(this).val());
	 $.post('index.php?r=support/ticket/ajax-queue-users&queue_id='+$(this).val(),function(r){
		$('#ticket-user_assigned_id').html(r) ;
	 })
   })
	
	$('#ticket-ticket_category_id_1').change(function(){
	 $.post('index.php?r=support/ticket/ajax-category-change&ticket_category_id='+$(this).val(),function(r){
		$('#ticket-ticket_category_id_2').html(r) ;
	 })
   })
   $('#w0').submit(function(event){
		error ='';
		$('#cke_1_contents').parent().parent().removeAttr('style').next('.error').remove();
		sageLength = CKEDITOR.instances['ticket-ticket_description'].getData().replace(/<[^>]*>/gi, '').length;
		if(sageLength==0){
			Add_ErrorTag($('#cke_1_contents').parent().parent(),'<?=Yii::t ('app','This Field is Required!')?>');
		event.preventDefault();
		}
	})
});
</script>
<div class="ticket-create">
    <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?= $this->title?></h5>
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
							<?= $this->render('_form', [
								'model' => $model,
							]) ?>
                    </div>
                </div>
</div>

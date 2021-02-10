<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Task $model
 */

$this->title = Yii::t('app', 'Create Task');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
   $('#task-project_id').change(function(){
	 $.post('index.php?r=pmt/task/ajax-project-users&project_id='+$(this).val(),function(r){
		$('#task-user_assigned_id').html(r) ;
	 })
   })
   $('#w0').submit(function(event)
   {
		error ='';
		var expected_start_datetime = $('#task-expected_start_datetime').val();
		var expected_end_datetime = $('#task-expected_end_datetime').val();
		Remove_Error($('#task-expected_start_datetime-datetime'));
		Remove_Error($('#task-expected_end_datetime-datetime'));
		if(expected_end_datetime !='')
		{
			if(expected_start_datetime !='')
			{
				var expected_start_date = new Date(expected_start_datetime);
				var expected_end_date = new Date(expected_end_datetime);
				if(expected_start_date > expected_end_date)
				{
					error+=Add_Error($('#task-expected_end_datetime-datetime'),'<?=Yii::t ('app','Expected End Should be After Expected Start')?>');
				}
				else
				{
					Remove_Error($('#task-expected_end_datetime-datetime'));
				}
			}
			else
			{
				error+=Add_Error($('#task-expected_start_datetime-datetime'),'<?=Yii::t ('app','Expected Start is Required')?>');
			}
		}

		var actual_start_datetime = $('#task-actual_start_datetime').val();
		var actual_end_datetime = $('#task-actual_end_datetime').val();
		Remove_Error($('#task-actual_start_datetime-datetime'));
		Remove_Error($('#task-actual_end_datetime-datetime'));
		if(actual_end_datetime !='')
		{
			if(actual_start_datetime !='')
			{
				var actual_start_date = new Date(actual_start_datetime);
				var actual_end_date = new Date(actual_end_datetime);
				if(actual_start_date > actual_end_date)
				{
					error+=Add_Error($('#task-actual_end_datetime-datetime'),'<?=Yii::t ('app','Actual End Should be After Actual Start')?>');
				}
				else
				{
					Remove_Error($('#task-actual_end_datetime-datetime'));
				}
			}
			else
			{
				error+=Add_Error($('#task-actual_start_datetime-datetime'),'<?=Yii::t ('app','Actual Start is Required')?>');
			}
		}

				

		$('#cke_1_contents').parent().parent().removeAttr('style').next('.error').remove();
		sageLength = CKEDITOR.instances['task-task_description'].getData().replace(/<[^>]*>/gi, '').length;
		if(sageLength==0){
			Add_ErrorTag($('#cke_1_contents').parent().parent(),'<?=Yii::t ('app','This Field is Required!')?>');
			event.preventDefault();
		}

		if(error !='')
				{
					event.preventDefault();
					return false;
				}
				else
				{
					return true;
				}

	})
});
</script>


 <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?= Yii::t('app', "Task Create") ?> <small class="m-l-sm"><?= Yii::t('app', "Enter Task Name, Start Time, ETA & Description") ?></small></h5>
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



<script>
	$(document).ready(function(e) {
       /* $('#task-actual_start_datetime').val('0000-00-00 00:00:00');
		$('#task-actual_end_datetime').val('0000-00-00 00:00:00');*/
		$('.project_task').click(function(event){
			$('#cke_4_contents').parent().parent().removeAttr('style').next('.error').remove();
			sageLength = CKEDITOR.instances['task_description'].getData().replace(/<[^>]*>/gi, '').length;
			if(sageLength==0){
				Add_ErrorTag($('#cke_4_contents').parent().parent(),'<?=Yii::t ('app','This Field is Required!')?>');
			event.preventDefault();
			}
		})
    });
</script>
<div class="modal fade taskae">
  <div class="modal-dialog  modal-lg">
    <div class="modal-content">
    	 <div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">New Task</h4>
      </div>
      <div class="modal-body">
			   <?= $this->render('_form_task', [
					'taskModel' => $taskModel,
				]) ?>
		</div>
   </div>
 </div>
</div>
   

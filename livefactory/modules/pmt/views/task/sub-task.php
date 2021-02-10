<script>
	/*$(document).ready(function(e) {
        $('.subtask_insert').click(function(event){
			if($('.actual_start_datetime').val()==''){
				//$('.actual_start_datetime').val('0000-00-00 00:00:00');
			}
			if($('.actual_end_datetime').val()==''){
				//$('.actual_end_datetime').val('0000-00-00 00:00:00');
			}	
			$('#cke_4_contents').parent().parent().removeAttr('style').next('.error').remove();
			
			sageLength = CKEDITOR.instances['sub_desc'].getData().replace(/<[^>]*>/gi, '').length;
			if(sageLength==0){
				Add_ErrorTag($('#cke_4_contents').parent().parent(),'<?=Yii::t ('app','This Field is Required!')?>');
			event.preventDefault();
			}
		})
    });*/
</script>
<div class="modal fade taskae">
  <div class="modal-dialog  modal-lg">
    <div class="modal-content">
    	 <div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?=Yii::t('app','SubTask')?></h4>
      </div>
      <div class="modal-body">
			   <?= $this->render('_form_subtask', [
					'sub_task' => $sub_task,
				]) ?>
		</div>
   </div>
 </div>
</div>
   

<?php
use kartik\widgets\DateTimePicker;
?>

<script>
var errorfrm='';
$(document).ready(function(e) {
	//Defect Timing Script

	$(document).on('click','.addTiming',function(){
			
			$('#start_time').change(function(){
				$.post('index.php?r=liveobjects/timesheet/ajax&id=<?=$_REQUEST['id']; ?>&type=<?=$entity_type; ?>&start_time='+escape($(this).val())+'&eid=<?=$_REQUEST['time_entry_id']; ?>',function(result){
					Remove_Error($('#start_time'));
					if(result>0){
						Add_Error($('#start_time'),"<?=Yii::t ('app','Already have Entry of this timing')?>");
						errorfrm='yes';
						 $('[name="timingSubmit"]').attr('disabled',true);
					}else{
						Remove_Error($('#start_time'));
						 errorfrm='';
						 $('[name="timingSubmit"]').removeAttr('disabled');
					}
				})	
				
				
			$('#time_end').change(function(){
					var error='';
					var start_time = $('#start_time').val();
					var time_end = $('#time_end').val();
					var startTime = new Date(start_time);
					var endTime = new Date(time_end);
					//alert(startTime>endTime);
					Remove_Error($('#time_end'));
					
					var first_error = false
					if(startTime>endTime){
						first_error = true;
						 error+=Add_Error($('#time_end'),"<?=Yii::t ('app','Start Time Should be Less than Completion Time')?>");
						 $('[name="timingSubmit"]').attr('disabled',true);
					}else{
						first_error = false;	
					}
					$.post('index.php?r=liveobjects/timesheet/ajax-time-date-validation&start_time='+escape(start_time)+'&end_time='+escape(time_end),function(result){
						if(!first_error)
						Remove_Error($('#time_end'));
					if(result=='yes'){
						error+=Add_Error($('#time_end'),"<?=Yii::t ('app','You can not use greater than 23 hours')?>");
						 $('[name="timingSubmit"]').attr('disabled',true);
					}
				})
				if(error !=''){
					 $('[name="timingSubmit"]').attr('disabled',true);
				}else{
					Remove_Error($('#time_end'));
					if(errorfrm ==''){
					  $('[name="timingSubmit"]').removeAttr('disabled');
					}
				}
			})
		/*	$('#timingSubmit').click(function(event){
								alert("Ashish3");

					var start_time = $('#start_time').val();
					var time_end = $('#time_end').val();
					var startTime = new Date(start_time);
					var endTime = new Date(time_end);
					var nowD = new Date();
					Remove_Error($('#time_end'));
					Remove_Error($('#start_time'));
					if(startTime > nowD){
						Add_Error($('#start_time'),'<?=Yii::t ('app','You Can not use Future Time')?>');
						event.preventDefault();
					}else{
						if(startTime>endTime){
							 Add_Error($('#time_end'),'<?=Yii::t ('app','Start Time Should be Less than Completion Time')?>');
							 event.preventDefault();
						}else{
							Remove_Error($('#time_end'));
						}
					}
					if(endTime > nowD){
						Add_Error($('#time_end'),'<?=Yii::t ('app','You can not use Future Time')?>');
						event.preventDefault();
					}else{
						if(startTime>endTime){
							 Add_Error($('#time_end'),'<?=Yii::t ('app','Start Time Should be Less than Completion Time')?>');
							 event.preventDefault();
						}else{
							Remove_Error($('#time_end'));
						}
					}
			})*/
		});
		 
		$('#timingSubmit').click(function(event){
			var error='';
		//	$('[data-validation="required"]').each(function(index, element) {
				//alert($(this).attr('id'));
				Remove_Error($('#start_time'));
				if($('#start_time').val() == ''){
				error+=Add_ErrorTag($('#start_time').parent(),"<?=Yii::t ('app','This Field is Required!')?>");

				}else{
						Remove_Error($('#start_time'));							
				}

				Remove_Error($('#time_end'));
				if($('#time_end').val() == ''){
				error+=Add_ErrorTag($('#time_end').parent(),"<?=Yii::t ('app','This Field is Required!')?>");

				}else{
						Remove_Error($('#time_end'));							
				}
			//});
			$('#time_notes').parent().removeAttr('style').next('.error').remove();
			
			sageLength = CKEDITOR.instances['time_notes'].getData().replace(/<[^>]*>/gi, '').length;
			if(sageLength==0){
				error+=Add_ErrorTag($('#time_notes').parent(),"<?=Yii::t ('app','This Field is Required!')?>");
			event.preventDefault();
			}
			
			
			if(error==''){
				return;	
			}else{
				event.preventDefault();
			}
		})
	})
});
	
</script>
<div class="modal fade timing">
  <div class="modal-dialog  modal-lg">
    <div class="modal-content">
    	 <div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?=Yii::t('app','Add Timing')?></h4>
        
      </div>
      <div class="modal-body">
			  <form  action="" method="post" enctype="multipart/form-data">
              <?php Yii::$app->request->enableCsrfValidation = true; ?>
    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
    		<?php if($_GET['time_entry_id']){?>
            	<input type="hidden" name="timing_edit" value="true">
            <?php }else{?>
    			<input type="hidden" name="timing_add" value="true">
            <?php } ?>

			<?php
				date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
				if($timeEntryModel->start_time != '')
				{
					$sTime = date('Y-m-d H:i:s', $timeEntryModel->start_time);
				}
				else
				{
					$sTime = '';
				}

				if($timeEntryModel->end_time != '')
				{
					$eTime = date('Y-m-d H:i:s', $timeEntryModel->end_time);
				}
				else
				{
					$eTime = '';
				}

			?>
        <div class="row">
         	 <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="name"><?=Yii::t('app','Start Time')?>:*</label>
                    <?= DateTimePicker::widget([
					'name' => 'start_time',
					'id' => 'start_time',
					'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
					'value' => $sTime,
					'pluginOptions' => [
						'autoclose'=>true,
						'format' => 'yyyy-mm-dd hh:ii:ss'
					],
					'readonly' => true,
				]);?>
               		<span class="help-block"></span>
                </div>
            </div>
			 <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label"><?=Yii::t('app','Stop Time')?>:*</label>
                   
					<?= DateTimePicker::widget([
					'name' => 'end_time',
					'id' => 'time_end',
					'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
					'value' => $eTime,
					'pluginOptions' => [
						'autoclose'=>true,
						'format' => 'yyyy-mm-dd hh:ii:ss'
					],
					'readonly' => true,

				]);?>
              
               		<span class="help-block"></span>
                </div>
            </div>
		</div>
        <div class="row">
                <div class="col-sm-12">
					<div class="form-group">
                    <label class="control-label"><?=Yii::t('app','Notes')?>:

                    </label>
                    <div class="controls">
                      <textarea class="form-control input-sm ckeditor" name="notes" id="time_notes" rows="8" style="width:100%"><?=$timeEntryModel->notes?></textarea>
                    </div>
                </div>
				</div>
			</div>
<br/>
<div class="form-actions">
		<button class="btn btn-primary" type="submit" name="timingSubmit" id="timingSubmit" value="timingSubmit"/><i class="fa fa-floppy-o"></i> <?=Yii::t('app','Save')?></button>
</div>
</form>

		</div>
   </div>
 </div>
</div>
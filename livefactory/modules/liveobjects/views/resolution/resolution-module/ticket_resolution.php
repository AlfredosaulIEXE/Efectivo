
<style type="text/css">
.modal-dialog{
    width:80% !important;
}
</style>

<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script>
function addError(obj,error){
	$(obj).parent().addClass('has-error');
	$(obj).next('.help-block').text(error);
}

function removeError(obj){
	$(obj).parent().removeClass('has-error');
	$(obj).next('.help-block').text('');
}

$(function(){
	$('#submit_resolution').click(function()
	{
		var error='';

		if($('#subject').val()=='')
		{
			addError($('#ls'),'<?=Yii::t ('app','This Field is Required!')?>');
			error='error';
		}
		else
		{
			removeError($('#ls'));
		}

		sageLength = CKEDITOR.instances['resolution'].getData().replace(/<[^>]*>/gi, '').length;
		
		if(sageLength == 0)
		{
			addError($('#ln'),'<?=Yii::t ('app','This Field is Required!')?>');
			error='error';
		}
		else
		{
			removeError($('#ln'));
		}

		if(error ==''){
			return true;
		}else{
			return false;
		}
	});
});

</script>

<!-- Modal -->
<div class="modal fade" id="myresolution" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Ticket Resolution</h4>
      </div>
      <form method="post"class="form-horizontal" role="form" name="resfrm" enctype="multipart/form-data">
      <?php Yii::$app->request->enableCsrfValidation = true; ?>
      <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
      <div class="modal-body">        
            <input type="hidden" name="ticket_id" value="<?= $model->id?>"/>
            <input type="hidden" name="queue_id" value="<?= $model->queue_id?>"/>
            <input type="hidden" name="queue_id" value="<?= $model->queue_id?>"/>            
            <div class="form-group">
              <label class="col-sm-2 control-label" id="ls" for="lead_source"><?=Yii::t('app', 'Subject')?>:<font color="#FF0000">*</font></label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="subject" name="subject"/>  
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" id="ln" for="lead_source"><?=Yii::t('app', 'Resolution')?>:<font color="#FF0000">*</font></label>
              <div class="col-sm-8">
                <textarea class="form-control ckeditor"  name="resolution" id="resolution" rows=6 ></textarea> <span class="help-block"></span> 
				<!--<textarea class="form-control"  name="resolution" id="resolution" rows=6 ></textarea> <span class="help-block"></span> -->
              </div>
            </div> 
           <!-- <div class="form-group">
              <label class="col-sm-2 control-label" for="lead_source"><?=Yii::t('app', 'Attachment ')?>:<font color="#FF0000">*</font></label>
              <div class="col-sm-8">
                <input type="file" name="res_image" class="form-control" />
              </div>
            </div>    -->    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="submit_resolution">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>

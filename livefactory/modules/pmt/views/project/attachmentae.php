<?php
use livefactory\modules\pmt\controllers\ProjectController;
$loggedUser=ProjectController::getLoggedUserDetail();
?>
<div class="modal fade bs-example-modal-lg savepopup">
<script>
$(function(){
		$('#newatt').click(function(){
			if($('#f_title').val()==''){
				addError($('#f_title'),'Title');
				return false;
			}else{
				removeError($('#f_title'));
				return true;
			}
		})
})
</script>
<form method="post" name="newatt" action=""  enctype="multipart/form-data">
<input type="hidden" name="entity_id" value="<?=$_REQUEST['id']?>">
<input type="hidden" name="add_attach" value="yes">
<?php Yii::$app->request->enableCsrfValidation = true; ?>
    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">New Attachment</h4>
      </div>
      <div class="modal-body">
      		<div class="form-group">
            	<label>Title</label>
        		<input type="text" name="filetitle" id="f_title"  class="form-control">
                <span class="help-block"></span>
            </div>
            <div class="form-group">
            	<label>Attachment</label>
            	<input type="file" name="attach" class="form-control">
            </div>
      </div>
      <div class="modal-footer">
      	<button type="submit" class="btn btn-primary" id="newatt" ><i class="fa fa-upload"></i> Upload</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</form>
</div><!-- /.New Attachment modal -->
<!--///////////////////////////////////////////-->
<div class="modal fade bs-example-modal-lg popup">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Change Attachment</h4>
      </div>
      		<form method="post" action=""  enctype="multipart/form-data">
            <?php Yii::$app->request->enableCsrfValidation = true; ?>
    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
    		  <input type="hidden" name="entity_id" value="<?=$_REQUEST['id']?>">
        	  <input type="hidden" value="<?=$attachModel->id?>" id="attId" name="att_id">
              <input type="hidden" value="true"  name="edit_attach">
              <div class="modal-body">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" value="<?=$attachModel->file_title?>" name="filetitle" id="attTitle" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Attachment</label>
                        <input type="file" name="attach" class="form-control">
                    </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Upload</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
              </div>
        </form>
        
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.Change Attachment modal -->
<!--//////////////////////////////////////////////////////-->
<div class="modal fade bs-example-modal-lg email">
<script>
function addError(obj,field){
	$(obj).parent().addClass('has-error');
	$(obj).next('.help-block').text(field+' is Required!');
}
function removeError(obj){
	$(obj).parent().removeClass('has-error');
	$(obj).next('.help-block').text('');
}
$(function(){
		$('#send_email').click(function(){
			var error='';
			if($('#uemail').val()==''){
				addError($('#uemail'),'To');
				error='error';
			}else{
				removeError($('#uemail'));
			}
			////////// Subject///////////
			if($('#esubject').val()==''){
				addError($('#esubject'),'Subject');
				error='error';
			}else{
				removeError($('#esubject'));
			}
			//////////////Body///////////
			if($('#ebody').val()==''){
				addError($('#ebody'),'Body');
				error='error';
			}else{
				removeError($('#ebody'));
			}
			if(error ==''){

				return true;
			}else{
				return false;
			}
		})
})
</script>
<form method="post" action=""  enctype="multipart/form-data">
<?php Yii::$app->request->enableCsrfValidation = true; ?>
    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
    <input type="hidden" name="send_attachment_file" value="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?=Yii::t('app', 'Send Mail')?></h4> (<i><?=Yii::t('app', 'Multi Email bind with \',\' Separator')?></i>)
      </div>
      <div class="modal-body">
      		<input type="hidden" name="filename" id="filename">
            <input type="hidden" name="attname" id="attname">
            
            <div class="form-group">
            	<label>To</label>
            	<input type="text" name="uemail" id="uemail" value="<?=ProjectController::getUserEmail($model->project_owner)?>" class="form-control" >
                <span class="help-block"></span>
            </div>
            <div class="form-group">
            	<label>CC</label>
            	<input type="text" name="cc" class="form-control" >
            </div>
            <div class="form-group">
            <label>Subject</label>
        	<input type="text" name="subject" class="form-control" value="<?php echo $loggedUser['first_name']." ".$loggedUser['last_name']." sent you a file"; ?>" id="esubject" >
            <span class="help-block"></span>
            </div>
            <div class="form-group">
            	<label>Body</label>
            	<textarea class="form-control" name="email_body" rows="8" id="ebody"></textarea>
                <span class="help-block"></span>
            </div>
      </div>
      <div class="modal-footer">
      	<button type="submit" class="btn btn-primary" id="send_email">
        	<i class="fa fa-envelope"></i> Send Email</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</form>
</div><!-- /.modal -->
<script>

function sendAttachment(attname,name){
			$('#attname').val(attname);
			$('#filename').val(name);
			//$('#uemail').val(uemail);

			var mailBody="Hello,\n\n"
			
				+ "<?php echo $loggedUser['first_name']." ".$loggedUser['last_name'] ?> sent you this file, click on the below link to view the file\n"
				
				+ "<a href='<?=str_replace('web/index.php','',$baseUrl)?>attachments/"+name+"'>"+attname+"</a> ";

			$("#ebody").html(mailBody);
			$('.email').modal('show');
}
function changeAttach(id,name){
			$('#attId').val(id);
			$('#attTitle').val(name);
			$('.popup').modal('show');
}
</script>

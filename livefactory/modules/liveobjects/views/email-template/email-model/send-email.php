

<div class="modal fade sendEmail">

<script>

$(function(){

		$('#sendemail').click(function(){

			var error='';

			if($('#toemail').val()==''){

				addError($('#toemail'),'To is Required');

				error='error';

			}else{

				removeError($('#toemail'));

			}

			////////// Subject///////////

			if($('#subject1').val()==''){

				addError($('#subject1'),'Subject is Required');

				error='error';

			}else{

				removeError($('#subject1'));

			}

			//////////////Body///////////

			if($('#body1').val()==''){

				addError($('#body1'),'Body');

				error='error';

			}else{

				removeError($('#body1'));

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

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

        <h4 class="modal-title"><?=Yii::t('app', 'Send Email')?></h4> (<i><?=Yii::t('app', 'Multi Email bind with \',\' Separator')?></i>)

      </div>

      <div class="modal-body">

        	<div class="form-group">

            	<label><?=Yii::t('app', 'To')?></label>

            	<input type="text" id="toemail" name="toemail"  class="form-control" value="<?=$email?>" />

                <span class="help-block"></span>

            </div>

            <div class="form-group">

            	<label>CC</label>

            	<input type="text" name="cc" class="form-control" >

            </div>

            <div class="form-group">

                <label><?=Yii::t('app', 'Subject')?></label>

                <input type="text" name="subject" id="subject1" class="form-control" value="">

                <span class="help-block"></span>

            </div>

            <div class="form-group">

            	<label><?=Yii::t('app', 'Body')?></label>

            	<textarea class="form-control" name="sendemaildesc"  id="body1" rows="8"></textarea>

                <span class="help-block"></span>

            </div>

      </div>

      <div class="modal-footer">

      	<button type="submit" class="btn btn-primary" id="sendemail">

        <i class="fa fa-envelope"></i> <?=Yii::t('app', 'Send Email')?></button>

        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i> <?=Yii::t('app', 'Close')?></button>

      </div>

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</form>

</div><!-- /.modal --> 
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
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


$('#w0').submit(function(event){
		error ='';
	var date_created = $('#invoice-date_created').val();
	var date_due = $('#invoice-date_due').val();
	if(date_created !='' && date_due !=''){
		var date_created_time = Date.parse(date_created);
		var date_due_time = Date.parse(date_due);
		
		Remove_Error($('#invoice-date_due'));
		if(parseInt(date_created_time) > parseInt(date_due_time)){
	
			 error+=Add_Error($('#invoice-date_due'),'<?=Yii::t ('app','Due Date Should be more than or equal to Create Date')?>');
	
		}else{
	
			Remove_Error($('#invoice-date_due'));
	
		}
				if(error !=''){
					event.preventDefault();
					return false;
				}else{
					return true;
				}
	}
  
		 })

})
</script>

<?php

use yii\helpers\Html;
use livefactory\models\InvoiceStatus;

/**
 * @var yii\web\View $this
 * @var livefactory\models\Invoice $model
 */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Invoice',
]) . ' ' . $model->invoice_number;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', ucfirst($model->customer->customer_name)), 'url' => ['/customer/customer/customer-view','id'=>$model->customer_id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Invoice'), 'url' => ['index']];
include_once('script.php');
?>
<div id="temp"></div>
<script>
$(document).ready(function(e) {
	//$('#temp').load("index.php?r=invoice/invoice/view-pdf&id=<?=$model->id?>");
	//$('#invoice-date_created-disp').val($('#invoice-date_created').val()=='0000-00-00 00:00:00'?'':'<?=date('Y/m/d',strtotime($model->date_created))?>');
	//$('#invoice-date_due-disp').val($('#invoice-date_due').val()=='0000-00-00 00:00:00'?'':'<?=date('Y/m/d',strtotime($model->date_due))?>');
});
</script>
<div class="invoice-update">
<div class="ibox float-e-margins">

                    <div class="ibox-title">

                        <h5><?= Html::encode($this->title) ?></h5>

                        <div class="ibox-tools">
							<?php
								if ( $model->invoice_status_id != InvoiceStatus::_NEW)
								{
							?>
								<a class="btn btn-xs btn-primary" href="index.php?r=invoice/invoice/view&id=<?=$model->id?>">

									<i class="fa fa-check"></i> <?= Yii::t('app','View')?>

								</a>
							<?php
								}
							?>

						    <a class="btn btn-xs btn-primary" href="index.php?r=invoice/invoice/duplicate-invoice&id=<?= $model->id?>">

                                <i class="fa fa-copy"></i> <?= Yii::t('app','Duplicate')?>

                            </a>
                           <!-- <a class="btn btn-xs btn-primary emailShow" href="javascript:void(0)" onClick="$('.email').modal('show')">

                                <i class="fa fa-envelope"></i> <?= Yii::t('app','Email')?>

                            </a>-->
							<!--
                            <a class="btn btn-xs btn-primary" href="index.php?r=invoice/invoice/update&id=<?=$_GET['id']?>&invoice=true">
                                <i class="fa fa-envelope"></i> <?= Yii::t('app','Generate Invoice')?>
                            </a>-->
                            
                            <a class="collapse-link">

                                <i class="fa fa-chevron-up"></i>

                            </a>

                            <a class="close-link">

                                <i class="fa fa-times"></i>

                            </a>

                        </div>

                    </div>

                    <div class="ibox-content">

							 <?= $this->render('_form', [
								'model' => $model,
								'taxList'=>$taxList,
								'InvoiceDetails'=>$invoiceDetails,
								'jSonList'=>$jSonList,
							]) ?>

                    </div>

                </div>
</div>
<!--//////////////////////////////////////////////////////-->
<style>
.ui-autocomplete{z-index:99999 !important}
</style>
<script>
function customerAutoComplate(){
	$( ".uemail,.cc" ).autocomplete({
	  source: <?=$cusjSonList?>,
	  minLength: 0,
	  focus: function( event, ui ) {
		$(this).val( ui.item.email );
		return false;
	},
	  select: function( event, ui ) {
		$(this).val( ui.item.email );
		 return false;
	  }
	}).autocomplete( "instance" )._renderItem = function( ul, item ) {
	return $( "<li>" )
	.append( "<a>" + item.value+ "</a>" )
	.appendTo( ul );
	};	
}
function addError(obj,error){

	$(obj).parent().addClass('has-error');

	$(obj).next('.help-block').text(error);

}

function removeError(obj){

	$(obj).parent().removeClass('has-error');

	$(obj).next('.help-block').text('');

}

$(function(){
	$(document).on('click','.emailShow',function(){
		customerAutoComplate();
	})
	customerAutoComplate();
		$('.addcc').click(function(){
			$('.cc-box').append('<div class="form-group"><input type="text" name="cc[]" class="cc form-control"></div>');
			customerAutoComplate();
			$('.cc').each(function(index, element) {
				if($(this).val() != ''){
					var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
					if(!re.test($(this).val())){
						addError($(this),'<?=Yii::t ('app','Email Not Valid!')?>');
						error='error';
					}else{
						removeError($(this));
					}
				}
            });
		})
		$('#send_email').click(function(){

			var error='';

			if($('#uemail').val()==''){

				addError($('#uemail'),'<?=Yii::t ('app','This Field is Required!')?>');

				error='error';

			}else{
				var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
      			if(!re.test($('#uemail').val())){
					addError($('#uemail'),'<?=Yii::t ('app','Email Not Valid!')?>');
					error='error';
				}else{
					removeError($('#uemail'));
				}

			}
			$('.cc').each(function(index, element) {
				removeError($(this));
				if($(this).val() != ''){
					var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
					if(!re.test($(this).val())){
						addError($(this),'<?=Yii::t ('app','Email Not Valid!')?>');
						error='error';
					}else{
						removeError($(this));
					}
				}
            });
			

			////////// Subject///////////

			if($('#esubject').val()==''){

				addError($('#esubject'),'<?=Yii::t ('app','This Field is Required!')?>');

				error='error';

			}else{

				removeError($('#esubject'));

			}

			//////////////Body///////////

			if($('#ebody').val()==''){

				addError($('#ebody'),'<?=Yii::t ('app','This Field is Required!')?>');

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
<div class="modal email">



<form method="post" action=""  enctype="multipart/form-data">

<?php Yii::$app->request->enableCsrfValidation = true; ?>

    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

        <h4 class="modal-title"><?=Yii::t('app', 'Send Mail')?></h4>

      </div>

      <div class="modal-body">

            

            <div class="form-group">

            	<label><?=Yii::t('app', 'To')?></label>

            	<input type="text" name="uemail" id="uemail"  value="" class="form-control uemail" >

                <span class="help-block"></span>

            </div>

            <div class="form-group cc-box">

            	<label><?=Yii::t('app', 'CC')?> <button type="button" class="btn btn-xs btn-primary addcc"><i class="fa fa-plus"></i></button></label>
				<div class="form-group">
            	<input type="text" name="cc[]" class="cc form-control" value="<?= $model->customer->email?>" readonly >
                </div>

            </div>

            <div class="form-group">

            <label><?=Yii::t('app', 'Subject')?></label>

        	<input type="text" name="subject" class="form-control" value="Your <?php echo $model->invoice_number ?> from <?php echo Yii::$app->params['company']['company_name']; ?>" readonly id="esubject" >

            <span class="help-block"></span>

            </div>

            <div class="form-group">

            	<label><?=Yii::t('app', 'Body')?></label>

            	<textarea class="form-control" name="email_body" rows="8" id="ebody"></textarea>

                <span class="help-block"></span>

            </div>

      </div>

      <div class="modal-footer">

      	<button type="submit" class="btn btn-primary btn-sm" id="send_email">

        	<i class="fa fa-envelope"></i> <?=Yii::t('app', 'Send Email')?></button>

        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>

      </div>

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</form>

</div><!-- /.modal -->


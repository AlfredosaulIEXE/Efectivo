<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use livefactory\models\search\PaymentDetails as PaymentDetailsSearch; 
use livefactory\models\InvoiceStatus;

/**
 * @var yii\web\View $this
 * @var livefactory\models\Invoice $model
 */
include_once('script.php');

$this->title = 	$model->invoice_number;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', ucfirst($model->customer->customer_name)), 'url' => ['/customer/customer/customer-view','id'=>$model->customer_id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Invoice'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.ui-autocomplete{z-index:99999 !important}
</style>
<div id="temp"></div>
<script>
$(document).ready(function(e) {
	$('#temp').load("index.php?r=invoice/invoice/view-pdf&id=<?=$model->id?>");
});
</script>
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
		});

		$('#ppcheckoutbtn').click(function()
		{
			var error='';
			<?php
				$remaining=floatval($model->grand_total) - floatval($model->total_paid);
			?>

			////////// Subject///////////
			if($('#amount_1').val()=='')
			{
				addError($('#amount_1'),'<?=Yii::t ('app','This Field is Required!')?>');
				error='error';
			}
			else if($('#amount_1').val() == '0')
			{
				addError($('#amount_1'),'<?=Yii::t ('app','Can not use 0!')?>');
				error='error';
			}
			else if($('#amount_1').val() > <?=$remaining?>)
			{
				addError($('#amount_1'),'<?=Yii::t ('app','Remaining payment is only: '.$remaining)?>');
				error='error';
			}
			else
			{
				removeError($('#amount_1'));
			}

			if(error ==''){
				return true;
			}else{
				return false;
			}
		});

		$('[data-valid-num="required"]').keyup(function(key){
			$('#amount_1').val() = "";
			
		})
})
</script>

<div class="invoice-view">
		
   <div class="row">

            <div class="col-lg-12">
                <div class="wrapper wrapper-content animated fadeInRight">
									<div class="ibox-title">

					<h5><?= Html::encode($this->title) ?></h5>

					<div class="ibox-tools">

					<?php
					if(Yii::$app->user->identity->userType->type!="Customer")
					{
						if ( $model->invoice_status_id == InvoiceStatus::_UNPAID)
						{
					?>
						<a class="btn btn-xs btn-primary" href="index.php?r=invoice/invoice/update&id=<?=$model->id?>&edit=t">

							<i class="fa fa-edit"></i> <?= Yii::t('app','Edit')?>

						</a>
					<?php
						}
					?>

					<?php
						if ( floatval($model->total_paid) > 0)
						{
					?>
						<a class="btn btn-xs btn-primary" href="javascript:void(0)" onClick="$('.showhistory').modal('show')">

							<i class="fa fa-list"></i> <?= Yii::t('app','Payment History')?>

						</a>
					<?php
						}
					?>
					
						<?= Html::a('<i class="fa fa-file-pdf-o"></i> '.Yii::t('app', 'Download Invoice'), ['/invoice/invoice/download','id'=>$model->id], ['class'=>'btn btn-primary btn-xs']) ?>

						<a class="btn btn-xs btn-primary" href="index.php?r=invoice/invoice/duplicate-invoice&id=<?= $model->id?>">

							<i class="fa fa-copy"></i> <?= Yii::t('app','Duplicate')?>

						</a>
						<a class="btn btn-xs btn-primary emailShow" href="javascript:void(0)" onClick="$('.email').modal('show')">

							<i class="fa fa-envelope"></i> <?= Yii::t('app','Email')?>

						</a>
					<?php
					}
					?>
						
						

					</div>

				</div>

                    <div class="ibox-content p-xl">
						

								
								<div class="row">
										<div class="col-sm-4">
											<?php
											if(Yii::$app->params['COMPANY_LOGO_ON_INVOICE'] == 'Yes')
											{
											?>
											<img width="140px" src="../logo/logo.png" class="img-responsive upload_logo">
											<?php
											}
											?>						
										</div>
										<div class="col-sm-8 text-right">
										<address style="margin-bottom:0px;">
											<strong class="text-navy"><?php echo Yii::t('app', 'Invoice Number'); ?>: </strong><?=$model->invoice_number?> <br>
											<?php
												date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
												$create_date=date('M d, Y', $model->date_created);
											?>
											<strong><?php echo Yii::t('app', 'Invoice Date'); ?>: </strong><?=$create_date?><br>
											<strong><?php echo Yii::t('app', 'Status'); ?>: <strong class="text-navy" <?=($model->invoice_status_id!==InvoiceStatus::_PAID?"style='color: red;'":'')?>><?=$model->invoiceStatus->label?></strong></strong><br>

											<?php
											if($model->po_number)
											{
											?>
											<strong><?php echo Yii::t('app', 'PO Number'); ?>: </strong> <?=$model->po_number?><br>
											<?php
											}
											?>
											
											<?php
											if(Yii::$app->params['TAX_NUMBER_ON_INVOICE'] == 'Yes')
											{
											?>
											<strong><?php echo Yii::t('app', 'Tax Number'); ?>: </strong><?=$model->tax_number?><br>
											<?php
											}
											?>
										</address>
										</div>
							</div>
							

						

						
						 
						<hr style="margin-bottom:10px;margin-top:10px;">
							
							
                            <div class="row" >

                                <div class="col-sm-8">

                                    <span><?php echo Yii::t('app', 'From'); ?>:</span>

                                    <address style="margin-bottom:0px;">

                                        <strong> <?= Yii::$app->params['company']['company_name']?></strong><br>

                                        <?= Yii::$app->params['address']['address_1']?>  , <?= Yii::$app->params['address']['address_2']?><br>

                                        <?= Yii::$app->params['address']['city']?>, <?= Yii::$app->params['address']['state']?>, <?= Yii::$app->params['address']['country']?><br>

                                        <abbr title="Phone"><?php echo Yii::t('app', 'Phone'); ?>:</abbr> <?= Yii::$app->params['company']['phone']?>

                                    </address>
								<br/><br/><h5><?php echo Yii::t('app', 'Currency'); ?>: <?=$model->currency->currency?> (<?=$model->currency->alphabetic_code?>)</h5>

                                </div>



                                <div class="col-sm-4 text-right">

                                    
                                    <span><?php echo Yii::t('app', 'To'); ?>:</span>

                                    <address style="margin-bottom:0px;">

                                        <strong><?=$model->customer->customer_name?></strong><br>

                                        <?=$office_address->address_1?>, <?=$office_address->address_2?><br>

                                        <?=$office_address->country->country?>, <?=$office_address->state->state?>, <?=$office_address->city->city?><br>

                                        <abbr title="Phone"><?php echo Yii::t('app', 'Phone'); ?>: </abbr> <?=$model->customer->phone?>

                                    </address>

                                   

                                </div>

                            </div>



                            <div class="table-responsive m-t">

                                <table class="table invoice-table">

                                    <thead>

                                    <tr>

                                        <th><?php echo Yii::t('app', 'Item List'); ?></th>

                                        <th><?php echo Yii::t('app', 'Quantity'); ?></th>

                                        <th><?php echo Yii::t('app', 'Unit Price'); ?></th>

                                        <th><?php echo Yii::t('app', 'Tax'); ?></th>

                                        <th><?php echo Yii::t('app', 'Total Price'); ?></th>

                                    </tr>

                                    </thead>

                                    <tbody>

                                    <?php 
										if(count($invoiceDetails) > 0){
											foreach($invoiceDetails as $detail){
									?>
                                    <tr>
                                        <td><?=$detail['product_description']?></td>
                                        <td><?=$detail['quantity']?></td>
                                        <td><?=$detail['rate']?></td>
                                        <td><?="<i style='font-size:10px'>".$detail['tax']['name'].($detail['tax']['name']!==null?'  (':'').$detail['tax']['tax_percentage'].($detail['tax']['name']!==null?' %)':'').' </i>'.$detail['tax_amount']?></td>	
                                        <td align="right"><?=$detail['total']?></td>
                                    </tr>
									<?php
											}
										}
									?>



                                    </tbody>

                                </table>

                            </div><!-- /table-responsive -->



                            <table class="table invoice-total">

                                <tbody>

								<tr>
									<td rowspan=8>
										<div class="col-sm-12">
											<?php
											if(Yii::$app->params['COMPANY_SEAL_ON_INVOICE'] == 'Yes')
											{
											?>
											
													<img width="140px" src="../logo/seal.png" class="img-responsive">
										
											<?php
											}
											?>
										</div>
									</td>
								</tr>

								 <tr>

                                    <td><strong><?= Yii::t('app','Total Tax')?> : </strong></td>

                                    <td><?=$model->total_tax_amount?></td>

                                </tr>

								<tr>

                                    <td><strong><?php echo Yii::t('app', 'Sub Total'); ?> : </strong></td>

                                    <td><?=$model->sub_total?></td>

                                </tr>

							
								
									<tr>

                                    <td><strong><?php echo Yii::t('app', 'Discount'); ?> : </strong></td>

                                    <td><?=$model->discount_amount?></td>

                                </tr>

                                <tr>

                                    <td><strong><?php echo Yii::t('app', 'TOTAL'); ?> : </strong></td>

                                    <td><?=$model->currency->alphabetic_code?> <?=$model->grand_total?></td>

                                </tr>

								<tr>
									<td><strong><?php echo Yii::t('app', 'Paid'); ?> : </strong></td>
                                    <td><?=$model->total_paid?></td>
                                </tr>
								 <tr>
									<td><strong><?php echo Yii::t('app', 'Outstanding'); ?> : </strong></td>
                                    <td><?=floatval($model->grand_total)-floatval($model->total_paid)?></td>
                                </tr>

                                </tbody>

                            </table>
							<?php
								if($model->invoice_status_id != InvoiceStatus::_PAID && $model->invoice_status_id != InvoiceStatus::_CANCELLED) // Paid and Canceled
								{
								?>
									<div class='text-right'>
										
										
								<?php
										if($model->invoice_status_id == InvoiceStatus::_UNPAID && Yii::$app->user->identity->userType->type!="Customer") //Unpaid
										{

								?>
											<a href="index.php?r=invoice/invoice/update&id=<?=$model->id?>&edit=cancel" class="btn btn-danger btn-medium" onClick="return confirm('<?=Yii::t ('app','Are you Sure!')?>')"> <?= Yii::t('app','Cancel Invoice')?></a>
								
								
								<?php
										}
								?>

										<a href="javascript:void(0)" class="btn btn-primary btn-medium paymentshow" onClick="$('.makepayment').modal('show')"> <?= Yii::t('app','Make Payment')?></a>
									</div>
								<?php
								}
					
							?>

							

                        </div>

						


                </div>
				
            </div>

        </div>
		
</div>



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

<!-- Below section need to be deleted - not in use -->
<div class="modal makepayment_to_be_deleted">
	<form method="post" action=""  enctype="multipart/form-data">
	<?php Yii::$app->request->enableCsrfValidation = true; ?>
		<input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">

	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=Yii::t('app', 'Make Payment')?></h4>
		  </div>

		  <div class="modal-body">
				<div class="form-group">
					<label><?=Yii::t('app', 'Payment Amount')?></label>
					<input type="text" name="ipayment" id="ipayment"  value="" class="form-control ipayment" data-valid-num="required">
					<span class="help-block"></span>
				</div>

				<div class="form-group">
					<label><?=Yii::t('app', 'Notes')?></label>
					<textarea class="form-control" name="inotes" rows="4" id="inotes"></textarea>
					<span class="help-block"></span>
				</div>
		  </div>

		  <div class="modal-footer">
			<button type="submit" class="btn btn-primary btn-sm" id="make_payment">
				<i class="fa fa-dollar"></i> <?=Yii::t('app', 'Make Payment')?></button>
			<button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
		  </div>

		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</form>
</div><!-- /.modal -->
<!-- Above section need to be deleted - not in use -->

<div class="modal showhistory">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=Yii::t('app', 'Payment History')?></h4>
		  </div>

		  <div class="modal-body">
				 <?= $this->render('../payment-details/index', [
												'model' => $model,
												'dataProvider' => (new PaymentDetailsSearch)->searchWithInvoiceID($model->id),
											]) 
				?>
		  </div>

		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal makepayment">
	<form id = "paypal_checkout" action = "index.php?r=invoice/invoice/paypal-payment&id=<?=$model->id?>" method = "post">
	<?php Yii::$app->request->enableCsrfValidation = true; ?>
		<input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">

	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=Yii::t('app', 'Make Payment')?></h4>
		  </div>

		  <div class="modal-body">
				<div class="form-group">
				 
					<div id = "item_1" class = "itemwrap">
					
						<label><?=Yii::t('app', 'Payment Amount')?></label>
							<input name = "amount_1" id = "amount_1" value = "" class="form-control amount_1" data-valid-num="required">
						<span class="help-block"></span>
						
					</div>
				</div>

				<div class="form-group">
					<label><?=Yii::t('app', 'Notes')?></label>
					<textarea class="form-control" name="inotes" rows="4" id="inotes"></textarea>
					<span class="help-block"></span>
				</div>
		  </div>

		  <div class="modal-footer">
			<button type="submit" class="btn btn-primary btn-sm" id="ppcheckoutbtn">
				<i class="fa fa-dollar"></i> <?=Yii::t('app', 'Make Payment')?></button>
			<button type="submit" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> <?=Yii::t('app', 'Close')?></button>
		  </div>

		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</form>
</div><!-- /.modal -->
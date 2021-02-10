<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\DatePicker;
use yii\helpers\ArrayHelper;
use livefactory\models\Customer;
use livefactory\models\Currency;
use livefactory\models\DiscountType;
use dosamigos\ckeditor\CKEditor;
use livefactory\models\InvoiceStatus;

/**
 * @var yii\web\View $this
 * @var livefactory\models\Invoice $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<style>
table .form-group{margin:0}
</style>
<div class="invoice-form">

    <?php 
	if ($_REQUEST['r'] == 'invoice/invoice/update')
		$dFlag = true;
	else
		$dFlag = false;
	
	date_default_timezone_set(Yii::$app->params['TIME_ZONE']);


	if ($model->date_created != '')
	{
		$model->date_created=date('Y-m-d', $model->date_created);
	}
	else
	{
		$model->date_created=date('Y-m-d');
	}

	
	if ($model->date_due != '')
	{
		$model->date_due=date('Y-m-d', $model->date_due);
	}

	$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 4,
    'attributes' => [
		//'invoice_number'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Invoice Code...', 'maxlength'=>255]], 
		//'invoice_status_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['value'=>$status, 'disabled'=>true]], 

		'customer_id'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'options'=>['prompt' => '--'.Yii::t ( 'app', 'Customer' ).'--',],'items'=>ArrayHelper::map(Customer::find()->orderBy('customer_name')->all(),'id','customer_name')], 
		
		'po_number'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter PO Number...', 'maxlength'=>255]], 
		
		'currency_id'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'options'=>['prompt' => '--'.Yii::t ( 'app', 'Currency' ).'--', 'value' => \livefactory\models\DefaultValueModule::getDefaultValueId('currency'), ],'items'=>ArrayHelper::map(Currency::find()->orderBy('currency')->all(),'id','currency')], 

		'invoice_status_id' => [
			'type'=> Form::INPUT_DROPDOWN_LIST, 
			//'type'=> Form::INPUT_TEXT, 
			'options'=>[
				'prompt' => '--'.Yii::t ( 'app', 'Invoice Status' ).'--',
				'value' => $model->invoice_status_id,	// Default value is New
				'disabled'=>true,
			],
			'items'=>ArrayHelper::map(InvoiceStatus::find()->all(),'id','label')
		],

		
		'date_created'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DatePicker::classname(),
							'options'=>[
										'type'=>DatePicker::TYPE_COMPONENT_PREPEND,
										'pluginOptions' => [
															  'autoclose'=>true,
															  'format' => 'yyyy-mm-dd',
															  'todayHighlight' => true,
															  'endDate' => '0d'
														],
										'readonly' => true,
										]
						], 
		
		'date_due'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DatePicker::classname(),
							'options'=>[
										'type'=>DatePicker::TYPE_COMPONENT_PREPEND,
										'pluginOptions' => [
															  'autoclose'=>true,
															  'format' => 'yyyy-mm-dd',
															  'todayHighlight' => true,
															 // 'endDate' => '0d'
														],
										'readonly' => true,
										]
						],

		'tax_number'=>['type'=> Form::INPUT_TEXT, 'options'=>['maxlength'=>255, 'readonly' => true]], 

    ]


    ]);
	
	?>
    	
        <div class="table-responsive m-t">
        	<input type="hidden" class="del_detail" name="del_detail">
                                <table class="table invoice-table" id="mytable">
                                    <thead>
                                        <tr style="border-bottom:2px solid #000;border-top:2px solid #000;">
                                            <th><?= Yii::t('app','Action')?></th>
                                            <th style="text-align:left" width="35%"><?= Yii::t('app','Description')?></th>
                                            <th style="text-align:center"><?= Yii::t('app','Rate')?></th>
                                            <th style="text-align:center" width="7%"><?= Yii::t('app','Qty')?></th>
                                            <th style="text-align:center" width="20%"><?= Yii::t('app','Tax')?></th>
                                            <th style="text-align:center"><?= Yii::t('app','Tax Amount')?></th>
                                            <th style="text-align:center"><?= Yii::t('app','Total')?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
										if(isset($InvoiceDetails) && count($InvoiceDetails) > 0){
											foreach($InvoiceDetails as $detail){
									?>
                                    <tr>
                                        <td>
                                        <input type="hidden" name="detail_id[]"  class="detail_id" value="<?=$detail['id']?>">
                                        <input type="hidden" name="product_id[]" class="product_id" value="<?=$detail['product_id']?>">
                                        <button type="button" class="rowRemove btn btn-danger remove_detail"><span class="fa fa-times"></span></button></td>
                                        <td><div class="form-group"><input type="text" name="description[]" value="<?=$detail['description']?>" class="form-control description" data-validation="required"></div></td>
                                        <td><div class="form-group"><input type="text" name="rate[]" value="<?=$detail['rate']?>" data-valid-min-num="required" data-valid-num="required" class="form-control rate" data-validation="required"></div></td>
                                        <td><div class="form-group"><input type="text" name="quantity[]" value="<?=$detail['quantity']?>" data-valid-min-num="required" value="1" data-valid-num="required" class="form-control quantity" data-validation="required"></div></td>
                                        
                                        <td>
                                         <div class="form-group">
                                            <select class="form-control tax_id" data-validation="required" name="tax_id[]">
                                                <option value="0">--<?= Yii::t('app','No Tax (0%)')?>--</option>
                                                <?php
                                                    foreach($taxList as $taxRow){
                                                ?>
                                                <option value="<?=$taxRow->id?>" <?=$taxRow->id==$detail['tax_id']?'selected':''?>>
													<?=$taxRow->name?> (<?=$taxRow->tax_percentage?>%)</option>
                                                <?php } ?>
                                            </select>
                                            </div>
                                        </td>
                                        <td><div class="form-group"><input type="text" name="tax_amount[]" value="<?=$detail['tax_amount']?>"  style="text-align:right" readonly class="form-control tax_amount"></div></td>	
                                        <td align="right"><div class="form-group"><input type="text" value="<?=$detail['total']?>"  style="text-align:right" readonly  name="total[]" class="form-control total"></div></td>
                                    </tr>
									<?php
											}
										}else{
									?>
                                    <tr>
                                        <td>
                                        <input type="hidden" name="detail_id[]" value="">
                                        <input type="hidden" name="product_id[]" class="product_id" value="">
                                        <button type="button" disabled class="rowRemove btn btn-danger" ><span class="fa fa-times"></span></button></td>
                                        <td><div class="form-group"><input type="text" name="description[]" class="form-control description" data-validation="required"></div></td>
                                        <td><div class="form-group"><input type="text" name="rate[]" data-valid-num="required" data-valid-min-num="required"  class="form-control rate" data-validation="required"></div></td>
                                        <td><div class="form-group"><input type="text" name="quantity[]" data-valid-min-num="required" value="1" data-valid-num="required" class="form-control quantity" data-validation="required"></div></td>
                                        
                                        <td>
                                         <div class="form-group">
                                            <select class="form-control tax_id" data-validation="required" name="tax_id[]">
                                                <option value="0">--<?= Yii::t('app','No Tax (0%)')?>--</option>
                                                <?php
                                                    foreach($taxList as $taxRow){
                                                ?>
                                                <option value="<?=$taxRow->id?>"><?=$taxRow->name?> (<?=$taxRow->tax_percentage?>%)</option>
                                                <?php } ?>
                                            </select>
                                            </div>
                                        </td>
                                        <td><div class="form-group"><input type="text" name="tax_amount[]" style="text-align:right" readonly class="form-control tax_amount"></div></td>	
                                        <td align="right"><div class="form-group"><input type="text" style="text-align:right" readonly  name="total[]" class="form-control total"></div></td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                            <div  class="pull-left">
                            	<div class="col-sm-12">
                           	 	 <input type="button" class="addrow btn btn-primary btn-sm" value="Add Line Item" />
                                 </div>
                             </div>
                            <div class="col-sm-5 pull-right">
                            
                            	<table class="table invoice-total">
                                <tbody>
                                 <tr>
                                    <td style="border-bottom:1px solid #dddddd; text-align:left; vertical-align:middle"><strong><?= Yii::t('app','Total Tax')?></strong></td>
                                    <td align="right"><div class="form-group"><input style="text-align:right" type="text" name="Invoice[total_tax_amount]" value="<?=$model->total_tax_amount?>" class="form-control total_tax_amount" readonly></div></td>
                                </tr>
                                <tr>
                                    <td style="border-bottom:1px solid #dddddd; text-align:left; vertical-align:middle">
                                   
                                    <strong><?= Yii::t('app','SubTotal')?></strong></td>
                                    <td align="right" style="width:50%"><div class="form-group"><input type="text" style="text-align:right" name="Invoice[sub_total]" value="<?=$model->sub_total?>" readonly class="form-control sub_total"></div></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-bottom:1px solid #dddddd; padding:0px; text-align:left">
                                    	<table class="table table-bordered">
                                        	<tr>
                                                <td width="30%" style="border-bottom:1px solid #dddddd; text-align:left"><strong><?= Yii::t('app','Discount Type')?></strong></td>
                                                <td style="border-bottom:1px solid #dddddd; text-align:center"><strong><?= Yii::t('app','Discount')?></strong></td>
                                                <td style="border-bottom:1px solid #dddddd; text-align:left"><strong><?= Yii::t('app','Discount Amount')?></strong></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-group">
                                                    <select name="Invoice[discount_type_id]" class="form-control discount_type">
                                                       <!-- <option value="">--<?= Yii::t('app','Discount Type')?>--</option>-->
                                                        <?php
                                                            foreach(DiscountType::find()->all() as $descRow){
                                                        ?>
                                                        <option value="<?=$descRow->id?>"  <?=$model->discount_type_id==$descRow->id?'selected':''?>>
														<?=$descRow->discount_type?></option>
                                                        <?php } ?>
                                                    </select>
                                                    </div>
                                                </td>
                                                <td align="right"><div class="form-group"><input style="text-align:right" type="text" name="Invoice[discount_figure]" class="form-control discount_figure"  value="<?=$model->discount_figure?$model->discount_figure:0?>" data-valid-num="required">
                                                </div>
                                              </td>
                                                <td align="right"><div class="form-group"><input type="text" style="text-align:right" readonly name="Invoice[discount_amount]" value="<?=$model->discount_amount?>"  class="discount_amount form-control"/>
                                                </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                
                                <tr style="border-bottom:2px solid #000;border-top:2px solid #000;">
                                    <td style="border-bottom:1px solid #dddddd; text-align:left; vertical-align:middle"><strong><?= Yii::t('app','Grand Total')?></strong></td>
                                    <td align="right"><div class="form-group"><input style="text-align:right" type="text" name="Invoice[grand_total]" readonly value="<?=$model->grand_total?>" class="form-control grand_total"></div></td>
                                </tr>

								<!--<tr style="border-bottom:2px solid #000;border-top:2px solid #000;">
                                    <td style="border-bottom:1px solid #dddddd; text-align:left; vertical-align:middle; color:blue"><strong><?= Yii::t('app','Total Paid')?></strong></td>
                                    <td align="right"><div class="form-group"><input style="text-align:right" data-valid-num="required" data-valid-max-paid="required" type="text" name="Invoice[total_paid]"  value="<?=$model->total_paid?>" class="form-control total_paid"></div></td>
                                </tr>-->

                                </tbody>
                            </table>
                            </div>
															
                          </div>
						  
                        <hr style="border:1px solid #000;" />    
    <?php
	 echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
		'notes'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter Note...', 'maxlength'=>255]], 

    ]


    ]);
    
	if($model->invoice_status_id != InvoiceStatus::_CANCELLED)
	{
		//echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm']);
		if($model->isNewRecord)
		{
			echo Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success btn-sm']);
		}
		else if($model->total_paid == 0)
		{
			echo Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary btn-sm']);
		}
	}
    ActiveForm::end(); 
	?>

</div>

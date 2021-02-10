<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use livefactory\models\Customer;
use livefactory\models\Lead;
use livefactory\models\Currency;
use livefactory\models\Estimate;
use livefactory\models\EstimateStatus;
use livefactory\models\DiscountType;
use dosamigos\ckeditor\CKEditor;
/**
 * @var yii\web\View $this
 * @var livefactory\models\Estimate $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<style>
table .form-group{margin:0}
</style>
<div class="estimate-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]); 

	$dFlag=false;

	if ($_REQUEST['customer_id'] || $_REQUEST['r']=='estimate/estimate/update')
		$dFlag=true;

	if($_REQUEST['entity_type'] == 'customer')
	{
		$entity='customer';
		$label='Customer';
		$array=ArrayHelper::map(Customer::find()->orderBy('customer_name')->all(),'id','customer_name');
	}
	else if($_REQUEST['entity_type'] == 'lead')
	{
		$entity='lead';
		$label='Lead';
		$array=ArrayHelper::map(Lead::find()->orderBy('lead_name')->all(),'id','lead_name');
	}
	else if ($model->entity_type == 'customer')
	{
		$entity='customer';
		$label='Customer';
		$array=ArrayHelper::map(Customer::find()->orderBy('customer_name')->all(),'id','customer_name');
	}
	else if ($model->entity_type == 'lead')
	{
		$entity='lead';
		$label='Lead';
		$array=ArrayHelper::map(Lead::find()->orderBy('lead_name')->all(),'id','lead_name');
	}

	//$model->entity_type=$entity;

	/*extract(Estimate::find()->select("max(estimation_code) max_estimation_code")->asArray()->one());
	$model->estimation_code='ESTMT'.str_pad($max_estimation_code+1, 9, "0", STR_PAD_LEFT);*/
	
	if (isset($model->estimate_status_id) && ($model->estimate_status_id > 0))
	{
		$estVal=$model->estimate_status_id;
	}
	else
	{
		$estVal=EstimateStatus::_CREATED;
	}

	//$model->date_issued = date('Y-m-d H:i:s');
	//print_r($model->date_issued);exit;

	echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 3,
    'attributes' => [

		//'estimation_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Estimation Code...', 'maxlength'=>255]], 

		//'po_number'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter PO Number...', 'maxlength'=>255]], 

		//'date_issued'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE]], 

		'customer_id'=>['type'=> Form::INPUT_DROPDOWN_LIST,
						'options'=>[
									'prompt' => '--'.Yii::t ( 'app', $label ).'--',
									'value' => $_REQUEST['customer_id'],
									'disabled' => $dFlag,
									],
						'items'=>$array], 
		
		'currency_id'=>[
		'type'=> Form::INPUT_DROPDOWN_LIST, 
		'options'=>[
			'prompt' => '--'.Yii::t ( 'app', 'Currency' ).'--',
			'value' => \livefactory\models\DefaultValueModule::getDefaultValueId('currency'),	
			],
			'items'=>ArrayHelper::map(Currency::find()->orderBy('currency')->all(),'id','currency')
			], 
		
		
		'estimate_status_id' => [
			'type'=> Form::INPUT_DROPDOWN_LIST, 
			'options'=>[
				'prompt' => '--'.Yii::t ( 'app', 'Estimate Status' ).'--',
				'value' => $estVal,	// Default value is created
				'disabled'=>true,
			],
			'items'=>ArrayHelper::map(EstimateStatus::find()->all(),'id','label')
		],

    ]


    ]);
	
	/*$form->field ( $model, 'notes' )->widget ( CKEditor::className (), [ 

						'options' => [ 

								'rows' => 10 

						],

						'preset' => 'basic' 

				] );*/
	?>
    	
        <div class="table-responsive m-t">
        	<input type="hidden" class="del_detail" name="del_detail">
                                <table class="table estimate-table" id="mytable">
                                    <thead>
                                        <tr style="border-bottom:2px solid #000;border-top:2px solid #000;">
                                            <th><?= Yii::t('app','Action')?></th>
                                            <th style="text-align:left" width="35%"><?= Yii::t('app','Item')?></th>
                                            <th style="text-align:center"><?= Yii::t('app','Rate')?></th>
                                            <th style="text-align:center" width="7%"><?= Yii::t('app','Qty')?></th>
                                            <th style="text-align:center" width="20%"><?= Yii::t('app','Tax')?></th>
                                            <th style="text-align:center"><?= Yii::t('app','Tax Amount')?></th>
                                            <th style="text-align:center"><?= Yii::t('app','Total')?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
										if(isset($estimateDetails) && count($estimateDetails) > 0){
											foreach($estimateDetails as $detail){
									?>
                                    <tr>
                                        <td>
                                        <input type="hidden" name="detail_id[]"  class="detail_id" value="<?=$detail['id']?>">
                                        <input type="hidden" name="product_id[]" class="product_id" value="<?=$detail['product_id']?>">
                                        
                                        <button type="button" class="rowRemove btn btn-danger remove_detail"><span class="fa fa-times"></span></button></td>
                                        <td><div class="form-group"><input type="text" name="description[]" value="<?=$detail['description']?>" class="form-control description" data-valid-desc="required" data-validation="required"></div></td>
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
                                        <td><div class="form-group"><input type="text" name="description[]" class="form-control description" data-valid-desc="required" data-validation="required"></div></td>
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
                            
                            	<table class="table estimate-total">
                                <tbody>
                                 <tr>
                                    <td style="border-bottom:1px solid #dddddd; text-align:left; vertical-align:middle"><strong><?= Yii::t('app','Total Tax')?></strong></td>
                                    <td align="right"><div class="form-group"><input style="text-align:right" type="text" name="Estimate[total_tax_amount]" value="<?=$model->total_tax_amount?>" class="form-control total_tax_amount" readonly></div></td>
                                </tr>
                                <tr>
                                    <td style="border-bottom:1px solid #dddddd; text-align:left; vertical-align:middle">
                                   
                                    <strong><?= Yii::t('app','SubTotal')?></strong></td>
                                    <td align="right" style="width:50%"><div class="form-group"><input type="text" style="text-align:right" name="Estimate[sub_total]" value="<?=$model->sub_total?>" readonly class="form-control sub_total"></div></td>
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
                                                    <select name="Estimate[discount_type_id]" class="form-control discount_type">
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
                                                <td align="right"><div class="form-group"><input style="text-align:right" type="text" name="Estimate[discount_figure]" class="form-control discount_figure"  value="<?=$model->discount_figure?$model->discount_figure:0?>" data-valid-num="required">
                                                </div>
                                              </td>
                                                <td align="right"><div class="form-group"><input type="text" style="text-align:right" readonly name="Estimate[discount_amount]" value="<?=$model->discount_amount?>"  class="discount_amount form-control"/>
                                                </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                
                                <tr style="border-bottom:2px solid #000;border-top:2px solid #000;">
                                    <td style="border-bottom:1px solid #dddddd; text-align:left; vertical-align:middle"><strong><?= Yii::t('app','Grand Total')?></strong></td>
                                    <td align="right"><div class="form-group"><input style="text-align:right" type="text" name="Estimate[grand_total]" readonly value="<?=$model->grand_total?>" class="form-control grand_total"></div></td>
                                </tr>
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
		'notes'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter Note...', 'maxlength'=>255, 'style' => 'resize:none']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm']);

	if(!empty($model->id)){
    
	?>
    
     <a href="index.php?r=estimate/estimate/view&id=<?=$model->id?>&entity_type=<?=$model->entity_type?>" class="btn btn-success btn-sm" onClick=""> <?= Yii::t('app','View')?></a>

		 <?php
            if($model->estimate_status_id !=EstimateStatus::_APPROVED){
         ?>
         <!--<a href="index.php?r=estimate/estimate/update&id=<?=$model->id?>&rqst=approve" class="btn btn-info btn-sm" onClick=""> <?= Yii::t('app','Approve Estimate')?></a>-->
		<?php
            }

            if($model->estimate_status_id !=EstimateStatus::_REJECTED){
         ?>
        <!-- <a href="index.php?r=estimate/estimate/update&id=<?=$model->id?>&rqst=reject" class="btn btn-danger btn-sm" onClick=""> <?= Yii::t('app','Reject Estimate')?></a>-->
		<?php
            }
        }
    ActiveForm::end(); 
	?>

</div>

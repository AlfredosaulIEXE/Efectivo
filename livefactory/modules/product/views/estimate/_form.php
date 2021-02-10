<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use livefactory\models\Customer;
use livefactory\models\Currency;
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

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 3,
    'attributes' => [

'estimation_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Estimation Code...', 'maxlength'=>255]], 

'po_number'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Po Number...', 'maxlength'=>255]], 

'date_issued'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 

'customer_id'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'options'=>['prompt' => '--'.Yii::t ( 'app', 'Customer' ).'--',],'items'=>ArrayHelper::map(Customer::find()->all(),'id','customer_name')], 

'currency_id'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'options'=>['prompt' => '--'.Yii::t ( 'app', 'Currency' ).'--',],'items'=>ArrayHelper::map(Currency::find()->all(),'id','currency')], 

//'sub_total'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Sub Total...', 'maxlength'=>10]], 

//'discount_type_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Discount Type...']], 

//'discount_figure'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Discount Figure...', 'maxlength'=>10]], 

//'discount_amount'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Discount Amount...', 'maxlength'=>10]], 

//'total_tax_amount'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Total Tax Amount...', 'maxlength'=>10]], 

//'grand_total'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Grand Total...', 'maxlength'=>10]], 

///'notes'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter Notes...', 'maxlength'=>255]], 

//'active'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Active...']], 
//
//'added_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Added At...']], 

//'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Updated At...']], 

    ]


    ]);
	if(empty($_GET['id'])){
	 echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'notes'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter Notes...', 'maxlength'=>255]], 

    ]


    ]);
	$form->field ( $model, 'notes' )->widget ( CKEditor::className (), [ 

						'options' => [ 

								'rows' => 10 

						],

						'preset' => 'basic' 

				] );
	?>
    	<input type="button" class="addrow btn btn-primary" value="Add Row" />
    	<table class="table table-striped table-bordered" id="mytable">
        	<thead>
                <tr>
                    <th><?= Yii::t('app','Action')?></th>
                    <th><?= Yii::t('app','Description')?></th>
                    <th><?= Yii::t('app','Rate')?></th>
                    <th><?= Yii::t('app','Qty.')?></th>
                    <th><?= Yii::t('app','Tax')?></th>
                    <th><?= Yii::t('app','Tax Amount')?></th>
                    <th><?= Yii::t('app','Total')?></th>
                </tr>
            </thead>
            <tbody>
            	<tr>
                    <td>
                    <input type="hidden" name="detail_id[]" value="">
                    <input type="hidden" name="product_id[]" class="product_id" value="">
                    <input type="button" disabled class="rowRemove btn btn-danger"  value="<?= Yii::t('app','Remove')?>" /></td>
                	<td><div class="form-group"><input type="text" name="description[]" class="form-control description" data-validation="required"></div></td>
                    <td><div class="form-group"><input type="text" name="rate[]" data-valid-num="required" class="form-control rate" data-validation="required"></div></td>
                    <td><div class="form-group"><input type="text" name="quantity[]" value="1" data-valid-num="required" class="form-control quantity" data-validation="required"></div></td>
                    <td width="20%">
                   	 <div class="form-group">
                    	<select class="form-control tax_id" data-validation="required" name="tax_id[]">
                        	<option value="">--<?= Yii::t('app','Tax')?>--</option>
                            <?php
								foreach($taxList as $taxRow){
							?>
                            <option value="<?=$taxRow->tax_percentage?>"><?=$taxRow->name?></option>
                            <?php } ?>
                        </select>
                        </div>
                    </td>
                    <td><div class="form-group"><input type="text" name="tax_amount[]" style="text-align:right" readonly class="form-control tax_amount" data-validation="required"></div></td>	
                    <td align="right"><div class="form-group"><input type="text" style="text-align:right" readonly  name="total[]" class="form-control total" data-validation="required"></div></td>
                </tr>
            </tbody>
            <tfoot>
            	<tr>
                	<td colspan="6" align="right"><strong>SubTotal</strong></td>
                    <td align="right"><div class="form-group"><input type="text" style="text-align:right" name="Estimate[sub_total]" readonly class="form-control sub_total" data-validation="required"></div></td>
                </tr>
                 <tr>
                    <td colspan="6" align="right"><strong>Total Tax</strong></td>
                	<td align="right"><div class="form-group"><input style="text-align:right" type="text" name="Estimate[total_tax_amount]" class="form-control total_tax_amount" readonly  data-validation="required"></div></td>
               	</tr>
                <tr>
                    <td colspan="6" align="right"><strong>Discount Type</strong></td>
                    <td>
                    	<div class="form-group">
                    	<select name="Estimate[discount_type_id]" class="form-control discount_type" data-validation="required">
                        	<option value="">--<?= Yii::t('app','Discount Type')?>--</option>
                            <?php
								foreach(DiscountType::find()->all() as $descRow){
							?>
                            <option value="<?=$descRow->id?>"><?=$descRow->discount_type?></option>
                            <?php } ?>
                        </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" align="right"><strong>Discount Figure</strong></td>
                	<td align="right"><div class="form-group"><input style="text-align:right" type="text" name="Estimate[discount_figure]" class="form-control discount_figure" data-valid-num="required" data-validation="required">
                    </div>
                    <input type="hidden" name="Estimate[discount_amount]"  class="discount_amount"/></td>
               	</tr>
                <tr>
                    <td colspan="6" align="right"><strong>Grand Total</strong></td>
                    <td align="right"><div class="form-group"><input style="text-align:right" type="text" name="Estimate[grand_total]" readonly class="form-control grand_total" data-validation="required"></div></td>
                </tr>
            </tfoot>
        </table>
    <?php
	
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
     
	}
	
	ActiveForm::end();?>

</div>

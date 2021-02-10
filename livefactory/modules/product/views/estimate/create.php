<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\Estimate $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Estimate',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Estimates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
ob_start();
?>
<tr>
    <td>
    <input type="hidden" name="detail_id[]" value="">
    <input type="hidden" name="product_id[]" class="product_id" value="">
    <input type="button" class="rowRemove btn btn-danger" value="<?= Yii::t('app','Remove')?>" /></td>
    <td><div class="form-group"><input type="text" name="description[]" class="form-control description" data-validation="required"></div></td>
    <td><div class="form-group"><input type="text" name="rate[]" class="form-control rate" data-validation="required"></div></td>
    <td><div class="form-group"><input type="text" name="quantity[]" value="1" data-valid-num="required" class="form-control quantity" data-validation="required"></div></td>
    <td>
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
    <td><div class="form-group"><input type="text" style="text-align:right" name="tax_amount[]" readonly class="form-control tax_amount" data-validation="required"></div></td>	
    <td><div class="form-group"><input type="text" readonly style="text-align:right" name="total[]" class="form-control total" data-validation="required"></div></td>
</tr>
<?php
$html = ob_get_clean();
$html =str_replace(PHP_EOL, '', $html);
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<?php $this->registerCssFile(Yii::$app->request->baseUrl.'/autocomplete/jquery-ui.css', ['depends' => [yii\web\YiiAsset::className()]]);?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl.'/autocomplete/jquery-ui.js', ['depends' => [yii\web\YiiAsset::className()]]);?>
<script>
var error='';
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
function fillSubTotal(){
	var sub_total =tax_amount=0;
	$('.total').each(function(index, element) {
		sub_total +=!isNaN(parseInt($(this).val()))?parseInt($(this).val()):0;
	});
	$('.tax_amount').each(function(index, element) {
		tax_amount +=!isNaN(parseInt($(this).val()))?parseInt($(this).val()):0;
	});
	$('.sub_total').val(sub_total);	
	$('.total_tax_amount').val(tax_amount);	
}
function cal(){
	if($('.sub_total').val() !='' && $('.discount_figure').val() !=''){
		if($('.discount_type').val() !='1' && parseInt($('.discount_figure').val()) >100){
			$('.discount_figure').val('100');
		}
		if($('.discount_figure').val() !=''){
			if(parseInt($('.sub_total').val()) < parseInt($('.discount_figure').val())){
				$('.discount_figure').val('0');
			}
		}
		if($('.discount_type').val() =='1'){
			$('.grand_total').val(parseInt($('.sub_total').val())-parseInt($('.discount_figure').val()));
			$('.discount_amount').val($('.discount_figure').val());
		}else{
			var perVal = (parseInt($('.discount_figure').val())/100)*parseInt($('.sub_total').val());
			$('.discount_amount').val(perVal);
			$('.grand_total').val(parseInt($('.sub_total').val())-parseInt(perVal));
		}
	}	
}
function valide(){
	$('#w0').submit(function(e){
			$('[data-validation="required"]').each(function(index, element) {
				Remove_Error($(this));
				if($(this).val() == ''){
					error+=Add_Error($(this),'This Field is Required!');
				}else{
					Remove_Error($(this));
				}
			});
			if(error !=''){
				e.preventDefault();	
			}
		})
	$('[data-valid-num="required"]').keypress(function(key) {
		if(!$(this).val().length){
			if(key.charCode == 48) return false;
		}
		if((key.charCode > 7 && key.charCode < 45) || key.charCode > 57) return false;
	});
}
function taxChange(){
	$('.tax_id').change(function(){
		if($(this).closest('tr').find('.rate').val() != ''){
			var tax = $(this).val();
			var rate = parseInt($(this).closest('tr').find('.rate').val());
			var qty = parseInt($(this).closest('tr').find('.quantity').val());
			var rate = rate*qty;
			if(!isNaN(rate)){
				var tax_amount = parseInt((parseFloat(tax)/100)*rate);
			}else{
				var tax_amount = 0;
			}
			$(this).closest('tr').find('.tax_amount').val(tax_amount);
			$(this).closest('tr').find('.total').val(tax_amount+rate);
			fillSubTotal();
			cal();
		}
	})
	
}
function rateChange(){
	$('.rate,.quantity').blur(function(){
		if($(this).closest('tr').find('.tax_id').val() != ''){
			var tax = $('.tax_id').val();
			var rate = parseInt($(this).closest('tr').find('.rate').val());
			var qty = parseInt($(this).closest('tr').find('.quantity').val());
			var rate = rate*qty;
			if(!isNaN(rate)){
				var tax_amount = parseInt((parseFloat(tax)/100)*rate);
			}else{
				var tax_amount = 0;
			}
			$(this).closest('tr').find('.tax_amount').val(tax_amount);
			$(this).closest('tr').find('.total').val(tax_amount+rate);
			fillSubTotal();
			cal();
		}
		
	})
}
function autoComplate(){
	$( ".description" ).autocomplete({
	  source: <?=$jSonList?>,
	  minLength: 0,
	  select: function( event, ui ) {
		$(this).closest('tr').find('.product_id').val(ui.item.id);
	  }
	}).autocomplete( "instance" )._renderItem = function( ul, item ) {
	return $( "<li>" )
	.append( "<a>" + item.value+ "</a>" )
	.appendTo( ul );
	};	
}
	$(function(){
		valide();
		$('.addrow').click(function(){
			$('#mytable tbody tr:last').after('<?=$html?>');
			autoComplate();
			taxChange();
			valide();
			rateChange();
		});
		$(document).on("click", ".rowRemove", function (e) {
			var target = e.target;
			$(target).closest('tr').remove();
			fillSubTotal();
			cal();
		});
		autoComplate();
		taxChange();
		valide();
		rateChange();
		$('.discount_figure').blur(function(){
			cal();
		})
		$('.discount_type').change(function(){
			cal();
		})
	})
	
	
</script>
<div class="estimate-create">
<div class="ibox float-e-margins">

                    <div class="ibox-title">

                        <h5><?= Html::encode($this->title) ?></h5>

                        <div class="ibox-tools">

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
							]) ?>

                    </div>

                </div>
</div>

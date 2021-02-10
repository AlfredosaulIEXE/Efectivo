<?php
ob_start();
?>
<tr>
    <td>
    <input type="hidden" name="detail_id[]" value="">
    <input type="hidden" name="product_id[]" class="product_id" value="">
    <button type="button"  class="rowRemove btn btn-danger" ><span class="fa fa-times"></span></button></td>
    <td><div class="form-group"><input type="text" name="description[]" class="form-control description" data-valid-desc="required" data-validation="required"></div></td>
    <td><div class="form-group"><input type="text" name="rate[]" class="form-control rate" data-valid-num="required" data-validation="required"></div></td>
    <td><div class="form-group"><input type="text" name="quantity[]" value="1" data-valid-num-qty="required" class="form-control quantity" data-validation="required"></div></td>
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
    <td><div class="form-group"><input type="text" style="text-align:right" name="tax_amount[]" readonly class="form-control tax_amount"></div></td>	
    <td><div class="form-group"><input type="text" readonly style="text-align:right" name="total[]" class="form-control total" ></div></td>
</tr>
<?php
$html = ob_get_clean();
$html =str_replace(PHP_EOL, '', $html);
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<?php $this->registerCssFile(Yii::$app->request->baseUrl.'/autocomplete/jquery-ui.css', ['depends' => [yii\web\YiiAsset::className()]]);?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl.'/autocomplete/jquery-ui.js', ['depends' => [yii\web\YiiAsset::className()]]);?>
<script>
var taxArray =<?=json_encode($taxs)?>;
var error='';
var del_ids = [];
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
		sub_total +=!isNaN(parseFloat($(this).val()))?parseFloat($(this).val()):0;
	});
	$('.tax_amount').each(function(index, element) {
		tax_amount +=!isNaN(parseFloat($(this).val()))?parseFloat($(this).val()):0;
	});
	$('.sub_total').val(sub_total.toFixed(2));	
	$('.total_tax_amount').val(tax_amount.toFixed(2));	
}
function cal(){
	if($('.sub_total').val() !='' && $('.discount_figure').val() !=''){
		if($('.discount_type').val() !='1' && parseFloat($('.discount_figure').val()) >=100){
			$('.discount_figure').val('100');
		}
		if($('.discount_type').val() =='1' &&  $('.discount_figure').val() !=''){
			if(parseFloat($('.sub_total').val()) < parseFloat($('.discount_figure').val())){
				$('.discount_figure').val('0');
			}
		}
		if($('.discount_type').val() =='1'){
			$('.grand_total').val((parseFloat($('.sub_total').val())-parseFloat($('.discount_figure').val())).toFixed(2) );
			$('.discount_amount').val($('.discount_figure').val());
		}else{
			var perVal = (parseFloat($('.discount_figure').val())/100)*parseFloat($('.sub_total').val());
			$('.discount_amount').val(perVal.toFixed(2));
			$('.grand_total').val((parseFloat($('.sub_total').val())-parseFloat(perVal)).toFixed(2));
		}
	}	
}
function fillRow(obj){
	if($(obj).closest('tr').find('.tax_id').val() != '' && $(obj).closest('tr').find('.rate').val() != ''  && $(obj).closest('tr').find('.quantity').val() != ''){
		
		var tax = 0;
		if(typeof taxArray[$(obj).closest('tr').find('.tax_id').val()] != 'undefined'){
			tax = taxArray[$(obj).closest('tr').find('.tax_id').val()];
		}
		var rate = parseFloat($(obj).closest('tr').find('.rate').val());
		var qty = parseInt($(obj).closest('tr').find('.quantity').val());
		var rate = rate*qty;
		if(!isNaN(rate)){
			var tax_amount = parseFloat((parseFloat(tax)/100)*rate);
		}else{
			var tax_amount = 0;
		}
		$(obj).closest('tr').find('.tax_amount').val(tax_amount.toFixed(2) );
		$(obj).closest('tr').find('.total').val((tax_amount+rate).toFixed(2) );
		fillSubTotal();
		cal();
	}	
}
function valide(){
	
	$('#w0').submit(function(e){
		error='';
			$('[data-validation="required"]').each(function(index, element) {
				Remove_Error($(this));
				if($(this).val() == ''){
					error+=Add_Error($(this),'This Field is Required!');
				}else{
					Remove_Error($(this));
				}
			});	
			$('[data-valid-min-num="required"]').each(function(){
				if($(this).val() != ''){
					Remove_Error($(this));
					
					if($(this).val() == '0'){
						error+=Add_Error($(this),'Can not use 0');
					}else{
						Remove_Error($(this));
					}
				}
			});
			/*$('[data-valid-desc="required"]').each(function(index, element) {
					Remove_Error($(this));
					if($(this).closest('tr').find('.product_id').val() == ''){
						error+=Add_Error($(this),'Item Field is Required!');
					}else{
						Remove_Error($(this));
					}
				})*/

			if(error !=''){
				e.preventDefault();	
			}
		})
	$('[data-valid-num="required"]').keyup(function(key) {
		var val = this.value;
		var re = /^([0-9]+[\.]?[0-9]?[0-9]?|[0-9]+)$/g;
		var re1 = /^([0-9]+[\.]?[0-9]?[0-9]?|[0-9]+)/g;
		if (re.test(val)) {
			//do something here
		
		} else {
			val = re1.exec(val);
			if (val) {
				this.value = val[0];
			} else {
				this.value = "";
			}
		}
		/*if(!$(this).val().length){
			
			if(key.charCode == 48) return false;
		}else{
			if($(this).val().length ==1 && key.charCode == 48)  return false;
		}
		if((key.charCode > 7 && key.charCode < 45) || key.charCode > 57) return false;
		///alert(key.charCode );
		var text = $(this).val();
		if( key.charCode != 0){
			if ((text.indexOf('.') != -1) && (text.substring(text.indexOf('.')).length > 2)) {
				$(this).val(text.slice(0,-1));
				
			}
		}*/
		
	});
	$('[data-valid-num-qty="required"]').keypress(function( event ){
		var key = event.which;
		
		if( ! ( key >= 48 && key <= 57 ) )
			event.preventDefault();
	});
	$('[data-validation="required"]').blur(function(){
		Remove_Error($(this));
		if($(this).val() == ''){
			error+=Add_Error($(this),'This Field is Required!');
		}else{
			Remove_Error($(this));
		}
	})
	$('[data-valid-min-num="required"]').blur(function(){
		if($(this).val() != ''){
			Remove_Error($(this));
			if($(this).val() == '0'){
				error+=Add_Error($(this),'Can not use 0');
			}else{
				Remove_Error($(this));
			}
		}
	})
	/*$(".quantity").keypress(function( event ){
		var key = event.which;
		
		if( ! ( key >= 48 && key <= 57 ) )
			event.preventDefault();
	});*/
	$('.quantity').on('input', function (event) { 
		this.value = this.value.replace(/[^0-9]/g, '');
	});
}

function taxChange(){
	$('.tax_id').change(function(){
		fillRow($(this));
	})
	
}
function rateChange(){
	$('.rate,.quantity').blur(function(){
		/////alert($(this).hasClass('rate'));
		if($(this).hasClass('rate') && $(this).val() ==''){
			$(this).closest('tr').find('.tax_amount').val('');
			$(this).closest('tr').find('.total').val('');	
		}
		fillRow($(this));
	})
}

function autoComplate(){
	$( ".description" ).autocomplete({
	  source: <?=$jSonList?>,
	  minLength: 0,
	  select: function( event, ui ) {
		$(this).closest('tr').find('.product_id').val(ui.item.id);
		$(this).closest('tr').find('.rate').val(ui.item.product_price);
		$(this).closest('tr').find('.tax_id').val(ui.item.tax_id);
		fillRow($(this));
	  }
	}).autocomplete( "instance" )._renderItem = function( ul, item ) {
	return $( "<li>" )
	.append( "<a>" + item.value+ "</a>" )
	.appendTo( ul );
	};	
}

	$(function(){
		$('#estimate-estimation_code').blur(function(){
			var that = $(this);
			Remove_Error($(that));
			$.post('index.php?r=estimate/estimate/ajax-duplicate-check-insert-case&code='+escape($(this).val())+'&id='+escape('<?=$model->id?>'),function(r){
				if(r > 0){
					Add_Error($(that),'This Estimation Code is already Taken!');
					$('[type="submit"]').attr('disabled',true);
					
				}else{
					$('[type="submit"]').removeAttr('disabled');
				}
			})
		})
		//Disabled First Row in Update case
		if('<?= isset($_GET['id'])?'yes':'no'?>' =='yes'){
			if($('#mytable tbody tr').length =='1'){
				$('.rowRemove').attr('disabled',true);	
			}
		}
		$('.addrow').click(function(){
			$('#mytable tbody tr:last').after('<?=$html?>');
			autoComplate();
			taxChange();
			valide();
			rateChange();
			if('<?= isset($_GET['id'])?'yes':'no'?>' =='yes'){
				if($('#mytable tbody tr').length =='1'){
					$('.rowRemove').attr('disabled',true);	
				}else{
					$('.rowRemove').removeAttr('disabled');	
				}
			}
		});
		
		valide();
		$(document).on("click", ".rowRemove", function (e) {
			var target = e.target;
			$(target).closest('tr').remove();
			fillSubTotal();
			cal();
			if('<?= isset($_GET['id'])?'yes':'no'?>' =='yes'){
				if($('#mytable tbody tr').length =='1'){
					$('.rowRemove').attr('disabled',true);	
				}else{
					$('.rowRemove').removeAttr('disabled');	
				}
			}
		});
		/// To delete Estimate Detail
		$('.remove_detail').click(function(){
			del_ids.push($(this).closest('tr').find('.detail_id').val());
			$('.del_detail').val(del_ids);
		})
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
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

/**
 * @var yii\web\View $this
 * @var livefactory\models\Invoice $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Invoice',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Invoice'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
include_once('script.php');
?>
<div class="invoice-create">
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

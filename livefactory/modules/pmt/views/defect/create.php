<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Defect $model
 */

$this->title = Yii::t('app', 'Create Defect');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Defects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>	
.cke_contents{max-height:250px}
</style>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script>
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
$(document).ready(function(e) {
   $('#defect-project_id').change(function(){
	 $.post('index.php?r=pmt/defect/ajax-project-users&project_id='+$(this).val(),function(r){
		$('#defect-user_assigned_id').html(r) ;
	 })
   })
	$('#w0').submit(function(event){
		$('#cke_1_contents').parent().parent().removeAttr('style').next('.error').remove();
		sageLength = CKEDITOR.instances['defect-defect_description'].getData().replace(/<[^>]*>/gi, '').length;
		if(sageLength==0){
			Add_ErrorTag($('#cke_1_contents').parent().parent(),'<?=Yii::t ('app','This Field is Required!')?>');
		event.preventDefault();
		}
	})
});
</script>


 <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?= Yii::t('app', "Create Defect") ?> <small class="m-l-sm"><?= Yii::t('app', "Enter Defect Title, Start Time, ETA & Description") ?></small></h5>
                        <div class="ibox-tools">
						    <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
							<!--
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-wrench"></i>
                            </a>
							
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="#">Config option 1</a>
                                </li>
                                <li><a href="#">Config option 2</a>
                                </li>
                            </ul>
							-->
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
										 <div class="project-create">
							<?= $this->render('_form', [
								'model' => $model,
							]) ?>
						
						</div>
                    </div>
                </div>


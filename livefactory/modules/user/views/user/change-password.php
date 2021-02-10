<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('app','Change Password');
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<!--<script src="../include/password.js"></script>-->
<script type="text/javascript">
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
$(document).ready(function(e) {
	$('#confirm').blur(function(){
		Remove_Error($(this));
		if($(this).val()!=$('#pass').val()){
		 error+=Add_Error($(this),'Confirm Password not Same!');
		}else{
			Remove_Error($(this));
		}
	})
	$('.changePass').click(function(event){
			var error='';
			
			$('[data-validation="required"]').each(function(index, element) {
				Remove_Error($(this));
				if($(this).val() == ''){
					error+=Add_Error($(this),'<?=Yii::t ('app','This Field is Required!')?>');
				}else{
						Remove_Error($(this));							
				}
			});
			if($('#confirm').val()!=$('#pass').val()){
			 error+=Add_Error($('#confirm'),'<?=Yii::t ('app','Confirm Password not Same!')?>');
			}else{
				Remove_Error($('#confirm'));
			}
		if(error==''){
			return;	
		}else{
			event.preventDefault();
		}
	})
	
	 var script=document.createElement('script');
				script.type='text/javascript';
				script.src='../include/password.js';
				
				$("body").append(script);
	$('#pass').password().on('show.bs.password', function(e) {
			//$('#methods').prop('checked', true);
		}).on('hide.bs.password', function(e) {
			//$('#methods').prop('checked', false);
		});
	$('#confirm').password().on('show.bs.password', function(e) {
			//$('#methods').prop('checked', true);
		}).on('hide.bs.password', function(e) {
		//	$('#methods').prop('checked', false);
		});
 });
</script>
<h3></h3>
<div class="col-lg-6 col-sm-offset-3">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5><?=Yii::t ('app','Change Password')?></h5>
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
        	<?php if($msg){?>
            	<div class="alert alert-success"><?=$msg?></div>
            <?php } ?>
            <form  action="" method="post" enctype="multipart/form-data" name="task_time" id="task_time">
              <?php Yii::$app->request->enableCsrfValidation = true; ?>
    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                <div class="form-group">
                	<label><?=Yii::t('app','New Password')?></label>
                    <input type="password" name="password" class="form-control" id="pass" data-validation="required">
                </div>
                <div class="form-group">
                	<label><?=Yii::t('app','Confirm New Password')?></label>
                    <input type="password" name="confirm_pass" class="form-control" id="confirm" data-validation="required">
                </div>
                <?= Html::submitButton(Yii::t('app','Change Password'), ['class' => 'btn btn-primary btn-sm changePass', 'name' => 'login-button']) ?>
		

            </form>
        </div>
    </div>
</div>
            

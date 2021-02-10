<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use livefactory\models\UserRole;
use livefactory\models\UserType;
use livefactory\models\Status;
use yii\authclient\widgets\AuthChoice;
use livefactory\models\search\UserType as UserTypeSearch;
use livecrm\controllers\SiteController;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */
$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<body class="black-bg">
<div class="middle-box loginscreen  animated fadeInDown">
	<?php
if(!empty($_GET['msg'])){
	
?>
<div class="alert alert-danger"><?=$_GET['msg']?></div>
<?php } ?>
        <div>
            <div>
                <img src="../logo/crm-logo.png" alt="logo" width="300">
            </div>
            <div class="text-white">
                <h3><?=Yii::t('app', 'Welcome')?></h3>
                <p><?=Yii::t('app', 'Please fill out the following fields to login:')?></p>
            </div>
            <?php $form = ActiveForm::begin(['id' => 'login-form', 'class' => 'm-t']); ?>
				<?php
				if(isset(Yii::$app->params['IS_DEMO']) && Yii::$app->params['IS_DEMO'] == "Yes")
				{
					$value='admin';
				}
				else
				{
					$value='';
				}
				?>
                <?= $form->field($model, 'username')->textInput(array('placeholder' => '', 'value' => $value))->label(false) ?>
                <?= $form->field($model, 'password')->passwordInput(array('placeholder' => '', 'value' => $value))->label(false) ?>
                <?= Html::submitButton(Yii::t('app', 'Login'), ['style'=>'background-color: #36b2c0 ;border-color: #36b2c0','class' => 'btn btn-success btn-block', 'name' => 'login-button']) ?>
               
               <br/>
                <p class="text-muted text-center">
                 <a href="javascript:void(0)" onclick="alert('Por favor contacta con tu supervisor');"><small><?=Yii::t('app', 'Forgot password?')?></small></a><br/>
                <?php
				if(Yii::$app->params['ALLOW_NEW_REGISTRATION'] == 'Yes')
				{
				?>

				<br/>

				<!--
				<i><?=Yii::t('app', 'Create/Register Account with :')?></i><br/>
				 <?php $authAuthChoice = AuthChoice::begin([                 
						'baseAuthUrl' => ['site/auth'],
						
					]); ?>
					<?php foreach ($authAuthChoice->getClients() as $client): ?>
					<?php $authAuthChoice->clientLink($client) ?>
					<?php endforeach; 
					AuthChoice::end();?>
					-->
				<?php
				}
				?>
		

            <?php ActiveForm::end(); 
			$hasError = count($user_model->errors);
			?>
            

        </div>
</div>
<style>

	.modal-dialog{width:80% !important;}

</style>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
 <link href="css/bootstrap.css" rel="stylesheet">
 <link href="css/style.css" rel="stylesheet">
<script src="js/bootstrap.min.js"></script>
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
$(document).ready(function(e) {
	$('.auth-link').addClass('btn').addClass('btn-xs').addClass('btn-primary');
    $('.userSubmit').click(function(event){
		var error='';
		$('[data-validation="required"]').each(function(index, element) {
			Remove_Error($(this));
			$(this).removeAttr('style').next('.error').remove();
			if($(this).val() == ''){
				error+=Add_Error($(this),'This Field is Required!');
			}else{
					Remove_Error($(this));							
			}
		});
		if(error==''){
			return;	
		}else{
			event.preventDefault();
		}
	})
	if(<?=$hasError?>){
		$('.user-modal').modal('show');
	}
	if('<?=$msg?>' !=''){
		$('.msg').modal('show');
		setTimeout(function(){
			$('.msg').modal('hide');
			window.location.href='index.php?r=site/login';
		},2000);
	}
});

</script>

<div class="modal fade user-modal">
			  <div class="modal-dialog">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=Yii::t('app', 'Close')?></span></button>
					<h4 class="modal-title"><?=Yii::t('app', 'Create Account')?></h4>
				  </div>
					<div class="modal-body">
						<?php
				
				$form = ActiveForm::begin ( [ 
						'type' => ActiveForm::TYPE_VERTICAL ,
						'options'=>array('enctype' => 'multipart/form-data')
				] );?>
                    <?php
					
				echo Form::widget ( [ 
						
						'model' => $user_model,
						'form' => $form,
						'columns' => 2,
						'attributes' => [ 
								
								'first_name' => [ 
										'label' => Yii::t('app', 'First Name'),
										'type' => Form::INPUT_TEXT,
										'options' => [ 
												'placeholder' => Yii::t('app', 'Enter First Name').'...',
												'maxlength' => 255 ,
												'data-validation'=>'required'
										] 
								],
								
								'last_name' => [ 
										'label' => Yii::t('app', 'Last Name'),
										'type' => Form::INPUT_TEXT,
										'options' => [ 
												'placeholder' => Yii::t('app', 'Enter Last Name').'...',
												'maxlength' => 255,
												'data-validation'=>'required' 
										] 
								],
								
								'username' => [ 
										'label' => Yii::t('app', 'Username'),
										'type' => Form::INPUT_TEXT,
										'options' => [ 
												'placeholder' => Yii::t('app', 'Enter Username').'...',
												'maxlength' => 255 ,
												'data-validation'=>'required'
										] 
								],

								'email' => [ 
										'label' => Yii::t('app', 'Email'),
										'type' => Form::INPUT_TEXT,
										'options' => [ 
												'placeholder' => Yii::t('app', 'Enter Email').'...',
												'maxlength' => 255 ,
												'data-validation'=>'required'
										] 
								],
								
								/*//'password'=>['type'=> Form::INPUT_PASSWORD, 'options'=>['placeholder'=>'Enter Password...', 'maxlength'=>255,
												'data-validation'=>'required']]*/
						] 
				] );
				if(Yii::$app->params['AUTO_PASSWORD'] !='Yes'){
				echo Form::widget ( [ 
						
						'model' => $user_model,
						'form' => $form,
						'columns' => 2,
						'attributes' => [ 
								
								'password_hash'=>[
									'label' => Yii::t('app', 'Password'),
									'type'=> Form::INPUT_PASSWORD, 'options'=>['placeholder'=>Yii::t('app', 'Enter Password').'...', 'maxlength'=>255,
												'data-validation'=>'required']]
						] 
				] );
				}
				echo '<input type="hidden" name="User[active]" value="0">
					  <input type="hidden" name="User[user_type_id]" value="'.UserTypeSearch::getCompanyUserType('Employee')->id.'">
                      <input type="hidden" name="User[added_at]" value="'.time().'">';

				
				echo Html::submitButton ( $user_model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), [ 
						'class' => $user_model->isNewRecord ? 'btn btn-primary userSubmit' : 'btn btn-primary userSubmit' 
				] );
				ActiveForm::end ();
				?>
               
			</div>
           			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade msg">
			  <div class="modal-dialog">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=Yii::t('app', 'Close')?></span></button>
					<h4 class="modal-title"><?=Yii::t('app', 'Account Registered!')?></h4>
				  </div>
					<div class="modal-body">
						<?php echo $msg?>
					</div>
            </div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
</div><!-- /.modal -->



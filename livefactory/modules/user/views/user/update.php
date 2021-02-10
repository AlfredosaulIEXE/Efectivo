<?php



use yii\helpers\Html;
use livefactory\models\search\User;
use livefactory\models\search\CommonModel;
use livefactory\models\search\UserType as UserTypeSearch;



/**

 * @var yii\web\View $this

 * @var common\models\User $model

 */



$this->title = Yii::t('app', 'Update User').' : ' . ' ' . $model->first_name." ".$model->last_name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];

$this->params['breadcrumbs'][] = ['label' => $model->first_name, 'url' => ['view', 'id' => $model->id]];

$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
function getUserRoles($id){
	$connection = \Yii::$app->db;
		$sql="select auth_item.* from auth_item,auth_assignment where auth_item.type=2 and auth_assignment.user_id=".$id." and auth_assignment.item_name=auth_item.name";
		$command=$connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$roles ='<ul class="list-group">';
		if(count($dataReader) > 0){
			foreach($dataReader as $role){
				$roles.='<li class="list-group-item">'.$role['name']."</li>";
			}
		}else{
			return '<div class="alert alert-danger">No Roles</div>';
		}
		
		return $roles."</ul>";	
}

?>

<script src="../../vendor/bower/jquery/dist/jquery.js"></script>

<script type="text/javascript"> 

$(document).ready(function(){
    if($('#user-user_type_id').val() ==<?=UserTypeSearch::getCompanyUserType('Customer')->id?>){
		 $('.field-user-entity_id').show();
	}else{
		 $('.field-user-entity_id').hide();
	}
	$('.tabbable').appendTo('#w0');
	$('#user-user_type_id').change(function(){
        if($(this).val() ==<?=UserTypeSearch::getCompanyUserType('Customer')->id?>){
			 $('.field-user-entity_id').show();
		}else{
			 $('.field-user-entity_id').hide();
		}
	})
	// $('#user-username').attr('disabled',true);
	$('#user-user_type_id').attr('disabled',true);
	$('#user-entity_id').attr('disabled',true);
	//$('.ddddd').modal('show');
if('<?php echo $model->username ?>' =='admin' || '<?php echo $model->username ?>' =='Admin'){
		//$('#user-user_role_id').attr('disabled',true);
		$('.field-user-user_type_id').hide();
		$('#user-user_type_id').hide();
	}
	if('<?=!empty($new_password)?$new_password:''?>' !=''){
	
	$('.msg-pwd').modal('show');

	//alert("La nueva contraseña es: "+'<?=!empty($new_password)?$new_password:''?>');

			//window.location.href='index.php?r=user/user/update&id=<?=$model->id?>&edit=t';

	}
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			
			reader.onload = function (e) {
				$('.upload').attr('src', e.target.result);
			}
			
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	
	$(".inp").change(function(){
		readURL(this);
		ajaxFileUpload(this);
		//$('#w0').submit();
	});
	$('.upload').click(function(){
		$('.inp').click();
	})
	function ajaxFileUpload(upload_field)
	{
	    console.log(upload_field.form);
	// Checking file type
		/*var re_text = /\.jpg|\.gif|\.jpeg/i;
		var filename = upload_field.value;
			if (filename.search(re_text) == -1) {
				alert("File should be either jpg or gif or jpeg");
				upload_field.form.reset();
				return false;
			}*/
	document.getElementById('picture_preview').innerHTML = '<div><img src="http://i.hizliresim.com/xAmY7B.gif" width="100%" border="0" /></div>';
	upload_field.form.action = 'index.php?r=user/user/update&id=<?=$_GET['id']?>';
	upload_field.form.target = 'upload_iframe';
	upload_field.form.submit();
	upload_field.form.action = '';
	upload_field.form.target = '';
	setTimeout(function(){
	document.getElementById('picture_preview').innerHTML = '';
	},2500)
	return true;
	}

	

});

</script>
<style>
.project-index .kv-panel-before,.project-index .kv-panel-after,.queue-index .kv-panel-before,.queue-index .kv-panel-after{
	padding:0px !important
}
</style>
<iframe name="upload_iframe" id="upload_iframe" style="display:none;"></iframe>
<div class="user-update">

	

	<!--

    <h1><?= Html::encode($this->title) ?></h1>

	-->



    <?= $this->render('_form', [

        'model' => $model,

    ]) ?>

<div class="tabbable">
<?php
if( 1 == 2)
{
?>
					<?php 
				if(in_array('pmt',yii::$app->params['modules'])){
				$alertCountDefect= CommonModel::getUserDefectsCount($model->id);
				$alertCountTask = CommonModel::getUserPendingTasksCount($model->id);
				$alertCountProject= CommonModel::getUserProjectsCount($model->id);
				}
				if(in_array('support',yii::$app->params['modules'])){
				$alertCountTicket= CommonModel::getUserTicketsCount($model->id);
				}?>
				
               <ul class="nav nav-tabs">
                <li class="active"><a href="#roles" role="tab" data-toggle="tab"><?php echo Yii::t('app', 'Roles'); ?></a></li>
				 
				 <?php if(in_array('pmt',yii::$app->params['modules'])){ ?>
				 <li><a href="#projects" role="tab" data-toggle="tab"><?php echo Yii::t('app', 'Projects'); ?> 
                 	<span class="badge"><?=$alertCountProject?></span>
                 </a></li>
				 
				
				 <li><a href="#tasks" role="tab" data-toggle="tab"><?php echo Yii::t('app', 'Tasks'); ?> 
                  	<span class="badge"><?=$alertCountTask?></span></a></li>

					<li><a href="#defects" role="tab" data-toggle="tab"><?php echo Yii::t('app', 'Defects'); ?>
                  	<span class="badge"><?=$alertCountDefect?></span></a></li>
					
				 <?php } 
				 if(in_array('support',yii::$app->params['modules'])){
				 ?>
					<li><a href="#queues" role="tab" data-toggle="tab"><?php echo Yii::t('app', 'Queues'); ?></a></li>

					<li><a href="#tickets" role="tab" data-toggle="tab"><?php echo Yii::t('app', 'Tickets'); ?>
                	<span class="badge"><?=$alertCountTicket?></span></a></li>
				 <?php } 
				 if(in_array('pmt',yii::$app->params['modules']) || in_array('support',yii::$app->params['modules']) ){
				 ?>
					<li><a href="#timesheet" role="tab" data-toggle="tab"><?php echo Yii::t('app', 'Timesheet'); ?></a></li>
               <?php } ?>
                
                </ul>
            
            <div class="tab-content">
                <div class="tab-pane active" id="roles"> 
                <br/>	
                	<div class="panel panel-info">
                    	<div class="panel-heading">
                        	<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> <?php echo Yii::t('app', 'Roles'); ?>
                            	<div class="pull-right">
                                	
                                </div>
                            </h3>
                        </div>
                        <div class="panel-body">
                        <a href="index.php?r=liveobjects/setting/rights&assign_user_id=<?=$model->id?>" class="btn btn-primary btn-sm">Roles & Operations</a>
                        	<?=getUserRoles($model->id)?>
                            
                        </div>
                    </div>
                </div>
				<?php if (in_array('support',yii::$app->params['modules'])){?>
                <div class="tab-pane fade" id="queues"> 
                <br/>	
                     <?php
                    $search = new User();
					$dataProvider = $search->searchQueue( Yii::$app->request->getQueryParams (), $model->id);
					
					echo Yii::$app->controller->renderPartial("queue_tab", [ 
							'dataProvider' => $dataProvider
					] );
					
					?>        
                </div>
				
				<?php }
				if (in_array('pmt',yii::$app->params['modules'])){ ?>
                <div class="tab-pane fade" id="projects"> 
                <br/>			
                   <?php
                    $search = new User();
					$dataProvider = $search->searchProject( Yii::$app->request->getQueryParams (), $model->id);
					
					echo Yii::$app->controller->renderPartial("project_tab", [ 
							'dataProvider' => $dataProvider
					] );
					
					?> 
                </div>
				
				
                <div class="tab-pane fade" id="tasks"> 
                <br/>			
                    <?php
                    $search = new User();
					$dataProvider = $search->searchTask( Yii::$app->request->getQueryParams (), $model->id);
					
					echo Yii::$app->controller->renderPartial("task_tab", [ 
							'dataProvider' => $dataProvider
					] );
					
					?>   
                </div>
				<?php }
				if(in_array('pmt',yii::$app->params['modules']) || in_array('support',yii::$app->params['modules']) ){
				?>
                <div class="tab-pane fade" id="timesheet"> 
                <br/>			
                    <?php
                    $search = new User();
					$dataProvider = $search->searchTimeEntry( Yii::$app->request->getQueryParams (), $model->id);
					
					echo Yii::$app->controller->renderPartial("timing_tab", [ 
							'dataProvider' => $dataProvider
					] );
					
					?>  	        
                </div>
				<?php } 
				if( in_array('support',yii::$app->params['modules']) ){
				?>
                <div class="tab-pane" id="tickets"> 
                <br/>	
                <?php
                    $search = new User();
					$dataProvider = $search->searchTicket( Yii::$app->request->getQueryParams (), $model->id);
					
					echo Yii::$app->controller->renderPartial("ticket_tab", [ 
							'dataProvider' => $dataProvider
					] );
					
					?>      
                </div>
				<?php } 
				if( in_array('pmt',yii::$app->params['modules']) ){
				?>
				<div class="tab-pane" id="defects"> 
                <br/>	
                <?php

                    $search = new User();
					$dataProvider = $search->searchDefect( Yii::$app->request->getQueryParams (), $model->id);
					
					echo Yii::$app->controller->renderPartial("defect_tab", [ 
							'dataProvider' => $dataProvider
					] );
					
					?>      
                </div>
				<?php } ?>
				
            </div>
            <?php
}
				echo Html::submitButton ( $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [ 
						'class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm' 
				] );
				if(Yii::$app->user->identity->userType->type!="Customer")
				{
				if(!empty($_GET['id']) and  strtolower($model->username)  !='admin' ){?>
                <a href="index.php?r=user/user/update&id=<?=$model->id?>&edit=t&active=<?=$model->active !='1'?'yes':'no'?>" onClick="return confirm('Are you Sure')" class="btn <?=$model->active !='1'?'btn-primary btn-sm':'btn-danger btn-sm'?>"><?=Yii::t('app', $model->active !='1'?'Activate User':'Deactivate User')?></a>
                
                <?php } 
				if(!empty($_GET['id']) and  strtolower($model->username)  !='admin' ){?>
                <a href="index.php?r=user/user/update&id=<?=$model->id?>&edit=t&reset_password=true" onClick="return confirm('¿Estás seguro de reiniciar la contraseña de este empleado?')" class="btn btn-success btn-sm"><?php echo Yii::t('app', 'Reset Password'); ?></a>
                
                <?php }
				}
				echo "</form>";
			?>
          </div>

</div>



<div class="modal fade msg-pwd">

  <div class="modal-dialog  modal-lg">

    <div class="modal-content">

    	 <div class="modal-header">

        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

       		 <h4 class="modal-title"><?php echo Yii::t('app', 'Password has been Reset'); ?></h4>

      </div>

    <div class="modal-body">
        <div class="alert alert-success"><?php echo Yii::t('app', 'New Password is'); ?>: <strong><?=!empty($new_password)?$new_password:''?></strong></div>
    </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"<?php if (isset($is_new)): ?> onclick="return window.location.href = '?r=user/user/update&id=<?=$model->id?>';" <?php endif; ?>><?php echo Yii::t('app', 'Close'); ?></button>
        </div>

   </div>

 </div>

</div>



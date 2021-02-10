<?php
use livefactory\modules\pmt\controllers\ProjectController;
use livefactory\models\search\CommonModel;
use yii\helpers\Html;
use livefactory\models\search\Project as ProjectSearch;
use livefactory\models\FileModel;
/**
 * @var yii\web\View $this
 * @var common\models\Project $model
 */
if(isset($_REQUEST['err_msg']))
{
	?>
	<script>
	alert("<?=$_REQUEST['err_msg']?>");
	</script>
	<?php
}
//$this->title = Yii::t('app', 'Update Project; '). ' ' . $model->project_id;
$this->title = $model->project_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Projects'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->project_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
$user_ids = array($model->project_owner_id,1);
if(in_array(Yii::$app->user->identity->id,$user_ids)){
		$disabled=0;
	}else{
			$disabled=1;
	}
/*if(!file_exists('../attachments/project'.$_GET['id'])){
	mkdir('../attachments/project'.$_GET['id']);
}*/
function getUserRoleCounts(){
	$connection = \Yii::$app->db;
	$id = Yii::$app->user->identity->id;
	$sql="select auth_item.* from auth_item,auth_assignment where auth_item.type=2 and auth_assignment.user_id=$id and auth_assignment.item_name=auth_item.name and auth_item.name='Customer'";
		$command=$connection->createCommand($sql);
		$dataReader=$command->queryAll();
		return count($dataReader);	
}
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script src="../../vendor/ckeditor/ckeditor/ckeditor.js"></script>
<script src="../include/bootstrap-datetimepicker.js"></script>

<style>	
.field-project-project_id{display:none}
.cke_contents{max-height:250px}
.slider .tooltip.top {
    margin-top: -36px;
    z-index: 100;
}
.close {
    color: #000000;
    float: right;
    font-size: 18px;
    font-weight: bold;
    line-height: 1;
    opacity: 0.2;
    text-shadow: 0 1px 0 #ffffff;
}
.nav-tabs > li > a{font-size:12px}
/*.nav-tabs > li > a{
	font-size:11px
}*/
</style>
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
function Add_ErrorTag(obj,msg){
	obj.css({'border':'1px solid #D16E6C'});
	
	obj.after('<div style="color:#D16E6C; clear:both" class="error"><i class="icon-remove-sign"></i> '+msg+'</div>');
	 return true;
}
function Remove_ErrorTag(obj){
	obj.removeAttr('style').next('.error').remove();
	return false;
}

$(document).on("click", '#project_task_button', function(event)
{
	//alert($('#task-expected_start_datetime').val());
		
	var error ='';
	var task_expected_actual_start = $('#task-expected_start_datetime').val();
	var task_expected_actual_end = $('#task-expected_end_datetime').val();

	if(task_expected_actual_start !='' && task_expected_actual_end !=''){
		var task_expected_startTime = new Date(task_expected_actual_start);
		var task_expected_endTime = new Date(task_expected_actual_end);
		Remove_Error($('#task-expected_end_datetime'));
		if(task_expected_startTime >= task_expected_endTime){
	
			 error+=Add_Error($('#task-expected_end_datetime'),'<?=Yii::t ('app','Expected Completion Date Should be more than Expected Start Date')?>');
	
		}else{
	
			Remove_Error($('#task-expected_end_datetime'));
	
		}
				if(error !=''){
					event.preventDefault();
					return false;
				}
	}
	
	error ='';
	var actual_actual_start = $('#task-actual_start_datetime').val();
	var actual_actual_end = $('#task-actual_end_datetime').val();

	if(actual_actual_start !='' && actual_actual_end !=''){
		var actual_startTime = new Date(actual_actual_start);
		var actual_endTime = new Date(actual_actual_end);
		Remove_Error($('#task-actual_end_datetime'));
		if(actual_startTime >= actual_endTime){
	
			 error+=Add_Error($('#task-actual_end_datetime'),'<?=Yii::t ('app','Actual Completion Date Should be more than Actual Start Date')?>');
	
		}else{
	
			Remove_Error($('#task-actual_end_datetime'));
	
		}
				if(error !=''){
					event.preventDefault();
					return false;
				}else{
					return true;
				}
	}
})
  
   
$(document).ready(function(){

    $('.tabbable').appendTo('#w0');
    //console.log($('a[data-toggle="tab"]:first').tab('show'))
    $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
        //save the latest tab; use cookies if you like 'em better:
        localStorage.setItem('lastTab_leadview', $(this).attr('href'));
    });

    //go to the latest tab, if it exists:
    var lastTab_leadview = localStorage.getItem('lastTab_leadview');
    if ($('a[href="' + lastTab_leadview + '"]').length > 0) {
        $('a[href="' + lastTab_leadview + '"]').tab('show');
    }
    else
    {
        // Set the first tab if cookie do not exist
        $('a[data-toggle="tab"]:first').tab('show');
    }
	if('<?=!empty($_REQUEST['attach_update'])?$_REQUEST['attach_update']:''?>' !=''){
		$('.popup').modal('show');
		
	}
	if('<?=!empty($_GET['note_id'])?$_GET['note_id']:''?>' !=''){
		$('.note_edit').modal('show');
	}
	/*$('#project-expected_start_datetime-disp').val('<?=$model->expected_start_datetime?>'=='0000-00-00 00:00:00' || '<?=$model->expected_start_datetime?>'==''?'':'<?=date('Y/m/d',strtotime($model->expected_start_datetime))?>');
	$('#project-expected_start_datetime').val('<?=$model->expected_start_datetime?>'=='0000-00-00 00:00:00'?'':'<?=date('Y/m/d',strtotime($model->expected_start_datetime))?>');
	$('#project-expected_end_datetime-disp').val($('#project-expected_end_datetime').val()=='0000-00-00 00:00:00'?'':'<?=date('Y/m/d',strtotime($model->expected_end_datetime))?>');
	$('#project-actual_start_datetime-disp').val($('#project-actual_start_datetime').val()=='0000-00-00 00:00:00'?'':'<?=date('Y/m/d',strtotime($model->actual_start_datetime))?>');
	$('#project-actual_end_datetime-disp').val($('#project-actual_end_datetime').val()=='0000-00-00 00:00:00'?'':'<?=date('Y/m/d',strtotime($model->actual_end_datetime))?>');*/
	// Disabled Customer Field
	if('<?=$model->customer_id?>' !='')
	$('#project-customer_id').attr('disabled','disabled');
	if('<?=$disabled?>'=='1'){
		/////$('#project-project_owner_id').attr('disabled','disabled');
	}
	if('<?=!empty($_GET['tasktab'])?$_GET['tasktab']:''?>' !=''){
		//alert('ddd');
		$('.tasktab').tab('show');	
	}
	
	if('<?=!empty($_GET['defecttab'])?$_GET['defecttab']:''?>' !=''){
		//alert('ddd');
		$('.defecttab').tab('show');	
	}
	if('<?=!empty($_GET['joined_user'])?$_GET['joined_user']:''?>' !=''){
		$('.joined_user').tab('show');	
	}
	/*$('#project-actual_start_datetime-disp').val($('#project-actual_start_datetime').val()=='0000-00-00 00:00:00'?'':'<?=date('Y/m/d',strtotime($model->actual_start_datetime))?>');
	$('#project-actual_end_datetime-disp').val($('#project-actual_end_datetime').val()=='0000-00-00 00:00:00'?'':'<?=date('Y/m/d',strtotime($model->actual_end_datetime))?>');*/
	CKEDITOR.config.autoParagraph = false;
	$('#w32 #task-user_assigned_id').load('index.php?r=pmt/task/ajax-project-users&project_id=<?=$model->id?>');
	$('#task-user_assigned_id').load('index.php?r=pmt/task/ajax-project-users&project_id=<?=$model->id?>');
	$('#defect-user_assigned_id').load('index.php?r=pmt/defect/ajax-project-users&project_id=<?=$model->id?>');
$('#w0').submit(function(event){
		error ='';
	var expected_actual_start = $('#project-expected_start_datetime').val();
	var expected_actual_end = $('#project-expected_end_datetime').val();

	if(expected_actual_start !='' && expected_actual_end !=''){
		var expected_startTime = new Date(expected_actual_start);
		var expected_endTime = new Date(expected_actual_end);
		Remove_Error($('#project-expected_end_datetime'));
		if(expected_startTime >= expected_endTime){
	
			 error+=Add_Error($('#project-expected_end_datetime'),'<?=Yii::t ('app','Expected Completion Date Should be more than Expected Start Date')?>');
	
		}else{
	
			Remove_Error($('#project-expected_end_datetime'));
	
		}
				if(error !=''){
					event.preventDefault();
					return false;
				}else{
					return true;
				}
	}
 })
});

</script>
 <div>
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
    					<h5> <?=Yii::t('app', 'Project')?> - <?=$model->project_id ?></h5>

						<div class="ibox-tools">
						
				<!-- previous / next button addded by deepak on 14 dec 2015 -->
				<?php if($previous = $model->prev) { ?> 
  <?= Html::a('Previous',  ['/pmt/project/project-view', 'id' => $previous->id], ['class'=>'btn btn-primary btn-xs']); ?>
				<?php } ?>
				<?php if($next = $model->next) { ?> 
  <?= Html::a('Next',  ['/pmt/project/project-view', 'id' => $next->id], ['class'=>'btn btn-primary btn-xs']); ?>
				<?php } ?>
				<!-- previous / next button addded by deepak on 14 dec 2015 -->
				<?php
				if(Yii::$app->user->identity->userType->type!="Customer")
				{
				?>
							<a href="index.php?r=pmt%2Fproject%2Fgroup-chat&id=<?=$model->id?>" class="btn btn-xs btn-info" style="color:#fff;"><span class="glyphicon glyphicon-new-window"></span> <?=Yii::t('app', 'Project Discussion board')?></a>
                            
                            <a href="index.php?r=customer/customer/customer-view&id=<?=$model->customer_id?>" class="btn btn-xs btn-info" style="color:#fff;"><span class="glyphicon glyphicon-new-window"></span> <?=Yii::t('app', 'View Customer')?></a>
				<?php
				}
				?>
						    <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                           
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
			<!--
        	<div class="pull-right">
            	<a href="index.php?r=customer/customer/customer-view&id=<?=$model->customer_id?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-new-window"></span> View Customer</a>&nbsp;
                <a class="close" href="index.php?r=pmt/project/index" >
                	<span class="glyphicon glyphicon-remove"></span>
                </a>
            </div>
			-->
    </div>
  					 <div class="ibox-content">
        <div class="project-update">
        
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        
        </div>
        <div class="tabbable">
                <ul class="nav nav-tabs">
                <li class="active"><a href="#desc" role="tab" data-toggle="tab"> <?=Yii::t('app', 'Description')?></a></li>
				
				<?php
				if(Yii::$app->user->can('Project.Update'))
				{
				?>
				<li><a href="#attachments" role="tab" data-toggle="tab"><?=Yii::t('app', 'Attachments')?>
                <span class="badge"> <?= FileModel::getAttachmentCount('project',$model->id)?></span>
                </a></li>
       
                <li><a href="#notes" role="tab" data-toggle="tab"><?=Yii::t('app', 'Notes')?></a></li>
                <li><a href="#assign_history" role="tab" data-toggle="tab"><?=Yii::t('app', 'Assignment History')?></a></li>
                <li><a href="#tasktab" class="tasktab" role="tab" data-toggle="tab"><?=Yii::t('app', 'Tasks')?>
                <span class="badge"> <?= CommonModel::getProjectTasksCount($model->id)?></span>
                </a></li>
                
                <li><a href="#defects" class="defecttab" role="tab" data-toggle="tab"><?=Yii::t('app', 'Defects')?>
                 <span class="badge"> <?= CommonModel::getProjectDefectsCount($model->id)?></span>
                </a></li>
                <li><a href="#user" class="joined_user" role="tab" data-toggle="tab"><?=Yii::t('app', 'Users')?>
                <span class="badge"> <?= CommonModel::getProjectUsersCount($model->id)?></span>
                </a>
                	
                </li>
                <!--<li><a href="#history" role="tab" data-toggle="tab">History</a></li>-->
                <li><a href="#history" role="tab" data-toggle="tab"><?=Yii::t('app', 'Activities')?></a></li>
               <!-- <?php
				if($model->project_owner_id ==Yii::$app->user->identity->id){?>
                 <li><a href="#timesheet" role="tab" data-toggle="tab"><?=Yii::t('app', 'Timesheet')?></a></li>
				<?php  } ?>-->
                <li><a href="#info" role="tab" data-toggle="tab"><?= Yii::t('app', 'Info')?></a></li>
				<?php
				}
				?>
                </ul>
            
            <div class="tab-content">
            <div class="tab-pane  active" id="desc"> 
            <br/>
            <?php
            echo '<div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                <label class="control-label" for="lname">'.Yii::t('app', 'Description').':
            
                                </label>
                                <div class="controls">
                                  <textarea class="form-control input-sm ckeditor" name="Project[project_description]" id="project_description" rows="8" style="width:100%">'.htmlspecialchars($model->project_description).'</textarea>
                                </div>
                            </div>
                            </div>
                        </div>';
            ?>
            </div>
            <div class="tab-pane" id="attachments"> 
            <br/>
                       <?php
                                        
                                        $searchModelAttch = new CommonModel();
                                        $dataProviderAttach = $searchModelAttch->searchAttachments( Yii::$app->request->getQueryParams (), $model->id,'project' );
                                        
                                        echo Yii::$app->controller->renderPartial("../../../liveobjects/views/file/attachment-module/attachments", [ 
                                                'dataProviderAttach' => $dataProviderAttach,
                                                'searchModelAttch' => $searchModelAttch,
                                                'task_id'=>$model->id
                                        ] );
                                        
                                        ?>
            </div>
            <div class="tab-pane fade" id="notes"> 
            <br/>	
                         <?php
                                        
                                        $searchModelNotes = new CommonModel();
                                        $dataProviderNotes = $searchModelNotes->searchNotes( Yii::$app->request->getQueryParams (), $model->id ,'project');
                                        
                                        echo Yii::$app->controller->renderPartial("../../../liveobjects/views/note/notes-module/notes", [ 
                                                'dataProviderNotes' => $dataProviderNotes,
                                                'searchModelNotes' => $searchModelNotes
                                        ] );
                                        
                                        ?>
            </div>
            <div class="tab-pane fade" id="history"> 
            <br/>			
                      <?php
                                        
                                        $searchModelHistory = new CommonModel();
                                        $dataProviderHistory = $searchModelHistory->searchHistory( Yii::$app->request->getQueryParams (), $model->id,'project' );
                                        
                                        echo Yii::$app->controller->renderPartial("../../../liveobjects/views/history/history-module/histories", [ 
                                                'dataProviderHistory' => $dataProviderHistory,
                                                'searchModelHistory' => $searchModelHistory 
                                        ] );
                                        
                                        ?>
            </div>
            <div class="tab-pane fade" id="assign_history"> 
            <br/>			
                     <?php
                                        
                        $searchModelAssHis = new CommonModel();
                        $dataProviderAssHis = $searchModelAssHis->searchAssignedHistory( Yii::$app->request->getQueryParams (), $model->id,'project' );
                        
                        echo Yii::$app->controller->renderPartial("../../../liveobjects/views/history/history-module/assigned_histories", [ 
                                'dataProviderAssHis' => $dataProviderAssHis,
                                'searchModelAssHis' => $searchModelAssHis 
                        ] );
                        
                        ?>     
            </div>
            <div class="tab-pane fade" id="user"> 
            <br/>			
                    <?php
                                        
                        $searchModelUser = new ProjectSearch();
                        $dataProviderUser = $searchModelUser->searchProjectUser( Yii::$app->request->getQueryParams (), $model->id );
                        $_SESSION['project_user_id']=$model->project_owner_id;
                        echo Yii::$app->controller->renderPartial("user_tab", [ 
                                'dataProviderUser' => $dataProviderUser,
                                'searchModelUser' => $searchModelUser ,
								'project_user_id'=>$model->project_owner_id
                        ] );
                        
                        ?>      
            </div>
            <div class="tab-pane fade" id="tasktab"> 
            <br/>			
                    <?php
                                        
                        $searchModelTask = new ProjectSearch();
                        $dataProviderTask = $searchModelTask->searchTask( Yii::$app->request->getQueryParams (), $model->id );
                        
                        echo Yii::$app->controller->renderPartial("task_tab", [ 
                                'dataProviderTask' => $dataProviderTask,
                              //  'searchModelTask' => $searchModelTask 
                        ] );
                        
                        ?>      
            </div>
            
            <div class="tab-pane fade" id="defects"> 
            <br/>			
                    <?php
                                        
                        $searchModelDefect = new ProjectSearch();
                        $dataProviderDefect = $searchModelDefect->searchDefect( Yii::$app->request->getQueryParams (), $model->id );
                        
                        echo Yii::$app->controller->renderPartial("defect_tab", [ 
                                'dataProviderDefect' => $dataProviderDefect,
                                'searchModelDefect' => $searchModelDefect 
                        ] );
                        
                        ?>      
            </div>
            <div class="tab-pane fade" id="activity"> 
            <br/>			
                    <?php
                                        
                        $searchModelActivity = new CommonModel();
                        $dataProviderActivity = $searchModelActivity->searchActivity( Yii::$app->request->getQueryParams (), $model->id,'project' );
                        
                        echo Yii::$app->controller->renderPartial("../../../liveobjects/views/history/history-module/activity_tab", [ 
                                'dataProviderActivity' => $dataProviderActivity,
                                'searchModelActivity' => $searchModelActivity 
                        ] );
                        
                        ?>      
            </div>
			<div class="tab-pane fade" id="timesheet"> 
    <br/>			
            <?php
                                
                $searchModelTime = new ProjectSearch();
                $dataProviderTime = $searchModelTime->searchTimesheet( Yii::$app->request->getQueryParams (), $model->id);
                echo Yii::$app->controller->renderPartial("timing_tab", [ 
                        'dataProviderTime' => $dataProviderTime
                ] );
                
                ?>      
    </div>
    <div class="tab-pane fade" id="info"> 
    <br/>			
		<div class="panel panel-info">
        	<div class="panel-heading">
            	
                <h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> <?= Yii::t('app','Information')?></h3>
            </div>
            <div class="panel-body">
           	 <table class="table table-bordered">
           	<thead>
            	<tr>
                	<th><?= Yii::t('app','Added By')?></th>
                    <th><?= Yii::t('app','Added When')?></th>
                    <th><?= Yii::t('app','Updated By')?></th>
                    <th><?= Yii::t('app','Updated When')?></th>
                </tr>
            </thead>
            <tbody>
            	<tr>
                	<td>
						<?php
                    	if(isset($model->addedByUser->first_name)){
									echo $model->addedByUser->first_name." ".$model->addedByUser->last_name." (".$model->addedByUser->username.")</i><br/>";	
						}else{
							echo '<i class="not-set">'.Yii::t('app', 'not set').'</i>';
						}
						?>
                    </td>
                    <td>
                    	 <?php
                    		echo  date('jS \of F Y H:i:s',$model->added_at);
						?>
                    </td>
                    <td>
                    <?php
                    	if(isset($model->lastUpdateUser->first_name)){
								echo $model->lastUpdateUser->first_name." ".$model->lastUpdateUser->last_name." (".$model->lastUpdateUser->username.")</i><br/>";	
					}else{
						echo '<i class="not-set">'.Yii::t('app', 'not set').'</i>';
						}
					?>
                    </td>
                    <td>
                    	<?php
                    	if(isset($model->updated_at) && $model->updated_at >0){
							echo  date('jS \of F Y H:i:s',$model->updated_at);	
						}else{
							echo '<i class="not-set">'.Yii::t('app', 'not set').'</i>';
							}
						?>
                    </td>
                </tr>	
            </tbody>
           </table>
            </div>
        </div>
                 
    </div>
            <input type="hidden" name="old_owner" value="<?=$model->project_owner_id?>">
            </div>
            <?php
if(($model->project_owner_id ==Yii::$app->user->identity->id || Yii::$app->params['user_role']=='admin') && Yii::$app->user->can('Project.Update')) {
            echo Html::submitButton ( $model->isNewRecord ?Yii::t('app', 'Create')  : Yii::t('app', 'Update'), [ 
            
                                    'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary btn-sm  update_task' 
            
                            ] );
?> <a href="javascript:void(0)" class="btn btn-success btn-sm" onClick="$('.add-notes-modal').modal('show');"><i class="glyphicon glyphicon-comment"></i> <?=Yii::t('app', 'New Note')?> </a>
							<?php
							if($model->project_owner_id ==Yii::$app->user->identity->id || Yii::$app->params['user_role']=='admin'){
		echo '<a href="javascript:void(0)" class="btn btn-success btn-sm" onClick="$(\'.exist_users\').modal(\'show\');"><i class="glyphicon glyphicon-user"></i> '.Yii::t('app', 'Add User').'</a>';
	}?>
    <?php if(Yii::$app->params['PROJECT_FILE_MANAGER'] =='Yes'){?>
                            <a href="index.php?r=pmt/project/file-manager&entity_type=project&entity_id=<?=$model->id?>" class="btn btn-success btn-sm" ><i class="glyphicon glyphicon-save"></i> <?=Yii::t('app', 'New Attachment')?> </a>
		<?php } else{?>
        <a href="javascript:void(0)" class="btn btn-success btn-sm" onClick="$('.savepopup').modal('show');"><i class="glyphicon glyphicon-save"></i> <?=Yii::t('app', 'New Attachment')?> </a>
        <?php } ?>
                            <a href="javascript:void(0)" class="btn btn-success btn-sm" onClick="$('.taskae').modal('show');"><i class="fa fa-edit"></i> <?=Yii::t('app', 'New Task')?></a>
                            
                            <a class="btn btn-success btn-sm" onclick="$('.defectae').modal('show');" href="javascript:void(0)">
<i class="fa fa-bug"></i> <?=Yii::t('app', 'New Defect')?></a>
                                
                            
                            
                            <?php
	}
            
                            //ActiveForm::end ();
                            echo "</form>";
            ?>
            </div>
    </div>
   				</div>
           </div>
       </div>
</div>
<?php
	$email=ProjectController::getUserEmail($model->project_owner_id);
	include_once(__DIR__ .'/../../../liveobjects/views/file/attachment-module/attachmentae.php');
	include_once(__DIR__ .'/../../../liveobjects/views/note/notes-module/noteae.php');
	include_once('taskae.php');
	
	include_once('defectae.php');
	include_once('join_user.php');
?>
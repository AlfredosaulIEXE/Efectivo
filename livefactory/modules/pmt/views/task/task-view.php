<?php
use livefactory\modules\pmt\controllers\TaskController;
use livefactory\models\search\CommonModel;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use livefactory\models\FileModel;
use kartik\widgets\ActiveForm;

use dosamigos\ckeditor\CKEditor;
use kartik\builder\Form;
use livefactory\models\search\Task as TaskSearch;
use livefactory\models\search\TaskStatus;
/**
 * @var yii\web\View $this
 * @var common\models\Task $model
 */

if(isset($_REQUEST['err_msg']))
{
	?>
	<script>
	alert("<?=$_REQUEST['err_msg']?>");
	</script>
	<?php
}

//$this->title = Yii::t('app', 'Update Task') . ' ' . $model->task_name;
$this->title = $model->task_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tasks'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->task_id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script src="../../vendor/ckeditor/ckeditor/ckeditor.js"></script>
<script src="../include/bootstrap-datetimepicker.js"></script>
<style>	
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
 
$(document).ready(function(){


	CKEDITOR.config.autoParagraph = false;
    $('.tabbable').appendTo('#w0');

	if('<?=!empty($_REQUEST['attach_update'])?$_REQUEST['attach_update']:''?>' !=''){
		$('.popup').modal('show');
		
	}
	if('<?=!empty($_GET['note_id'])?$_GET['note_id']:''?>' !=''){
		$('.note_edit').modal('show');
	}
	if('<?=!empty($_GET['time_entry_id'])?$_GET['time_entry_id']:''?>' !=''){
		$('.timing').modal('show');
	}
	$('.stopTime').click(function(){
		$('.timenotes').modal('show');
		
	})
	//Sub-Task Script
	$(document).on('click','.taskScript',function(){
			 $('.form_datetime1').datetimepicker({
				 format: 'yyyy/mm/dd hh:ii:ss',
				 autoclose:true
				//format: 'D d M yyyy hh:ii:ss'
			});
			$('.form_datetime2').datetimepicker({
				format: 'yyyy/mm/dd hh:ii:ss',
				autoclose:true
				//format: 'D d M yyyy hh:ii:ss'
			});
			$('.form_datetime3').datetimepicker({
				format: 'yyyy/mm/dd hh:ii:ss',
				autoclose:true
			//	format: 'D d M yyyy hh:ii:ss'
			});
			$('.form_datetime4').datetimepicker({
				format: 'yyyy/mm/dd hh:ii:ss',
				autoclose:true
				//format: 'D d M yyyy hh:ii:ss'
			});
	})
	 $('.subtask_insert').click(function(event){
		 Remove_Error($('.expected_end_datetime'));
		 Remove_Error($('.expected_start_datetime'));
		if($('.expected_start_datetime').val()==''){
			 Add_Error($('.expected_start_datetime'),"<?=Yii::t ('app','This Field is Required!')?>");
			 event.preventDefault();
		}else{
			Remove_Error($('.expected_start_datetime'));
		}
		if($('.expected_end_datetime').val()==''){
			 Add_Error($('.expected_end_datetime'),"<?=Yii::t ('app','This Field is Required!')?>");
			 event.preventDefault();
		}else{
			Remove_Error($('.expected_end_datetime'));
		}
	 })
	$('#task-expected_start_datetime-disp').val($('#task-expected_start_datetime').val()=='0000-00-00 00:00:00'?'':'<?=date('Y/m/d H:i:s',strtotime($model->expected_start_datetime))?>');
	$('#task-expected_end_datetime-disp').val($('#task-expected_end_datetime').val()=='0000-00-00 00:00:00'?'':'<?=date('Y/m/d H:i:s',strtotime($model->expected_end_datetime))?>');
	$('#task-actual_start_datetime-disp').val($('#task-actual_start_datetime').val()=='0000-00-00 00:00:00'?'':'<?=date('Y/m/d H:i:s',strtotime($model->actual_start_datetime))?>');
	$('#task-actual_end_datetime-disp').val($('#task-actual_end_datetime').val()=='0000-00-00 00:00:00'?'':'<?=date('Y/m/d H:i:s',strtotime($model->actual_end_datetime))?>');
	
$('#task-actual_end_datetime').change(function(){
	setTimeout(function(){
	if($('#task-task_status_id').val() ==<?=TaskStatus::_COMPLETED?>){
		var actual_start = $('#task-actual_start_datetime').val();
		var actual_end = $('#task-actual_end_datetime').val();
		var startTime = new Date(actual_start);
		var endTime = new Date(actual_end);
		//alert($('#task-actual_end_datetime').val());
	//	alert(startTime>endTime);
		Remove_Error($('#task-actual_end_datetime-disp'));
		if(startTime>endTime){
			 Add_Error($('#task-actual_end_datetime-disp'),"<?=Yii::t ('app','Start Time Should be Less than Completion Time!')?>");
			 $('.update_task').attr('disabled',true);
		}else{
			Remove_Error($('#task-actual_end_datetime-disp'));
			  $('.update_task').removeAttr('disabled');
		}
	}
	},1000)
})
$('#task-task_status_id').change(function(){
	if($('#task-task_status_id').val() == <?=TaskStatus::_COMPLETED?> && '<?=Yii::$app->params['SHOW_ADD_NOTES_POPUP_ON_COMPLETION']?>' =='Yes'){
		if('<?=!empty($_COOKIE['inserted_notes'.$model->id])?$_COOKIE['inserted_notes'.$model->id]:''?>' != '1'){
			$('.add-notes-modal').modal('show');
		}
	}
})

	

$('.update_task').click(function(event){
	debugger;
	error ='';
		var expected_start_datetime = $('#task-expected_start_datetime').val();
		var expected_end_datetime = $('#task-expected_end_datetime').val();
		Remove_Error($('#task-expected_start_datetime-datetime'));
		Remove_Error($('#task-expected_end_datetime-datetime'));
		if(expected_end_datetime !='')
		{
			if(expected_start_datetime !='')
			{
				var expected_start_date = new Date(expected_start_datetime);
				var expected_end_date = new Date(expected_end_datetime);
				if(expected_start_date > expected_end_date)
				{
					error+=Add_Error($('#task-expected_end_datetime-datetime'),"<?=Yii::t ('app','Expected End Should be After Expected Start')?>");
				}
				else
				{
					Remove_Error($('#task-expected_end_datetime-datetime'));
				}
			}
			else
			{
				error+=Add_Error($('#task-expected_start_datetime-datetime'),"<?=Yii::t ('app','Expected Start is Required')?>");
			}
		}

		var actual_start_datetime = $('#task-actual_start_datetime').val();
		var actual_end_datetime = $('#task-actual_end_datetime').val();
		Remove_Error($('#task-actual_start_datetime-datetime'));
		Remove_Error($('#task-actual_end_datetime-datetime'));
		if(actual_end_datetime !='')
		{
			if(actual_start_datetime !='')
			{
				var actual_start_date = new Date(actual_start_datetime);
				var actual_end_date = new Date(actual_end_datetime);
				if(actual_start_date > actual_end_date)
				{
					error+=Add_Error($('#task-actual_end_datetime-datetime'),"<?=Yii::t ('app','Actual End Should be After Actual Start')?>");
				}
				else
				{
					Remove_Error($('#task-actual_end_datetime-datetime'));
				}
			}
			else
			{
				error+=Add_Error($('#task-actual_start_datetime-datetime'),"<?=Yii::t ('app','Actual Start is Required')?>");
			}
		}

	
		$('#cke_1_contents').parent().parent().removeAttr('style').next('.error').remove();
		sageLength = CKEDITOR.instances['task_description'].getData().replace(/<[^>]*>/gi, '').length;
		if(sageLength==0){
			Add_ErrorTag($('#cke_1_contents').parent().parent(),"<?=Yii::t ('app','This Field is Required!')?>");
			event.preventDefault();
		}



	if($('#task-task_status_id').val() == <?=TaskStatus::_COMPLETED?> && '<?=Yii::$app->params['SHOW_ADD_NOTES_POPUP_ON_COMPLETION']?>' =='Yes'){
			if('<?=!empty($_COOKIE['inserted_notes'.$model->id])?$_COOKIE['inserted_notes'.$model->id]:''?>' != '1'){
				alert("<?=Yii::t ('app','Please Add Notes!')?>");
				$('.add-notes-modal').modal('show');
				event.preventDefault();
			}else{
				
				error='';
				Remove_Error($('#task-actual_start_datetime-disp'));
				Remove_Error($('#task-actual_end_datetime-disp'));
				
				var actual_start = $('#task-actual_start_datetime').val();
				var actual_end = $('#task-actual_end_datetime').val();
				var startTime = new Date(actual_start);
				var endTime = new Date(actual_end);
				 //alert($('#task-actual_end_datetime').val());
	            // alert(startTime>endTime);
				if(startTime>endTime){
					 error+=Add_Error($('#task-actual_end_datetime-disp'),"<?=Yii::t ('app','Start Time Should be Less than Completion Time')?>");
				}else{
					Remove_Error($('#task-actual_end_datetime-disp'));
				}
				if($('#task-actual_start_datetime-disp').val() ==''){
					error+=Add_Error($('#task-actual_start_datetime-disp'),"<?=Yii::t ('app','This field Required!')?>");
				}else{
					Remove_Error($('#task-actual_start_datetime-disp'));
				}
				if($('#task-actual_start_datetime-disp').val() ==''){
					error+=Add_Error($('#task-actual_end_datetime-disp'),"<?=Yii::t ('app','This field Required!')?>");
				}else{
					Remove_Error($('#task-actual_end_datetime-disp'));
				}
				if(error !=''){
					event.preventDefault();
					return false;
				}else{
					return true;
				}
			}
		}else{
			if(error !=''){
					event.preventDefault();
					return false;
				}else{
					return true;
				}		
		}
	})
	if('<?=$model->project_id?>' !='')
	$('#task-project_id').attr('disabled','disabled');
	
	$('#task-project_id').change(function(){
	 $.post('index.php?r=pmt/task/ajax-project-users&project_id='+$(this).val(),function(r){
		$('#task-user_assigned_id').html(r) ;
	 })
   })
   $('#task-user_assigned_id,.user_assigned_id').load('index.php?r=pmt/task/ajax-project-users&project_id=<?=$model->project_id?>&user_id=<?=$model->user_assigned_id?>');
   $('#w32 #task-user_assigned_id').load('index.php?r=pmt/task/ajax-project-users&project_id=<?=$model->project_id?>');
});
if('<?=!empty($_COOKIE['start_time'])?$_COOKIE['start_time']:''?>' !=''){
	setInterval(function(){
		$.post('<?=str_replace('index.php','',$_SESSION['base_url'])?>ajax_clock.php',function(result){
			var alink ='<i class="glyphicon glyphicon-time"></i> Stop Timer '+result;
				$(".stopTime").html(alink);	
			})
		},1000)
}
</script>
<div class="ibox float-e-margins">
                    <div class="ibox-title">
						<h5><?= $model->task_id ?></h5>
                        <div class="ibox-tools">
							<?php if($model->parent_id){?>
            	<a href="index.php?r=pmt/task/task-view&id=<?=$model->parent_id?>" class="btn btn-xs btn-info" style="color:#fff;"><span class="glyphicon glyphicon-new-window"></span> <?= Yii::t('app', 'View Parent Task')?></a>&nbsp;
                <?php } ?>
				<!-- previous / next button addded by deepak on 14 dec 2015 -->
				<?php if(($previous = $model->prev) && (($model->user_assigned_id == Yii::$app->user->identity->id)||(Yii::$app->params['user_role'] =='admin'))) { ?> 
  <?= Html::a('Previous',  ['/pmt/task/task-view', 'id' => $previous->id], ['class'=>'btn btn-primary btn-xs']); ?>
				<?php } ?>
				<?php if(($next = $model->next) && (($model->user_assigned_id == Yii::$app->user->identity->id)||(Yii::$app->params['user_role'] =='admin'))) { ?> 
  <?= Html::a('Next',  ['/pmt/task/task-view', 'id' => $next->id], ['class'=>'btn btn-primary btn-xs']); ?>
				<?php } ?>
				<!-- previous / next button addded by deepak on 14 dec 2015 -->
				
                <a href="index.php?r=pmt/project/project-view&id=<?=$model->project_id?>" class="btn btn-xs btn-info" style="color:#fff;"><span class="glyphicon glyphicon-new-window"></span> <?= Yii::t('app', 'View Project')?></a>&nbsp;
						    <a class="collapse-link">
								 <i class="fa fa-chevron-up"></i>
						    </a>
                            <a class="close-link">
								 <i class="fa fa-times"></i>
							</a>
                        </div>
                    </div>
                    <div class="ibox-content">
						<div class="task-update">

		<?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    
    </div>
					    <div class="tabbable">
        <ul class="nav nav-tabs">
        <li class="active"><a href="#desc" role="tab" data-toggle="tab"><?= Yii::t('app', 'Task Description')?></a></li>
        <li><a href="#attachments" role="tab" data-toggle="tab"><?= Yii::t('app', 'Attachments')?>	
           
            <span class="badge"> <?= FileModel::getAttachmentCount('task',$model->id)?></span>
        </a></li>
        <li><a href="#notes" role="tab" data-toggle="tab"><?= Yii::t('app', 'Notes')?></a></li>
        <li><a href="#subtasktab" role="tab" data-toggle="tab"><?= Yii::t('app', 'SubTasks')?>
        	<span class="badge"><?= TaskSearch::getSubTaskCount($model->id)?></span>
        	 
        </a></li>
        <li><a href="#timesheet" role="tab" data-toggle="tab"><?= Yii::t('app', 'Timesheet')?></a></li>
        <li><a href="#assign_history" role="tab" data-toggle="tab"><?= Yii::t('app', 'Assignment History')?></a></li>
        <!--<li><a href="#history" role="tab" data-toggle="tab">History</a></li>-->
        <li><a href="#history" role="tab" data-toggle="tab"><?= Yii::t('app', 'Activity')?></a></li>
        <li><a href="#info" role="tab" data-toggle="tab"><?= Yii::t('app', 'Informations')?></a></li>
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
                          <textarea class="form-control input-sm ckeditor" name="Task[task_description]" id="task_description" rows="8" style="width:100%">'.htmlspecialchars($model->task_description).'</textarea>
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
                                $dataProviderAttach = $searchModelAttch->searchAttachments( Yii::$app->request->getQueryParams (), $model->id,'task' );
                                
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
                                $dataProviderNotes = $searchModelNotes->searchNotes( Yii::$app->request->getQueryParams (), $model->id,'task' );
                                
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
                                $dataProviderHistory = $searchModelHistory->searchHistory( Yii::$app->request->getQueryParams (), $model->id,'task' );
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
                $dataProviderAssHis = $searchModelAssHis->searchAssignedHistory( Yii::$app->request->getQueryParams (), $model->id,'task'  );
                
                echo Yii::$app->controller->renderPartial("../../../liveobjects/views/history/history-module/assigned_histories", [ 
                        'dataProviderAssHis' => $dataProviderAssHis,
                        'searchModelAssHis' => $searchModelAssHis 
                ] );
                
                ?>     
    </div>
    <div class="tab-pane fade" id="timesheet"> 
    <br/>			
            <?php
                                
                $searchModelTaskTime = new CommonModel();
                $dataProviderTaskTime = $searchModelTaskTime->searchTimeEntry( Yii::$app->request->getQueryParams (), $model->id,'task'  );
				
                $_SESSION['project_id']=$model->project_id ;
               echo Yii::$app->controller->renderPartial("../../../liveobjects/views/timesheet/timesheets", [ 
                        'dataProvider' => $dataProviderTaskTime,
                        'searchModel' => $searchModelTaskTime,
						'user_assigned_id'=>$model->user_assigned_id,
						'cookie_id'=>'taskStartedId',
                ] );
                
                ?>      
    </div>
    <div class="tab-pane fade" id="subtasktab"> 
    <br/>			
            <?php
                                
                $searchModelTask = new TaskSearch();
                $dataProviderTask = $searchModelTask->searchSubTask( Yii::$app->request->getQueryParams (), $model->id );
                
                echo Yii::$app->controller->renderPartial("sub_task_tab", [ 
                        'dataProviderTask' => $dataProviderTask,
                        'searchModelTask' => $searchModelTask 
                ] );
                
                ?>      
    </div>
    <div class="tab-pane fade" id="activity"> 
    <br/>			
            <?php
                                
                $searchModelActivity = new CommonModel();
                $dataProviderActivity = $searchModelActivity->searchActivity( Yii::$app->request->getQueryParams (), $model->id,'task' );
                
                echo Yii::$app->controller->renderPartial("../../../liveobjects/views/history/history-module/activity_tab", [ 
                        'dataProviderActivity' => $dataProviderActivity,
                        'searchModelActivity' => $searchModelActivity 
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
    <input type="hidden" name="old_owner" value="<?=$model->user_assigned_id?>">
    <input type="hidden" name="old_task_priority_id" value="<?=$model->task_priority_id?>">
    <input type="hidden" name="old_task_status_id" value="<?=$model->task_status_id?>">
    </div>
    <?php
		
    echo Html::submitButton ( $model->isNewRecord ? Yii::t('app', 'Create' ): Yii::t('app', 'Update'), [ 
    
                            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary btn-sm  update_task' 
    
                    ] );?> <a href="javascript:void(0)" class="btn btn-success btn-sm" onClick="$('.add-notes-modal').modal('show');"><i class="glyphicon glyphicon-comment"></i> <?= Yii::t('app', 'New Note')?></a>
                    <a href="javascript:void(0)" class="btn btn-success btn-sm" onClick="$('.savepopup').modal('show');"><i class="glyphicon glyphicon-save"></i> <?= Yii::t('app', 'New Attachment')?></a>
                    <a href="javascript:void(0)" class="btn btn-success btn-sm taskScript" onClick="$('.taskae').modal('show');"><i class="glyphicon glyphicon-tag"></i> <?= Yii::t('app', 'Add Subtask')?></a>
                     
                     <?php
if(Yii::$app->user->identity->id == $model->user_assigned_id || Yii::$app->params['user_role'] == 'admin'){
                    if(!empty($_COOKIE['start_time'])){
                        if($_COOKIE['taskStartedId']==$_GET['id']){
                    ?>
                    <a href="javascript:void(0)" class="btn btn-sm   btn-danger <?=$_COOKIE['start_time']?'':'hideBtn'?> stopTime"  data-toggle="modal"><i class="glyphicon glyphicon-time"></i> <?= Yii::t('app', 'End Timer')?></a>
                    <?php 
                        }
                    }else{?>
                        <a href="index.php?r=pmt/task/task-view&id=<?=$_GET['id']?>&starttime=true" class="btn btn-sm  btn-success <?=$_COOKIE['start_time']?'hideBtn':''?>"><i class="glyphicon glyphicon-time"></i> <?= Yii::t('app', 'Start Timer')?></a>
                    <?php }
					 }
					?>
                   
                    <?php
    
                    //ActiveForm::end ();
                    echo "</form>";
    ?>
    </div>
					</div>
</div>


<?php
	//$entity_user=$model->user_assigned_id;
	$email=TaskController::getUserEmail($model->user_assigned_id);
	include_once(__DIR__ .'/../../../liveobjects/views/file/attachment-module/attachmentae.php');
	include_once(__DIR__ .'/../../../liveobjects/views/note/notes-module/noteae.php');
	$entity_type='task';//// This Variable is Impotant 
	include_once(__DIR__ .'/../../../liveobjects/views/timesheet/timesheetae.php');
	include_once(__DIR__ .'/../../../liveobjects/views/timesheet/timenote.php');
?>

<?php
$_SESSION['pid']=$model->project_id;
	include_once('sub-task.php');
	//include_once('task_timingae.php');
	///include_once('task_time_noteae.php');
?>

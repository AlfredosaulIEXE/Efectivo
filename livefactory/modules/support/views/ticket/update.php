<?php

use yii\helpers\Html;
use livefactory\modules\support\controllers\TicketController;
use livefactory\models\search\CommonModel;
use yii\helpers\ArrayHelper;
use livefactory\models\FileModel;
use livefactory\models\TicketStatus;
use kartik\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use kartik\builder\Form;
use livefactory\models\search\Ticket as TicketSearch;
use livefactory\models\search\TicketResolution as TicketResolutionSearch;

/**
 * @var yii\web\View $this
 * @var livefactory\models\Ticket $model
 */
if(isset($_REQUEST['err_msg']))
{
	?>
	<script>
	alert("<?=$_REQUEST['err_msg']?>");
	</script>
	<?php
}

$this->title = Yii::t('app', $model->ticket_id);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tickets'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
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



$(document).ready(function(){

// Resolution modal popup can't be closed unless resolution is saved.

	CKEDITOR.config.autoParagraph = false;
	//if('<?=getUserRoleCounts()>0?'ss':''?>' !='')
	$('#ticket-ticket_customer_id').attr('disabled',true);
	
    $('.tabbable').appendTo('#w0');
   // $('#ticket-due_date-disp').val($('#ticket-due_date').val()=='0000-00-00'?'':'<?=date('Y/m/d H:i:s',strtotime($model->due_date))?>');
	if('<?=!empty($_REQUEST['attach_update'])?$_REQUEST['attach_update']:''?>' !=''){

		$('.popup').modal('show');
	}

	/* Keep this commented code as it can be useful in future. Below code makes popup box undismissable
	if($('#ticket-ticket_status_id').val()==3)
	{
		//$('#ticket-ticket_status_id').attr('disabled',true);
		$('#myresolution').modal({
			backdrop: 'static',
			keyboard: false
		})
	}*/

    //resolution popup page
   /* if('<?=$_GET['id']?>'!='')
    {
        //alert('dfdf');
        $('#ticket-ticket_status_id').change(function(e){
            if($(this).val()==3)
            {
                $('#myresolution').modal('show');
            }
        })
    }*/

	if('<?=!empty($_GET['note_id'])?$_GET['note_id']:''?>' !=''){
		$('.note_edit').modal('show');
	}

	$('#ticket-ticket_status_id').change(function(){
		if($('#ticket-ticket_status_id').val() == <?=TicketStatus::_COMPLETED?> && '<?=Yii::$app->params['SHOW_ADD_NOTES_POPUP_ON_COMPLETION']?>' =='Yes'){
			if('<?=!empty($_COOKIE['inserted_notes'.$model->id])?$_COOKIE['inserted_notes'.$model->id]:''?>' != '1'){
				$('.add-notes-modal').modal('show');
			}
		}
	})

	$('.update_ticket').click(function(event){
	error='';
	if($('#ticket-ticket_status_id').val() == <?=TicketStatus::_COMPLETED?> && '<?=Yii::$app->params['SHOW_ADD_NOTES_POPUP_ON_COMPLETION']?>' =='Yes'){
			if('<?=!empty($_COOKIE['inserted_notes'.$model->id])?$_COOKIE['inserted_notes'.$model->id]:''?>' != '1'){
				alert('<?=Yii::t ('app','Please Add Notes!')?>');
				$('.add-notes-modal').modal('show');
				event.preventDefault();
			}else{
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

	$('#ticket-department_id').change(function(){
	 $.post('index.php?r=support/ticket/ajax-department-queue&department_id='+$(this).val(),function(r){
		$('#ticket-queue_id').html(r) ;
	 });

	  $.post('index.php?r=support/ticket/ajax-ticket-category&department_id='+$(this).val(),function(r){
		$('#ticket-ticket_category_id_1').html(r) ;
	 })
	})
	 $('#ticket-queue_id').load('index.php?r=support/ticket/ajax-department-queue&department_id=<?=$model->department_id?>&queue_id=<?=$model->queue_id?>');
	 $('#ticket-ticket_category_id_1').load('index.php?r=support/ticket/ajax-ticket-category&department_id=<?=$model->department_id?>&ticket_category_id_1=<?=$model->ticket_category_id_1?>');

	$('#ticket-queue_id').change(function(){
	 $.post('index.php?r=support/ticket/ajax-queue-users&queue_id='+$(this).val(),function(r){
		$('#ticket-user_assigned_id').html(r) ;
	 })
	})
	 $('#ticket-user_assigned_id').load('index.php?r=support/ticket/ajax-queue-users&queue_id=<?=$model->queue_id?>&user_id=<?=$model->user_assigned_id?>');
	

	$('#ticket-ticket_category_id_1').change(function(){
	 $.post('index.php?r=support/ticket/ajax-category-change&ticket_category_id='+$(this).val(),function(r){
		$('#ticket-ticket_category_id_2').html(r) ;
	 })
   })
	$('#ticket-ticket_category_id_2').load('index.php?r=support/ticket/ajax-category-change&ticket_category_id=<?=$model->ticket_category_id_1?>&ticket_category_id_2=<?=$model->ticket_category_id_2?>');

	 /*if('<?=$model->queue_id?>' !='')
	$('#ticket-queue_id').attr('disabled','disabled');*/
	
	
	if('<?=!empty($_GET['time_entry_id'])?$_GET['time_entry_id']:''?>' !=''){
		$('.timing').modal('show');
	}
	$('.stopTime').click(function(){
		$('.timenotes').modal('show');
	})
	$('.field-ticket-added_at').hide();
	if('<?=!empty($_COOKIE['ticket_start_time'])?$_COOKIE['ticket_start_time']:''?>' !=''){
	setInterval(function(){
			$.post('<?=str_replace('index.php','',$_SESSION['base_url'])?>ajax_clock3.php',function(result){
				var alink ='<i class="glyphicon glyphicon-time"></i> Stop Timer '+result;
						$(".stopTime").html(alink);	
					})
			},1000)
	}
});
</script>
<div class="ticket-update">
   <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?= $this->title?></h5>
                        <div class="ibox-tools">
						<!-- previous / next button addded by deepak on 17 jun 2017 -->
	<?php if(($previous = $model->prev) && (($model->user_assigned_id == Yii::$app->user->identity->id)||(Yii::$app->params['user_role'] =='admin') || (Yii::$app->user->identity->userType->type=="Customer"))) { ?> 
  <?= Html::a('Previous',  ['/support/ticket/update', 'id' => $previous->id], ['class'=>'btn btn-primary btn-xs']); ?>
				<?php } ?>
				<?php if(($next = $model->next) && (($model->user_assigned_id == Yii::$app->user->identity->id)||(Yii::$app->params['user_role'] =='admin') ||(Yii::$app->user->identity->userType->type=="Customer"))) { ?> 
  <?= Html::a('Next',  ['/support/ticket/update', 'id' => $next->id], ['class'=>'btn btn-primary btn-xs']); ?>
				<?php } ?>
				<!-- previous / next button addded by deepak on 17 jun 2017 -->
						<!--<a class="btn btn-xs btn-primary emailShow" href="javascript:void(0)" onClick="$('.ticketemail').modal('show')">
							<i class="fa fa-envelope"></i> <?= Yii::t('app','Email')?>
						</a>-->
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
							]) ?>
                    </div>
					<div class="tabbable">
        <ul class="nav nav-tabs">
        <li class="active"><a href="#desc" role="tab" data-toggle="tab"><?= Yii::t('app', 'Ticket Description')?></a></li>
		<?php
		if(Yii::$app->user->can('Ticket.Update'))
		{
		?>
			<li><a href="#attachments" role="tab" data-toggle="tab"><?= Yii::t('app', 'Attachments')?>	
				  <span class="badge"> <?= FileModel::getAttachmentCount('ticket',$model->id)?></span>
			</a></li>

			<li><a href="#notes" role="tab" data-toggle="tab"><?= Yii::t('app', 'Notes')?></a></li>
			<li><a href="#timesheet" role="tab" data-toggle="tab"><?= Yii::t('app', 'Timesheet')?></a></li>
			<li><a href="#assign_history" role="tab" data-toggle="tab"><?= Yii::t('app', 'Assignment History')?></a></li>
			
			<li><a href="#history" role="tab" data-toggle="tab"><?= Yii::t('app', 'Activity')?></a></li>
			<li><a href="#info" role="tab" data-toggle="tab"><?= Yii::t('app', 'Information')?></a></li>
			<li><a href="#resolution" role="tab" data-toggle="tab"><?= Yii::t('app', 'Resolution')?></a></li>
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
                          <textarea class="form-control input-sm ckeditor" name="Ticket[ticket_description]" id="ticket_description" rows="8" style="width:100%">'.htmlspecialchars($model->ticket_description).'</textarea>
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
                                $dataProviderAttach = $searchModelAttch->searchAttachments( Yii::$app->request->getQueryParams (), $model->id,'ticket' );
    
                                echo Yii::$app->controller->renderPartial("../../../liveobjects/views/file/attachment-module/attachments", [ 
                                        'dataProviderAttach' => $dataProviderAttach,
                                        'searchModelAttch' => $searchModelAttch,
                                        'ticket_id'=>$model->id
                                ] );
                                ?>
    </div>
	<div class="tab-pane fade" id="timesheet"> 
    <br/>			
            <?php
       
                $searchModelTaskTime = new CommonModel();
                $dataProviderTaskTime = $searchModelTaskTime->searchTimeEntry( Yii::$app->request->getQueryParams (), $model->id,'ticket'  );

               echo Yii::$app->controller->renderPartial("../../../liveobjects/views/timesheet/timesheets", [ 
                        'dataProvider' => $dataProviderTaskTime,
                        'searchModel' => $searchModelTaskTime,
						'user_assigned_id'=>$model->user_assigned_id,
						'cookie_id'=>'ticketStartedId',
                ] );

                ?>      

    </div>
    <div class="tab-pane fade" id="notes"> 
    <br/>	

                 <?php

                                $searchModelNotes = new CommonModel();
                                $dataProviderNotes = $searchModelNotes->searchNotes( Yii::$app->request->getQueryParams (), $model->id,'ticket' );

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
                                $dataProviderHistory = $searchModelHistory->searchHistory( Yii::$app->request->getQueryParams (), $model->id,'ticket' );
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
                $dataProviderAssHis = $searchModelAssHis->searchAssignedHistory( Yii::$app->request->getQueryParams (), $model->id,'ticket'  );

				echo Yii::$app->controller->renderPartial("../../../liveobjects/views/history/history-module/assigned_histories", [ 
                        'dataProviderAssHis' => $dataProviderAssHis,
                        'searchModelAssHis' => $searchModelAssHis 
                ] );
                ?>     

    </div>

    <div class="tab-pane fade" id="activity"> 

    <br/>			
            <?php

                $searchModelActivity = new CommonModel();
                $dataProviderActivity = $searchModelActivity->searchActivity( Yii::$app->request->getQueryParams (), $model->id,'ticket' );

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

	 <div class="tab-pane fade" id="resolution"> 

    <br/>			
            <?php

                $searchModelResolution = new TicketResolutionSearch();
                $dataProviderResolution = $searchModelResolution->searchResolutions($model->id);

                echo Yii::$app->controller->renderPartial("../ticket-resolution/ticket-tab-index", [ 
                        'dataProvider' => $dataProviderResolution,
                        'searchModel' => $searchModelResolution,
                ] );

                ?>      

    </div>

    <input type="hidden" name="old_owner" value="<?=$model->user_assigned_id?>">
    <input type="hidden" name="old_ticket_priority_id" value="<?=$model->ticket_priority_id?>">
    <input type="hidden" name="old_ticket_status_id" value="<?=$model->ticket_status_id?>">
    </div>

    <?php
	if(Yii::$app->user->can('Ticket.Update'))
	{
    echo Html::submitButton ( $model->isNewRecord ? Yii::t('app', 'Create' ): Yii::t('app', 'Update'), [ 
                            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary btn-sm  update_ticket' 
                    ] );?> <a href="javascript:void(0)" class="btn btn-success btn-sm" onClick="$('.add-notes-modal').modal('show');"><i class="glyphicon glyphicon-comment"></i> <?= Yii::t('app', 'New Note')?></a>

                    <a href="javascript:void(0)" class="btn btn-success btn-sm" onClick="$('.savepopup').modal('show');"><i class="glyphicon glyphicon-save"></i> <?= Yii::t('app', 'New Attachment')?></a>
					<a href="javascript:void(0)" class="btn btn-success btn-sm" onClick="$('#myresolution').modal('show');"><i class="glyphicon glyphicon-plus"></i> <?= Yii::t('app', 'New Resolution')?></a>
					<a href="javascript:void(0)" class="btn btn-success btn-sm" onClick="$('.linkwithexisting').modal('show');"><i class="glyphicon glyphicon-link"></i> <?= Yii::t('app', 'Link With Existing Resolution')?></a>
                    <?php                     
                    if($model->user_assigned_id!=Yii::$app->user->identity->id){
                    ?>
                    <a href="index.php?r=support/ticket/index&ticket_assigned_id=<?=$_REQUEST['id']?>&page=update" class="btn btn-primary btn-sm"><?= Yii::t('app', 'Yank')?></a>
                    <?php
                    }
                    ?>

                    <?php
	}
                    //ActiveForm::end ();
                    echo "</form>";
    ?>
        </div>
    </div>
</div>
<?php

	//$entity_user=$model->user_assigned_id;

	$email=TicketController::getUserEmail($model->user_assigned_id);

	include_once(__DIR__ .'/../../../liveobjects/views/file/attachment-module/attachmentae.php');
	include_once(__DIR__ .'/../../../liveobjects/views/note/notes-module/noteae.php');
	$entity_type='ticket';//// This Variable is Impotant 
	include_once(__DIR__ .'/../../../liveobjects/views/timesheet/timesheetae.php');
	include_once(__DIR__ .'/../../../liveobjects/views/timesheet/timenote.php');
    include_once(__DIR__ .'/../../../liveobjects/views/resolution/resolution-module/ticket_resolution.php');

?>

<div class="modal ticketemail">
	<form method="post" action=""  enctype="multipart/form-data">
	<?php Yii::$app->request->enableCsrfValidation = true; ?>
		<input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
	  
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=Yii::t('app', 'Send Mail')?></h4>
		  </div>

		  <div class="modal-body">
				<div class="form-group">
					<label><?=Yii::t('app', 'To')?></label>
					<input type="text" name="uemail" id="uemail"  value="<?= $model->customer->email?>" readonly class="form-control uemail" >
					<span class="help-block"></span>
				</div>

				<div class="form-group">
					<label><?=Yii::t('app', 'Subject')?></label>
					<input type="text" name="subject" class="form-control" value="RE: <?php echo $model->ticket_title ?>" readonly id="esubject" >
					<span class="help-block"></span>
				</div>

				<div class="form-group">
					<label><?=Yii::t('app', 'Body')?></label>
					<textarea class="form-control" name="email_body" rows="8" id="ebody"></textarea>
					<span class="help-block"></span>
				</div>
		  </div>

		  <div class="modal-footer">
			<button type="submit" class="btn btn-primary btn-sm" id="send_email">
				<i class="fa fa-envelope"></i> <?=Yii::t('app', 'Send Email')?></button>
			<button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</form>
</div><!-- /.modal -->
<?php

namespace livefactory\modules\support\controllers;

use Yii;
use livefactory\models\NoteModel;
use livefactory\models\FileModel;
use livefactory\models\AssignmentHistoryModel;
use livefactory\models\HistoryModel;
use livefactory\models\File;
use livefactory\models\User;
use livefactory\models\Note;
use livefactory\models\History;
use livefactory\models\Sla;
use livefactory\models\TimeEntry;
use livefactory\models\AssignmentHistory;
use livefactory\models\AuthAssignment;


use livefactory\models\Ticket;
use livefactory\models\Queue;
use livefactory\models\TicketStatus;
use livefactory\models\TicketPriority;
use livefactory\models\search\Ticket as TicketSearch;
use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use livefactory\models\SendEmail;
use livefactory\models\Customer as CustomerDetail;
use livefactory\models\User as UserDetail;
use livefactory\models\search\CommonModel as SessionVerification;
use livefactory\models\TimeDiffModel;
use livefactory\models\TimesheetModel;
use livefactory\models\TicketResolution;
use livefactory\models\ResolutionReference;

use \Datetime;
use \DateInterval;

/**
 * TicketController implements the CRUD actions for Ticket model.
 */
class TicketController extends Controller
{
	public $entity_type='ticket';
	public static function getUserEmail($id){
		$userModel = UserDetail::findOne($id);	
		return $userModel->email;
	}
	public static function getCustomerEmail($id){
		$userModel = CustomerDetail::findOne($id);	
		return $userModel->email;
	}
	public static function getCustomerFullName($id){
		$user = CustomerDetail::findOne($id);
		
		return $user->first_name." ".$user->last_name;	
	}
	public static function getUserFullName($id){
		$user = UserDetail::findOne($id);
		
		return $user->first_name." ".$user->last_name;	
	}
	public static function getLoggedUserFullName(){
		$user = UserDetail::findOne(Yii::$app->user->identity->id);
		return $user->first_name." ".$user->last_name;	
	}
	public static function getLoggedUserDetail(){
		$user = UserDetail::find()->where('id='.Yii::$app->user->identity->id)->asArray()->one();
		return $user;	
	}
	public function ticketIdUdate($id){
		$zirolengh=6-intval(strlen($id));
		$stringId ="TICKET".str_repeat("0", $zirolengh).$id;
		$ticketUpdate= Ticket::find()->where(['id' => $id])->one();
		$ticketUpdate->ticket_id=$stringId;
		$ticketUpdate->update();
		return 	$stringId;
	}
	public function updateTicketSpendTime($id){
		$taskModel = TimeEntry::find()->where("entity_id=$id and entity_type='".$this->entity_type."'")->asArray()->all();
					
		$spend_t=0;
		$dotNumTot=0;
		$secondTot=0;
		$solidNumTot=0;
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		foreach($taskModel as $trow1){
			list($solidNum,$dotNum,$seconds) = explode('.',TimeDiffModel::getTimeDiff(date('Y/m/d H:i:s',($trow1['start_time'])),date('Y/m/d H:i:s',($trow1['end_time']))));
			$solidNumTot+=$solidNum;
			$dotNumTot+=$dotNum;
			$secondTot+=$seconds;
		}
		///Seconds
		list($plusNum1)=explode('.',$secondTot/60);
		$seconddotVal=round($secondTot%60);
		$dotNumTot =$dotNumTot+$plusNum1;
		///////$seconddotVal=strlen($seconddotVal)==1?"0".$seconddotVal:$seconddotVal;
		
		list($plusNum)=explode('.',$dotNumTot/60);
		$dotVal=round($dotNumTot%60);
		$solidNum =$solidNumTot+$plusNum;
		$dotVal=strlen($dotVal)==1?"0".$dotVal:$dotVal;
		$solidNum=strlen($solidNum)==1?"0".$solidNum:$solidNum;
		$spend_t=$solidNum.".".$dotVal;
		// Update Task Spend Time 
		$editTicket= Ticket::findOne($id);//->where(['id' =>$id])->one();
		$editTicket->time_spent=$spend_t;
		//$editTask->modified_at=strtotime(date('Y-m-d H:i:s'));
		//$editTicket->updated_at=strtotime(date('Y-m-d H:i:s'));
		$editTicket->updated_at= time();
		$editTicket->update();	
	}
	public function getTicketStuts($id){
		$status = TicketStatus::findOne($id);
		
		return $status->label;	
	}
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
	public function init(){
		SessionVerification::checkSessionDestroy();
    	if(empty(Yii::$app->user->identity->id)){
          $this->redirect(array('/site/login'));
		}
	}
    /**
     * Lists all Ticket models.
     * @return mixed
     */
    public function actionIndex()
    {
		if(!Yii::$app->user->can('Ticket.Index')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		if(!empty($_REQUEST['multiple_del'])){
			if(!Yii::$app->user->can('Ticket.Delete')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
			$rows=$_REQUEST['selection'];
			for($i=0;$i<count($rows);$i++){
				$this->findModel($rows[$i])->delete();
			}
		}
		if(!empty($_REQUEST['ticket_assigned_id']))
        {        	
        	$id = $_REQUEST['ticket_assigned_id'];        	
        	$ticketUpdate = Ticket::find()->where(['id' => $id])->one();        	
			$ticketUpdate->user_assigned_id=Yii::$app->user->identity->id;
			$ticketUpdate->update();
			if($_REQUEST['page']=="update")
			{
				return $this->redirect(['update', 'id'=>$id]);
			}
			if(!empty($_REQUEST['Ticket']['queue_id']))
			{
				return $this->redirect(['index', 'Ticket[queue_id]' => $_REQUEST['Ticket']['queue_id'],'r'=>'support/ticket/index']);	
			}	
			if($_REQUEST['page']=="index")
			{
				return $this->redirect(['index']);
			}	
        }
        $searchModel = new TicketSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);

    }
	

    /**
     * Displays a single Ticket model.
     * @param integer $id
     * @return mixed
     */
	 
	 public function actionQueue($id){
		 
		 if(!Yii::$app->user->can('Queue.Index')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		
		$operatorIds = Yii::$app->authManager->getUserIdsByRole('Admin'); 
		
        $searchModel = new TicketSearch;
		if(in_array( Yii::$app->user->identity->id,$operatorIds)) //Admin
		{
			$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
		}
		else
		{
			$dataProvider = $searchModel->searchTicketsWithQueueID($id);
		}

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
	 }
	 
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->id]);
        } else {
        return $this->render('view', ['model' => $model]);
}
    }

    /**
     * Creates a new Ticket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		if(!Yii::$app->user->can('Ticket.Create')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}

	    $model = new Ticket;
		$emailObj = new SendEmail;

		if($model->load(Yii::$app->request->post()))
		{
			if(isset($_GET['customer_id']))
				$model->ticket_customer_id = $_GET['customer_id'];
			else
				{
					if(Yii::$app->user->identity->entity_type == 'customer')
					{
						$model->ticket_customer_id = Yii::$app->user->identity->entity_id;
					}
				}	

			if ($model->save()) {
				$stringId='TICKET'.str_pad($model->id, 9, "0", STR_PAD_LEFT);
				$model->ticket_id=$stringId;
				$model->added_at = time();
				$email_to_customer_title = '';

				if(Yii::$app->params['PREPEND_TICKET_ID_IN_TITLE'] == 'Yes')
				{
					$model->ticket_title = "[".$stringId."] ".$model->ticket_title;
					$email_to_customer_title = $model->ticket_title;
				}
				else
				{
					$email_to_customer_title = "[".$stringId."] ".$model->ticket_title;
				}
				
				$slaObj = Sla::find()->where('ticket_priority_id ='.$model->ticket_priority_id .' and ticket_impact_id="'.$model->ticket_impact_id.'"')->one();
				$slaSecs=$slaObj->sla * 60 * 60;
				$dueDate=$model->added_at+$slaSecs;
				$model->due_date = $dueDate;

				$model->save();

				// Add Notes
				$note = new Note();
				$note->entity_id=$model->id;
				$note->entity_type='ticket';
				$note->notes=$model->ticket_description;
				$note->user_id=$model->added_by_user_id;
				$note->added_at=time();
				$note->save();

				//Add History For Ticket
				HistoryModel::historyInsert($this->entity_type,$model->id,'Ticket Created with Id  <a href="index.php?r=support/ticket/update&id='.$model->id.'">'.$stringId.'</a>');
				
				/* send email to customer on ticket creation with title and status  */
				$ticketStatusModel = TicketStatus::findOne($model->ticket_status_id);
				$emailObj->sendCustomerEmailTemplate($this->getCustomerEmail($model->ticket_customer_id),$this->getCustomerFullName($model->ticket_customer_id),'<a href="'.$_SESSION['base_url'].'?r=support/ticket/update&id='.$model->id.'">'.$stringId.'</a>',$email_to_customer_title,$ticketStatusModel->label);

				// Entry on Assigned History
				if($model->user_assigned_id){
					AssignmentHistoryModel::assignHistoryInsert($this->entity_type,$model->id,$model->user_assigned_id,'Ticket Assigned to user');
					
					$emailObj->sendTicketEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),'<a href="'.$_SESSION['base_url'].'?r=support/ticket/update&id='.$model->id.'">'.$stringId.'</a>',$model->ticket_title,$model->ticket_description);
				}
				if(!empty($_GET['customer_id'])){
					 return $this->redirect(['/customer/customer/customer-view', 'id' =>$_GET['customer_id']]);
				}
				if(Yii::$app->params['SHOW_ADD_ATTACHMENT_PAGE_NEW_TICKET'] =='Yes'){
					return $this->redirect(['add-attachment', 'entity_id' => $model->id]);
				}else{
					return $this->redirect(['update', 'id' => $model->id]);
				}
			} else {
				return $this->render('create', [
					'model' => $model,
				]);
			}
		}
		else {
				return $this->render('create', [
					'model' => $model,
				]);
			}
    }
	public function actionAddAttachment(){
			//$attachType = array('doc','xls','pdf','images','audio','vedio','zip');
			if(!empty($_FILES['attach'])){
				$file=FileModel::bulkFileInsert($_REQUEST['entity_id'],$this->entity_type);
			
            return $this->redirect(['update', 'id' => $_REQUEST['entity_id']]);
			} else {
            return $this->render('add-attachment');
        }
		
	}
    /**
     * Updates an existing Ticket model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		if(!(Yii::$app->user->can('Ticket.Update') || Yii::$app->user->can('Ticket.View'))) {
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
        $model = $this->findModel($id);
		$emailObj = new SendEmail;
		$attachModelR='';
		$noteModelR=$timeEntryModel='';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$model->last_updated_by_user_id = Yii::$app->user->identity->id;
			$model->updated_at = time();

			$slaObj = Sla::find()->where('ticket_priority_id ='.$model->ticket_priority_id .' and ticket_impact_id="'.$model->ticket_impact_id.'"')->one();
			$slaSecs=$slaObj->sla * 60 * 60;
			$newDueDate=$model->added_at+$slaSecs;
			$model->due_date = $newDueDate;
			//print_r($newDueDate);print_r($model->due_date);exit;
			$model->save();

			$old_owner=!empty($_REQUEST['old_owner'])?$_REQUEST['old_owner']:'';
			$old_ticket_priority_id=!empty($_REQUEST['old_ticket_priority_id'])?$_REQUEST['old_ticket_priority_id']:'';
			$old_ticket_status_id=!empty($_REQUEST['old_ticket_status_id'])?$_REQUEST['old_ticket_status_id']:'';
			


			/// Assigned user Changed
			if($model->user_assigned_id != $old_owner)
			{
				$old_owner = $old_owner==''?0:$old_owner;
				$model->user_assigned_id = $model->user_assigned_id ==''?0:$model->user_assigned_id;
				
				if($model->user_assigned_id != 0)
				{
					//Send an Email
					$emailObj->sendTicketChangedUserEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),$this->getLoggedUserFullName(),'<a href="'.$_SESSION['base_url'].'?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>',$this->getTicketStuts($model->ticket_status_id));
				}

				AssignmentHistoryModel::assignHistoryChange($this->entity_type,$model->id,$model->user_assigned_id,$old_owner,"Changed Assigned User",$model->added_at);
				
				//Add History
				HistoryModel::historyInsert($this->entity_type,$model->id,"History is updated as Ticket is assigned to ".$this->getUserFullName($model->user_assigned_id)." by ".$this->getUserFullName($old_owner).' into ( <a href="'.$_SESSION['base_url'].'?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>)');
			}
			/// Ticket Priority Changed
			if($model->ticket_priority_id != $old_ticket_priority_id){
				$ticketPriorityModel = TicketPriority::findOne($model->ticket_priority_id);
				$ticketPriorityModelOld = TicketPriority::findOne($old_ticket_priority_id);
				//Add History
				HistoryModel::historyInsert($this->entity_type,$model->id,"Ticket priority changed from ".$ticketPriorityModelOld->label." to ".$ticketPriorityModel->label." by ".$this->getLoggedUserFullName().'  into ( <a href="index.php?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>)');
				
				//Send an Email
				$emailObj->sendTicketChangedPriorityEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),$this->getLoggedUserFullName(),'<a href="'.$_SESSION['base_url'].'?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>',$ticketPriorityModelOld->label,$ticketPriorityModel->label);
			}
			
			/// Ticket Status Changed
			if($model->ticket_status_id != $old_ticket_status_id){
				$ticketStatusModel = TicketStatus::findOne($model->ticket_status_id);
				$ticketStatusModelOld = TicketStatus::findOne($old_ticket_status_id);
				//Add History
				HistoryModel::historyInsert($this->entity_type,$model->id,"Ticket status changed from ".$ticketStatusModelOld->label." to ".$ticketStatusModel->label." by ".$this->getLoggedUserFullName().'  into ( <a href="index.php?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>)');
				//Send an Email
				$emailObj->sendTicketChangedStatusEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),$this->getLoggedUserFullName(),'<a href="'.$_SESSION['base_url'].'?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>',$ticketStatusModelOld->label,$ticketStatusModel->label);
				/* send email to customer when ticket status is updated  */
				$emailObj->sendCustomerEmailTemplate($this->getCustomerEmail($model->ticket_customer_id),$this->getCustomerFullName($model->ticket_customer_id),'<a href="'.$_SESSION['base_url'].'?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>','['.$model->ticket_id.']',$ticketStatusModel->label);
			}
			
			//Add History
			HistoryModel::historyInsert($this->entity_type,$model->id,'Ticket is updated ( <a href="index.php?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>)');
            	//return $this->redirect(['index']);
			
			return $this->redirect(['update', 'id' => $model->id]);
            
        } else {

			if(!empty($_REQUEST['uemail']))
			{
				$uemail = $_REQUEST['uemail'];
				$body = $_REQUEST['email_body'];
				$cc = '';
				$subject = $_REQUEST['subject'];		

				SendEmail::sendLiveEmail ($uemail, $body, $cc, $subject, false);

				return $this->redirect(['update', 'id' => $_REQUEST['id']]);
			}


        	//add resolution
			if(!empty($_REQUEST['subject'])){
				
				$resolution = new TicketResolution;
				$resolution->subject = $_REQUEST['subject'];
				$resolution->resolution = $_REQUEST['resolution'];
				$resolution->resolved_by_user_id = Yii::$app->user->identity->id;
				$resolution->added_at = time();
				$resolution->save();

				$resolution->resolution_number = 'RESOL'.str_pad($resolution->id, 9, "0", STR_PAD_LEFT);
				$resolution->save();

				$resRef = new ResolutionReference;
				$resRef->ticket_id=$_REQUEST['id'];
				$resRef->resolution_id = $resolution->id;
				$resRef->save();

				$ticObj = Ticket::findOne($_REQUEST['id']);
				$ticObj->ticket_status_id=TicketStatus::_RESOLVED;
				$ticObj->save();
				/*if(!empty($_FILES['res_image']['tmp_name'])){

					move_uploaded_file($_FILES['res_image']['tmp_name'],'../resolution/'.$resolution->id.'.png');

				}*/
				return $this->redirect(['update', 'id' => $_REQUEST['id']]);

			}
			// Send Attachment File to Ticket Assigned User
			if(!empty($_REQUEST['send_attachment_file'])){
				//Send an Email
				SendEmail::sendLiveEmail($_REQUEST['uemail'],$_REQUEST['email_body'], $_REQUEST['cc'], $_REQUEST['subject']);
					return $this->redirect(['update', 'id' => $_REQUEST['id']]);
			}
			// Delete Ticket Attachment
			if(!empty($_REQUEST['attachment_del_id'])){
					$Attachmodel = File::findOne($_REQUEST['attachment_del_id']);
					if (!is_null($Attachmodel)) {
						$Attachmodel->delete();
					}
					//Add History For Ticket
					HistoryModel::historyInsert($this->entity_type,$model->id,$model->ticket_id.' Ticket Attachment Deleted from ( <a href="index.php?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>)');
					return $this->redirect(['update', 'id' => $_REQUEST['id']]);
			}
			// Delete Ticket Notes
			if(!empty($_REQUEST['note_del_id'])){
					$NoteDel = Note::findOne($_REQUEST['note_del_id']);
					if (!is_null($NoteDel)) {
						$NoteDel->delete();
					}
					//Add History For Ticket
					HistoryModel::historyInsert($this->entity_type,$model->id,$model->ticket_id.'Ticket Note  Deleted from ( <a href="index.php?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>)');
					return $this->redirect(['update', 'id' => $_REQUEST['id']]);
			}
			// Unlink Resolution
			if(!empty($_REQUEST['unlink'])){
					
					(new ResolutionReference)->deleteTicketResolution($_REQUEST['unlink'], $model->id);
					
					//Add History For Ticket
					HistoryModel::historyInsert($this->entity_type,$model->id,$model->ticket_id.'Resolution unlinked from ( <a href="index.php?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>)');
					return $this->redirect(['update', 'id' => $_REQUEST['id']]);
			}

			// Link wih Existing Resolutions
			if(!empty($_REQUEST['multiple_link_res']))
			{
				$rows=$_REQUEST['selection'];

				for($i=0;$i<count($rows);$i++)
				{
					$resRef = new ResolutionReference;
					$resRef->resolution_id=$rows[$i];
					$resRef->ticket_id=$_REQUEST['id'];
					$resRef->save();

					//Add History For Ticket
					HistoryModel::historyInsert($this->entity_type, $model->id, 'Ticket linked with new resolution (<a href="index.php?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>)');
				}

				return $this->redirect(['update', 'id' => $_REQUEST['id']]);
			}

			// Add Attachment for Ticket
			if(!empty($_REQUEST['add_attach'])){
				$aid=FileModel::fileInsert($_REQUEST['entity_id'],$this->entity_type);
				if($aid > 0)
				{
					$link="<a href='".str_replace('web/index.php','',$_SESSION['base_url'])."attachments/".$aid.strrchr($_FILES['attach']['name'], ".")."'>".$_FILES['attach']['name']."</a>";
					$emailObj->sendAddAttachmentEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),$link,'<a href="'.$_SESSION['base_url'].'?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>');
					//SendEmail::sendLiveEmail($this->getUserEmail($model->user_assigned_id),$link, false,$this->getLoggedUserFullName());
					//Add History For Ticket
					HistoryModel::historyInsert($this->entity_type,$model->id,'Added Attachment into ( <a href="index.php?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>)');
						return $this->redirect(['update', 'id' => $_REQUEST['id']]);
				}
				else
				{
					if($aid == 0) // Invalid extension
					{
						$msg = "File type not allowed to be uploaded!";
					}
					else // File size exceeded maximum limit
					{
						$msg = "File size exceeded maximum allowed size (".Yii::$app->params['FILE_SIZE'].")";
					}

					return $this->redirect(['update', 'id' => $_REQUEST['id'], 'err_msg' => $msg]);
				}
			}
			
			
			
			// Ticket Attachment get
			if(!empty($_REQUEST['attach_update'])){
				$attachModelR=File::findOne($_REQUEST['attach_update']);
				//Add History For Ticket
				HistoryModel::historyInsert($this->entity_type,$model->id,$model->ticket_id.' Ticket Attachment Updated in ( <a href="index.php?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>)');
			}
			// Ticket Notes get
			if(!empty($_REQUEST['note_id'])){
				$noteModelR=Note::findOne($_REQUEST['note_id']);
			}
			// Ticket Attachment Update
			if(!empty($_REQUEST['edit_attach'])){
					$file=FileModel::fileEdit();
					if($_FILES['attach']['name']){
						$aid=$_REQUEST['att_id'];
						$link="<a href='".str_replace('web/index.php','',$_SESSION['base_url'])."attachments/".$aid.strrchr($_FILES['attach']['name'], ".")."'>".$_FILES['attach']['name']."</a>";
						//Send an Email
						$emailObj->sendUpdateAttachmentEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),$link,'<a href="'.$_SESSION['base_url'].'?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>');
					}
				//Add History For Ticket
				HistoryModel::historyInsert($this->entity_type,$model->id,'Updated Attachment in ( <a href="index.php?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>)');
					return $this->redirect(['update', 'id' => $_REQUEST['id']]);
			}
			
			// Add Notes
			if(!empty($_REQUEST['add_note_model'])){
				$nid = NoteModel::noteInsert($_REQUEST['id'],$this->entity_type);
				if($nid){
					setcookie('inserted_notes'.$_REQUEST['id'],true,time()+7200);
				}
				//Send an Email
				$emailObj->sendTicketNoteEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),$this->getLoggedUserFullName()." <br>".$_REQUEST['notes'],'<a href="'.$_SESSION['base_url'].'?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>', $model->ticket_title);

				if($_REQUEST['PublicNote'] == 'Yes')
				{
					// Send Email to Customer
					$ticketStatusModel = TicketStatus::findOne($model->ticket_status_id);

					$emailObj->sendTicketNoteEmailTemplate($this->getCustomerEmail($model->ticket_customer_id),$this->getCustomerFullName($model->ticket_customer_id),$this->getLoggedUserFullName()." <br>".$_REQUEST['notes'],'<a href="'.$_SESSION['base_url'].'?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>','['.$model->ticket_id.']');
				}

				//Add History For Ticket
				HistoryModel::historyInsert($this->entity_type,$model->id,'Added Note into ( <a href="index.php?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>)');
				return $this->redirect(['update', 'id' => $_REQUEST['id']]);
			}
			
			// Update Notes
			if(!empty($_REQUEST['edit_note_model'])){
				$nid = NoteModel::noteEdit();
				//Send an Email
				$emailObj->sendTicketNoteUpdateEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),$this->getLoggedUserFullName()." <br>".$_REQUEST['notes'],'<a href="'.$_SESSION['base_url'].'?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>' ,$model->ticket_title);

				if($_REQUEST['PublicNote'] == 'Yes')
				{
					// Send Email to Customer
					$ticketStatusModel = TicketStatus::findOne($model->ticket_status_id);

					$emailObj->sendTicketNoteUpdateEmailTemplate($this->getCustomerEmail($model->ticket_customer_id),$this->getCustomerFullName($model->ticket_customer_id),$this->getLoggedUserFullName()." <br>".$_REQUEST['notes'],'<a href="'.$_SESSION['base_url'].'?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>' ,'['.$model->ticket_id.']');
				}

				//Add History For Ticket
				HistoryModel::historyInsert($this->entity_type,$model->id,'Updated Note in ( <a href="index.php?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>)');
				return $this->redirect(['update', 'id' => $_REQUEST['id']]);
			}
			/*===================================================Timing==============================================================*/
			// Add Task Timing
			if(!empty($_REQUEST['timing_add'])){
				////var_dump($_REQUEST['timing_add']);
				TimesheetModel::timeEntryAdd($_REQUEST['notes'],'MANUAL',$_REQUEST['start_time'],$_REQUEST['end_time'],$this->entity_type);
				// Update Task Spend Time
				$this->updateTicketSpendTime($_REQUEST['id']);
				//Add History For Task
				HistoryModel::historyInsert($this->entity_type,$model->id,'Added Ticket Work Timing into ( <a href="index.php?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>)');
				return $this->redirect(['update', 'id' => $_REQUEST['id']]);
			}
			if(!empty($_REQUEST['time_entry_id'])){
				$timeEntryModel = TimeEntry::findOne($_REQUEST['time_entry_id']);
			}
			// Edit Task Timing
			if(!empty($_REQUEST['timing_edit'])){
				TimesheetModel::timeEntryEdit($_REQUEST['notes'],$_REQUEST['time_entry_id'],$_REQUEST['start_time'],$_REQUEST['end_time']);
				// Update Task Spend Time
				$this->updateTicketSpendTime($_REQUEST['id']);
				//Add History For Task
				HistoryModel::historyInsert($this->entity_type,$model->id,'Updated Ticket Work Timing in ( <a href="index.php?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>)');
				return $this->redirect(['update', 'id' => $_REQUEST['id']]);
			}
			// Edit Task taskTimeEditApproved
			if(!empty($_REQUEST['appid'])){
				TimesheetModel::timeEntryApproved($_REQUEST['appid']);
				return $this->redirect(['update', 'id' => $_REQUEST['id']]);
			}
			// Delete Task Time
			if(!empty($_REQUEST['time_del_id'])){
					$Attachmodel = TimeEntry::findOne($_REQUEST['time_del_id']);
					if (!is_null($Attachmodel)) {
						$Attachmodel->delete();
					}
					// Update Task Spend Time
					$this->updateTicketSpendTime($_REQUEST['id']);
					//Add History For Task
				HistoryModel::historyInsert($this->entity_type,$model->id,'Deleted Ticket Work Timing from  ( <a href="index.php?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>)');
					return $this->redirect(['update', 'id' => $_REQUEST['id']]);
			}
			
			if(!empty($_REQUEST['starttime'])){
				date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
				setcookie('ticket_start_time',time());
				//setcookie('ticket_start_time',date('Y-m-d H:i:s'),time()+7200);
				setcookie('ticketStartedId',$_REQUEST['id']);
				 return $this->redirect(['update', 'id' => $_REQUEST['id']]);
			}
			if(!empty($_REQUEST['timenotes']) && !empty($_COOKIE['ticket_start_time'])){
				date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
				$start_time=date('Y-m-d H:i:s', $_COOKIE['ticket_start_time']);
				$end_time = date('Y-m-d H:i:s');
				// Add Task Time
				TimesheetModel::timeEntryAdd($_REQUEST['timenotes'],'AUTO',$start_time,$end_time,$this->entity_type);
				//setcookie('ticket_start_time',date('Y-m-d H:i:s'),time()-3600);
				setcookie('ticketStartedId',$_REQUEST['id'],time()-3600);
				// Update Task Spend Time
				$this->updateTicketSpendTime($_REQUEST['id']);
				//Add History For Task
				HistoryModel::historyInsert($this->entity_type,$model->id,'Added Note into ( <a href="index.php?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>)');
				return $this->redirect(['update', 'id' => $_REQUEST['id']]);
			}
            return $this->render('update', [
                'model' => $model,
				'attachModel'=>$attachModelR,
				'noteModel'=>$noteModelR,
				'timeEntryModel'=>$timeEntryModel,
            ]);
        }
    }
	public function actionMyTickets()
    {
		if(!Yii::$app->user->can('Ticket.MyTicket')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}

		if(!empty($_REQUEST['multiple_del'])){
			if(!Yii::$app->user->can('Ticket.Delete')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
			$rows=$_REQUEST['selection'];
			for($i=0;$i<count($rows);$i++){
				$this->findModel($rows[$i])->delete();
			}
		}
        $searchModel = new TicketSearch;
        $dataProvider = $searchModel->searchMyTickets(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

	public function actionPendingTicket()
    {
		if(!empty($_REQUEST['multiple_del'])){
			if(!Yii::$app->user->can('Ticket.Delete')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
			$rows=$_REQUEST['selection'];
			for($i=0;$i<count($rows);$i++){
				$this->findModel($rows[$i])->delete();
			}
		}
        $searchModel = new TicketSearch;
        $dataProvider = $searchModel->searchPendingTickets(Yii::$app->request->getQueryParams());

        return $this->render('pending-ticket', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
	public function actionMyCalendar(){
		if(!Yii::$app->user->can('Ticket.MyCalendar')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		$sql = "SELECT * FROM tbl_ticket  where user_assigned_id = '".Yii::$app->user->identity->id."' order by id DESC limit 100";
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
			///echo count($dataReader);
			return $this->render('my-calendar', [
				'dataProvider' => $dataReader,
			]);
	}
	public function actionJobQueue(){		
		$sql = "SELECT * FROM tbl_ticket  where user_assigned_id = '".Yii::$app->user->identity->id."' order by id DESC limit 100";
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
			///echo count($dataReader);
			return $this->render('job-queue', [
				'dataProvider' => $dataReader,
			]);
	}
	public function actionAjaxQueueUsers(){
		$queue_id=$_REQUEST['queue_id'];
		$user_id=$_REQUEST['user_id'];
		
		/*
		// if not user_id
		if(!$user_id)
		{
			// find queue supervisor
			$user_id = Queue::findOne($queue_id)->queue_supervisor_user_id;
		}
		*/

		$sql="SELECT * FROM tbl_user WHERE id in(select user_id from tbl_queue_users where queue_id=$queue_id)";
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
		 return $this->renderPartial('ajax-queue-users', [
                'dataReader' => $dataReader,
				'user_id'=>$user_id,
            ]);
	}
	public function actionAjaxDepartmentQueue(){
		$department_id=$_REQUEST['department_id'];
		$queue_id=$_REQUEST['queue_id'];
		$sql="SELECT * FROM tbl_queue WHERE department_id=$department_id and active=1";
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
		 return $this->renderPartial('ajax-department-queue', [
                'dataReader' => $dataReader,
				'queue_id'=>$queue_id,
            ]);
	}
	
	public function actionAjaxTicketSla(){
      $request = Yii::$app->request;
      $get = $request->get();
      $ticket_priority_id=$get['ticket_priority_id'];
      //var_dump($ticket_priority_id);die();
      $ticket_impact_id=$get['ticket_impact_id'];
      if(Sla::find()->where(['ticket_priority_id'=>$ticket_priority_id,'ticket_impact_id'=>$ticket_impact_id])->exists())
      { 
        return false;
      } else{
          echo 'No Sla defined for the selected Ticket Priority & Impact.'."\n".'Proceeding further will set current Datetime as Due Date ';
      }
  }
	
	public function actionAjaxTicketCategory(){
		$department_id=$_REQUEST['department_id'];
		$ticket_category_id_1=$_REQUEST['ticket_category_id_1'];
		$sql="SELECT * FROM tbl_ticket_category WHERE parent_id=0 and department_id=$department_id and active=1";
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
		 return $this->renderPartial('ajax-ticket-category', [
                'dataReader' => $dataReader,
				'ticket_category_id_1'=>$ticket_category_id_1,
            ]);
	}
	public function actionAjaxCategoryChange(){
		$ticket_category_id=$_REQUEST['ticket_category_id'];
		$ticket_category_id_2=$_REQUEST['ticket_category_id_2'];
		
		$sql="SELECT * FROM tbl_ticket_category WHERE parent_id=$ticket_category_id and active=1";
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
		 return $this->renderPartial('ajax-category-change', [
                'dataReader' => $dataReader,
				'ticket_category_id_2'=>$ticket_category_id_2,
            ]);
	}
	
    /**
     * Deletes an existing Ticket model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		 if(!Yii::$app->user->can('Ticket.Delete')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Ticket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ticket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		if(Yii::$app->user->identity->userType->type=="Customer")
		{
			if (($model = Ticket::find()->where("id=$id and ticket_customer_id=".Yii::$app->user->identity->entity_id)->one()) !== null) {
				return $model;
			} else {
				throw new NotFoundHttpException('The requested page does not exist.');
			}
		}
		else
		{
			if (($model = Ticket::findOne($id)) !== null) {
				return $model;
			} else {
				throw new NotFoundHttpException('The requested page does not exist.');
			}
		}
    }
}

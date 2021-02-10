<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use kartik\widgets\ActiveForm;

use dosamigos\ckeditor\CKEditor;
use kartik\builder\Form;
use livefactory\modules\pmt\controllers\TaskController;
use livefactory\modules\pmt\controllers\DefectController;
use livefactory\modules\cron\controllers\EmailReader;
use livefactory\models\SendEmail;
use livefactory\models\TimeDiffModel;
use livefactory\models\Customer;
use livefactory\models\Ticket;
use livefactory\models\User;
use livefactory\models\Note;
use livefactory\models\Sla;
use livefactory\models\Queue;
use livefactory\models\TicketStatus;
use livefactory\models\NoteModel;
use livefactory\models\FileModel;
use livefactory\models\AssignmentHistoryModel;
use livefactory\models\HistoryModel;
use livefactory\models\File;
use livefactory\models\History;
use livefactory\models\AssignmentHistory;

use livefactory\models\TicketPriority;
use livefactory\models\search\Ticket as TicketSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use livefactory\models\Customer as CustomerDetail;
use livefactory\models\User as UserDetail;
use livefactory\models\search\CommonModel as SessionVerification;
use livefactory\models\TimesheetModel;
use livefactory\models\TicketResolution;
use livefactory\models\ResolutionReference;

	function getUserEmail($id){
		$userModel = UserDetail::findOne($id);	
		return $userModel->email;
	}
	function getCustomerEmail($id){
		$userModel = CustomerDetail::findOne($id);	
		return $userModel->email;
	}
	function getCustomerFullName($id){
		$user = CustomerDetail::findOne($id);
		
		return $user->first_name." ".$user->last_name;	
	}
	function getUserFullName($id){
		$user = UserDetail::findOne($id);
		
		return $user->first_name." ".$user->last_name;	
	}
	
	$debug = Yii::$app->params['SHOW_DEBUG_TOOLBAR'];
	
	ob_end_flush();
	ob_start();
	set_time_limit(0);

	if($debug == "Yes")
	{
	error_reporting(E_ALL);
	echo "<pre>";
	}

	// getting config items for incoming email server
	$params['server'] = Yii::$app->params['INCOMING_EMAIL_SERVER_HOST'];
	$params['user'] = Yii::$app->params['INCOMING_EMAIL_SERVER_USERNAME'];
	$params['pass'] = base64_decode(Yii::$app->params['INCOMING_EMAIL_SERVER_PASSWORD']);
	$params['type'] = Yii::$app->params['INCOMING_EMAIL_SERVER_TYPE'];
	$params['port'] = Yii::$app->params['INCOMING_EMAIL_SERVER_PORT'];
	$params['security'] = Yii::$app->params['INCOMING_SERVER_ENCRYPTION'];
	
	$reader = new EmailReader($params);
	$staff = false;
	
	echo "message count:".$reader->msg_cnt."<br/>\n";

	for ($i = 0; $i < $reader->msg_cnt; $i++)
	{
		$message_count = $i+1;
		echo "reading email number ".$message_count."<br/>\n";

		$from = $reader->getFromEmail($i);
	
		echo "from email ".$from."<br/>\n";

		echo "searching user with entity_type customer and email ".$from."<br/>\n";

		// get customer from db
		$cususer = User::findOne([
		'email' => $from,
		'entity_type' => 'customer',
		]);

		$customer_id = $cususer->entity_id;

		if($customer_id)
		{
			echo "<strong>EMAIL IS FROM CUSTOMER</strong>"."<br/>\n";
			echo "customer's user row found with customer id: ".$customer_id."<br/>\n";
		
			$user_id = $cususer->id;

			if($user_id)
			{
				echo "user found for customer with customer id: ".$customer_id." found user id: ".$user_id."<br/>\n";
			}
			else
			{
				echo "ERROR: customer's user not found for customer id: ".$customer_id."<br/>\n";

				if(Yii::$app->params['DELETE_SPAM_EMAL'] == 'Yes')
				{
					echo "delete the message from mailbox"."<br/>\n";

					$reader->delete($i+1);
					echo "**********************************************************************************************"."<br/>\n";
					ob_flush();
					flush(); //ie working must
				}
				else
				{
					echo "Moving to processed folder if exists"."<br/>\n";

					$reader->move($i+1, Yii::$app->params['PROCESSED_MAIL_FOLDER']);
					echo "**********************************************************************************************"."<br/>\n";
					ob_flush();
					flush(); //ie working must
				}
				continue; // Move on to next mail.
			}

		}
		else
		{
			echo "customer's user with entity type customer and email ".$from." does not exist"."<br/>\n";
			
			echo "checking if the email is from a staff user..."."<br/>\n";
			// check if the email is from staff
			$user = User::findOne([
			'email' => $from,
			]);

			$user_id = $user->id;
			if($user_id)
			{
				echo "<strong>EMAIL IS FROM STAFF</strong>"."<br/>\n";
				echo "staff user found id: ".$user_id."<br/>\n";
				$staff = true;
			}
			else
			{
				echo "<strong>FROM EMAIL NOT FOUND IN DATABASE. SPAM!!!</strong>"."<br/>\n";
				echo "staff user with email ".$from." does not exist"."<br/>\n";
				
				if(Yii::$app->params['DELETE_SPAM_EMAL'] == 'Yes')
				{
					echo "delete the message from mailbox"."<br/>\n";

					$reader->delete($i+1);
					echo "**********************************************************************************************"."<br/>\n";
					ob_flush();
					flush(); //ie working must
				}
				else
				{
					echo "Moving to processed folder if exists"."<br/>\n";

					$reader->move($i+1, Yii::$app->params['PROCESSED_MAIL_FOLDER']);
					echo "**********************************************************************************************"."<br/>\n";
					ob_flush();
					flush(); //ie working must
				}

				continue; // Move on to next mail.
			}
		}

		$subject = $reader->inbox[$i]['header']->subject;
		
		echo "email subject ".$subject."<br/>\n";

		$start = strpos($subject,"[TICKET");

		$ticket_id =  intval(substr ( $subject , $start+7, 9));
		
		if($ticket_id == 0)
		echo "ticket id not found from email subject ".$ticket_id."<br/>\n";
		else
		echo "ticket id found from email subject ".$ticket_id."<br/>\n";

		// find if the ticket exists with this subject
		// get customer from db
		if($staff)
		{
			echo "searching ticket if email from staff..."."<br/>\n";
			$ticket = Ticket::find()->where(['id' => $ticket_id])
								->andWhere("ticket_status_id in (".TicketStatus::_NEEDSACTION.", ".TicketStatus::_INPROCESS.", ".TicketStatus::_REOPENED.")")
								->one();
			if($ticket)
			{
				echo "ticket found"."<br/>\n";
				$ticket_id = $ticket->id;
			}
			else
			{
				echo "ticket not found"."<br/>\n";
			}
		}
		else
		{
			echo "searching ticket if email from customer..."."<br/>\n";
			$ticket = Ticket::find()
								->where(['ticket_customer_id' => $customer_id, 'id' => $ticket_id])
								->andWhere("ticket_status_id in (".TicketStatus::_NEEDSACTION.", ".TicketStatus::_INPROCESS.", ".TicketStatus::_REOPENED.")")
								->one();
			if($ticket)
			{
				echo "ticket found"."<br/>\n";
				$ticket_id = $ticket->id;
			}
			else
			{
				echo "ticket not found"."<br/>\n";
			}
		}

		if($ticket_id)
		{
			echo "ticket found id: ".$ticket_id."<br/>\n";
		}
		else
		{
			echo "ticket with subject ".$subject." does not exist"."<br/>\n";
		}
		
		$body = $reader->inbox[$i]['body'];

		if($user_id && $ticket_id)
		{
			echo "If user found and ticket found..."."<br/>\n";
			echo "adding notes to ticket..."."<br/>\n";

			// add note
			$note = new Note();
			$note->entity_id=$ticket_id;
			$note->entity_type='ticket';
			$note->notes=$body;
			$note->user_id=$user_id;
			$note->added_at=time();
			if($note->save()) {
				echo "note with id: ".$note->id." created"."<br/>\n";
			}
			else
			{
				echo "error: note can not be added"."<br/>\n";
				var_dump($note);
				exit;
			}
		}
		else if($customer_id && !$ticket_id)
		{
			echo "email received is from customer and no ticket found"."<br/>\n";
			echo "creating new ticket..."."<br/>\n";
			// create ticket
			$ticket = new Ticket();
			$ticket->ticket_title=$subject;
			$ticket->ticket_description=$body;
			$ticket->ticket_priority_id=Yii::$app->params['DEFAULT_TICKET_PRIORITY'];
			$ticket->ticket_impact_id=Yii::$app->params['DEFAULT_TICKET_IMPACT'];
			$ticket->ticket_category_id_1=Yii::$app->params['DEFAULT_TICKET_CATEGORY'];
			$ticket->department_id=Yii::$app->params['DEFAULT_TICKET_DEPARTMENT'];
			$ticket->queue_id=Yii::$app->params['DEFAULT_TICKET_QUEUE'];
			$ticket->user_assigned_id=Queue::findOne(Yii::$app->params['DEFAULT_TICKET_QUEUE'])->queue_supervisor_user_id;
			$ticket->ticket_status_id=TicketStatus::_NEEDSACTION;
			$ticket->ticket_customer_id=$customer_id;
			$ticket->added_by_user_id=$user_id;
			$ticket->last_updated_by_user_id=$user_id;
			$ticket->created_by=$user_id;
			
			$emailObj = new SendEmail;
			
			if($ticket->save()) {
				echo "ticket with id: ".$ticket->id." created"."<br/>\n";
				
				$ticket->ticket_id='TICKET'.str_pad($ticket->id, 9, "0", STR_PAD_LEFT);
				$ticket->added_at = time();

				if(Yii::$app->params['PREPEND_TICKET_ID_IN_TITLE'] == 'Yes')
				{
					$ticket->ticket_title = "[".$ticket->ticket_id."] ".$ticket->ticket_title;
				}
				
				echo "getting sla..."."<br/>\n";

				$slaObj = Sla::find()->where('ticket_priority_id ='.$ticket->ticket_priority_id .' and ticket_impact_id="'.$ticket->ticket_impact_id.'"')->one();
				if($slaObj)
				{
					echo "sla found ".$slaObj->sla."<br/>\n";
					$slaSecs=$slaObj->sla * 60 * 60;
				}
				else
				{
					echo "sla not found. setting it to current date"."<br/>\n";
					$slaSecs=0;
				}
				$dueDate=$ticket->added_at+$slaSecs;
				$ticket->due_date = $dueDate;
				$ticket->save();
				
				if($debug == "Yes")
				print_r($ticket);
				
				echo "adding notes to ticket..."."<br/>\n";

				$note = new Note();
				$note->entity_id=$ticket->id;
				$note->entity_type='ticket';
				$note->notes=$body;
				$note->user_id=$user_id;
				$note->added_at=time();
				$note->save();

				echo "notes added.";
				
				echo "adding ticket history..."."<br/>\n";
				//Add History For Ticket
				HistoryModel::historyInsert('ticket',$ticket->id,'Ticket Created with Id  <a href="index.php?r=support/ticket/update&id='.$ticket->id.'">'.$ticket->ticket_id.'</a>',$user_id);
				echo "ticket history added."."<br/>\n";

				// Entry on Assigned History
				if($ticket->user_assigned_id){
					echo "if ticket user assigned id found. sending emails..."."<br/>\n";
					AssignmentHistoryModel::assignHistoryInsert('ticket',$ticket->id,$ticket->user_assigned_id,'Ticket Assigned to user',$user_id);
					/* send email to customer on ticket creation with title and status  */
					$ticketStatusModel = TicketStatus::findOne($ticket->ticket_status_id);
					
					echo "sending email to customer at ".getCustomerEmail($ticket->ticket_customer_id)."<br/>\n";
					$emailObj->sendCustomerEmailTemplate(getCustomerEmail($ticket->ticket_customer_id),getCustomerFullName($ticket->ticket_customer_id),'<a href="'.$_SESSION['base_url'].'?r=support/ticket/update&id='.$ticket->id.'">'.$ticket->ticket_id.'</a>',$ticket->ticket_title,$ticketStatusModel->label);
					
					echo "sending email to user assigned at ".getUserEmail($ticket->user_assigned_id)."<br/>\n";
					$emailObj->sendTicketEmailTemplate(getUserEmail($ticket->user_assigned_id),getUserFullName($ticket->user_assigned_id),'<a href="'.$_SESSION['base_url'].'?r=support/ticket/update&id='.$ticket->id.'">'.$ticket->ticket_id.'</a>',$ticket->ticket_title,$ticket->ticket_description);
				}
				else
				{
					echo "ticket user assigned id not found. not sending emails, there was some problem!!!"."<br/>\n";
				}
			}
			else
			{
				var_dump($ticket);
				exit;
			}
			
		}
		else
		{
			echo "not creating a new ticket..."."<br/>\n";
		}
		
		if(Yii::$app->params['DELETE_MAIL_AFTER_PROCESSING'] == 'No')
		{
			echo "Moving message to processed folder"."<br/>\n";

			$reader->move($i+1, Yii::$app->params['PROCESSED_MAIL_FOLDER']);
			echo "Moving to Next email if there..."."<br/>\n";
			echo "**********************************************************************************************"."<br/>\n";
			ob_flush();
			flush(); //ie working must
		}
		else
		{
			echo "delete the message from mailbox"."<br/>\n";

			$reader->delete($i+1);
			echo "**********************************************************************************************"."<br/>\n";
			ob_flush();
			flush(); //ie working must
		}
	}
	
	echo "All done..."."<br/>\n";
	
	echo '</pre>';
	
	// Delete all mails marked for deletion as a result of move
	imap_expunge($reader->conn);

	$reader->close();
	ob_end_flush();
	exit;
?>
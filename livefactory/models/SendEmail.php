<?php



namespace livefactory\models;



use Yii;

use yii\db\Query;

use livefactory\models\EmailTemplate;
//use livefactory\models\PhpMailer;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

class SendEmail extends \yii\db\ActiveRecord

{

	/**

     * @inheritdoc

     */

    public static function tableName()
    {
        return '';
    }

	public static function sendLiveEmail($uemail, $body, $cc = false, $subject, $from_system = true, $attachment = false, $attachment_name = false) 
	{
		$email = new \PHPMailer(true);
		
		try
		{
			if ($from_system)
			{
				$email->From = Yii::$app->params['SYSTEM_EMAIL'];
				$email->FromName = Yii::$app->params['company']['company_name'];
			}
			else
			{
				$user = User::findOne(Yii::$app->user->identity->id);
				$email->From = $user->email;
				$email->FromName = $user->fullName;
			}

			if (!empty($cc))
			{
				$cc = explode(',',$cc);
				foreach($cc as $email_id)
				{
					$email->AddCC($email_id);
				}
			}

			$uemail = explode(',',$uemail);

			$email->Subject = $subject;
			$email->Body = $body;
			foreach($uemail as $email_id)
			{
				$email->AddAddress($email_id);
			}

			if ($attachment)
			{
				$email->AddAttachment($attachment, $attachment_name?$attachment_name:'attachment');
			}
			
			$email->Timeout       =   10; // set the timeout (seconds)
			$email->SMTPKeepAlive = true; // don't close the connection between messages
			
			if(Yii::$app->params['SHOW_DEBUG_TOOLBAR'] == 'Yes')
				$email->SMTPDebug  = 1;

			if (Yii::$app->params['SMTP_AUTH']=='Yes')
			{
				$email->IsSMTP();

				$email->Host = Yii::$app->params['SMTP_HOST'];
				$email->Port = Yii::$app->params['SMTP_PORT'];
				$email->Username = Yii::$app->params['SMTP_USERNAME'];
				$email->Password = base64_decode(Yii::$app->params['SMTP_PASSWORD']);

				if(Yii::$app->params['SMTP_ENCRYPTION'] == 'ssl' || Yii::$app->params['SMTP_ENCRYPTION'] == 'tls')
				{
					$email->SMTPSecure = Yii::$app->params['SMTP_ENCRYPTION']; 
				}
				else
				{
					$email->SMTPSecure = false;
					$email->SMTPAutoTLS = false;
				}
				$email->SMTPAuth = true;
			}

			$email->IsHTML(true);
			$email->Send();
			return true;
		}
		catch (\Exception $e)
		{
			return $e->errorMessage();
		}
	}

	public function emailSendMethod($uemail, $body, $cc = false, $subject) 
	{
		$email = new \PHPMailer(true);
		
		try
		{

			$email->From = Yii::$app->params['SYSTEM_EMAIL'];
			$email->FromName = Yii::$app->params['company']['company_name'];

			if (!empty($cc))
			{
				$cc = explode(',',$cc);
				foreach($cc as $email_id)
				{
					$email->AddCC($email_id);
				}
			}

			$uemail = explode(',',$uemail);

			$email->Subject = $subject;
			$email->Body = $body;
			foreach($uemail as $email_id)
			{
				$email->AddAddress($email_id);
			}
			
			$email->Timeout       =   10; // set the timeout (seconds)
			$email->SMTPKeepAlive = true; // don't close the connection between messages

			if(Yii::$app->params['SHOW_DEBUG_TOOLBAR'] == 'Yes')
				$email->SMTPDebug  = 1;

			if (Yii::$app->params['SMTP_AUTH']=='Yes')
			{
				$email->IsSMTP();
				$email->SMTPAuth = true;
				$email->Host = Yii::$app->params['SMTP_HOST'];
				$email->Port = Yii::$app->params['SMTP_PORT'];
				$email->Username = Yii::$app->params['SMTP_USERNAME'];
				$email->Password = base64_decode(Yii::$app->params['SMTP_PASSWORD']);

				if(Yii::$app->params['SMTP_ENCRYPTION'] == 'ssl' || Yii::$app->params['SMTP_ENCRYPTION'] == 'tls')
				{
					$email->SMTPSecure = Yii::$app->params['SMTP_ENCRYPTION']; 
				}
				else
				{
					$email->SMTPSecure = false;
					$email->SMTPAutoTLS = false;
				}
			}

			$email->IsHTML(true);
			if(Yii::$app->params['SHOW_DEBUG_TOOLBAR'] == 'Yes')
			{
				echo "<pre>";
				print_r($email);
			}
			$email->Send();
			return true;
		}
		catch (\Exception $e)
		{
			if(Yii::$app->params['SHOW_DEBUG_TOOLBAR'] == 'Yes')
			{
				echo $e->errorMessage();
				exit;
			}
			return $e->errorMessage();
		}
	}

    public function getCompanyDetail($body){
		$from = array('COMPANY_NAME', 'COMPANY_ADDRESS', 'COMPANY_PHONE','COMPANY_FAX','COMPANY_EMAIL');

		$to   = array(Yii::$app->params['company']['company_name'],Yii::$app->params['address']['address_1']."  ".Yii::$app->params['address']['city']."  ".Yii::$app->params['address']['state']."  ".Yii::$app->params['address']['country'],"  <b>Phone:</b> ".Yii::$app->params['company']['phone'],"  <b>Fax:</b> ".Yii::$app->params['company']['fax'],"  <b>Email:</b> ".Yii::$app->params['company']['company_email']);
		 return str_replace($from, $to,$body);
		
	}
	public function sendNoteEmailTemplate($email,$user_name,$user_by,$url){

		$tempalte = EmailTemplate::findOne(2);
		
		$from = array('FIRST_NAME LAST_NAME', 'USER_BY', 'LINK');

		$to   = array($user_name,$user_by,$url);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['NOTE_ADD_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false, $tempalte->template_subject);

	}

	public function sendNoteUpdateEmailTemplate($email,$user_name,$user_by,$url){

		$tempalte = EmailTemplate::findOne(3);

		$from = array('FIRST_NAME LAST_NAME', 'USER_BY', 'LINK');

		$to   = array($user_name,$user_by,$url);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['UPDATE_NOTE_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false, $tempalte->template_subject);

	}

	public function sendTicketNoteEmailTemplate($email,$user_name,$user_by,$url,$subject){

		$tempalte = EmailTemplate::findOne(2);
		
		$from = array('FIRST_NAME LAST_NAME', 'USER_BY', 'LINK');

		$to   = array($user_name,$user_by,$url);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['NOTE_ADD_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false, $subject);

	}

	public function sendTicketNoteUpdateEmailTemplate($email,$user_name,$user_by,$url,$subject){

		$tempalte = EmailTemplate::findOne(3);

		$from = array('FIRST_NAME LAST_NAME', 'USER_BY', 'LINK');

		$to   = array($user_name,$user_by,$url);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['UPDATE_NOTE_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false, $subject);

	}

	public function sendTaskEmailTemplate($email,$user_name,$url,$desc){

		$tempalte = EmailTemplate::findOne(9);

		$from = array('FIRST_NAME LAST_NAME', 'LINK','DISCRIPTION');

		$to   = array($user_name,$url,$desc);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['TASK_CREATE_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false, $tempalte->template_subject);

	}
	
	/*  code for timesheet email */
	public function sendTimesheetEntryTemplate($email,$user_name,$user_by,$url){

		$tempalte = EmailTemplate::findOne(25);

		$from = array('FIRST_NAME LAST_NAME', 'USER_BY', 'LINK');

		$to   = array($user_name,$user_by,$url);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['NEW_TIMESHEET_ENTRY_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false, $tempalte->template_subject);

	}
	
	
	public function sendTicketEmailTemplate($email,$user_name,$url,$title,$desc){

		$tempalte = EmailTemplate::findOne(19);

		$from = array('FIRST_NAME LAST_NAME', 'LINK','DISCRIPTION');

		$to   = array($user_name,$url,$desc);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['TICKET_CREATE_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false, $title);

	}
	
	
	public function sendCustomerEmailTemplate($email,$user_name,$url,$title,$status){

		$tempalte = EmailTemplate::findOne(26);

		$from = array('FIRST_NAME LAST_NAME', 'LINK','TITLE','STATUS');

		$to   = array($user_name,$url,$title,$status);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['SEND_EMAIL_TO_CUSTOMER_ON_TICKET_CREATION']=='1')
		SendEmail::sendLiveEmail($email, $body,false, $title);

	}

	public function sendTaskChangedUserEmailTemplate($email,$user_name,$user_by,$url,$status){

		$tempalte = EmailTemplate::findOne(4);

		$from = array('FIRST_NAME LAST_NAME', 'USER_BY', 'LINK','STATUS');

		$to   = array($user_name,$user_by,$url,$status);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['TASK_CHANGED_USER_EMAIL']=='1')	
		SendEmail::sendLiveEmail($email, $body,false,$tempalte->template_subject);

	}
	
	public function sendTicketChangedUserEmailTemplate($email,$user_name,$user_by,$url,$status){

		$tempalte = EmailTemplate::findOne(20);

		$from = array('FIRST_NAME LAST_NAME', 'USER_BY', 'LINK','STATUS');

		$to   = array($user_name,$user_by,$url,$status);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['TICKET_CHANGED_USER_EMAIL']=='1')	
		SendEmail::sendLiveEmail($email, $body,false,$tempalte->template_subject);

	}

	public function sendTaskChangedPriorityEmailTemplate($email,$user_name,$user_by,$url,$from_label,$to_label){

		$tempalte = EmailTemplate::findOne(5);

		$from = array('FIRST_NAME LAST_NAME', 'USER_BY', 'LINK','FROM_LABEL','TO_LABEL');

		$to   = array($user_name,$user_by,$url,$from_label,$to_label);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['TASK_CHANGED_PRIORITY']=='1')
		SendEmail::sendLiveEmail($email, $body,false,$tempalte->template_subject);

	}
	public function sendTicketChangedPriorityEmailTemplate($email,$user_name,$user_by,$url,$from_label,$to_label){

		$tempalte = EmailTemplate::findOne(21);

		$from = array('FIRST_NAME LAST_NAME', 'USER_BY', 'LINK','FROM_LABEL','TO_LABEL');

		$to   = array($user_name,$user_by,$url,$from_label,$to_label);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['TICKET_CHANGED_PRIORITY']=='1')
		SendEmail::sendLiveEmail($email, $body,false,$tempalte->template_subject);

	}

	public function sendTaskChangedStatusEmailTemplate($email,$user_name,$user_by,$url,$from_status,$to_status){

		$tempalte = EmailTemplate::findOne(6);

		$from = array('FIRST_NAME LAST_NAME', 'USER_BY', 'LINK','FROM_STATUS','TO_STATUS');

		$to   = array($user_name,$user_by,$url,$from_status,$to_status);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['TASK_CHANGED_STATUS_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false,$tempalte->template_subject);

	}
	public function sendTicketChangedStatusEmailTemplate($email,$user_name,$user_by,$url,$from_status,$to_status){

		$tempalte = EmailTemplate::findOne(22);

		$from = array('FIRST_NAME LAST_NAME', 'USER_BY', 'LINK','FROM_STATUS','TO_STATUS');

		$to   = array($user_name,$user_by,$url,$from_status,$to_status);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['TICKET_CHANGED_STATUS_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false,$tempalte->template_subject);

	}

	public function sendAddAttachmentEmailTemplate($email,$user_name,$url,$entity_url){

		$tempalte = EmailTemplate::findOne(7);

		$from = array('FIRST_NAME LAST_NAME', 'LINK','ENTITY');

		$to   = array($user_name,$url,$entity_url);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['ADD_ATTACHMENT_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false,$tempalte->template_subject);

	}

	public function sendUpdateAttachmentEmailTemplate($email,$user_name,$url,$entity_url){

		$tempalte = EmailTemplate::findOne(8);

		$from = array('FIRST_NAME LAST_NAME', 'LINK','ENTITY');

		$to   = array($user_name,$url,$entity_url);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['UPDATE_ATTACHMENT_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false,$tempalte->template_subject);

	}

	public function sendResetPasswordEmailTemplate($email,$user_name,$password){

		$tempalte = EmailTemplate::findOne(1);

		$from = array('NAME', 'PASSWORD');

		$to   = array($user_name,$password);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['RESET_PASSWORD_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false,$tempalte->template_subject);

	}

	public function sendLeadChangedUserEmailTemplate($email,$user_name,$user_by,$url,$status){

		$tempalte = EmailTemplate::findOne(10);

		$from = array('FIRST_NAME LAST_NAME', 'USER_BY', 'LINK','STATUS');

		$to   = array($user_name,$user_by,$url,$status);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		//if(Yii::$app->params['UPDATE_NOTE_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false,$tempalte->template_subject);

	}

	public function sendLeadChangedPriorityEmailTemplate($email,$user_name,$user_by,$url,$from_label,$to_label){

		$tempalte = EmailTemplate::findOne(11);

		$from = array('FIRST_NAME LAST_NAME', 'USER_BY', 'LINK','FROM_LABEL','TO_LABEL');

		$to   = array($user_name,$user_by,$url,$from_label,$to_label);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);	
		$body = str_replace($from, $to,$tempalte->template_body);
		//if(Yii::$app->params['UPDATE_NOTE_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false,$tempalte->template_subject);

	}

	public function sendLeadChangedStatusEmailTemplate($email,$user_name,$user_by,$url,$from_status,$to_status){

		$tempalte = EmailTemplate::findOne(12);

		$from = array('FIRST_NAME LAST_NAME', 'USER_BY', 'LINK','FROM_STATUS','TO_STATUS');

		$to   = array($user_name,$user_by,$url,$from_status,$to_status);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		//if(Yii::$app->params['UPDATE_NOTE_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false,$tempalte->template_subject);

	}

	public function sendLeadEmailTemplate($email,$user_name,$url,$desc){

		$tempalte = EmailTemplate::findOne(13);

		$from = array('FIRST_NAME LAST_NAME', 'LINK','DISCRIPTION');

		$to   = array($user_name,$url,$desc);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		//if(Yii::$app->params['UPDATE_NOTE_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false, $tempalte->template_subject);

	}
	public function sendDefectChangedUserEmailTemplate($email,$user_name,$user_by,$url,$status){

		$tempalte = EmailTemplate::findOne(14);

		$from = array('FIRST_NAME LAST_NAME', 'USER_BY', 'LINK','STATUS');

		$to   = array($user_name,$user_by,$url,$status);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['DEFECT_USER_CHANGED_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false,$tempalte->template_subject);

	}

	public function sendDefectChangedPriorityEmailTemplate($email,$user_name,$user_by,$url,$from_label,$to_label){

		$tempalte = EmailTemplate::findOne(15);

		$from = array('FIRST_NAME LAST_NAME', 'USER_BY', 'LINK','FROM_LABEL','TO_LABEL');

		$to   = array($user_name,$user_by,$url,$from_label,$to_label);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['DEFECT_CHANGED_PRIORITY_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false,$tempalte->template_subject);

	}

	public function sendDefectChangedStatusEmailTemplate($email,$user_name,$user_by,$url,$from_status,$to_status){

		$tempalte = EmailTemplate::findOne(16);

		$from = array('FIRST_NAME LAST_NAME', 'USER_BY', 'LINK','FROM_STATUS','TO_STATUS');

		$to   = array($user_name,$user_by,$url,$from_status,$to_status);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['DEFECT_CHANGED_STATUS_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false,$tempalte->template_subject);

	}

	public function sendDefectEmailTemplate($email,$user_name,$url,$desc){

		$tempalte = EmailTemplate::findOne(17);

		$from = array('FIRST_NAME LAST_NAME', 'LINK','DISCRIPTION');

		$to   = array($user_name,$url,$desc);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);	
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['DEFECT_CREATE_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false, $tempalte->template_subject);

	}
	public function sendNewUserEmailTemplate($email,$user_name,$username,$password){

		$tempalte = EmailTemplate::findOne(18);

		$from = array('EMAIL','FIRSTNAME LASTNAME', 'USERNAME','PASSWORD','LINK');

		$to   = array($email,$user_name,$username,$password,'<a href="'.$_SESSION['base_url'].'?r=site/login">here</a>');
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		if(Yii::$app->params['NEW_USER_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false, str_replace('EMAIL',$email,$tempalte->template_subject));

	}
	public function sendActivateUserEmailTemplate($email,$user_name,$username,$password,$link){

		$tempalte = EmailTemplate::findOne(23);

		$from = array('EMAIL','FIRSTNAME LASTNAME', 'USERNAME','PASSWORD','LINK');

		$to   = array($email,$user_name,$username,$password,$link);
		$tempalte->template_body = $this->getCompanyDetail($tempalte->template_body);
		$body = str_replace($from, $to,$tempalte->template_body);
		//if(Yii::$app->params['NEW_USER_EMAIL']=='1')
		SendEmail::sendLiveEmail($email, $body,false, str_replace('EMAIL',$email,$tempalte->template_subject));

	}
}


<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use kartik\widgets\ActiveForm;

use dosamigos\ckeditor\CKEditor;
use kartik\builder\Form;
use livefactory\modules\pmt\controllers\TaskController;
use livefactory\modules\pmt\controllers\DefectController;
use livefactory\models\SendEmail;
$emailObj = new SendEmail;
$subject='';
include_once(__DIR__ .'/../../../../../vendor/swiftmailer/swiftmailer/lib/swift_required.php');

date_default_timezone_set(Yii::$app->params['TIME_ZONE']);

//require_once 'lib/swift_required.php';
//$taskModel = new TaskController;
	foreach($dataProvider as $project){
	$toEmail = TaskController::getProjectOwnerEmail($project['id']);
	$userEmails = TaskController::getProjectUsersEmail($project['id']);
	$subject='Daily User Work Summary Project '.$project['project_name'];
    $body ='Hi '.$project['first_name']." ".$project['last_name']."<br/>";
	$body.="Please find below summary report for $project[project_name]<br/>";
	$body.="Total Done Tasks - "; 
	$body.=count(TaskController::getDoneRecords($project['id']))."<br/>";
	$body.="Total Done Defect - "; 
	$body.=count(DefectController::getDoneRecords($project['id']))."<br/>";
	$body.='<h3>Done Tasks</h3>
    <table width="100%" cellpadding="5" style="border-collapse:collapse">
    	<tr>
        	<th style="padding:5px; border:1px solid #ccc;">Task Name</th>
            <th style="padding:5px; border:1px solid #ccc;">User</th>
            <th style="padding:5px; border:1px solid #ccc;">Actual Start Datetime</th>
            <th style="padding:5px; border:1px solid #ccc;">Actual End Datetime</th>
        </tr>';
       
			if(count(TaskController::getDoneRecords($project['id'])) >0 ){
				foreach(TaskController::getDoneRecords($project['id']) as $taskNeed){
	
        $body.='<tr>
        	<td style="padding:5px; border:1px solid #ccc;">'.$taskNeed[task_name].'</td>
            <td style="padding:5px; border:1px solid #ccc;">'.$taskNeed[user][first_name].'</td>
            <td style="padding:5px; border:1px solid #ccc;">'.date('Y-m-d H:i:s', $taskNeed[actual_start_datetime]).'</td>
            <td style="padding:5px; border:1px solid #ccc;">'.date('Y-m-d H:i:s', $taskNeed[actual_end_datetime]).'</td>
         </tr>';
			} 
		}else{
			$body.="<tr style='padding:5px; border:1px solid #ccc;'><td>No Result</td></tr>";	
		}
    $body.='</table>';
		$body.='<h3>Done Defects</h3>
    <table width="100%" cellpadding="5" style="border-collapse:collapse">
    	<tr>
        	<th style="padding:5px; border:1px solid #ccc;">Defect Name</th>
            <th style="padding:5px; border:1px solid #ccc;">User</th>
            <th style="padding:5px; border:1px solid #ccc;">Actual Start Datetime</th>
            <th style="padding:5px; border:1px solid #ccc;">Actual End Datetime</th>
        </tr>';
       
			if(count(DefectController::getDoneRecords($project['id'])) >0 ){
				foreach(DefectController::getDoneRecords($project['id']) as $defectNeed){
	
        $body.='<tr>
        	<td style="padding:5px; border:1px solid #ccc;">'.$defectNeed[defect_name].'</td>
            <td style="padding:5px; border:1px solid #ccc;">'.$defectNeed[user][first_name].'</td>
            <td style="padding:5px; border:1px solid #ccc;">'.date('Y-m-d H:i:s', $defectNeed[actual_start_datetime]).'</td>
            <td style="padding:5px; border:1px solid #ccc;">'.date('Y-m-d H:i:s', $defectNeed[actual_end_datetime]).'</td>
         </tr>';
			} 
		}else{
			$body.="<tr style='padding:5px; border:1px solid #ccc;'><td>No Result</td></tr>";	
		}
    $body.='</table>';
	//////var_dump($body);
		// Create the mail transport configuration
		$transport = Swift_MailTransport::newInstance();
		 
		// Create the message
		$message = Swift_Message::newInstance();
		////$userEmails[]=$toEmail;
		$message->setTo($userEmails);
		$message->setSubject($subject);
		$message->setContentType("text/html");
		$message->setBody($body);
		$message->setFrom(Yii::$app->params['SYSTEM_EMAIL']);
		 
		// Send the email
		$mailer = Swift_Mailer::newInstance($transport);
		$mailer->send($message);
 //SendEmail::sendLiveEmail($toEmail, $body, $cc = false, $subject); 
} 
//header("location:index.php?r=liveobjects/setting&report=true");
?>
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

//require_once 'lib/swift_required.php';
//$taskModel = new TaskController;
	foreach($dataProvider as $project){
	$toEmail = TaskController::getProjectOwnerEmail($project['id']);
	$userEmails = TaskController::getProjectUsersEmail($project['id']);
	$subject='Work Summary Project '.$project['project_name'];
    $body ='Hi '.$project['first_name']." ".$project['last_name']."<br/>";
	$body.="Please find below summary report for $project[project_name]<br/>";
	$body.="Total Opened Tasks - "; 
	$body.=count(TaskController::getNeedActions($project['id']))+count(TaskController::getInprocessTasks($project['id']))."<br/>";
	$body.="Total Opened Defect - "; 
	$body.=count(DefectController::getNeedActions($project['id']))+count(DefectController::getInprocessDefects($project['id']))."<br/>";
	$body.='<h3>Task Need Actions</h3>
    <table width="100%" cellpadding="5" style="border-collapse:collapse">
    	<tr>
        	<th style="padding:5px; border:1px solid #ccc;">Task Name</th>
            <th style="padding:5px; border:1px solid #ccc;">User</th>
            <th style="padding:5px; border:1px solid #ccc;">Expected Start Datetime</th>
            <th style="padding:5px; border:1px solid #ccc;">Expected End Datetime</th>
        </tr>';
       
			if(count(TaskController::getNeedActions($project['id'])) >0 ){
				foreach(TaskController::getNeedActions($project['id']) as $taskNeed){
	
        $body.='<tr>
        	<td style="padding:5px; border:1px solid #ccc;">'.$taskNeed[task_name].'</td>
            <td style="padding:5px; border:1px solid #ccc;">'.$taskNeed[user][first_name].'</td>
            <td style="padding:5px; border:1px solid #ccc;">'.$taskNeed[expected_start_datetime].'</td>
            <td style="padding:5px; border:1px solid #ccc;">'.$taskNeed[expected_end_datetime].'</td>
         </tr>';
			} 
		}else{
			$body.="<tr style='padding:5px; border:1px solid #ccc;'><td>No Result</td></tr>";	
		}
    $body.='</table>
    <hr/>
		<h3>Inprocess Tasks</h3>
        <table width="100%" cellpadding="5" style="border-collapse:collapse">
            <tr>
                <th style="padding:5px; border:1px solid #ccc;">Task Name</th>
                <th style="padding:5px; border:1px solid #ccc;">User</th>
                <th style="padding:5px; border:1px solid #ccc;">Expected Start Datetime</th>
                <th style="padding:5px; border:1px solid #ccc;">Expected End Datetime</th>
            </tr>';
		 	if(count(TaskController::getInprocessTasks($project['id'])) >0 ){
                foreach(TaskController::getInprocessTasks($project['id']) as $taskNeed){
            $body.='<tr>
                <td style="padding:5px; border:1px solid #ccc;">'.$taskNeed['task_name'].'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.$taskNeed['user']['first_name'].'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.$taskNeed['expected_start_datetime'].'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.$taskNeed['expected_end_datetime'].'</td>
            </tr>';
              } 
			}else{
			$body.= "<tr style='padding:5px; border:1px solid #ccc;'><td>No Result</td></tr>";	
		}
		$body.='</table>
    <hr/>
		<h3>Week Tasks</h3>
        <table width="100%" cellpadding="5" style="border-collapse:collapse">
            <tr>
                <th style="padding:5px; border:1px solid #ccc;">Task Name</th>
                <th style="padding:5px; border:1px solid #ccc;">User</th>
                <th style="padding:5px; border:1px solid #ccc;">Actual Start Datetime</th>
                <th style="padding:5px; border:1px solid #ccc;">Actual End Datetime</th>
            </tr>';
            
				if(count(TaskController::getWeakClosedTasks($project['id'])) >0 ){
                foreach(TaskController::getWeakClosedTasks($project['id']) as $taskNeed){
            $body.='<tr>
                <td style="padding:5px; border:1px solid #ccc;">'.$taskNeed['task_name'].'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.$taskNeed['user']['first_name'].'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.$taskNeed['actual_start_datetime'].'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.$taskNeed['actual_end_datetime'].'</td>
            </tr>';
              } 
			}else{
			$body.= "<tr style='padding:5px; border:1px solid #ccc;'><td>No Result</td></tr>";	
		}
		$body.='</table>
    <hr/>
		<h3>Month Tasks</h3>
        <table width="100%" cellpadding="5" style="border-collapse:collapse">
            <tr>
                <th style="padding:5px; border:1px solid #ccc;">Task Name</th>
                <th style="padding:5px; border:1px solid #ccc;">User</th>
                <th style="padding:5px; border:1px solid #ccc;">Actual Start Datetime</th>
                <th style="padding:5px; border:1px solid #ccc;">Actual End Datetime</th>
            </tr>';
            
			if(count(TaskController::getMonthClosedTasks($project['id'])) >0 ){
                foreach(TaskController::getMonthClosedTasks($project['id']) as $taskNeed){
           
            $body.='<tr>
                <td style="padding:5px; border:1px solid #ccc;">'.$taskNeed['task_name'].'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.$taskNeed['user']['first_name'].'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.$taskNeed['actual_start_datetime'].'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.$taskNeed['actual_end_datetime'].'</td>
            </tr>';
            } 
			}else{
			$body.="<tr style='padding:5px; border:1px solid #ccc;'><td>No Result</td></tr>";	
		}
        $body.='</table>';
		$body.='<h3>Defect Need Actions</h3>
    <table width="100%" cellpadding="5" style="border-collapse:collapse">
    	<tr>
        	<th style="padding:5px; border:1px solid #ccc;">Defect Name</th>
            <th style="padding:5px; border:1px solid #ccc;">User</th>
            <th style="padding:5px; border:1px solid #ccc;">Expected Start Datetime</th>
            <th style="padding:5px; border:1px solid #ccc;">Expected End Datetime</th>
        </tr>';
       
			if(count(DefectController::getNeedActions($project['id'])) >0 ){
				foreach(DefectController::getNeedActions($project['id']) as $defectNeed){
	
        $body.='<tr>
        	<td style="padding:5px; border:1px solid #ccc;">'.$defectNeed[defect_name].'</td>
            <td style="padding:5px; border:1px solid #ccc;">'.$defectNeed[user][first_name].'</td>
            <td style="padding:5px; border:1px solid #ccc;">'.$defectNeed[expected_start_datetime].'</td>
            <td style="padding:5px; border:1px solid #ccc;">'.$defectNeed[expected_end_datetime].'</td>
         </tr>';
			} 
		}else{
			$body.="<tr style='padding:5px; border:1px solid #ccc;'><td>No Result</td></tr>";	
		}
    $body.='</table>
    <hr/>
		<h3>Inprocess Defects</h3>
        <table width="100%" cellpadding="5" style="border-collapse:collapse">
            <tr>
                <th style="padding:5px; border:1px solid #ccc;">Defect Name</th>
                <th style="padding:5px; border:1px solid #ccc;">User</th>
                <th style="padding:5px; border:1px solid #ccc;">Expected Start Datetime</th>
                <th style="padding:5px; border:1px solid #ccc;">Expected End Datetime</th>
            </tr>';
		 	if(count(DefectController::getInprocessDefects($project['id'])) >0 ){
                foreach(DefectController::getInprocessDefects($project['id']) as $defectNeed){
            $body.='<tr>
                <td style="padding:5px; border:1px solid #ccc;">'.$defectNeed['defect_name'].'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.$defectNeed['user']['first_name'].'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.$defectNeed['expected_start_datetime'].'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.$defectNeed['expected_end_datetime'].'</td>
            </tr>';
              } 
			}else{
			$body.= "<tr style='padding:5px; border:1px solid #ccc;'><td>No Result</td></tr>";	
		}
		$body.='</table>
    <hr/>
		<h3>Week Defects</h3>
        <table width="100%" cellpadding="5" style="border-collapse:collapse">
            <tr>
                <th style="padding:5px; border:1px solid #ccc;">Defect Name</th>
                <th style="padding:5px; border:1px solid #ccc;">User</th>
                <th style="padding:5px; border:1px solid #ccc;">Actual Start Datetime</th>
                <th style="padding:5px; border:1px solid #ccc;">Actual End Datetime</th>
            </tr>';
            
				if(count(DefectController::getWeakClosedDefects($project['id'])) >0 ){
                foreach(DefectController::getWeakClosedDefects($project['id']) as $defectNeed){
            $body.='<tr>
                <td style="padding:5px; border:1px solid #ccc;">'.$defectNeed['defect_name'].'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.$defectNeed['user']['first_name'].'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.$defectNeed['actual_start_datetime'].'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.$defectNeed['actual_end_datetime'].'</td>
            </tr>';
              } 
			}else{
			$body.= "<tr style='padding:5px; border:1px solid #ccc;'><td>No Result</td></tr>";	
		}
		$body.='</table>
    <hr/>
		<h3>Month Defects</h3>
        <table width="100%" cellpadding="5" style="border-collapse:collapse">
            <tr>
                <th style="padding:5px; border:1px solid #ccc;">Defect Name</th>
                <th style="padding:5px; border:1px solid #ccc;">User</th>
                <th style="padding:5px; border:1px solid #ccc;">Actual Start Datetime</th>
                <th style="padding:5px; border:1px solid #ccc;">Actual End Datetime</th>
            </tr>';
            
			if(count(DefectController::getMonthClosedDefects($project['id'])) >0 ){
                foreach(DefectController::getMonthClosedDefects($project['id']) as $defectNeed){
           
            $body.='<tr>
                <td style="padding:5px; border:1px solid #ccc;">'.$defectNeed['defect_name'].'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.$defectNeed['user']['first_name'].'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.$defectNeed['actual_start_datetime'].'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.$defectNeed['actual_end_datetime'].'</td>
            </tr>';
            } 
			}else{
			$body.="<tr style='padding:5px; border:1px solid #ccc;'><td>No Result</td></tr>";	
		}
        $body.='</table>';
		// Create the mail transport configuration
		$transport = Swift_MailTransport::newInstance();
		 
		// Create the message
		$message = Swift_Message::newInstance();
		$userEmails[]=$toEmail;
		$message->setTo($userEmails);
		$message->setSubject($subject);
		$message->setContentType("text/html");
		$message->setBody($body);
		$message->setFrom("contact@liveobjects.org", "Live Objects");
		 
		// Send the email
		$mailer = Swift_Mailer::newInstance($transport);
		$mailer->send($message);
 //SendEmail::sendLiveEmail($toEmail, $body, $cc = false, $subject); 
} ?>
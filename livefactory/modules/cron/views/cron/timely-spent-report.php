<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use kartik\widgets\ActiveForm;

use dosamigos\ckeditor\CKEditor;
use kartik\builder\Form;
use livefactory\modules\pmt\controllers\TaskController;
use livefactory\modules\pmt\controllers\DefectController;
use livefactory\models\SendEmail;
use livefactory\models\TimeDiffModel;

date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
$emailObj = new SendEmail;
function approved($val){

	if($val=='0')

	$label = "<span class=\"label label-warning\">Waiting for Approval</span>";

	else if($val=='1')

	$label = "<span class=\"label label-success\">Approved</span>";	

	else

	$label = "<span class=\"label label-danger\">Rejected</span>";	

	return $label;

}
$subject='';
include_once(__DIR__ .'/../../../../../vendor/swiftmailer/swiftmailer/lib/swift_required.php');

//require_once 'lib/swift_required.php';
//$taskModel = new TaskController;
	foreach($dataProvider as $project){
	$toEmail = TaskController::getProjectOwnerEmail($project['id']);
	$userEmails = TaskController::getProjectUsersEmail($project['id']);
	$spend_t=$dotNumTot=$solidNumTot=$secondTot=0;
	foreach(TaskController::getTimeSpent($project['id']) as $trow1){

		list($solidNum,$dotNum,$seconds) = explode('.',TimeDiffModel::getTimeDiff(date('Y/m/d H:i:s',($trow1['start_time'])),date('Y/m/d H:i:s',($trow1['end_time']))));

		$solidNumTot+=$solidNum;

		$dotNumTot+=$dotNum;
		$secondTot+=$seconds;
	}
	///Seconds
	list($plusNum1)=explode('.',$secondTot/60);
	$seconddotVal=round($secondTot%60);
	$dotNumTot =$dotNumTot+$plusNum1;
	$seconddotVal=strlen($seconddotVal)==1?$seconddotVal:$seconddotVal;
	
	list($plusNum)=explode('.',$dotNumTot/60);;

	$dotVal=round($dotNumTot%60);

	$solidNum =$solidNumTot+$plusNum;

	$spend_t=$solidNum." hours, ".$dotVal." minutes, ".$seconddotVal." seconds";
	
	/*------------------------------Defect Total Time----------------------------------------------------------------------*/
		$spend_t1=$dotNumTot=$solidNumTot=$secondTot=0;
        foreach(DefectController::getTimeSpent($project['id']) as $trow1){

            list($solidNum,$dotNum,$seconds) = explode('.',TimeDiffModel::getTimeDiff(date('Y/m/d H:i:s',($trow1['start_time'])),date('Y/m/d H:i:s',($trow1['end_time']))));

            $solidNumTot+=$solidNum;

            $dotNumTot+=$dotNum;
			$secondTot+=$seconds;
        }
		///Seconds
		list($plusNum1)=explode('.',$secondTot/60);
		$seconddotVal=round($secondTot%60);
		$dotNumTot =$dotNumTot+$plusNum1;
		$seconddotVal=strlen($seconddotVal)==1?$seconddotVal:$seconddotVal;
		
        list($plusNum)=explode('.',$dotNumTot/60);;

        $dotVal=round($dotNumTot%60);

        $solidNum =$solidNumTot+$plusNum;

        $spend_t1=$solidNum." hours, ".$dotVal." minutes, ".$seconddotVal." seconds";
		
		
	$subject='Weekly Time Spent Summary Project '.$project['project_name'];
    $body ='Hi '.$project['first_name']." ".$project['last_name']."<br/>";
	$body.="Please find below summary report for $project[project_name]<br/>";
	$body.="Total Tasks Time - "; 
	$body.= $spend_t."<br/>";
	$body.="Total Defects Time  - "; 
	$body.= $spend_t1."<br/>";
	$body.='<h3> Task Timesheet</h3>';
	$body.= '<table width="100%" cellpadding="5" style="border-collapse:collapse">
				<tr>
					<th style="padding:5px; border:1px solid #ccc;">User</th>
					<th style="padding:5px; border:1px solid #ccc;">Project</th>
					<th style="padding:5px; border:1px solid #ccc;">Task</th>
					<th style="padding:5px; border:1px solid #ccc;">Type</th>
					<th style="padding:5px; border:1px solid #ccc;">Start Date</th>
					<th style="padding:5px; border:1px solid #ccc;">End Date</th>
					<th style="padding:5px; border:1px solid #ccc;">Time Spent</th>
					<th style="padding:5px; border:1px solid #ccc;">Approved</th>
				</tr>';
	$dataProvider=TaskController::getTimeSpent($project['id']);
	 if(count($dataProvider) > 0){

        
		 foreach($dataProvider as $row){

           $body.= '<tr>

            	<td style="padding:5px; border:1px solid #ccc;">'.$row['first_name'].'</td>

                <td style="padding:5px; border:1px solid #ccc;">'.$row['project_name'].'</td>

                <td style="padding:5px; border:1px solid #ccc;">'.$row['task_name'].'</td>

                <td style="padding:5px; border:1px solid #ccc;">'.$row['entry_type'].'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.date('Y-m-d H:i:s', $row['start_time']).'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.date('Y-m-d H:i:s', $$row['end_time']).'</td>

                <td style="padding:5px; border:1px solid #ccc;">'.TimeDiffModel::dateDiff(date('Y-m-d H:i:s', $row['end_time']),date('Y-m-d H:i:s', $row['start_time'])).'</td>

                <td style="padding:5px; border:1px solid #ccc;">'.approved($row['approved']).'</td>

            </tr>';

        } 

        

        $body.= '<tr>

            <td colspan="8" align="right">

                <strong>Total Spent Time: '.$spend_t.'</strong>

            </td>

        </tr>';
		 }else{
			$body.="<tr style='padding:5px; border:1px solid #ccc;'><td>No Result</td></tr>";	
		}
   		 $body.='</table><hr/>';
	$body.='<h3> Defect Timesheet</h3>';
	$body.= '<table width="100%" cellpadding="5" style="border-collapse:collapse">
				<tr>
					<th style="padding:5px; border:1px solid #ccc;">User</th>
					<th style="padding:5px; border:1px solid #ccc;">Project</th>
					<th style="padding:5px; border:1px solid #ccc;">Defect</th>
					<th style="padding:5px; border:1px solid #ccc;">Type</th>
					<th style="padding:5px; border:1px solid #ccc;">Start Date</th>
					<th style="padding:5px; border:1px solid #ccc;">End Date</th>
					<th style="padding:5px; border:1px solid #ccc;">Time Spent</th>
					<th style="padding:5px; border:1px solid #ccc;">Approved</th>
				</tr>';
	$dataProvider=DefectController::getTimeSpent($project['id']);
	 if(count($dataProvider) > 0){

        
		 foreach($dataProvider as $row){

           $body.= '<tr>

            	<td style="padding:5px; border:1px solid #ccc;">'.$row['first_name'].'</td>

                <td style="padding:5px; border:1px solid #ccc;">'.$row['project_name'].'</td>

                <td style="padding:5px; border:1px solid #ccc;">'.$row['defect_name'].'</td>

                <td style="padding:5px; border:1px solid #ccc;">'.$row['entry_type'].'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.date('Y-m-d H:i:s', $row['start_time']).'</td>
                <td style="padding:5px; border:1px solid #ccc;">'.date('Y-m-d H:i:s', $row['end_time']).'></td>

                <td style="padding:5px; border:1px solid #ccc;">'.TimeDiffModel::dateDiff(date('Y-m-d H:i:s', $row['end_time']),date('Y-m-d H:i:s', $row['start_time'])).'</td>

                <td style="padding:5px; border:1px solid #ccc;">'.approved($row['approved']).'</td>

            </tr>';

        } 

        $body.= '<tr>

            <td colspan="8" align="right">

                <strong>Total Spent Time: '.$spend_t1.'</strong>

            </td>

        </tr>';
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
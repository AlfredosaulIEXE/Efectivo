<?php

namespace livefactory\modules\pmt\controllers;

use Yii;
use yii\helpers\Html;

use livefactory\models\NoteModel;
use livefactory\models\FileModel;
use livefactory\models\AssignmentHistoryModel;
use livefactory\models\HistoryModel;
use livefactory\models\TimeDiffModel;

use livefactory\models\Defect;
use livefactory\models\File;
use livefactory\models\Note;
use livefactory\models\History;
use livefactory\models\SendEmail;
use livefactory\models\DefectPriority;
use livefactory\models\Project;
use livefactory\models\DefectTime;
use livefactory\models\TimeEntry;
use livefactory\models\DefectStatus;
use livefactory\models\DefectSla;
use livefactory\models\User as UserDetail;
use livefactory\models\AssignmentHistory;
use livefactory\models\search\Defect as DefectSearch;
use livefactory\models\TimesheetModel;
use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use livefactory\models\search\CommonModel as SessionVerification;
use \DateTime;
use \DateTimeZone;
/**
 * DefectController implements the CRUD actions for Defect model.
 */
class DefectController extends Controller
{
	public $entity_type='defect';
	public $user_id =1;
	
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
	public static function getUserEmail($id){
		$userModel = UserDetail::findOne($id);	
		return $userModel->email;
	}
	public static function getProjectOwnerEmail($id){
		$projectModel = Project::findOne($id);
		$projectModelOwner = UserDetail::findOne($projectModel->project_owner_id);
		return $projectModelOwner->email;
	}
	public static function getProjectOwnerId($id){
		$projectModel = Project::findOne($id);
		
		return $projectModel->project_owner_id;
	}
	
	public static function getProjectOwnerFullName($id){
		$projectUsersModel = Project::find()->where("id=$id")->asArray()->all();
		//var_dump($projectUsersModel);
		//die();
		foreach($projectUsersModel as $user){
			$user = UserDetail::findOne($user['project_owner_id']);
			}
		
		return $user->first_name." ".$user->last_name;	
	}
	
	public static function getUserFullName($id){
		$user = UserDetail::findOne($id);
		
		return $user->first_name." ".$user->last_name;	
	}
	public static function getDefectStuts($id){
		$status = DefectStatus::findOne($id);
		
		return $status->label;	
	}
	public static function getLoggedUserFullName(){
		$user = UserDetail::findOne(Yii::$app->user->identity->id);
		return $user->first_name." ".$user->last_name;	
	}
	public static function getLoggedUserDetail(){
		$user = UserDetail::find()->where('id='.Yii::$app->user->identity->id)->asArray()->one();
		return $user;	
	}
	
	public function updateDefectSpendTime($id){
		$defectModel = TimeEntry::find()->where("entity_id=$id and entity_type='".$this->entity_type."'")->asArray()->all();
					
		$spend_t=0;
		$dotNumTot=0;
		$secondTot=0;
		$solidNumTot=0;
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		foreach($defectModel as $trow1){
			list($solidNum,$dotNum,$seconds) = explode('.',TimeDiffModel::getTimeDiff(date('Y/m/d H:i:s',($trow1['start_time'])),date('Y/m/d H:i:s',($trow1['end_time']))));
			$solidNumTot+=$solidNum;
			$dotNumTot+=$dotNum;
			$secondTot+=$seconds;
		}
		///Seconds
		list($plusNum1)=explode('.',$secondTot/60);
		$seconddotVal=round($secondTot%60);
		$dotNumTot =$dotNumTot+$plusNum1;
		
		
		list($plusNum)=explode('.',$dotNumTot/60);;
		$dotVal=round($dotNumTot%60);
		$solidNum =$solidNumTot+$plusNum;
		$dotVal=strlen($dotVal)==1?"0".$dotVal:$dotVal;
		$solidNum=strlen($solidNum)==1?"0".$solidNum:$solidNum;
		$spend_t=$solidNum.".".$dotVal;
		// Update Defect Spend Time 
		$editDefect= Defect::findOne($id);//->where(['id' =>$id])->one();
		$editDefect->time_spent=$spend_t;
		$editDefect->updated_at=time();
		$editDefect->update();	
	}
	public function defectIdUdate($id){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$zirolengh=6-intval(strlen($id));
		$stringId ="Defect".str_repeat("0", $zirolengh).$id;
		$defectUpdate= Defect::find()->where(['id' => $id])->one();
		$defectUpdate->defect_id=$stringId;
		$defectUpdate->added_at=strtotime(date('Y-m-d h:i:s'));
		$defectUpdate->update();
		return 	$stringId;
	}
	public function getTotalNeedAction(){
		if(Yii::$app->params['user_role'] !='admin'){
			return Defect::find()->where("defect_status_id in (".DefectStatus::_NEEDSACTION.", ".DefectStatus::_INPROCESS.") ")->count();
		}else{
			return Defect::find()->where("defect_status_id in (".DefectStatus::_NEEDSACTION.", ".DefectStatus::_INPROCESS.")  and EXISTS(Select *
FROM tbl_project_user  WHERE project_id ='".$defect_model->project_id."' and user_id=".Yii::$app->user->identity->id.")")->count();
		}
	}
    /**
     * Lists all Defect models.
     * @return mixed
     */
    public function actionIndex()
    {
		if(!Yii::$app->user->can('Defect.Index')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
        $searchModel = new DefectSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
		if(!empty($_REQUEST['multiple_del'])){
			if(!Yii::$app->user->can('Defect.Delete')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
			$rows=$_REQUEST['selection'];
			for($i=0;$i<count($rows);$i++){
				$this->findModel($rows[$i])->delete();
			}
		}
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Defect model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		if(!Yii::$app->user->can('Defect.View')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->id]);
        } else {
        return $this->render('view', ['model' => $model]);
}
    }
    /**
     * Creates a new Defect model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		if(!Yii::$app->user->can('Defect.Create')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		$emailObj = new SendEmail;
        $model = new Defect;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			/// Create Defect Id
			//$stringId = $this->defectIdUdate($model->id);
			$stringId = 'DEFECT'.str_pad($model->id, 9, "0", STR_PAD_LEFT);
			$model->defect_id=$stringId;
			$model->added_at=time();
			
			/*
			$slaObj = DefectSla::find()->where('defect_priority_id ='.$model->defect_priority_id.' and defect_type_id = '.$model->defect_type_id)->one();
			$StartSlaSecs=$slaObj->start_sla * 60 * 60;
			$EndSlaSecs=$slaObj->end_sla * 60 * 60;
			$model->expected_start_datetime=$model->added_at+$StartSlaSecs;
			$model->expected_end_datetime=$model->added_at+$EndSlaSecs;
			8*/
			
			if($model->expected_start_datetime != '')
			{
				$expected_start_datetime = new DateTime($_REQUEST['Defect']['expected_start_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
				$model->expected_start_datetime = $expected_start_datetime->getTimestamp();
			}

			if($model->expected_end_datetime != '')
			{
				$expected_end_datetime = new DateTime($_REQUEST['Defect']['expected_end_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
				$model->expected_end_datetime = $expected_end_datetime->getTimestamp();
			}

			if($model->actual_start_datetime != '')
			{
				$actual_start_datetime = new DateTime($_REQUEST['Defect']['actual_start_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
				$model->actual_start_datetime = $actual_start_datetime->getTimestamp();
			}

			if($model->actual_end_datetime != '')
			{
				$actual_end_datetime = new DateTime($_REQUEST['Defect']['actual_end_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
				$model->actual_end_datetime = $actual_end_datetime->getTimestamp();
			}

			$model->save();

			// Add Notes For Defect
			if(!empty($_REQUEST['notes'])){
				$nid = NoteModel::noteInsert($model->id,$this->entity_type);
				// Notes Email Send ($email,$user_name,$user_by,$url)
				$emailObj->sendNoteEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),$this->getLoggedUserFullName(),'<a href="'.$_SESSION['base_url'].'?r=pmt/defect/defect-view&id='.$model->id.'">'.$stringId.'</a>');
			}
			
			
			//Add History For Defect
			HistoryModel::historyInsert($this->entity_type,$model->id,'Defecto Createdo con el Id '.$model->id);
			
			// Entry on Assigned History
			if($model->user_assigned_id){
				AssignmentHistoryModel::assignHistoryInsert($this->entity_type,$model->id,$model->user_assigned_id,'Defect Assigned to user');
				//Email Send  sendDefectEmailTemplate($email,$user_name,$url,$desc)
				$emailObj->sendDefectEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),'<a href="'.$_SESSION['base_url'].'?r=pmt/defect/defect-view&id='.$model->id.'">'.$stringId.'</a>',$model->defect_description); 
			}
			if(Yii::$app->params['SHOW_ADD_ATTACHMENT_PAGE_NEW_DEFECT'] =='Yes'){
				return $this->redirect(['add-attachment', 'entity_id' => $model->id,'user_id'=>$model->user_assigned_id]);	
			}else{
				return $this->redirect(['defect-view', 'id' => $model->id]);
			}
        } 
		
		/*  this code was rendering different create page from manage defect add defect button
		elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                        'model' => $model
            ]);
        }
		*/
		
		
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
			
            return $this->redirect(['defect-view', 'id' => $_REQUEST['entity_id']]);
			} else {
            return $this->render('add-attachment');
        }
		
	}
    /**
     * Updates an existing Defect model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		if(!Yii::$app->user->can('Defect.Update')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
	public function getDateTimeFormat($date){
		$datetime = new \DateTime($date);
		return $datetime->format('Y-m-d H:i:s');	
	}
	public function actionDefectView($id)
    {
		if(!Yii::$app->user->can('Defect.View')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		$user_id=1;
        $model = $this->findModel($id);
		$sub_defect = new Defect;
		$emailObj = new SendEmail;
		$attachModelR='';
		$noteModelR='';
		//Add Sub Defect
		if(!empty($_REQUEST['defectid'])){
			if ($sub_defect->load(Yii::$app->request->post()) && $sub_defect->save()) {
				
				/// Create Defect Id
				//$stringId = $this->defectIdUdate($sub_defect->id);
				$stringId = 'DEFECT'.str_pad($sub_defect->id, 9, "0", STR_PAD_LEFT);
				$sub_defect->defect_id=$stringId;
				$sub_defect->added_at=time();
				$sub_defect->save();
				$subDefectUpdate = Defect::findOne($sub_defect->id);

				$slaObj = DefectSla::find()->where('defect_priority_id ='.$subDefectUpdate->defect_priority_id.' and defect_type_id = '.$subDefectUpdate->defect_type_id)->one();

				$StartSlaSecs=$slaObj->start_sla * 60 * 60;
				$EndSlaSecs=$slaObj->end_sla * 60 * 60;
				$subDefectUpdate->expected_start_datetime=$subDefectUpdate->added_at+$StartSlaSecs;
				$subDefectUpdate->expected_end_datetime=$subDefectUpdate->added_at+$EndSlaSecs;

				$expected_start_datetime = new DateTime($_REQUEST['Defect']['expected_start_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
				$model->expected_start_datetime = $expected_start_datetime->getTimestamp();

				if($model->expected_end_datetime != '')
				{
					$expected_end_datetime = new DateTime($_REQUEST['Defect']['expected_end_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
					$model->expected_end_datetime = $expected_end_datetime->getTimestamp();
				}

				if($model->actual_start_datetime != '')
				{
					$actual_start_datetime = new DateTime($_REQUEST['Defect']['actual_start_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
					$model->actual_start_datetime = $actual_start_datetime->getTimestamp();
				}

				if($model->actual_end_datetime != '')
				{
					$actual_end_datetime = new DateTime($_REQUEST['Defect']['actual_end_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
					$model->actual_end_datetime = $actual_end_datetime->getTimestamp();
				}


				$subDefectUpdate->update();
				//Add History For Defect
				HistoryModel::historyInsert($this->entity_type,$subDefectUpdate->id,'Defecto Creado con el Id '.$subDefectUpdate->id);
				//Add History For Defect
				HistoryModel::historyInsert($this->entity_type,$model->id,'Sub Defecto Creado con el Id '.$subDefectUpdate->id);
				return $this->redirect(['defect-view', 'id' => $_REQUEST['defectid']]);
			}
		}
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$model->last_updated_by_user_id = Yii::$app->user->identity->id;
			$model->updated_at = time();
			
			if($model->expected_start_datetime != '')
			{
				$expected_start_datetime = new DateTime($_REQUEST['Defect']['expected_start_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
				$model->expected_start_datetime = $expected_start_datetime->getTimestamp();
			}

			if($model->expected_end_datetime != '')
			{
				$expected_end_datetime = new DateTime($_REQUEST['Defect']['expected_end_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
				$model->expected_end_datetime = $expected_end_datetime->getTimestamp();
			}

			if($model->actual_start_datetime != '')
			{
				$actual_start_datetime = new DateTime($_REQUEST['Defect']['actual_start_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
				$model->actual_start_datetime = $actual_start_datetime->getTimestamp();
			}

			if($model->actual_end_datetime != '')
			{
				$actual_end_datetime = new DateTime($_REQUEST['Defect']['actual_end_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
				$model->actual_end_datetime = $actual_end_datetime->getTimestamp();
			}
			
			/*
			$slaObj = DefectSla::find()->where('defect_priority_id ='.$model->defect_priority_id.' and defect_type_id = '.$model->defect_type_id)->one();
			$StartSlaSecs=$slaObj->start_sla * 60 * 60;
			$EndSlaSecs=$slaObj->end_sla * 60 * 60;
			$model->expected_start_datetime=$model->added_at+$StartSlaSecs;
			$model->expected_end_datetime=$model->added_at+$EndSlaSecs;
			*/

			if($model->defect_status_id == DefectStatus::_INPROCESS)
			{
				if($model->actual_start_datetime == '')
					$model->actual_start_datetime = time();

				$model->actual_end_datetime = '';
			}

			if($model->defect_status_id == DefectStatus::_COMPLETED)
			{
				if($model->actual_end_datetime == '')
					$model->actual_end_datetime = time();

				$model->defect_progress = 100;
			}

			$model->update();
			$old_owner=!empty($_REQUEST['old_owner'])?$_REQUEST['old_owner']:'';
			$old_defect_priority_id=!empty($_REQUEST['old_defect_priority_id'])?$_REQUEST['old_defect_priority_id']:'';
			$old_defect_status_id=!empty($_REQUEST['old_defect_status_id'])?$_REQUEST['old_defect_status_id']:'';
			/// Assigned user Changed
			if($model->user_assigned_id != $old_owner){				
				//Send an Email
				$emailObj->sendDefectChangedUserEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),$this->getLoggedUserFullName(),'<a href="'.$_SESSION['base_url'].'?r=pmt/defect/defect-view&id='.$model->id.'">'.$model->defect_id.'</a>',$this->getDefectStuts($model->defect_status_id));
				
				// Add AssignmentHistory
				AssignmentHistoryModel::assignHistoryChange($this->entity_type,$model->id,$model->user_assigned_id,$old_owner,"Changed Assigned User",$model->added_at);
				
				//Add History
				HistoryModel::historyInsert($this->entity_type,$model->id,"History is updated as Defect is assigned to ".$this->getUserFullName($model->user_assigned_id)." by ".$this->getUserFullName($old_owner).' into ( <a href="index.php?r=pmt/defect/defect-view&id='.$model->id.'">'.$model->defect_id.'</a>)');
			}
			
			/// Defect Priority Changed
			if($model->defect_priority_id != $old_defect_priority_id){
				$defectPriorityModel = DefectPriority::findOne($model->defect_priority_id);
				$defectPriorityModelOld = DefectPriority::findOne($old_defect_priority_id);
				//Add History
				HistoryModel::historyInsert($this->entity_type,$model->id,"Defect priority changed from ".$defectPriorityModelOld->label." to ".$defectPriorityModel->label." by ".$this->getLoggedUserFullName().'  into ( <a href="index.php?r=pmt/defect/defect-view&id='.$model->id.'">'.$model->defect_id.'</a>)');
				
				//Send an Email
				$emailObj->sendDefectChangedPriorityEmailTemplate($this->getUserEmail($model->user_assigned_id).",".$this->getProjectOwnerEmail($model->project_id),$this->getUserFullName($model->user_assigned_id),$this->getLoggedUserFullName(),'<a href="'.$_SESSION['base_url'].'?r=pmt/defect/defect-view&id='.$model->id.'">'.$model->defect_id.'</a>',$defectPriorityModelOld->label,$defectPriorityModel->label);
				
			}
			
			/// Defect Status Changed
			if($model->defect_status_id != $old_defect_status_id){
				$defectStatusModel = DefectStatus::findOne($model->defect_status_id);
				$defectStatusModelOld = DefectStatus::findOne($old_defect_status_id);
				//Add History
				HistoryModel::historyInsert($this->entity_type,$model->id,"Defect status changed from ".$defectStatusModelOld->label." to ".$defectStatusModel->label." by ".$this->getLoggedUserFullName().'  into ( <a href="index.php?r=pmt/defect/defect-view&id='.$model->id.'">'.$model->defect_id.'</a>)');
				//Send an Email
				$emailObj->sendDefectChangedStatusEmailTemplate($this->getUserEmail($model->user_assigned_id).",".$this->getProjectOwnerEmail($model->project_id),$this->getUserFullName($model->user_assigned_id),$this->getLoggedUserFullName(),'<a href="'.$_SESSION['base_url'].'?r=pmt/defect/defect-view&id='.$model->id.'">'.$model->defect_id.'</a>',$defectStatusModelOld->label,$defectStatusModel->label);
				
			}
			//Add History
			HistoryModel::historyInsert($this->entity_type,$model->id,'Defecto actualizado '.$model->id);
            //return $this->redirect(['index']);
			return $this->redirect(['defect-view', 'id' => $_REQUEST['id']]);
        } else {
			$timeEntryModel = new TimeEntry();
			// Send Attachment File to Defect Assigned User
			if(!empty($_REQUEST['send_attachment_file'])){
				//Send an Email
				SendEmail::sendLiveEmail($_REQUEST['uemail'],$_REQUEST['email_body'], $_REQUEST['cc'], $_REQUEST['subject']);
					return $this->redirect(['defect-view', 'id' => $_REQUEST['id']]);
			}
			// Delete Defect Attachment
			if(!empty($_REQUEST['attachment_del_id'])){
					$Attachmodel = File::findOne($_REQUEST['attachment_del_id']);
					if (!is_null($Attachmodel)) {
						$Attachmodel->delete();
					}
					//$Attachmodel = File::findOne($_REQUEST['attachment_del_id'])->delete();
					//Add History For Defect
					HistoryModel::historyInsert($this->entity_type,$model->id,$model->defect_id.' Defect Attachment Deleted from ( <a href="index.php?r=pmt/defect/defect-view&id='.$model->id.'">'.$model->defect_id.'</a>)');
					return $this->redirect(['defect-view', 'id' => $_REQUEST['id']]);
			}
			// Delete Defect 
			if(!empty($_REQUEST['defect_del'])){
					$defectDel = Defect::findOne($_REQUEST['defect_del']);
					if (!is_null($defectDel)) {
						$defectDel->delete();
					}
					//Add History For Defect
					HistoryModel::historyInsert($this->entity_type,$model->id,$model->defect_id.' Defect SubDefect  Deleted from ( <a href="index.php?r=pmt/defect/defect-view&id='.$model->id.'">'.$model->defect_id.'</a>)');
					return $this->redirect(['defect-view', 'id' => $_REQUEST['id']]);
			}
			// Delete Defect Notes
			if(!empty($_REQUEST['note_del_id'])){
				$NoteDel = Note::findOne($_REQUEST['note_del_id']);
				if (!is_null($NoteDel)) {
					$NoteDel->delete();
				}
					//$NoteDel = Note::findOne($_REQUEST['note_del_id'])->delete();
					//Add History For Defect
					HistoryModel::historyInsert($this->entity_type,$model->id,$model->defect_id.'Defect Note  Deleted from ( <a href="index.php?r=pmt/defect/defect-view&id='.$model->id.'">'.$model->defect_id.'</a>)');
					return $this->redirect(['defect-view', 'id' => $_REQUEST['id']]);
			}
			
			
			// Add Attachment for Defect
			if(!empty($_REQUEST['add_attach'])){
				$aid=FileModel::fileInsert($_REQUEST['entity_id'],$this->entity_type);
				if($aid > 0)
				{
					$link="<a href='".str_replace('web/index.php','',$_SESSION['base_url'])."attachments/".$aid.strrchr($_FILES['attach']['name'], ".")."'>".$_FILES['attach']['name']."</a>";
					$emailObj->sendAddAttachmentEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),$link,'<a href="'.$_SESSION['base_url'].'?r=pmt/defect/defect-view&id='.$model->id.'">'.$model->defect_id.'</a>');
					
					//Add History For Defect
					HistoryModel::historyInsert($this->entity_type,$model->id,'Archivo Adjunto agregado');
						return $this->redirect(['defect-view', 'id' => $_REQUEST['id']]);
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

					return $this->redirect(['defect-view', 'id' => $_REQUEST['id'], 'err_msg' => $msg]);
				}
			}
			
			
			
			// Defect Attachment get
			if(!empty($_REQUEST['attach_update'])){
				$attachModelR=File::findOne($_REQUEST['attach_update']);
				//Add History For Defect
				HistoryModel::historyInsert($this->entity_type,$model->id,$model->defect_id.' Defect Attachment Updated in ( <a href="index.php?r=pmt/defect/defect-view&id='.$model->id.'">'.$model->defect_id.'</a>)');
			}
			// Defect Notes get
			if(!empty($_REQUEST['note_id'])){
				$noteModelR=Note::findOne($_REQUEST['note_id']);
			}
			// Defect Attachment Update
			if(!empty($_REQUEST['edit_attach'])){
					$file=FileModel::fileEdit();
					if($_FILES['attach']['name']){
						$aid=$_REQUEST['att_id'];
						$link="<a href='".str_replace('web/index.php','',$_SESSION['base_url'])."attachments/".$aid.strrchr($_FILES['attach']['name'], ".")."'>".$_FILES['attach']['name']."</a>";
						//Send an Email
						$emailObj->sendUpdateAttachmentEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),$link,'<a href="'.$_SESSION['base_url'].'?r=pmt/defect/defect-view&id='.$model->id.'">'.$model->defect_id.'</a>');
						
					}
				//Add History For Defect
				HistoryModel::historyInsert($this->entity_type,$model->id,'Archivo adjunto actualizado');
					return $this->redirect(['defect-view', 'id' => $_REQUEST['id']]);
			}
			
			// Add Notes
			if(!empty($_REQUEST['add_note_model'])){
				$nid = NoteModel::noteInsert($_REQUEST['id'],$this->entity_type);
				if($nid){
					setcookie('inserted_notes'.$_REQUEST['id'],true,time()+7200);
				}
				//Send an Email
				$emailObj->sendNoteEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),$this->getLoggedUserFullName()." <br>".$_REQUEST['notes'],'<a href="'.$_SESSION['base_url'].'?r=pmt/defect/defect-view&id='.$model->id.'">'.$model->defect_id.'</a>');
				//Add History For Defect
				HistoryModel::historyInsert($this->entity_type,$model->id,'Nota agregada');
				return $this->redirect(['defect-view', 'id' => $_REQUEST['id']]);
			}
			
			// Update Notes
			if(!empty($_REQUEST['edit_note_model'])){
				$nid = NoteModel::noteEdit();
				//Send an Email
				$emailObj->sendNoteUpdateEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),$this->getLoggedUserFullName()." <br>".$_REQUEST['notes'],'<a href="'.$_SESSION['base_url'].'?r=pmt/defect/defect-view&id='.$model->id.'">'.$model->defect_id.'</a>');
				
				//Add History For Defect
				HistoryModel::historyInsert($this->entity_type,$model->id,'Nota actualizada');
				return $this->redirect(['defect-view', 'id' => $_REQUEST['id']]);
			}
			/*===================================================Timing==============================================================*/
			
			// Add Defect Timing
			if(!empty($_REQUEST['timing_add'])){
				TimesheetModel::timeEntryAdd($_REQUEST['notes'],'MANUAL',$_REQUEST['start_time'],$_REQUEST['end_time'],$this->entity_type);
				// Update Defect Spend Time
				$this->updateDefectSpendTime($_REQUEST['id']);
				//Send an Email
				$emailObj->sendTimesheetEntryTemplate($this->getProjectOwnerEmail($model->project_id),$this->getProjectOwnerFullName($model->project_id),$this->getLoggedUserFullName()." <br>".$_REQUEST['timesheet'],'<a href="'.$_SESSION['base_url'].'?r=pmt/defect/defect-view&id='.$model->id.'">'.$model->defect_id.'</a>');
			
				//Add History For Defect
				HistoryModel::historyInsert($this->entity_type,$model->id,'Se agregó tiempo de trabajo defectuoso en ');
				return $this->redirect(['defect-view', 'id' => $_REQUEST['id']]);
			}
			if(!empty($_REQUEST['time_entry_id'])){
				$timeEntryModel = TimeEntry::findOne($_REQUEST['time_entry_id']);
			}
			// Edit Defect Timing
			if(!empty($_REQUEST['timing_edit'])){
				TimesheetModel::timeEntryEdit($_REQUEST['notes'],$_REQUEST['time_entry_id'],$_REQUEST['start_time'],$_REQUEST['end_time']);
				// Update Defect Spend Time
				$this->updateDefectSpendTime($_REQUEST['id']);
				//Add History For Defect
				HistoryModel::historyInsert($this->entity_type,$model->id,'Tiempo de trabajo de defecto actualizado en ');
				return $this->redirect(['defect-view', 'id' => $_REQUEST['id']]);
			}
			// Edit Defect defectTimeEditApproved
			if(!empty($_REQUEST['appid'])){
				TimesheetModel::timeEntryApproved($_REQUEST['appid']);
				return $this->redirect(['defect-view', 'id' => $_REQUEST['id']]);
			}
			// Delete Defect Timing
			if(!empty($_REQUEST['time_del_id'])){
					$Attachmodel = TimeEntry::findOne($_REQUEST['time_del_id']);
					if (!is_null($Attachmodel)) {
						$Attachmodel->delete();
					}
					// Update Defect Spend Time
					$this->updateDefectSpendTime($_REQUEST['id']);
					//Add History For Defect
				HistoryModel::historyInsert($this->entity_type,$model->id,'Tiempo de trabajo de defecto eliminado desde ');
					return $this->redirect(['defect-view', 'id' => $_REQUEST['id']]);
			}
			if(!empty($_REQUEST['starttime'])){
				date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
				//setcookie('defect_start_time',date('Y-m-d H:i:s'));
				setcookie('defect_start_time',time());
				setcookie('defectStartedId',$_REQUEST['id']);
				 return $this->redirect(['defect-view', 'id' => $_REQUEST['id']]);
			}
			if(!empty($_REQUEST['timenotes']) && !empty($_COOKIE['defect_start_time'])){
				date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
				$start_time=date('Y-m-d H:i:s', $_COOKIE['defect_start_time']);
				$end_time = date('Y-m-d H:i:s');
				// Add Defect Time
				TimesheetModel::timeEntryAdd($_REQUEST['timenotes'],'AUTO',$start_time,$end_time,$this->entity_type);
				//setcookie('defect_start_time',date('Y-m-d H:i:s'),time()-3600);
				setcookie('defectStartedId',$_REQUEST['id'],time()-3600);
				// Update Defect Spend Time
				$this->updateDefectSpendTime($_REQUEST['id']);
				//Add History For Defect
				HistoryModel::historyInsert($this->entity_type,$model->id,'Nota agregada');
				return $this->redirect(['defect-view', 'id' => $_REQUEST['id']]);
			}
			
			
			
			
            return $this->render('defect-view', [
                'model' => $model,
				'attachModel'=>$attachModelR,
				'noteModel'=>$noteModelR,
				'sub_defect'=>$sub_defect,
				'timeEntryModel'=>$timeEntryModel,
            ]);
        }
    }
	public function actionAjaxDefect($id){
		$start_time=!empty($_REQUEST['start_time'])?$_REQUEST['start_time']:'';
		$eid=!empty($_REQUEST['eid'])?$_REQUEST['eid']:'';
		if($eid){
			$defectModel = TimeEntry::find()->where("id != $eid and entity_id=$id and entity_type='defect' and start_time<='$start_time' and end_time >='$start_time'")->one();
		}else{
		$defectModel = TimeEntry::find()->where("entity_id=$id and entity_type='defect' and start_time<='$start_time' and end_time >='$start_time'")->one();	
		}
		 return $this->renderPartial('ajax-defect', [
                'name' => $defectModel->id,
            ]);
	}
	
	public function actionAjaxDefectTimeDateValidation(){
		$start_time=$_REQUEST['start_time'];
		$end_time=$_REQUEST['end_time'];
		if($end_time){
			list($hours,$min)=explode('.',TimeDiffModel::getTimeDiff($start_time,$end_time));
			$error='';
			if(intval($hours) > 23){
				$error='yes';	
			}else{
				$error='no';	
			}
		}
		 return $this->renderPartial('ajax-defect-time-date-validation', [
                'error' =>$error,
            ]);
	}
	public function getSpentTime($enity_id,$user_id){
		$defectModel = TimeEntry::find()->where("entity_id=$enity_id and entity_type='".$this->entity_type."'")->asArray()->all();
					
		$spend_t=0;
		$dotNumTot=0;
		$solidNumTot=0;
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		foreach($defectModel as $trow1){
			list($solidNum,$dotNum) = explode('.',TimeDiffModel::getTimeDiff(date('Y/m/d H:i:s',strtotime($trow1['start_time'])),date('Y/m/d H:i:s',strtotime($trow1['end_time']))));
			$solidNumTot+=$solidNum;
			$dotNumTot+=$dotNum;
		}
		list($plusNum)=explode('.',$dotNumTot/60);;
		$dotVal=round($dotNumTot%60);
		$solidNum =$solidNumTot+$plusNum;
		$dotVal=strlen($dotVal)==1?"0".$dotVal:$dotVal;
		$solidNum=strlen($solidNum)==1?"0".$solidNum:$solidNum;
		$spend_t=$solidNum.":".$dotVal;	
		return $solidNum;
	}
	public function actionDefectClosedReports(){
		if(!Yii::$app->user->can('Report.DefectClosedReport')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$start_date= !empty($_REQUEST['start_date'])?strtotime($_REQUEST['start_date']):'';
		$date=!empty($_REQUEST['date'])?$_REQUEST['date']:'this_month';
		$end_date=!empty($_REQUEST['end_date'])?strtotime($_REQUEST['end_date'])+(24*60*60):'';
		$weekStartDate = strtotime('last monday');
		$lastMonthFirstDate = strtotime('first day of last month');
		$lastMonthLastDate = strtotime('last day of last month');
		$monthFirstDate = strtotime('first day of this month');
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$curdate=strtotime(date('Y-m-d')."+1 days");
		$filter=array('today','yesterday');
		if(!in_array($date,$filter)){
		
			$sql="SELECT tbl_user.first_name,tbl_user.last_name,count(tbl_defect.id) counts,from_unixtime(tbl_defect.actual_end_datetime, '%Y-%m-%d') actual_end_datetime FROM `tbl_defect`,tbl_user WHERE tbl_defect.user_assigned_id=tbl_user.id and defect_status_id=".DefectStatus::_INPROCESS;
			
			if($date=='last_month'){
				 $sql.=" and  (actual_end_datetime) >='$lastMonthFirstDate' and (actual_end_datetime)<='$lastMonthLastDate' ";	
			}
			if(empty($start_date) && empty($end_date) && $date=='this_month'){
				 $sql.=" and  (actual_end_datetime) >='$monthFirstDate' and (actual_end_datetime)<='$curdate'";	
			}
			if($date=='this_week'){
				 $sql.=" and  (actual_end_datetime) >='$weekStartDate' and (actual_end_datetime)<='$curdate' ";	
			}
			if(!empty($start_date) && !empty($end_date)){
				 $sql.=" and  (actual_end_datetime) >='$start_date' and (actual_end_datetime)<='$end_date' ";	
			}
			$sql .="  GROUP BY tbl_user.first_name,tbl_user.last_name,actual_end_datetime ORDER by (actual_end_datetime)";

			//print_r($sql);exit;
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
		}else{
			date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
			$date_value=$date=='today'?date('Y-m-d'):date('Y-m-d',strtotime("-1 days"));
			
			$sql="SELECT tbl_user.first_name,
						tbl_user.last_name,tbl_user.id uid,
						from_unixtime(tbl_defect.actual_end_datetime,'%H') actual_end_datetime,
						tbl_defect.user_assigned_id,tbl_defect.id tid,count(tbl_defect.id) defectcount FROM `tbl_defect`,tbl_user WHERE tbl_defect.user_assigned_id=tbl_user.id and defect_status_id=".DefectStatus::_INPROCESS."  and  from_unixtime(actual_end_datetime, '%Y-%m-%d') = '$date_value'  GROUP BY from_unixtime(tbl_defect.actual_end_datetime,'%H'),tbl_defect.user_assigned_id ORDER by user_assigned_id,from_unixtime(actual_end_datetime, '%Y-%m-%d')";
//print_r($sql);exit;
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
			}
			return $this->render('defect-closed-reports', [
				'dataProvider' => $dataReader,
			]);
		
	}
	public function getNeedActions($project_id){
		$defectModel = Defect::find()->joinWith('user')->where("project_id=$project_id and defect_status_id in (".DefectStatus::_INPROCESS.", ".DefectStatus::_NEEDSACTION.")")->orderBy('actual_end_datetime')->asArray()->all();
		return $defectModel;
	}
	public function getDoneRecords($project_id){
		$defectModel = Defect::find()->joinWith('user')->where("project_id=$project_id and defect_status_id=".DefectStatus::_COMPLETED." and date(actual_end_datetime) = curdate()")->orderBy('actual_end_datetime')->asArray()->all();
		return $defectModel;
	}
	public function getInprocessDefects($project_id){
		$defectModel = Defect::find()->joinWith('user')->where("project_id=$project_id and defect_status_id=".DefectStatus::_INPROCESS."")->orderBy('actual_end_datetime')->asArray()->all();
		return $defectModel;
	}
	public function getWeakClosedDefects($project_id){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$weekStartDate = date('Y-m-d',strtotime('last monday'));
		$curdate=date('Y-m-d');
		$defectModel = Defect::find()->joinWith('user')->where("project_id=$project_id and defect_status_id=".DefectStatus::_COMPLETED." and date(actual_end_datetime) >='$weekStartDate' and date(actual_end_datetime) <='$curdate'")->orderBy('actual_end_datetime')->asArray()->all();
		
		return $defectModel;
	}
	public function getMonthClosedDefects($project_id){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$monthFirstDate = date('Y-m-d',strtotime('first day of this month'));
		$curdate=date('Y-m-d');
		$defectModel = Defect::find()->joinWith('user')->where("project_id=$project_id and defect_status_id=".DefectStatus::_COMPLETED." and date(actual_end_datetime) >='$monthFirstDate' and date(actual_end_datetime) <='$curdate'")->orderBy('actual_end_datetime')->asArray()->all();
		return $defectModel;
	}
	public function getTotalUserSpentTime($uid,$date){
		
	  $sql="select tbl_time_entry.* from tbl_time_entry,tbl_defect where tbl_defect.user_assigned_id='$uid' and tbl_defect.id=tbl_time_entry.entity_id and (end_time)='$date' and tbl_time_entry.entity_type='defect'";
	 // print_r($sql);
	  $connection = \Yii::$app->db;
	  $command=$connection->createCommand($sql);
	  $dataReader=$command->queryAll();			
		$spend_t=0;
		$dotNumTot=0;
		$solidNumTot=0;
		$secondTot=0;
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		foreach($dataReader as $trow1){
			list($solidNum,$dotNum,$seconds) = explode('.',TimeDiffModel::getTimeDiff(date('Y/m/d H:i:s',($trow1['start_time'])),date('Y/m/d H:i:s',($trow1['end_time']))));
			$solidNumTot+=$solidNum;
			$dotNumTot+=$dotNum;
			
			$secondTot+=$seconds;
		}
		///Seconds
		list($plusNum1)=explode('.',$secondTot/60);
		$seconddotVal=round($secondTot%60);
		$dotNumTot =$dotNumTot+$plusNum1;
		
		
		list($plusNum)=explode('.',$dotNumTot/60);;
		$dotVal=round($dotNumTot%60);
		$solidNum =$solidNumTot+$plusNum;
		$dotVal=strlen($dotVal)==1?"0".$dotVal:$dotVal;
		$solidNum=strlen($solidNum)==1?"0".$solidNum:$solidNum;
		$spend_t=$solidNum.".".$dotVal;
		
		/*$seconds = 0;
		foreach($dataReader as $trow1)
		{
			$seconds += $trow1['end_time'] - $trow1['start_time'];
		}
		
		$zero    = new DateTime("@0");
		$offset  = new DateTime("@$seconds");
		$diff    = $zero->diff($offset);
		$spend_t= sprintf("%d.%d", $diff->days * 24 + $diff->h, $diff->i);*/

		return $spend_t;
  }
	public function actionTimeSpentReport(){
		if(!Yii::$app->user->can('Report.DefectTimeSpentReport')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$start_date=!empty($_REQUEST['start_date'])?strtotime($_REQUEST['start_date']):'';
		$date=!empty($_REQUEST['date'])?$_REQUEST['date']:'this_month';
		$end_date=!empty($_REQUEST['end_date'])?strtotime($_REQUEST['end_date'])+(24*60*60):'';
		$weekStartDate = strtotime('last monday');
		$lastMonthFirstDate = strtotime('first day of last month');
		$lastMonthLastDate = strtotime('last day of last month');
		$monthFirstDate = strtotime('first day of this month');
		$lastWeekStartDate=strtotime(date('Y-m-d',strtotime('last monday'))."- 7 days");
		$lastWeekEndDate=strtotime('last sunday');
		$curdate=strtotime(date('Y-m-d')."+1 days");

		$sql="SELECT tbl_project.id pid,tbl_user.first_name,tbl_defect.user_assigned_id,tbl_project.project_name,tbl_defect.id tid,tbl_defect.defect_id, tbl_defect.defect_name,from_unixtime(tbl_defect.actual_end_datetime) actual_end_datetime ,from_unixtime(tbl_time_entry.start_time) start_time, from_unixtime(tbl_time_entry.end_time) end_time, tbl_time_entry.entity_id, tbl_time_entry.entity_type, tbl_time_entry.user_id, tbl_time_entry.entry_type, tbl_time_entry.modified_by_user_id, tbl_time_entry.notes, tbl_time_entry.approved FROM `tbl_project`,tbl_defect,tbl_time_entry,tbl_user WHERE tbl_user.id=tbl_time_entry.user_id and tbl_project.id=tbl_defect.project_id and tbl_defect.id=tbl_time_entry.entity_id and tbl_time_entry.entity_type='defect' ";
			
			if(!empty($start_date) && !empty($end_date)){
				 $sql.=" and  (end_time) >='$start_date' and (end_time)<='$end_date' ";	
			}else if(!empty($start_date)){
				$sql.=" and  (end_time) >='$start_date' and (end_time)<='$start_date' ";	
			}else{
				if($date=='last_month'){
				 $sql.=" and  (end_time) >='$lastMonthFirstDate' and (end_time)<='$lastMonthLastDate' ";	
				}
				if($date=='this_month'){
					 $sql.=" and  (end_time) >='$monthFirstDate' and (end_time)<='$curdate'";	
				}
				if($date=='this_week'){
					 $sql.=" and  (end_time) >='$weekStartDate' and (end_time)<='$curdate' ";	
				}
				if($date=='last_week'){
					 $sql.=" and  (end_time) >='$lastWeekStartDate' and (end_time)<='$lastWeekEndDate' ";	
				}	
			}
			if($_REQUEST['status']!=''){
				 $sql.=" and  tbl_time_entry.approved ='$_REQUEST[status]'";	
			}
			if($_REQUEST['user_id']!=''){
				 $sql.=" and tbl_time_entry.user_id ='$_REQUEST[user_id]'";	
			}
			if($_REQUEST['project_id']!=''){
				 $sql.=" and  tbl_defect.project_id='$_REQUEST[project_id]' ";	
			}
		$sql.="  ORDER by  (end_time), defect_name";
		//print_r($sql);exit;
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
			$users=array();
			$data=array();
			$userData = UserDetail::find()->asArray()->all();
			$projectData = Project::find()->where("EXISTS(Select *

FROM tbl_project_user  WHERE project_id =tbl_project.id and user_id=".Yii::$app->user->identity->id.")")->asArray()->all();
			foreach($dataReader as $row){
				$users[$row['user_id']]=$row['first_name'];
				//$data[$row['user_assigned_id']][date('Y-m-d',strtotime($row['end_time']))] +=$this->getTotalUserSpentTime($row['user_assigned_id'],strtotime($row['end_time']));
				$time_sec = intval(strtotime($row['end_time'])) - intval(strtotime($row['start_time']));
				$time_hours = $time_sec/3600;
				$data[$row['user_id']][date('Y-m-d',strtotime($row['end_time']))] +=$time_hours;
			}

			//print_r($data);exit;
			return $this->render('time-spent-report', [
				'dataProvider' => $dataReader,
				'users'=>$users,
				'data'=>$data,
				'userData'=>$userData,
				'projectData'=>$projectData
			]);
		return $defectModel;
	}
	public function getTimeSpent($project_id){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$weekStartDate = date('Y-m-d',strtotime('last monday'));
		$curdate=date('Y-m-d');
		$sql="SELECT tbl_project.id pid,tbl_user.first_name,tbl_user.last_name,tbl_defect.user_assigned_id,tbl_project.project_name,tbl_defect.id tid,tbl_defect.defect_id, tbl_defect.defect_name,tbl_defect.actual_end_datetime,tbl_time_entry.* FROM `tbl_project`,tbl_defect,tbl_time_entry,tbl_user WHERE tbl_user.id=tbl_defect.user_assigned_id and tbl_project.id=tbl_defect.project_id and tbl_defect.id=tbl_time_entry.entity_id and tbl_time_entry.entity_type='defect' and  date(end_time) >='$weekStartDate' and date(end_time)<='$curdate'   and  tbl_defect.project_id='$project_id'   ORDER by  date(end_time), defect_name";
		//echo $sql;	
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
		return $dataReader;
	}
	public function actionAutomail(){
		$sql="SELECT tbl_user.first_name,tbl_user.last_name,tbl_project.id,tbl_project.project_name FROM `tbl_project`,tbl_user WHERE tbl_project.project_owner_id=tbl_user.id";
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
			return $this->render('automail', [
				'dataProvider' => $dataReader,
			]);
	}
	public function actionAjaxProjectUsers(){
		$project_id=$_REQUEST['project_id'];
		$user_id=$_REQUEST['user_id'];
		$sql="SELECT * FROM tbl_user WHERE id in(select user_id from tbl_project_user where project_id=$project_id)";
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
		 return $this->renderPartial('ajax-project-users', [
                'dataReader' => $dataReader,
				'user_id'=>$user_id,
            ]);
	}
	public function actionAjaxUserProjects(){
		$project_id=$_REQUEST['project_id'];
		$user_id=$_REQUEST['user_id'];
		$sql="SELECT * FROM tbl_project WHERE id in(select project_id from tbl_project_user where user_id=$user_id)";
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
		 return $this->renderPartial('ajax-user-projects', [
                'dataReader' => $dataReader,
				'user_id'=>$user_id,
				'project_id'=>$project_id,
            ]);
	}
	public function actionMyCalendar(){
		if(!Yii::$app->user->can('Defect.MyCalendar')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		$sql = "SELECT *, (expected_start_datetime)   expected_start_datetime,IF(defect_status_id=".DefectStatus::_COMPLETED.",(actual_end_datetime),(expected_end_datetime))  expected_end_datetime FROM tbl_defect  where user_assigned_id = '".Yii::$app->user->identity->id."' order by id DESC limit 100";
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
			//echo count($dataReader);
			return $this->render('my-calendar', [
				'dataProvider' => $dataReader,
			]);
	}
    /**
     * Deletes an existing Defect model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		if(!Yii::$app->user->can('Defect.Delete')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
	
	public function actionNeedActions(){
		if(!Yii::$app->user->can('Defect.NeedAction')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		$searchModel = new DefectSearch;
        $dataProvider = $searchModel->searchNeedActions(Yii::$app->request->getQueryParams());
        return $this->render('need-actions', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
	}
	public function actionMyDefects(){
		if(!Yii::$app->user->can('Defect.MyDefect')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		if(!empty($_REQUEST['multiple_del'])){
			$rows=$_REQUEST['selection'];
			for($i=0;$i<count($rows);$i++){
				$this->findModel($rows[$i])->delete();
			}
		}
		$searchModel = new DefectSearch;
        $dataProvider = $searchModel->searchMyDefects(Yii::$app->request->getQueryParams());

		return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
	}
	public function actionDefectAllReports(){
			
            return $this->render('defect-all-reports');
		
	}
	public function actionDefectAssignmentReport(){
			if(!Yii::$app->user->can('Report.DefectAssignment ')){
				throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
			}
            return $this->render('defect-assignment-report');
		
	}
    /**
     * Finds the Defect model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Defect the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		if(Yii::$app->params['user_role'] !='admin'){
			$defect_model = Defect::findOne($id);
			if (($model = Defect::find()->where("id=$id and EXISTS(Select *
FROM tbl_project_user  WHERE project_id ='".$defect_model->project_id."' and user_id=".Yii::$app->user->identity->id.")")->one()) !== null) {
				return $model;
			} else {
				throw new NotFoundHttpException('The requested page does not exist.');
			}
		}else{
			if (($model = Defect::findOne($id)) !== null) {
				return $model;
			} else {
				throw new NotFoundHttpException('The requested page does not exist.');
			}
		}
    }
	
}

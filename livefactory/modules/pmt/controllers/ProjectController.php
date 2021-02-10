<?php

namespace livefactory\modules\pmt\controllers;

use Yii;
use yii\helpers\Html;

use livefactory\models\NoteModel;
use livefactory\models\FileModel;
use livefactory\models\AssignmentHistoryModel;
use livefactory\models\HistoryModel;


use livefactory\models\File;
use livefactory\models\Note;
use livefactory\models\History;
use livefactory\models\ProjectUser;
use livefactory\models\ProjectStatus;
use livefactory\models\SendEmail;
use livefactory\models\User as UserDetail;
use livefactory\models\Project;
use livefactory\models\Task;
use livefactory\models\TaskSla;
use livefactory\models\AssignmentHistory;
use livefactory\models\search\Project as ProjectSearch;
use livefactory\models\Defect;
use livefactory\models\DefectSla;
use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use livefactory\models\TimeEntry;
use livefactory\models\GroupChat;
use livefactory\models\search\CommonModel as SessionVerification;
use \DateTime;
use \DateTimeZone;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
{
	
	public $entity_type='project';
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
	public function getTimeDiff($to,$from){
		//echo $to;
		if($to !='-0001/11/30 00:00:00'){
		$datetime1 = new \DateTime($to);
		$datetime2 = new \DateTime($from);
		$interval = $datetime1->diff($datetime2);
		$elapsed = $interval->format('%H.%I');
		if($second)
		$elapsed = $interval->format('%H.%I.%s');
		return $elapsed;
		}else{
			return '00.00';	
		}
	}
	public function taskIdUdate($id){
		$zirolengh=6-intval(strlen($id));
		$stringId ="TASK".str_repeat("0", $zirolengh).$id;
		$taskUpdate= Task::find()->where(['id' => $id])->one();
		$taskUpdate->task_id=$stringId;
		$taskUpdate->added_at=time();
		$taskUpdate->update();	
		return $stringId;
	}
	public function defectIdUpdate($id){
		$zirolengh=6-intval(strlen($id));
		$stringId ="Defect".str_repeat("0", $zirolengh).$id;
		$defectUpdate= Defect::find()->where(['id' => $id])->one();
		$defectUpdate->defect_id=$stringId;
		$defectUpdate->added_at=time();
		$defectUpdate->update();	
		return $stringId;
	}
	public function projectIdUdate($id){
		$zirolengh=6-intval(strlen($id));
		$stringId ="PROJECT".str_repeat("0", $zirolengh).$id;
		$ticketUpdate= Project::find()->where(['id' => $id])->one();
		$ticketUpdate->project_id=$stringId;
		$ticketUpdate->update();
		return 	$stringId;
	}
	public function timeEditApproved($id){
		if(!empty($_REQUEST['approved']) && $_REQUEST['approved']=='Yes'){
			$app='1';
		}else if(!empty($_REQUEST['approved']) && $_REQUEST['approved']=='No'){
			$app='0';
		}else{
			$app='-1';
		}
		$editTime= TimeEntry::find()->where(['id' => $id])->one();
		$editTime->approved=$app;
		$editTime->modified_at=strtotime(date('Y-m-d'));
		$editTime->update();	
	}
    /**
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
		if(!Yii::$app->user->can('Project.Index')){
			throw new NotFoundHttpException('You dont have permissions to view this page.');
		}
        $searchModel = new ProjectSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
		if(!empty($_REQUEST['multiple_del'])){
			if(!Yii::$app->user->can('Project.Delete')){
			throw new NotFoundHttpException('You dont have permissions to view this page.');
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
     * Displays a single Project model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		if(!Yii::$app->user->can('Project.View')){
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
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		if(!Yii::$app->user->can('Project.Create')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		$emailObj = new SendEmail;
        $model = new Project;
		
	
		if($model->load(Yii::$app->request->post()))
		{
	//			echo '<pre>';
	//	print_r($_REQUEST);exit;

			if(isset($_GET['customer_id']))
				$model->customer_id = $_GET['customer_id'];
			if ($model->save()) {
				/// Update Project
				
				$stringId = 'PROJECT'.str_pad($model->id, 9, "0", STR_PAD_LEFT);
				$model->project_id=$stringId;

				$model->added_at=time();

				$expected_start_datetime = new DateTime($_REQUEST['Project']['expected_start_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
				$model->expected_start_datetime = $expected_start_datetime->getTimestamp();

				if($model->expected_end_datetime != '')
				{
					$expected_end_datetime = new DateTime($_REQUEST['Project']['expected_end_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
					$model->expected_end_datetime = $expected_end_datetime->getTimestamp();
				}

				if($model->actual_start_datetime != '')
				{
					$actual_start_datetime = new DateTime($_REQUEST['Project']['actual_start_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
					$model->actual_start_datetime = $actual_start_datetime->getTimestamp();
				}

				if($model->actual_end_datetime != '')
				{
					$actual_end_datetime = new DateTime($_REQUEST['Project']['actual_end_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
					$model->actual_end_datetime = $actual_end_datetime->getTimestamp();
				}

				if(Yii::$app->params['AUTO_PROJECT_ID'] =='Yes'){
					$stringId = 'PROJECT'.str_pad($model->id, 9, "0", STR_PAD_LEFT);
					$model->project_id=$stringId;
				}

				$model->save();
				/*if(!file_exists('../attachments/project'.$model->id)){
					if(!is_dir('../attachments/project'.$_GET['entity_id']))
						mkdir('../attachments/project'.$_GET['entity_id']);
				}*/
				// Add Notes For Task
				if(!empty($_REQUEST['notes'])){
					$nid=NoteModel::noteInsert($model->id,$this->entity_type);
					//Note Email Send
					$emailObj->sendNoteEmailTemplate($this->getUserEmail($model->project_owner_id),$this->getUserFullName($model->project_owner_id),$this->getLoggedUserFullName()." <br/>".$_REQUEST['notes'],'<a href="'.$_SESSION['base_url'].'?r=pmt/project/project-view&id='.$model->id.'">'.$model->project_name.'</a>');
				}
				//Add History For Project
				HistoryModel::historyInsert($this->entity_type,$model->id,'Proyecto creado');
				// add assignment history to project if the project is assigned
				if($model->project_owner_id != '')
				{
					
					$projectUserAdd = new ProjectUser();
					$projectUserAdd->project_id=$model->id;
					$projectUserAdd->user_id=$model->project_owner_id;
					$projectUserAdd->added_at = time();
					$projectUserAdd->save();
					if($model->project_owner_id != Yii::$app->user->identity->id){
						$projectUserAdd1 = new ProjectUser();
						$projectUserAdd1->project_id=$model->id;
						$projectUserAdd1->user_id=Yii::$app->user->identity->id;
						$projectUserAdd1->added_at = time();
						$projectUserAdd1->save();
					}
					AssignmentHistoryModel::assignHistoryInsert($this->entity_type,$model->id,$model->project_owner_id,'Assigned to user');
					
				}
				if(!empty($_GET['customer_id'])){
					 return $this->redirect(['/customer/customer/customer-view', 'id' =>$_GET['customer_id']]);
				}else{
					if(Yii::$app->params['SHOW_ADD_ATTACHMENT_PAGE_NEW_PROJECT'] =='Yes'){
						//if(Yii::$app->params['PROJECT_FILE_MANAGER'] =='Yes'){
						//	return $this->redirect(['file-manager', 'entity_id' => $model->id]);
						//}else{
							return $this->redirect(['add-attachment', 'entity_id' => $model->id]);
						//}
					}else{
						return $this->redirect(['project-view', 'id' => $model->id]);
					}
					 
				}
			}
			else
			{
            return $this->render('create', [
                'model' => $model,
            ]);
			}
		}else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
	public function actionAddAttachment(){
			//$attachType = array('doc','xls','pdf','images','audio','vedio','zip');
			if(!empty($_FILES['attach'])){
				$file=FileModel::bulkFileInsertProject($_REQUEST['entity_id'],$this->entity_type);
            return $this->redirect(['project-view', 'id' => $_REQUEST['entity_id']]);
			} else {
            return $this->render('add-attachment');
        }
		
	}
	public function actionFileManager(){
            return $this->render('file-manager');
	}
    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		if(!Yii::$app->user->can('Project.Update')){
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
	public function actionProjectView($id){
		if(!(Yii::$app->user->can('Project.Update') || Yii::$app->user->can('Project.View'))) {
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		$emailObj = new SendEmail;
		$model = $this->findModel($id);
		$taskModel = new Task;
		$defectModel = new Defect;
		$attachModelR=$noteModelR='';
		//Get Users 
		$projectUserModel = UserDetail::find()->where("NOT EXISTS(Select *
FROM tbl_project_user  WHERE project_id =".$model->id." and user_id=tbl_user.id) and active=1")->orderBy('first_name')->asArray()->all();

		//Add Task
		if(!empty($_REQUEST['add_task'])){
			if ($taskModel->load(Yii::$app->request->post()) && $taskModel->save()) {
				/// Create Task Id
				$stringId = 'TASK'.str_pad($taskModel->id, 9, "0", STR_PAD_LEFT);
				$taskModel->task_id=$stringId;
				$taskModel->added_at=time();
				
				/*
				$slaObj = TaskSla::find()->where('task_priority_id ='.$taskModel->task_priority_id.' and task_type_id = '.$taskModel->task_type_id)->one();
				$StartSlaSecs=$slaObj->start_sla * 60 * 60;
				$EndSlaSecs=$slaObj->end_sla * 60 * 60;
				$taskModel->expected_start_datetime=$taskModel->added_at+$StartSlaSecs;
				$taskModel->expected_end_datetime=$taskModel->added_at+$EndSlaSecs;
				*/

				if(isset($_REQUEST['Task']['expected_start_datetime']) && !empty($_REQUEST['Task']['expected_start_datetime']))
					{
						$expected_start_datetime = new DateTime($_REQUEST['Task']['expected_start_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$taskModel->expected_start_datetime = $expected_start_datetime->getTimestamp();
					}

					if(isset($_REQUEST['Task']['expected_end_datetime']) && !empty($_REQUEST['Task']['expected_end_datetime']))
					{
						$expected_end_datetime = new DateTime($_REQUEST['Task']['expected_end_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$taskModel->expected_end_datetime = $expected_end_datetime->getTimestamp();
					}

					if(isset($_REQUEST['Task']['actual_start_datetime']) && !empty($_REQUEST['Task']['actual_start_datetime']))
					{
						$actual_start_datetime = new DateTime($_REQUEST['Task']['actual_start_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$taskModel->actual_start_datetime = $actual_start_datetime->getTimestamp();
					}

					if(isset($_REQUEST['Task']['actual_end_datetime']) && !empty($_REQUEST['Task']['actual_end_datetime']))
					{
						$actual_end_datetime = new DateTime($_REQUEST['Task']['actual_end_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$taskModel->actual_end_datetime = $actual_end_datetime->getTimestamp();
					}

				$taskModel->save();

				HistoryModel::historyInsert('task',$taskModel->id,'Tarea creada con Id'.$stringId);
				//Add History For Project
				HistoryModel::historyInsert($this->entity_type,$model->id,'Add Task for Project (  <a href="index.php?r=pmt/project/project-view&id='.$model->id.'">'.$model->project_name.'</a>)');
				return $this->redirect(['project-view', 'id' => $_REQUEST['id']]);
			}
		}
		//Add Defect
		if(!empty($_REQUEST['add_defect'])){
			if ($defectModel->load(Yii::$app->request->post()) && $defectModel->save()) {
				if(Yii::$app->params['AUTO_PROJECT_ID'] =='Yes' && $model->project_id == ''){
					$pstringId=$this->projectIdUdate($model->id);
				}
				/// Create Task Id
				$stringId = 'DEFECT'.str_pad($defectModel->id, 9, "0", STR_PAD_LEFT);
				$defectModel->defect_id=$stringId;
				$defectModel->added_at=time();
				
				/*
				$slaObj = DefectSla::find()->where('defect_priority_id ='.$defectModel->defect_priority_id.' and defect_type_id = '.$defectModel->defect_type_id)->one();
				$StartSlaSecs=$slaObj->start_sla * 60 * 60;
				$EndSlaSecs=$slaObj->end_sla * 60 * 60;
				$defectModel->expected_start_datetime=$defectModel->added_at+$StartSlaSecs;
				$defectModel->expected_end_datetime=$defectModel->added_at+$EndSlaSecs;
				*/

				
				if(isset($_REQUEST['Defect']['expected_start_datetime']) && !empty($_REQUEST['Defect']['expected_start_datetime']))
					{
						$expected_start_datetime = new DateTime($_REQUEST['Defect']['expected_start_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$defectModel->expected_start_datetime = $expected_start_datetime->getTimestamp();
					}

					if(isset($_REQUEST['Defect']['expected_end_datetime']) && !empty($_REQUEST['Defect']['expected_end_datetime']))
					{
						$expected_end_datetime = new DateTime($_REQUEST['Defect']['expected_end_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$defectModel->expected_end_datetime = $expected_end_datetime->getTimestamp();
					}

					if(isset($_REQUEST['Defect']['actual_start_datetime']) && !empty($_REQUEST['Defect']['actual_start_datetime']))
					{
						$actual_start_datetime = new DateTime($_REQUEST['Defect']['actual_start_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$defectModel->actual_start_datetime = $actual_start_datetime->getTimestamp();
					}

					if(isset($_REQUEST['Defect']['actual_end_datetime']) && !empty($_REQUEST['Defect']['actual_end_datetime']))
					{
						$actual_end_datetime = new DateTime($_REQUEST['Defect']['actual_end_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$defectModel->actual_end_datetime = $actual_end_datetime->getTimestamp();
					}


				$defectModel->save();

				HistoryModel::historyInsert('defect',$defectModel->id,'Defect Created with Id  <a href="index.php?r=pmt/defect/defect-view&id='.$defectModel->id.'">'.$stringId.'</a>');
				//Add History For Project
				HistoryModel::historyInsert($this->entity_type,$model->id,'Add Defect for Project (  <a href="index.php?r=pmt/project/project-view&id='.$model->id.'">'.$model->project_name.'</a>)');
				return $this->redirect(['project-view', 'id' => $_REQUEST['id']]);
			}
		}
	
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$model->last_updated_by_user_id = Yii::$app->user->identity->id;
			
		

			$expected_start_datetime = new DateTime($_REQUEST['Project']['expected_start_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
				$model->expected_start_datetime = $expected_start_datetime->getTimestamp();

				if($model->expected_end_datetime != '')
				{
					$expected_end_datetime = new DateTime($_REQUEST['Project']['expected_end_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
					$model->expected_end_datetime = $expected_end_datetime->getTimestamp();
				}

				if($model->actual_start_datetime != '')
				{
					$actual_start_datetime = new DateTime($_REQUEST['Project']['actual_start_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
					$model->actual_start_datetime = $actual_start_datetime->getTimestamp();
				}

				if($model->actual_end_datetime != '')
				{
					$actual_end_datetime = new DateTime($_REQUEST['Project']['actual_end_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
					$model->actual_end_datetime = $actual_end_datetime->getTimestamp();
				}
			
			if($model->project_status_id == ProjectStatus::_INPROCESS)	//In Process
			{
				if($model->actual_start_datetime == '') //Request parameter is not set as field is disabled
					$model->actual_start_datetime = time();
				
				$model->actual_end_datetime = '';
			}
			else if($model->project_status_id == ProjectStatus::_COMPLETED) //Completed
			{
				if($model->actual_end_datetime == '')  //Request parameter is not set as field is disabled
					$model->actual_end_datetime = time();

				$model->project_progress = 100;
			}

			$model->updated_at = time();
			$model->update();
			//Add History
			HistoryModel::historyInsert($this->entity_type,$model->id,'Project is updated - (<a href="index.php?r=pmt/project/project-view&id='.$model->id.'">'.$model->project_name.'</a>)');
			// Add AssignmentHistory
			if($model->project_owner_id != $_REQUEST['old_owner']){ 
				AssignmentHistoryModel::assignHistoryChange($this->entity_type,$model->id,$model->project_owner_id,$_REQUEST['old_owner'],'Changed Project Owner  - (<a href="index.php?r=pmt/project/project-view&id='.$model->id.'">'.$model->project_name.'</a>)',$model->added_at);
			}
            //return $this->redirect(['index']);
			return $this->redirect(['project-view', 'id' => $_REQUEST['id']]);
        } else {
			// Add user for project
			if(!empty($_REQUEST['p_users'])){
				$p_users = $_REQUEST['p_users'];
				for($i=0;$i<count($p_users);$i++){
					$projectUserAdd = new ProjectUser();
					$projectUserAdd->project_id=$_REQUEST['id'];
					$projectUserAdd->user_id=$p_users[$i];
					$projectUserAdd->added_at=strtotime(date('Y-m-d H:i:s'));
					$projectUserAdd->save();
				}
				//Add History
			HistoryModel::historyInsert($this->entity_type,$model->id,'Add Users For Project - (<a href="index.php?r=pmt/project/project-view&id='.$model->id.'">'.$model->project_name.'</a>)');
				return $this->redirect(['project-view', 'id' => $_REQUEST['id']]);	
			}
			// Send Attachment File to Task Assigned User
			if(!empty($_REQUEST['send_attachment_file'])){
				//Send an Email	
				SendEmail::sendLiveEmail($_REQUEST['uemail'],$_REQUEST['email_body'], $_REQUEST['cc'], $_REQUEST['subject']);
				
					return $this->redirect(['project-view', 'id' => $_REQUEST['id']]);
			}
			// Delete Project Attachment
			if(!empty($_REQUEST['attachment_del_id'])){
				$Attachmodel = File::findOne($_REQUEST['attachment_del_id']);
				if (!is_null($Attachmodel)) {
					$Attachmodel->delete();
				}
			//Add History
			HistoryModel::historyInsert($this->entity_type,$model->id,'Deleted Attachment from Project  - (<a href="index.php?r=pmt/project/project-view&id='.$model->id.'">'.$model->project_name.'</a>)');
					return $this->redirect(['project-view', 'id' => $_REQUEST['id']]);
			}
			// Delete Task 
			if(!empty($_REQUEST['task_del'])){
				$taskDel = Task::findOne($_REQUEST['task_del']);
				if (!is_null($taskDel)) {
					$taskDel->delete();
				}

			
			//Add History
			HistoryModel::historyInsert($this->entity_type,$model->id,'Deleted Task from Project - (<a href="index.php?r=pmt/project/project-view&id='.$model->id.'">'.$model->project_name.'</a>)');
					return $this->redirect(['project-view', 'id' => $_REQUEST['id']]);
			}

			// Delete Defect 
			if(!empty($_REQUEST['defect_del'])){
				$defectDel = Defect::findOne($_REQUEST['defect_del']);
				if (!is_null($defectDel)) {
					$defectDel->delete();
				}
			
			//Add History
			HistoryModel::historyInsert($this->entity_type,$model->id,'Deleted Defect from Project - (<a href="index.php?r=pmt/project/project-view&id='.$model->id.'">'.$model->project_name.'</a>)');
					return $this->redirect(['project-view', 'id' => $_REQUEST['id']]);
			}

			// Delete Project User  
			if(!empty($_REQUEST['udel'])){
				$ProjectUser = ProjectUser::findOne($_REQUEST['udel']);
				if (!is_null($ProjectUser)) {
					$ProjectUser->delete();
				}
				////	$ProjectUser = ProjectUser::findOne($_REQUEST['udel'])->delete();
			//Add History
			HistoryModel::historyInsert($this->entity_type,$model->id,'Project User Deleted from Project - (<a href="index.php?r=pmt/project/project-view&id='.$model->id.'">'.$model->project_name.'</a>)');
					return $this->redirect(['project-view', 'id' => $_REQUEST['id']]);
			}
			// Delete Project Notes
			if(!empty($_REQUEST['note_del_id'])){
					$NoteDel = Note::findOne($_REQUEST['note_del_id']);
					if (!is_null($NoteDel)) {
						$NoteDel->delete();
					}
			//Add History
			HistoryModel::historyInsert($this->entity_type,$model->id,'Deleted Note from Project - (<a href="index.php?r=pmt/project/project-view&id='.$model->id.'">'.$model->project_name.'</a>)');
					return $this->redirect(['project-view', 'id' => $_REQUEST['id']]);
			}
			// Add Attachment for Project
			if(!empty($_REQUEST['add_attach'])){
				$aid=FileModel::fileInsert($_REQUEST['entity_id'],$this->entity_type);
				if($aid > 0)
				{
					$link="<a href='".str_replace('web/index.php','',$_SESSION['base_url'])."attachments/".$aid.strrchr($_FILES['attach']['name'], ".")."'>".$_FILES['attach']['name']."</a>";
					// Send Email
					$emailObj->sendAddAttachmentEmailTemplate($this->getUserEmail($model->project_owner_id),$this->getUserFullName($model->project_owner_id),$link,'<a href="'.$_SESSION['base_url'].'?r=pmt/project/project-view&id='.$model->id.'">'.$model->project_name.'</a>');
					//SendEmail::sendLiveEmail($this->getUserEmail($model->project_owner_id),$link, false,$this->getLoggedUserFullName());
					//Add History
					HistoryModel::historyInsert($this->entity_type,$model->id,'Added Attachment into Project - (<a href="index.php?r=pmt/project/project-view&id='.$model->id.'">'.$model->project_name.'</a>)');
						return $this->redirect(['project-view', 'id' => $_REQUEST['id']]);
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

					return $this->redirect(['project-view', 'id' => $_REQUEST['id'], 'err_msg' => $msg]);
				}
			}
			// Project Attachment get
			if(!empty($_REQUEST['attach_update'])){
				$attachModelR=File::findOne($_REQUEST['attach_update']);
			}
			// Project Notes get
			if(!empty($_REQUEST['note_id'])){
				$noteModelR=Note::findOne($_REQUEST['note_id']);
			}
			// Task Attachment Update
			if(!empty($_REQUEST['edit_attach'])){
				$file=FileModel::fileEditProject();
					if($_FILES['attach']['name']){
						$aid=$_REQUEST['att_id'];
						$link="<a href='".str_replace('web/index.php','',$_SESSION['base_url'])."attachments/".$aid.strrchr($_FILES['attach']['name'], ".")."'>".$_FILES['attach']['name']."</a>";
			//Add History
			HistoryModel::historyInsert($this->entity_type,$model->id,'Updated Attachment in Project - (<a href="index.php?r=pmt/project/project-view&id='.$model->id.'">'.$model->project_name.'</a>)');
						//Send an Email
						//SendEmail::sendLiveEmail($this->getUserEmail($model->project_owner_id),$link, false,$this->getLoggedUserFullName());
						$emailObj->sendUpdateAttachmentEmailTemplate($this->getUserEmail($model->project_owner_id),$this->getUserFullName($model->project_owner_id),$link,'<a href="'.$_SESSION['base_url'].'?r=pmt/project/project-view&id='.$model->id.'">'.$model->project_name.'</a>');
					}
					return $this->redirect(['project-view', 'id' => $_REQUEST['id']]);
			}
			
			// Add Notes
			if(!empty($_REQUEST['add_note_model'])){
				$nid = NoteModel::noteInsert($_REQUEST['id'],$this->entity_type);
				if($nid){
					setcookie('inserted_notes'.$_REQUEST['id'],true,time()+7200);
				}
				$link="<a href='".$_SESSION['base_url']."?r=pmt%2Fproject%2Fproject-view&id=".$model->id."'>".$model->project_name."</a>";
				//Send an Email
				$emailObj->sendNoteEmailTemplate($this->getUserEmail($model->project_owner_id),$this->getUserFullName($model->project_owner_id),$this->getLoggedUserFullName()." <br>".$_REQUEST['notes'],$link);
				//SendEmail::sendLiveEmail($this->getUserEmail($model->project_owner_id),"Dear ".$this->getUserFullName($model->project_owner_id)." <br/>A new note added to the project $link by ".$this->getLoggedUserFullName()." <br/>".$_REQUEST['notes']."<br/> Thanks", false,"Notes added to the project (".$model->project_name.")");
				//Add History
			HistoryModel::historyInsert($this->entity_type,$model->id,'Added Note into  Project - (<a href="index.php?r=pmt/project/project-view&id='.$model->id.'">'.$model->project_name.'</a>)');
				return $this->redirect(['project-view', 'id' => $_REQUEST['id']]);
			}
			
			// Update Notes
			if(!empty($_REQUEST['edit_note_model'])){
				$nid = NoteModel::noteEdit();
				//Send an Email
				$emailObj->sendNoteUpdateEmailTemplate($this->getUserEmail($model->project_owner_id),$this->getUserFullName($model->project_owner_id),$this->getLoggedUserFullName()." <br>".$_REQUEST['notes'],'<a href="'.$_SESSION['base_url'].'?r=pmt/project/project-view&id='.$model->id.'">'.$model->project_name.'</a>');
				//SendEmail::sendLiveEmail($this->getUserEmail($model->project_owner_id)," Notes Update by ".$this->getLoggedUserFullName()." ".$_REQUEST['notes'], false,'Notes Update');
				//Add History
			HistoryModel::historyInsert($this->entity_type,$model->id,'Updated Note in Project - (<a href="index.php?r=pmt/project/project-view&id='.$model->id.'">'.$model->project_name.'</a>)');
				return $this->redirect(['project-view', 'id' => $_REQUEST['id']]);
			}
			// Edit Task taskTimeEditApproved
			if(!empty($_REQUEST['appid'])){
				$this->timeEditApproved($_REQUEST['appid']);
				return $this->redirect(['project-view', 'id' => $_REQUEST['id']]);
			}
            return $this->render('project-view', [
                'model' => $model,
				'attachModel'=>$attachModelR,
				'noteModel'=>$noteModelR,
				'taskModel' =>$taskModel,
				'defectModel' =>$defectModel,
				'projectUserModel'=>$projectUserModel,
            ]);
        }
	}
    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		if(!Yii::$app->user->can('Project.Delete')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
	public function actionGroupChat($id)
    {
		$project = $this->findModel($id);
		if(is_null($project)){
			throw new NotFoundHttpException('The requested page does not exist.');
		}
		$project_users = ProjectUser::find()->where("project_id=$id")->asArray()->all();
		$uids  = array();
		$users = array();
		if(count($project_users)>0){
			foreach($project_users as $project_user){
				$uids[$project_user['user_id']] = $project_user['user_id'];	
			}
			unset($uids[Yii::$app->user->identity->id]);
			if(count($uids) > 0){
				$ids = implode(',',array_keys($uids));
				$users = UserDetail::find()->where("id IN($ids)")->asArray()->all();
			}
		}
        return $this->render('group-chat', [
                'users' => $users,
				'project'=>$project
	]);
    }
	public function actionInsertChat($entity_type,$entity_id,$message){
		if(!empty($message)){
			$obj = new GroupChat;
			$obj->user_id=Yii::$app->user->identity->id;
			$obj->message=$message;
			$obj->sent=time();
			$obj->recd=1;
			$obj->entity_id=$entity_id;
			$obj->entity_type=$entity_type;
			$obj->save();
		}	
	}
	public function actionAjexGetChat($entity_type,$entity_id){
		$sql="select tbl_user.first_name,tbl_user.last_name,tbl_group_chat.* from  tbl_group_chat,tbl_user  where tbl_user.id = tbl_group_chat.user_id and  tbl_group_chat.entity_type='$entity_type' and tbl_group_chat.entity_id='$entity_id' and date_add(from_unixtime(tbl_group_chat.sent), INTERVAL 7 HOUR) > now()"; 

		$connection = \Yii::$app->db;

		$command=$connection->createCommand($sql);

		$chats=$command->queryAll();/*
		$chats = GroupChat::find()->asArray()->where("entity_type='$entity_type' and entity_id='$entity_id' and date_add(sent, INTERVAL 23 HOUR) > now()")->all();*/
		$box='';
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		if(count($chats)>0){
			foreach($chats as $chat){
				$csent = date('Y-m-d H:i:s', $chat['sent']);
		$box.='<div class="chat-message">

                                        <img class="message-avatar" src="../users/'.$chat['user_id'].'.png" alt=""  onerror="this.onerror=null;this.src=\'../users/noicon.jpg\'">

                                        <div class="message">

                                            <a class="message-author" href="#">'.$chat['first_name']." ".$chat['last_name'].'</a>

											<span class="message-date"> '.$csent.' </span>

                                            <span class="message-content">'.$chat['message'].'</span>

                                        </div>

                                    </div>';
				}
			}
			echo $box.",".count($chats);
	}
    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		if(Yii::$app->params['user_role'] !='admin'){
			if(Yii::$app->user->identity->userType->type=="Customer")
			{
				if (($model = Project::find()->where("id=$id and customer_id=".Yii::$app->user->identity->entity_id)->one()) !== null) {
				return $model;
				} else {
					throw new NotFoundHttpException('The requested page does not exist.');
				}
			}
			else
			{
				if (($model = Project::find()->where("id=$id and EXISTS(Select *
	FROM tbl_project_user  WHERE project_id =$id and user_id=".Yii::$app->user->identity->id.")")->one()) !== null) {
					return $model;
				} else {
					throw new NotFoundHttpException('The requested page does not exist.');
				}
			}
		}else{
			if (($model = Project::findOne($id)) !== null) {
				return $model;
			} else {
				throw new NotFoundHttpException('The requested page does not exist.');
			}
		}
    }
}

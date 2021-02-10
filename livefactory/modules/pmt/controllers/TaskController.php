<?php

namespace livefactory\modules\pmt\controllers;

use Yii;
use yii\helpers\Html;

use livefactory\models\NoteModel;
use livefactory\models\FileModel;
use livefactory\models\AssignmentHistoryModel;
use livefactory\models\HistoryModel;
use livefactory\models\TimeDiffModel;

use livefactory\models\Task;
use livefactory\models\File;
use livefactory\models\Note;
use livefactory\models\History;
use livefactory\models\SendEmail;
use livefactory\models\ProjectUser;
use livefactory\models\TaskPriority;
use livefactory\models\Project;
use livefactory\models\TaskTime;
use livefactory\models\TimeEntry;
use livefactory\models\TaskStatus;
use livefactory\models\TaskSla;
use livefactory\models\User as UserDetail;
use livefactory\models\AssignmentHistory;
use livefactory\models\search\Task as TaskSearch;
use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use livefactory\models\search\CommonModel as SessionVerification;
use yii\web\User;
use livefactory\models\TimesheetModel;
use \DateTime;
use \DateTimeZone;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
{
	public $entity_type='task';
	
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
	public static  function getProjectOwnerEmail($id){
		$projectModel = Project::findOne($id);
		$projectModelOwner = UserDetail::findOne($projectModel->project_owner_id);
		return $projectModelOwner->email;
	}
	public static function getProjectUsersEmail($id){
		//$id=43;
		$projectUsersModel = ProjectUser::find()->where("project_id=$id")->asArray()->all();
		foreach($projectUsersModel as $user){
			$projectUser = UserDetail::findOne($user['user_id']);
			if($projectUser->email)
			$email[]=$projectUser->email;
		}
		return $email;
	}
	public static function getProjectOwnerId($id){
		$projectModel = Project::findOne($id);
		
		return $projectModel->project_owner_id;
	}
	public static function getUserFullName($id){
		$user = UserDetail::findOne($id);
		
		return $user->first_name." ".$user->last_name;	
	}
	
	public static function getProjectOwnerFullName($id){
		$projectUsersModel = Project::find()->where("id=$id")->asArray()->all();
		//var_dump($projectUsersModel);
		//die();
		foreach($projectUsersModel as $user){
			$user = UserDetail::findOne($user['project_owner_id']);
			// if($projectUser->email)
			// $email[]=$projectUser->email;
		}
		
		return $user->first_name." ".$user->last_name;	
	}
	
	public static function getTaskStuts($id){
		$status = TaskStatus::findOne($id);
		
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
	public static function getLoggedUserRole(){
		if(Yii::$app->user->identity->id){
		return '';//$dataReader[0]['label'];
		}
	}

	public function updateTaskSpendTime($id){
		$taskModel = TimeEntry::find()->where("entity_id=$id and entity_type='".$this->entity_type."'")->asArray()->all();
					
		$spend_t=0;
		$dotNumTot=0;
		$solidNumTot=0;
		$secondTot=0;
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
		
		
		
		list($plusNum)=explode('.',$dotNumTot/60);
		$dotVal=round($dotNumTot%60);
		$solidNum =$solidNumTot+$plusNum;
		$dotVal=strlen($dotVal)==1?"0".$dotVal:$dotVal;
		$solidNum=strlen($solidNum)==1?"0".$solidNum:$solidNum;
		$spend_t=$solidNum.".".$dotVal;
		// Update Task Spend Time 
		$editTask= Task::findOne($id);//->where(['id' =>$id])->one();
		$editTask->time_spent=$spend_t;
		//$editTask->modified_at=strtotime(date('Y-m-d H:i:s'));
		//$editTask->updated_at=strtotime(date('Y-m-d H:i:s'));
		$editTask->updated_at=time();
		$editTask->update();	
	}
	public function taskIdUdate($id){
		$zirolengh=6-intval(strlen($id));
		$stringId ="TASK".str_repeat("0", $zirolengh).$id;
		$taskUpdate= Task::find()->where(['id' => $id])->one();
		$taskUpdate->task_id=$stringId;
		$taskUpdate->added_at=strtotime(date('Y-m-d H:i:s'));	
		$taskUpdate->update();
		return 	$stringId;
	}
	public function getTotalNeedAction(){
		if(Yii::$app->params['user_role'] !='admin'){
			return Task::find()->where("task_status_id in (".TaskStatus::_NEEDSACTION.", ".TaskStatus::_INPROCESS.")")->count();
		}else{
			return Task::find()->where("task_status_id in (".TaskStatus::_NEEDSACTION.", ".TaskStatus::_INPROCESS.") and EXISTS(Select *
FROM tbl_project_user  WHERE project_id ='".$task_model->project_id."' and user_id=".Yii::$app->user->identity->id.")")->count();
		}
	}
    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionIndex()
    {
		if(!Yii::$app->user->can('Task.Index')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
        $searchModel = new TaskSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
		if(!empty($_REQUEST['multiple_del'])){
			if(!Yii::$app->user->can('Task.Delete')){
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

	public function actionEstimation()
    {
		if(!Yii::$app->user->can('Task.Index')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
        $searchModel = new TaskSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
		if(!empty($_REQUEST['multiple_del'])){
			if(!Yii::$app->user->can('Task.Delete')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
			$rows=$_REQUEST['selection'];
			for($i=0;$i<count($rows);$i++){
				$this->findModel($rows[$i])->delete();
			}
		}
        return $this->render('estimation', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

	public function actionAllocation()
    {
		if(!Yii::$app->user->can('Task.Index')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
        $searchModel = new TaskSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
		if(!empty($_REQUEST['multiple_del'])){
			if(!Yii::$app->user->can('Task.Delete')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
			$rows=$_REQUEST['selection'];
			for($i=0;$i<count($rows);$i++){
				$this->findModel($rows[$i])->delete();
			}
		}
        return $this->render('allocation', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		if(!Yii::$app->user->can('Task.View')){
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
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		if(Yii::$app->user->can('Task.Create')){
		$emailObj = new SendEmail;
        $model = new Task;
		$user_id=1;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			/// Create Task Id
			$stringId = 'TASK'.str_pad($model->id, 9, "0", STR_PAD_LEFT);
			$model->task_id=$stringId;
			$model->added_at=time();

			/*
			$slaObj = TaskSla::find()->where('task_priority_id ='.$model->task_priority_id.' and task_type_id = '.$model->task_type_id)->one();
			$StartSlaSecs=$slaObj->start_sla * 60 * 60;
			$EndSlaSecs=$slaObj->end_sla * 60 * 60;
			$model->expected_start_datetime=$model->added_at+$StartSlaSecs;
			$model->expected_end_datetime=$model->added_at+$EndSlaSecs;
			*/
			
					if(isset($_REQUEST['Task']['expected_start_datetime']) && !empty($_REQUEST['Task']['expected_start_datetime']))
					{
						$expected_start_datetime = new DateTime($_REQUEST['Task']['expected_start_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->expected_start_datetime = $expected_start_datetime->getTimestamp();
					}

					if(isset($_REQUEST['Task']['expected_end_datetime']) && !empty($_REQUEST['Task']['expected_end_datetime']))
					{
						$expected_end_datetime = new DateTime($_REQUEST['Task']['expected_end_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->expected_end_datetime = $expected_end_datetime->getTimestamp();
					}

					if(isset($_REQUEST['Task']['actual_start_datetime']) && !empty($_REQUEST['Task']['actual_start_datetime']))
					{
						$actual_start_datetime = new DateTime($_REQUEST['Task']['actual_start_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->actual_start_datetime = $actual_start_datetime->getTimestamp();
					}

					if(isset($_REQUEST['Task']['actual_end_datetime']) && !empty($_REQUEST['Task']['actual_end_datetime']))
					{
						$actual_end_datetime = new DateTime($_REQUEST['Task']['actual_end_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->actual_end_datetime = $actual_end_datetime->getTimestamp();
					}
			$model->save();
			
			// Add Notes For Task
			if(!empty($_REQUEST['notes'])){
				$nid = NoteModel::noteInsert($model->id,$this->entity_type);
				// Notes Email Send ($email,$user_name,$user_by,$url)
				$emailObj->sendNoteEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),$this->getLoggedUserFullName(),'<a href="'.$_SESSION['base_url'].'?r=pmt/task/task-view&id='.$model->id.'">'.$stringId.'</a>');
				
				
				/*SendEmail::sendLiveEmail($this->getUserEmail($model->user_assigned_id),"A new note added by ".$this->getLoggedUserFullName()." ".$_REQUEST['notes'], false,'New Note Added');*/
			}
			
			
			//Add History For Task
			HistoryModel::historyInsert($this->entity_type,$model->id,'Tarea creada con Id '.$stringId);
			
			// Entry on Assigned History
			if($model->user_assigned_id){
				AssignmentHistoryModel::assignHistoryInsert($this->entity_type,$model->id,$model->user_assigned_id,'Task Assigned to user');
				//Email Send  sendTaskEmailTemplate($email,$user_name,$url,$desc)
				$emailObj->sendTaskEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),'<a href="'.$_SESSION['base_url'].'?r=pmt/task/task-view&id='.$model->id.'">'.$stringId.'</a>',$model->task_description);
				
				
				//SendEmail::sendLiveEmail($this->getUserEmail($model->user_assigned_id),"New Task Assigned you <br/><b>Task Name:</b>".$model->task_name."<br/><b>Description:</b>".$model->task_description, false,"New Task Assigned");
			}
			if(Yii::$app->params['SHOW_ADD_ATTACHMENT_PAGE_NEW_TASK'] =='Yes'){
				return $this->redirect(['add-attachment', 'entity_id' => $model->id,'user_id'=>$model->user_assigned_id]);
			}else{
				return $this->redirect(['task-view', 'id' => $model->id]);
			}
            
        } 
		
		/*   this code was rendering different create page from manage task add task button
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
		}else{
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
    }
	
	public function actionAddAttachment(){
			//$attachType = array('doc','xls','pdf','images','audio','vedio','zip');
			if(!empty($_FILES['attach'])){
				$file=FileModel::bulkFileInsert($_REQUEST['entity_id'],$this->entity_type);
			
            return $this->redirect(['task-view', 'id' => $_REQUEST['entity_id']]);
			} else {
            return $this->render('add-attachment');
        }
		
	}
    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		if(!Yii::$app->user->can('Task.Update')){
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
	public function actionTaskView($id)
    {
		
		if(!Yii::$app->user->can('Task.View')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		$user_id=1;
        $model = $this->findModel($id);
		$sub_task = new Task;
		$emailObj = new SendEmail;
		$attachModelR='';
		$noteModelR='';
		//Add Sub Task
		if(!empty($_REQUEST['taskid'])){
			if ($sub_task->load(Yii::$app->request->post()))
			{
				$sub_task->id = null;
				$sub_task->task_id = '';
				$sub_task->task_status_id = TaskStatus::_NEEDSACTION; //Needs Action
				$sub_task->isNewRecord = true;
				if($sub_task->save())
				{
					$stringId = 'TASK'.str_pad($sub_task->id, 9, "0", STR_PAD_LEFT);
					$sub_task->task_id=$stringId;
					$sub_task->added_at=time();
					$sub_task->save();

					$subTaskUpdate = Task::findOne($sub_task->id);
					
					/*
					$slaObj = TaskSla::find()->where('task_priority_id ='.$subTaskUpdate->task_priority_id.' and task_type_id = '.$subTaskUpdate->task_type_id)->one();
					$StartSlaSecs=$slaObj->start_sla * 60 * 60;
					$EndSlaSecs=$slaObj->end_sla * 60 * 60;
					$subTaskUpdate->expected_start_datetime=$subTaskUpdate->added_at+$StartSlaSecs;
					$subTaskUpdate->expected_end_datetime=$subTaskUpdate->added_at+$EndSlaSecs;
					*/

					if(isset($_REQUEST['Task']['expected_start_datetime']) && !empty($_REQUEST['Task']['expected_start_datetime']))
					{
						$expected_start_datetime = new DateTime($_REQUEST['Task']['expected_start_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->expected_start_datetime = $expected_start_datetime->getTimestamp();
					}

					if(isset($_REQUEST['Task']['expected_end_datetime']) && !empty($_REQUEST['Task']['expected_end_datetime']))
					{
						$expected_end_datetime = new DateTime($_REQUEST['Task']['expected_end_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->expected_end_datetime = $expected_end_datetime->getTimestamp();
					}

					if(isset($_REQUEST['Task']['actual_start_datetime']) && !empty($_REQUEST['Task']['actual_start_datetime']))
					{
						$actual_start_datetime = new DateTime($_REQUEST['Task']['actual_start_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->actual_start_datetime = $actual_start_datetime->getTimestamp();
					}

					if(isset($_REQUEST['Task']['actual_end_datetime']) && !empty($_REQUEST['Task']['actual_end_datetime']))
					{
						$actual_end_datetime = new DateTime($_REQUEST['Task']['actual_end_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->actual_end_datetime = $actual_end_datetime->getTimestamp();
					}
					$subTaskUpdate->update();
					//Add History For Task
					HistoryModel::historyInsert($this->entity_type,$subTaskUpdate->id,'Tarea creada con Id '.$stringId);
					//Add History For Task
					HistoryModel::historyInsert($this->entity_type,$model->id,'Sub Tarea creada con Id '.$stringId);
					return $this->redirect(['task-view', 'id' => $_REQUEST['taskid']]);
				}
			}
			return $this->redirect(['task-view', 'id' => $_REQUEST['taskid']]);
		}
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
		
			$model->last_updated_by_user_id = Yii::$app->user->identity->id;
			
					if(isset($_REQUEST['Task']['expected_start_datetime']) && !empty($_REQUEST['Task']['expected_start_datetime']))
					{
						$expected_start_datetime = new DateTime($_REQUEST['Task']['expected_start_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->expected_start_datetime = $expected_start_datetime->getTimestamp();
					}

					if(isset($_REQUEST['Task']['expected_end_datetime']) && !empty($_REQUEST['Task']['expected_end_datetime']))
					{
						$expected_end_datetime = new DateTime($_REQUEST['Task']['expected_end_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->expected_end_datetime = $expected_end_datetime->getTimestamp();
					}

					if(isset($_REQUEST['Task']['actual_start_datetime']) && !empty($_REQUEST['Task']['actual_start_datetime']))
					{
						$actual_start_datetime = new DateTime($_REQUEST['Task']['actual_start_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->actual_start_datetime = $actual_start_datetime->getTimestamp();
					}

					if(isset($_REQUEST['Task']['actual_end_datetime']) && !empty($_REQUEST['Task']['actual_end_datetime']))
					{
						$actual_end_datetime = new DateTime($_REQUEST['Task']['actual_end_datetime'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->actual_end_datetime = $actual_end_datetime->getTimestamp();
					}
			
			/*
			$slaObj = TaskSla::find()->where('task_priority_id ='.$model->task_priority_id.' and task_type_id = '.$model->task_type_id)->one();
			$StartSlaSecs=$slaObj->start_sla * 60 * 60;
			$EndSlaSecs=$slaObj->end_sla * 60 * 60;
			$model->expected_start_datetime=$model->added_at+$StartSlaSecs;
			$model->expected_end_datetime=$model->added_at+$EndSlaSecs;
			*/
			
			if($model->task_status_id == TaskStatus::_INPROCESS)
			{
				if(empty($model->actual_start_datetime))
					$model->actual_start_datetime = time();

				$model->actual_end_datetime = '';
			}
			if($model->task_status_id == TaskStatus::_COMPLETED)
			{
				
				if(empty($model->actual_end_datetime))
					$model->actual_end_datetime = time();

				$model->task_progress = 100;
			}

			$model->updated_at = time();
			$model->update();
			$old_owner=!empty($_REQUEST['old_owner'])?$_REQUEST['old_owner']:'';
			$old_task_priority_id=!empty($_REQUEST['old_task_priority_id'])?$_REQUEST['old_task_priority_id']:'';
			$old_task_status_id=!empty($_REQUEST['old_task_status_id'])?$_REQUEST['old_task_status_id']:'';
			/// Assigned user Changed
			if($model->user_assigned_id != $old_owner){				
				//Send an Email
				$emailObj->sendTaskChangedUserEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),$this->getLoggedUserFullName(),'<a href="'.$_SESSION['base_url'].'?r=pmt/task/task-view&id='.$model->id.'">'.$model->task_id.'</a>',$this->getTaskStuts($model->task_status_id));
				//SendEmail::sendLiveEmail($this->getUserEmail($model->user_assigned_id),"Dear ".$this->getUserFullName($model->user_assigned_id).", <br/>Task (".$model->task_name.") Status (".$this->getTaskStuts($model->task_status_id).") is assigned to you by  ".$this->getUserFullName($old_owner)."<br/> Thanks", false,"Task (".$model->task_name.") is assigned to you.");
				// Add AssignmentHistory
				AssignmentHistoryModel::assignHistoryChange($this->entity_type,$model->id,$model->user_assigned_id,$old_owner,"Changed Assigned User",$model->added_at);
				
				//Add History
				HistoryModel::historyInsert($this->entity_type,$model->id,"History is updated as Task is assigned to ".$this->getUserFullName($model->user_assigned_id)." by ".$this->getUserFullName($old_owner).' into ( <a href="'.$_SESSION['base_url'].'?r=pmt/task/task-view&id='.$model->id.'">'.$model->task_id.'</a>)');
			}
			
			/// Task Priority Changed
			if($model->task_priority_id != $old_task_priority_id){
				$taskPriorityModel = TaskPriority::findOne($model->task_priority_id);
				$taskPriorityModelOld = TaskPriority::findOne($old_task_priority_id);
				//Add History
				HistoryModel::historyInsert($this->entity_type,$model->id,"Task priority changed from ".$taskPriorityModelOld->label." to ".$taskPriorityModel->label." by ".$this->getLoggedUserFullName().'  into ( <a href="index.php?r=pmt/task/task-view&id='.$model->id.'">'.$model->task_id.'</a>)');
				
				//Send an Email
				$emailObj->sendTaskChangedPriorityEmailTemplate($this->getUserEmail($model->user_assigned_id).",".$this->getProjectOwnerEmail($model->project_id),$this->getUserFullName($model->user_assigned_id),$this->getLoggedUserFullName(),'<a href="'.$_SESSION['base_url'].'?r=pmt/task/task-view&id='.$model->id.'">'.$model->task_id.'</a>',$taskPriorityModelOld->label,$taskPriorityModel->label);
				//SendEmail::sendLiveEmail($this->getUserEmail(Yii::$app->user->identity->id).",,".$this->getProjectOwnerEmail($model->project_id),"Task(".$model->task_name.") </b> Priority changed from ".$taskPriorityModelOld->label." to ".$taskPriorityModel->label." by ".$this->getLoggedUserFullName(), false,"Task Priority Chenged");
			}
			
			/// Task Status Changed
			if($model->task_status_id != $old_task_status_id){
				$taskStatusModel = TaskStatus::findOne($model->task_status_id);
				$taskStatusModelOld = TaskStatus::findOne($old_task_status_id);
				//Add History
				HistoryModel::historyInsert($this->entity_type,$model->id,"Task status changed from ".$taskStatusModelOld->label." to ".$taskStatusModel->label." by ".$this->getLoggedUserFullName().'  into ( <a href="index.php?r=pmt/task/task-view&id='.$model->id.'">'.$model->task_id.'</a>)');
				//Send an Email
				$emailObj->sendTaskChangedStatusEmailTemplate($this->getUserEmail($model->user_assigned_id).",".$this->getProjectOwnerEmail($model->project_id),$this->getUserFullName($model->user_assigned_id),$this->getLoggedUserFullName(),'<a href="'.$_SESSION['base_url'].'?r=pmt/task/task-view&id='.$model->id.'">'.$model->task_id.'</a>',$taskStatusModelOld->label,$taskStatusModel->label);
				//SendEmail::sendLiveEmail($this->getUserEmail(Yii::$app->user->identity->id).",,".$this->getProjectOwnerEmail($model->project_id),"Task(".$model->task_name.") </b> Status changed from ".$taskStatusModelOld->label." to ".$taskStatusModel->label." by ".$this->getLoggedUserFullName(), false,"Task Status Chenged");
			}
			//Add History
			HistoryModel::historyInsert($this->entity_type,$model->id,'Tarea actualizada');
           // return $this->redirect(['index']);
			return $this->redirect(['task-view', 'id' => $_REQUEST['id']]);
        } else {
			$timeEntryModel = new TimeEntry();
			// Send Attachment File to Task Assigned User
			if(!empty($_REQUEST['send_attachment_file'])){
				//Send an Email
				SendEmail::sendLiveEmail($_REQUEST['uemail'],$_REQUEST['email_body'], $_REQUEST['cc'], $_REQUEST['subject']);
					return $this->redirect(['task-view', 'id' => $_REQUEST['id']]);
			}
			// Delete Task Attachment
			if(!empty($_REQUEST['attachment_del_id'])){
					$Attachmodel = File::findOne($_REQUEST['attachment_del_id']);
					if (!is_null($Attachmodel)) {
						$Attachmodel->delete();
					}
					//Add History For Task
					HistoryModel::historyInsert($this->entity_type,$model->id,$model->task_id.' Adjunto de tarea eliminado de '.$model->task_id);
					return $this->redirect(['task-view', 'id' => $_REQUEST['id']]);
			}
			// Delete Task 
			if(!empty($_REQUEST['task_del'])){
					$taskDel = Task::findOne($_REQUEST['task_del']);
					if (!is_null($taskDel)) {
						$taskDel->delete();
					}
					//Add History For Task
					HistoryModel::historyInsert($this->entity_type,$model->id,$model->task_id.' Tarea Subtarea eliminada de'.$model->task_id);
					return $this->redirect(['task-view', 'id' => $_REQUEST['id']]);
			}
			// Delete Task Notes
			if(!empty($_REQUEST['note_del_id'])){
					$NoteDel = Note::findOne($_REQUEST['note_del_id']);
					if (!is_null($NoteDel)) {
						$NoteDel->delete();
					}
					//Add History For Task
					HistoryModel::historyInsert($this->entity_type,$model->id,$model->task_id.'Nota de Tarea eliminada de '.$model->task_id);
					return $this->redirect(['task-view', 'id' => $_REQUEST['id']]);
			}
			
			
			// Add Attachment for Task
			if(!empty($_REQUEST['add_attach'])){
				$aid=FileModel::fileInsert($_REQUEST['entity_id'],$this->entity_type);
				if($aid > 0)
				{
					$link="<a href='".str_replace('web/index.php','',$_SESSION['base_url'])."attachments/".$aid.strrchr($_FILES['attach']['name'], ".")."'>".$_FILES['attach']['name']."</a>";
					$emailObj->sendAddAttachmentEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),$link,'<a href="'.$_SESSION['base_url'].'?r=pmt/task/task-view&id='.$model->id.'">'.$model->task_id.'</a>');
					//SendEmail::sendLiveEmail($this->getUserEmail($model->user_assigned_id),$link, false,$this->getLoggedUserFullName());
					//Add History For Task
					HistoryModel::historyInsert($this->entity_type,$model->id,'Archivo adjunto agregado en'.$model->task_id);
						return $this->redirect(['task-view', 'id' => $_REQUEST['id']]);
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

					return $this->redirect(['task-view', 'id' => $_REQUEST['id'], 'err_msg' => $msg]);
				}
			}
			
			
			
			// Task Attachment get
			if(!empty($_REQUEST['attach_update'])){
				$attachModelR=File::findOne($_REQUEST['attach_update']);
				//Add History For Task
				HistoryModel::historyInsert($this->entity_type,$model->id,$model->task_id.' Adjunto de tarea actualizado');
			}
			// Task Notes get
			if(!empty($_REQUEST['note_id'])){
				$noteModelR=Note::findOne($_REQUEST['note_id']);
			}
			// Task Attachment Update
			if(!empty($_REQUEST['edit_attach'])){
					$file=FileModel::fileEdit();
					if($_FILES['attach']['name']){
						$aid=$_REQUEST['att_id'];
						$link="<a href='".str_replace('web/index.php','',$_SESSION['base_url'])."attachments/".$aid.strrchr($_FILES['attach']['name'], ".")."'>".$_FILES['attach']['name']."</a>";
						//Send an Email
						$emailObj->sendUpdateAttachmentEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),$link,'<a href="'.$_SESSION['base_url'].'?r=pmt/task/task-view&id='.$model->id.'">'.$model->task_id.'</a>');
						//SendEmail::sendLiveEmail($this->getUserEmail($model->user_assigned_id),$link, false,$this->getLoggedUserFullName());
					}
				//Add History For Task
				HistoryModel::historyInsert($this->entity_type,$model->id,'Adjunto actualizado');
					return $this->redirect(['task-view', 'id' => $_REQUEST['id']]);
			}
			
			// Add Notes
			if(!empty($_REQUEST['add_note_model'])){
				$nid = NoteModel::noteInsert($_REQUEST['id'],$this->entity_type);
				if($nid){
					setcookie('inserted_notes'.$_REQUEST['id'],true,time()+7200);
				}
				//Send an Email
				$emailObj->sendNoteEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),$this->getLoggedUserFullName()." <br>".$_REQUEST['notes'],'<a href="'.$_SESSION['base_url'].'?r=pmt/task/task-view&id='.$model->id.'">'.$model->task_id.'</a>');
			//	SendEmail::sendLiveEmail($this->getUserEmail($model->user_assigned_id),"A new note added by ".$this->getLoggedUserFullName()." ".$_REQUEST['notes'], false,'New Note Added');
				//Add History For Task
				HistoryModel::historyInsert($this->entity_type,$model->id,'Se agregó una nota ');
				return $this->redirect(['task-view', 'id' => $_REQUEST['id']]);
			}
			
			// Update Notes
			if(!empty($_REQUEST['edit_note_model'])){
				$nid = NoteModel::noteEdit();
				//Send an Email
				$emailObj->sendNoteUpdateEmailTemplate($this->getUserEmail($model->user_assigned_id),$this->getUserFullName($model->user_assigned_id),$this->getLoggedUserFullName()." <br>".$_REQUEST['notes'],'<a href="'.$_SESSION['base_url'].'?r=pmt/task/task-view&id='.$model->id.'">'.$model->task_id.'</a>');
				//Add History For Task
				HistoryModel::historyInsert($this->entity_type,$model->id,'Nota actualizada');
				return $this->redirect(['task-view', 'id' => $_REQUEST['id']]);
			}
			/*===================================================Timing==============================================================*/
			// Add Task Timing
			if(!empty($_REQUEST['timing_add'])){
				TimesheetModel::timeEntryAdd($_REQUEST['notes'],'MANUAL',$_REQUEST['start_time'],$_REQUEST['end_time'],$this->entity_type);
				// Update Task Spend Time
				$this->updateTaskSpendTime($_REQUEST['id']);
				//Send an Email
				$emailObj->sendTimesheetEntryTemplate($this->getProjectOwnerEmail($model->project_id),$this->getProjectOwnerFullName($model->project_id),$this->getLoggedUserFullName()." <br>".$_REQUEST['timesheet'],'<a href="'.$_SESSION['base_url'].'?r=pmt/task/task-view&id='.$model->id.'">'.$model->task_id.'</a>');
			
				//Add History For Task
				HistoryModel::historyInsert($this->entity_type,$model->id,'Se agregó tiempo de trabajo de tarea');
				return $this->redirect(['task-view', 'id' => $_REQUEST['id']]);
			}
			if(!empty($_REQUEST['time_entry_id'])){
				$timeEntryModel = TimeEntry::findOne($_REQUEST['time_entry_id']);
			}
			// Edit Task Timing
			if(!empty($_REQUEST['timing_edit'])){
				TimesheetModel::timeEntryEdit($_REQUEST['notes'],$_REQUEST['time_entry_id'],$_REQUEST['start_time'],$_REQUEST['end_time']);
				// Update Task Spend Time
				$this->updateTaskSpendTime($_REQUEST['id']);
				//Add History For Task
				HistoryModel::historyInsert($this->entity_type,$model->id,'Tarea de trabajo de tarea actualizada');
				return $this->redirect(['task-view', 'id' => $_REQUEST['id']]);
			}
			// Edit Task taskTimeEditApproved
			if(!empty($_REQUEST['appid'])){
				TimesheetModel::timeEntryApproved($_REQUEST['appid']);
				return $this->redirect(['task-view', 'id' => $_REQUEST['id']]);
			}
			// Delete Task Time
			if(!empty($_REQUEST['time_del_id'])){
					$Attachmodel = TimeEntry::findOne($_REQUEST['time_del_id']);
					if (!is_null($Attachmodel)) {
						$Attachmodel->delete();
					}
					// Update Task Spend Time
					$this->updateTaskSpendTime($_REQUEST['id']);
					//Add History For Task
				HistoryModel::historyInsert($this->entity_type,$model->id,'Tiempo de trabajo de tarea eliminado');
					return $this->redirect(['task-view', 'id' => $_REQUEST['id']]);
			}
			if(!empty($_REQUEST['starttime'])){
				
				// setcookie('start_time',date('Y-m-d H:i:s'),time()+7200);
				// setcookie('taskStartedId',$_REQUEST['id'],time()+7200);

				date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
				//setcookie('defect_start_time',date('Y-m-d H:i:s'));
				setcookie('start_time',time());
				setcookie('taskStartedId',$_REQUEST['id']);
				
				 return $this->redirect(['task-view', 'id' => $_REQUEST['id']]);
			}
			if(!empty($_REQUEST['timenotes']) && !empty($_COOKIE['start_time'])){
				date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
				$start_time=date('Y-m-d H:i:s', $_COOKIE['start_time']);
				$end_time = date('Y-m-d H:i:s');
				// Add Task Time
				TimesheetModel::timeEntryAdd($_REQUEST['timenotes'],'AUTO',$start_time,$end_time,$this->entity_type);
				//setcookie('start_time',date('Y-m-d H:i:s'),time()-3600);
				setcookie('taskStartedId',$_REQUEST['id'],time()-3600);
				// Update Task Spend Time
				$this->updateTaskSpendTime($_REQUEST['id']);
				//Add History For Task
				HistoryModel::historyInsert($this->entity_type,$model->id,'Nota agregada');
				return $this->redirect(['task-view', 'id' => $_REQUEST['id']]);
			}
            return $this->render('task-view', [
                'model' => $model,
				'attachModel'=>$attachModelR,
				'noteModel'=>$noteModelR,
				'sub_task'=>$sub_task,
				'timeEntryModel'=>$timeEntryModel,
            ]);
        }
    }
	public function actionAjaxTask($id){
		$start_time=!empty($_REQUEST['start_time'])?$_REQUEST['start_time']:'';
		$eid=!empty($_REQUEST['eid'])?$_REQUEST['eid']:'';
		if($eid){
			$taskModel = TimeEntry::find()->where("id != $eid and entity_id=$id and entity_type='task' and start_time<='$start_time' and end_time >='$start_time'")->one();
		}else{
		$taskModel = TimeEntry::find()->where("entity_id=$id and entity_type='task' and start_time<='$start_time' and end_time >='$start_time'")->one();	
		}
		 return $this->renderPartial('ajax-task', [
                'name' => $taskModel->id,
            ]);
	}
	
	public function actionAjaxTaskTimeDateValidation(){
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
		 return $this->renderPartial('ajax-task-time-date-validation', [
                'error' =>$error,
            ]);
	}
	public function getSpentTime($enity_id,$user_id){
		$taskModel = TimeEntry::find()->where("entity_id=$enity_id and entity_type='".$this->entity_type."'")->asArray()->all();
					
		$spend_t=0;
		$dotNumTot=0;
		$solidNumTot=0;
		foreach($taskModel as $trow1){
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
	public function actionTaskClosedReports(){
		if(!Yii::$app->user->can('Report.TaskClosedReport')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$start_date= !empty($_REQUEST['start_date'])?strtotime($_REQUEST['start_date']):'';
		$date=!empty($_REQUEST['date'])?$_REQUEST['date']:'this_month';
		$end_date=!empty($_REQUEST['end_date'])?strtotime($_REQUEST['end_date'])+(24*60*60):'';
		$weekStartDate = strtotime('last monday');
		$lastMonthFirstDate = strtotime('first day of last month');
		$lastMonthLastDate =strtotime('last day of last month');
		$monthFirstDate = strtotime('first day of this month');
		$curdate=strtotime(date('Y-m-d')."+1 days");
		
		//print_r($start_date);
		//print_r($end_date);
		//exit;

		$filter=array('today','yesterday');
		if(!in_array($date,$filter)){
		
			$sql="SELECT tbl_user.first_name,tbl_user.last_name,count(tbl_task.id) counts,from_unixtime(tbl_task.actual_end_datetime, '%Y-%m-%d') actual_end_datetime FROM `tbl_task`,tbl_user WHERE tbl_task.user_assigned_id=tbl_user.id and task_status_id=".TaskStatus::_COMPLETED;
			
			if($date=='last_month'){
				 $sql.=" and  (actual_end_datetime) >='$lastMonthFirstDate' and (actual_end_datetime)<='$lastMonthLastDate' ";	
			}
			if(empty($start_date) && empty($end_date) && $date=='this_month'){
				 $sql.=" and  (actual_end_datetime) >='$monthFirstDate' and (actual_end_datetime)<='$curdate'";	
			}
			if($date=='this_week'){
				 $sql.=" and  (actual_end_datetime) >='$weekStartDate' and (actual_end_datetime) <='$curdate' ";	
			}
			if(!empty($start_date) && !empty($end_date)){
				 $sql.=" and  (actual_end_datetime) >='$start_date' and (actual_end_datetime) <='$end_date' ";	
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
						from_unixtime(tbl_task.actual_end_datetime,'%H') actual_end_datetime,
						tbl_task.user_assigned_id,tbl_task.id tid,count(tbl_task.id) taskcount FROM `tbl_task`,tbl_user WHERE tbl_task.user_assigned_id=tbl_user.id and task_status_id=".TaskStatus::_COMPLETED."  and  from_unixtime(actual_end_datetime, '%Y-%m-%d') = '$date_value'  GROUP BY from_unixtime(tbl_task.actual_end_datetime,'%H'),tbl_task.user_assigned_id ORDER by user_assigned_id,from_unixtime(actual_end_datetime, '%Y-%m-%d')";
			//print_r($sql);exit;
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
			}
			return $this->render('task-closed-reports', [
				'dataProvider' => $dataReader,
			]);
		
	}
	public function getNeedActions($project_id){
		
		$taskModel = Task::find()->joinWith('user')->where("project_id=$project_id and task_status_id in (".TaskStatus::_NEEDSACTION.", ".TaskStatus::_INPROCESS.")")->orderBy('actual_end_datetime')->asArray()->all();
		return $taskModel;
	}
	public function getDoneRecords($project_id){
		$date = date('Y-m-d');
		///var_dump("project_id=$project_id and task_status_id=2 and date(actual_end_datetime) = '$date'");
		$taskModel = Task::find()->joinWith('user')->where("project_id=$project_id and task_status_id=".TaskStatus::_COMPLETED." and date(actual_end_datetime) = '$date'")->orderBy('actual_end_datetime')->asArray()->all();
		return $taskModel;
	}
	public function getInprocessTasks($project_id){
		
		$taskModel = Task::find()->joinWith('user')->where("project_id=$project_id and task_status_id=".TaskStatus::_INPROCESS."")->orderBy('actual_end_datetime')->asArray()->all();
		return $taskModel;
	}
	public function getWeakClosedTasks($project_id){
		
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$weekStartDate = date('Y-m-d',strtotime('last monday'));
		$curdate=date('Y-m-d');
		$taskModel = Task::find()->joinWith('user')->where("project_id=$project_id and task_status_id=".TaskStatus::_COMPLETED." and date(actual_end_datetime) >='$weekStartDate' and date(actual_end_datetime) <='$curdate'")->orderBy('actual_end_datetime')->asArray()->all();
		
		return $taskModel;
	}
	public function getMonthClosedTasks($project_id){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$monthFirstDate = date('Y-m-d',strtotime('first day of this month'));
		$curdate=date('Y-m-d');
		$taskModel = Task::find()->joinWith('user')->where("project_id=$project_id and task_status_id=".TaskStatus::_COMPLETED." and date(actual_end_datetime) >='$monthFirstDate' and date(actual_end_datetime) <='$curdate'")->orderBy('actual_end_datetime')->asArray()->all();
		return $taskModel;
	}
	public function getTotalUserSpentTime($uid,$date){
	
	  $sql="select tbl_time_entry.* from tbl_time_entry,tbl_task where tbl_task.user_assigned_id='$uid' and tbl_task.id=tbl_time_entry.entity_id and (end_time)='$date' and tbl_time_entry.entity_type='task'";
	  $connection = \Yii::$app->db;
	  $command=$connection->createCommand($sql);
	  $dataReader=$command->queryAll();
		$spend_t=0;
		$dotNumTot=0;
		$solidNumTot=0;
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		foreach($dataReader as $trow1){
			list($solidNum,$dotNum) = explode('.',TimeDiffModel::getTimeDiff(date('Y/m/d H:i:s',($trow1['start_time'])),date('Y/m/d H:i:s',($trow1['end_time']))));
			$solidNumTot+=$solidNum;
			$dotNumTot+=$dotNum;
		}
		list($plusNum)=explode('.',$dotNumTot/60);
		$dotVal=round($dotNumTot%60);
		$solidNum =$solidNumTot+$plusNum;
		$dotVal=strlen($dotVal)==1?"0".$dotVal:$dotVal;
		$solidNum=strlen($solidNum)==1?"0".$solidNum:$solidNum;
		$spend_t=$solidNum.".".$dotVal;
		return $spend_t;
  }
	public function actionTimeSpentReport(){
		if(!Yii::$app->user->can('Report.TaskTimeSpentReport')){
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

		$sql="SELECT tbl_project.id pid,tbl_user.first_name,tbl_task.user_assigned_id,tbl_project.project_name,tbl_task.id tid,tbl_task.task_id, tbl_task.task_name,from_unixtime(tbl_task.actual_end_datetime) actual_end_datetime ,from_unixtime(tbl_time_entry.start_time) start_time, from_unixtime(tbl_time_entry.end_time) end_time, tbl_time_entry.entity_id, tbl_time_entry.entity_type, tbl_time_entry.user_id, tbl_time_entry.entry_type, tbl_time_entry.modified_by_user_id, tbl_time_entry.notes, tbl_time_entry.approved FROM `tbl_project`,tbl_task,tbl_time_entry,tbl_user WHERE tbl_user.id=tbl_time_entry.user_id and tbl_project.id=tbl_task.project_id and tbl_task.id=tbl_time_entry.entity_id and tbl_time_entry.entity_type='task' ";
			
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
				 $sql.=" and  tbl_task.project_id='$_REQUEST[project_id]' ";	
			}
		$sql.="  ORDER by  (end_time), task_name";
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
		return $taskModel;
	}
	public function getTimeSpent($project){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$lastMonthFirstDate = date('Y-m-d',strtotime('first day of last month'));
		$weekStartDate = date('Y-m-d',strtotime('last monday'));
		$curdate=date('Y-m-d');
		$sql="SELECT tbl_project.id pid,tbl_user.first_name,tbl_task.user_assigned_id,tbl_project.project_name,tbl_task.id tid,tbl_task.task_id, tbl_task.task_name,tbl_task.actual_end_datetime,tbl_time_entry.* FROM `tbl_project`,tbl_task,tbl_time_entry,tbl_user WHERE tbl_user.id=tbl_task.user_assigned_id and tbl_project.id=tbl_task.project_id and tbl_task.id=tbl_time_entry.entity_id and tbl_time_entry.entity_type='task' and  date(end_time) >='$lastMonthFirstDate' and date(end_time)<='$curdate'  and  tbl_task.project_id='$project'   ORDER by  date(end_time), task_name";
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
		if(!Yii::$app->user->can('Task.MyCalendar')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		$sql = "SELECT *, (expected_start_datetime)   expected_start_datetime,IF(task_status_id=".TaskStatus::_COMPLETED.",(actual_end_datetime),(expected_end_datetime))  expected_end_datetime FROM tbl_task  where user_assigned_id = '".Yii::$app->user->identity->id."' order by id DESC limit 100";


			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
			///echo count($dataReader);
			return $this->render('my-calendar', [
				'dataProvider' => $dataReader,
			]);
	}
    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		if(!Yii::$app->user->can('Task.Delete')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
	
	public function actionNeedActions(){
		if(!Yii::$app->user->can('Task.NeedAction')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		$searchModel = new TaskSearch;
        $dataProvider = $searchModel->searchNeedActions(Yii::$app->request->getQueryParams());
        return $this->render('need-actions', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
	}
	public function actionMyTasks(){
		if(!Yii::$app->user->can('Task.MyTask')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		if(!empty($_REQUEST['multiple_del'])){
			$rows=$_REQUEST['selection'];
			for($i=0;$i<count($rows);$i++){
				$this->findModel($rows[$i])->delete();
			}
		}
		$searchModel = new TaskSearch;
        $dataProvider = $searchModel->searchMyTasks(Yii::$app->request->getQueryParams());
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
	}
	public function actionTaskAllReports(){
			if(!Yii::$app->user->can('Report.ProjectAllReports')){
				throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
			}
            return $this->render('task-all-reports');
		
	}
	public function actionTaskAssignmentReport(){
			if(!Yii::$app->user->can('Report.TaskAssignmentReport')){
				throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
			}
            return $this->render('task-assignment-report');
		
	}
    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		if(Yii::$app->params['user_role'] !='admin'){
			$task_model = Task::findOne($id);
			if (($model = Task::find()->where("id=$id and EXISTS(Select *
FROM tbl_project_user  WHERE project_id ='".$task_model->project_id."' and user_id=".Yii::$app->user->identity->id.")")->one()) !== null) {
				return $model;
			} else {
				throw new NotFoundHttpException('The requested page does not exist.');
			}
		}else{
			if (($model = Task::findOne($id)) !== null) {
				return $model;
			} else {
				throw new NotFoundHttpException('The requested page does not exist.');
			}
		}
    }
	
}

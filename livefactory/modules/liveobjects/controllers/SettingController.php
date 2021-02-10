<?php
/*
 *     The contents of this file are subject to the Initial
 *     Developer's Public License Version 1.0 (the "License");
 *     you may not use this file except in compliance with the
 *     License. You may obtain a copy of the License at
 *     http://www.liveobjects.org/livecrm/license.php
 *
 *     Software distributed under the License is distributed on
 *     an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND, either
 *     express or implied.  See the License for the specific
 *     language governing rights and limitations under the License.
 *
 *
 *  The Original Code was created by Mohit Gupta (mohit.gupta@liveobjects.org) for LiveObjects Technologies Pvt. Ltd. (contact@liveobjects.org)
 *
 *  Copyright (c) 2014 - 2015 LiveObjects Technologies Pvt. Ltd.
 *  All Rights Reserved.
 *
 *  This translation and editing was done by Mohit Gupta of LiveObjects
 *
*/
namespace livefactory\modules\liveobjects\controllers;

use Yii;
use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use livefactory\models\ConfigItem;
use livefactory\models\search\ConfigItem as ConfigItemSearch;
use livefactory\models\Address;
use livefactory\models\AddressModel;
use livefactory\models\Company;
use livefactory\models\search\CommonModel as SessionVerification;
use livefactory\models\Glocalization;
use livefactory\models\User;
use livefactory\models\UserType;
use livefactory\models\search\User as UserSearch;
use livefactory\models\SendEmail;
// Rights
use livefactory\models\AuthAssignment;
use livefactory\models\AuthItem;
use livefactory\models\AuthItemChild;
use livefactory\models\search\UserType as UserTypeSearch;
use livefactory\models\Customer;
use livefactory\models\Project as ProjectModel;
use livefactory\models\ImageUpload;
class SettingController extends Controller
{
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
    	if(!isset(Yii::$app->user->identity->id)){
          $this->redirect(array('/site/login'));
		}
		if(!Yii::$app->user->can('Setting.Pages')){
          $this->redirect(array('/site/index'));
		}
	}
    public function actionIndex()
    {	
		
		$companyModel = Company::findOne(Yii::$app->params['company']['id']);
		$addressModel = Address::find()->where("entity_type='user' and entity_id=".$companyModel->id)->one();
		//Theme Setting
		$dataProvider = ConfigItem::find()->where("config_item_value='theme'")->asArray()->all();
		$dataProviderEmail = ConfigItem::find()->where("config_item_value='email_send'")->asArray()->all();
		$dataProviderColor = ConfigItem::find()->where("config_item_value='theme_color'")->asArray()->all();
		
		// Language
		$languages= Glocalization::find()->asArray()->all();
		
		if ($companyModel->load(Yii::$app->request->post()) && $companyModel->save()) {
			AddressModel::addressUpdate($addressModel->id);
			$reload='yes';
			return $this->render('index', [
				'dataProvider' => $dataProvider,
				'dataProviderColor'=>$dataProviderColor,
				
				'dataProviderEmail'=>$dataProviderEmail,
				'companyModel'=>$companyModel,
				'addressModel'=>$addressModel,
				'reload'=>$reload,
				'languages'=>$languages,
			]);
			
		}
		if(!empty($_REQUEST['ids'])){
			$ids=	$_REQUEST['ids'];
			foreach($ids as $id){
				$active=$_REQUEST['active'.$id];
				$updateConfig = ConfigItem::findOne($id);
				$updateConfig->active = $active;
				$updateConfig->save();
			}
			if(!empty($_REQUEST['color'])){
				$updateConfig = ConfigItem::findOne($_REQUEST['color']);
				$updateConfig->active = 1;
				$updateConfig->save();
				$connection = \Yii::$app->db;
				$sql="update tbl_config_item set active='0' where id !=$_REQUEST[color] and config_item_value='theme_color'";
				$connection->createCommand($sql)->execute();
			}
			$reload='yes';
			//$reload='no';
			return $this->render('index', [
				'dataProvider' => $dataProvider,
				'dataProviderColor'=>$dataProviderColor,
				
				'dataProviderEmail'=>$dataProviderEmail,
				'companyModel'=>$companyModel,
				'addressModel'=>$addressModel,
				'reload'=>$reload,
				'languages'=>$languages,
			]);
		}
		//Email Config
		if(!empty($_REQUEST['email_ids'])){
			foreach($dataProviderEmail as $email_row){
				$active=$_REQUEST[$email_row['config_item_name']];
				$updateConfig = ConfigItem::find()->where("config_item_name='".$email_row['config_item_name']."'")->one();
				$updateConfig->active = $active;
				$updateConfig->save();
			}
			$reload='yes';
			return $this->render('index', [
				'dataProvider' => $dataProvider,
				'dataProviderColor'=>$dataProviderColor,
				
				'dataProviderEmail'=>$dataProviderEmail,
				'companyModel'=>$companyModel,
				'addressModel'=>$addressModel,
				'reload'=>$reload,
				'languages'=>$languages,
			]);
		}
		//Logo Setting

		if(isset($_FILES['company_logo']) && !empty($_FILES['company_logo']['name'])){
			
			move_uploaded_file($_FILES['company_logo']['tmp_name'],"../logo/logo.png");
			
			$reload='yes';
			return $this->render('index', [
				'dataProvider' => $dataProvider,
				'dataProviderColor'=>$dataProviderColor,
				
				'dataProviderEmail'=>$dataProviderEmail,
				'companyModel'=>$companyModel,
				'addressModel'=>$addressModel,
				'reload'=>$reload,
				'languages'=>$languages,
			]);
		}

		if(isset($_FILES['company_seal']) && !empty($_FILES['company_seal']['name'])){
			
			move_uploaded_file($_FILES['company_seal']['tmp_name'],"../logo/seal.png");
			
			$reload='yes';
			return $this->render('index', [
				'dataProvider' => $dataProvider,
				'dataProviderColor'=>$dataProviderColor,
				
				'dataProviderEmail'=>$dataProviderEmail,
				'companyModel'=>$companyModel,
				'addressModel'=>$addressModel,
				'reload'=>$reload,
				'languages'=>$languages,
			]);
		}

		if(!empty($_GET['email_send'])){
			$emailObj = new SendEmail;
			$user = User::find()->where("username='admin'")->one();
			$retVal = SendEmail::sendLiveEmail($user->email,"SMTP Testing Email <br/> Thanks", false,"SMTP Testing Email ");
			
			if($retVal == '1' || $retVal == true)
			{
				$ret_msg = 'Test email sent successfully to '.$user->email;
			}
			else
			{
				$ret_msg = $retVal;
			}
			return $this->render('index', [
				'dataProvider' => $dataProvider,
				'dataProviderColor'=>$dataProviderColor,
				'reload' => 'yes',
				'dataProviderEmail'=>$dataProviderEmail,
				'companyModel'=>$companyModel,
				'addressModel'=>$addressModel,
				'languages'=>$languages,
				'sent_email'=>$ret_msg,
			]);
		}
		return $this->render('index', [
				'dataProvider' => $dataProvider,
				'dataProviderColor'=>$dataProviderColor,
				'reload' => 'yes',
				'dataProviderEmail'=>$dataProviderEmail,
				'companyModel'=>$companyModel,
				'addressModel'=>$addressModel,
				'languages'=>$languages,
			]);
    }

	public function actionUpdate()
    {
		
		if(isset($_POST))
		{
			if(isset($_POST['chat'])){
				$model = ConfigItem::findByName('CHAT');
				$model->active = $_POST['chat'];
				$model->save();
				unset($_POST['chat']);
			}
			foreach($_POST as $key => $value)
			{
				$model = ConfigItem::findByName($key);
				if($model != null)
				{
					if ($model->config_item_name == 'SMTP_PASSWORD')
					{
						if ($value == "**********")
						{
							$cMdl = ConfigItem::findByName('SMTP_PASSWORD');
							$model->config_item_value = $cMdl->config_item_value;
						}
						else
							$model->config_item_value = base64_encode($value);
					}
					else if ($model->config_item_name == 'INCOMING_EMAIL_SERVER_PASSWORD')
					{
						if ($value == "**********")
						{
							$cMdl = ConfigItem::findByName('INCOMING_EMAIL_SERVER_PASSWORD');
							$model->config_item_value = $cMdl->config_item_value;
						}
						else
							$model->config_item_value = base64_encode($value);
					}
					else if ($model->config_item_name == 'SESSION_TIMEOUT_PERIOD')
					{
						$model->config_item_value = strval($value*24*60*60);
					}
					/*
					else if($value == Yii::t('app', 'Yes'))
					{
						$model->config_item_value = 'Yes';
					}
					else if($value == Yii::t('app', 'No'))
					{
						$model->config_item_value = 'No';
					}
					*/
					else
						$model->config_item_value = $value;
					$model->save();
				}
				
			}
		}
		return $this->redirect(['index']);
    }

	public function actionTruncateTable(){
		$table = !empty($_POST['table_name'])?$_POST['table_name']:'';
		$all_table_empty = !empty($_POST['all_table_empty'])?$_POST['all_table_empty']:'';
		$allTables=array('tbl_address','tbl_contact','tbl_country','tbl_customer','tbl_file','tbl_history','tbl_note','tbl_product','tbl_session_details','tbl_user');
		
$tables=array('tbl_city','tbl_company','tbl_country','tbl_config_item','tbl_currency','tbl_customer_type','tbl_defect_priority','tbl_defect_status','tbl_email_template','tbl_employee_type','tbl_glocalization','tbl_lead_priority','tbl_lead_source','tbl_lead_status','tbl_lic_mdl','tbl_lic_prd','tbl_lic_prd_mdl','tbl_product_category','tbl_project_type','tbl_region','tbl_state','tbl_status','tbl_task_priority','tbl_task_status','tbl_user','tbl_user_type');
		$error='';
		$msg='';
		if(!empty($table)){
			if(!in_array($table,$tables)){
				if(in_array($table,$allTables)){
					$msg=$table." has been empty";
					$connection = \Yii::$app->db;
					$command=$connection->createCommand()->truncateTable($table)->execute();
					$model = ConfigItem::findByName('TRUNCATE_TABLE');
					if($model != null){
	
						$model->config_item_value = "temp".strtotime(date('Y-m-d'));
	
						$model->save();
	
					}
				}else{
					$error='Table Not Exists!';
				}
			}else{
				$error='You Can not Empty '.$table;	
			}
			
		}
		if(!empty($all_table_empty) && isset($_POST['all_table_empty'])){
			foreach($allTables as $value){
				$connection = \Yii::$app->db;
				$command=$connection->createCommand()->truncateTable($value)->execute();
			}
			$model = ConfigItem::findByName('TRUNCATE_TABLE');
				if($model != null){
					$model->config_item_value = "temp".strtotime(date('Y-m-d'));
					$model->save();
				}
			$msg="All Table have been empty";
		}
		if(!empty($_GET['send_varified_code'])){
			$emailObj = new SendEmail;
			$code = Yii::$app->security->generateRandomString (8);
			$model = ConfigItem::findByName('TRUNCATE_TABLE');
	
					if($model != null)
	
					{
	
						$model->config_item_value = $code;
	
						$model->save();
	
					}
			$user = User::find()->where("username='admin'")->one();
			SendEmail::sendLiveEmail($user->email,$code."<br/> Thanks", false,"Truncate Verification Code");	
			return $this->redirect(['truncate-table','sent'=>'yes']);
		}
		return $this->render('truncate-table', [
				'allTables' => $allTables,
				'error' => $error,
				'msg' => $msg,
            ]);
	}
	public function actionSendVerifiedCode(){
		$emailObj = new SendEmail;
		$code = Yii::$app->security->generateRandomString (8);
		$model = ConfigItem::findByName('TRUNCATE_TABLE');
				if($model != null)
				{
					$model->config_item_value = $code;
					$model->save();
				}
		$user = User::find()->where("username='admin'")->one();
		//SendEmail::sendLiveEmail($user->email,$code."<br/> Thanks", false,"Truncate Verification Code");
		return $this->redirect(['truncate-table']);
	}
	
	
	public function actionRights(){
		$searchModel = new UserSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
		$connection = \Yii::$app->db;
		$sql="select tbl_user.*,tbl_user_type.type from tbl_user, tbl_user_type where  tbl_user_type.id=tbl_user.user_type_id";
		$command=$connection->createCommand($sql);
		$users=$command->queryAll();
		///var_dump($users);
		$authItems = AuthItem::find()->asArray()->all();
		$roles = AuthItem::find()->where("type = 2")->andWhere('name NOT IN (\'Customer\', \'Employee\')')->asArray()->all();
		$operations = AuthItem::find()->where("type = 0")->asArray()->all();
		// Remove Assigment User
		if(!empty($_GET['assign_user_remove'])){
			$item_name = urldecode($_GET['assign_user_remove']);
			if (($model = AuthAssignment::find()->where("user_id=$_GET[assign_user_id] and item_name='$item_name'")->one()) !== null) {
				$model->delete();
				 return $this->redirect(['rights', 'assign_user_id' => $_GET[assign_user_id]]);
			} else {
				throw new NotFoundHttpException('The requested page does not exist.');
			}
		}
		// Add Assigment User
		if(!empty($_POST['auth_item'])){
			$model = new AuthAssignment;
			$model->item_name = $_POST['auth_item'];
			$model->user_id =  $_GET[assign_user_id];
			
			$model->save();
			//var_dump($model->errors);
			if(count($model->errors)>0){
				return $this->render('rights', [
					'users' => $users,
					'authItems' => $authItems,
					'operations' => $operations,
					'roles' => $roles,
					'dataProvider' => $dataProvider,
					'assigment_error' => $model->errors,
	
				]);
			}else
				 return $this->redirect(['rights', 'assign_user_id' => $_GET[assign_user_id]]);
		}
		// Remove Parent Child of Role
		if(!empty($_GET['parent']) && !empty($_GET['child']) && !empty($_GET['role_child_del'])){
			$authItemChildObj  = AuthItemChild::find()->where("parent='".urldecode($_GET[parent])."' and child='".urldecode($_GET[child])."'")->one();
			$authItemChildObj->delete();
			//var_dump($authItemChildObj->errors);
		return $this->redirect(['rights','role_id'=>$_GET['role_id']]);
			
		}
		// Remove Parent Child of Opration
		if(!empty($_GET['parent']) && !empty($_GET['child']) && !empty($_GET['operation_child_del'])){
			$authItemChildObj  = AuthItemChild::find()->where("parent='".urldecode($_GET[parent])."' and child='".urldecode($_GET[child])."'")->one();
			
			$authItemChildObj->delete();
			return $this->redirect(['rights','operation_id'=>$_GET['operation_id']]);
			
		}
		// Add Parent Child
		if(!empty($_GET['parent']) && !empty($_GET['child']) && empty($_GET['remove_child']) && empty($_GET['operation_child_del'])){
			$authItemChildObj  = new AuthItemChild;
			
			$authItemChildObj->parent = urldecode($_GET['parent']);
			$authItemChildObj->child = urldecode($_GET['child']);
			$authItemChildObj->save();
			 return $this->redirect(['rights']);
		}
		// Remove Parent Child
		if(!empty($_GET['parent']) && !empty($_GET['child']) && !empty($_GET['remove_child'])){
			$authItemChildObj  = AuthItemChild::find()->where("parent='".urldecode($_GET[parent])."' and child='".urldecode($_GET[child])."'")->one();
			
			$authItemChildObj->delete();
			return $this->redirect(['rights']);
			
		}
		
		// Add Role
		if(!empty($_POST['role_name']) && !empty($_POST['role_description'])){
			$authItemObj = new AuthItem;
			$authItemObj->name = $_POST['role_name'];
			$authItemObj->description = $_POST['role_description'];
			$authItemObj->data = $_POST['role_data'];
			$authItemObj->type = 2;
			$authItemObj->save();
			///var_dump($authItemObj->errors);
			if(count($authItemObj->errors)>0){
				return $this->render('rights', [
					'users' => $users,
					'authItems' => $authItems,
					'operations' => $operations,
					'roles' => $roles,
					'dataProvider' => $dataProvider,
					'role_add_error' => $authItemObj->errors,
	
				]);
			}else
				 return $this->redirect(['rights']);
		}
		// Add Role child
		if(!empty($_POST['role_child_auth_item'])){
			$authItemChildObj  = new AuthItemChild;
			
			$authItemChildObj->parent = urldecode($_GET['role_id']);
			$authItemChildObj->child = urldecode($_POST['role_child_auth_item']);
			$authItemChildObj->save();
			///var_dump($authItemChildObj->errors);
			if(count($authItemChildObj->errors)>0){
				return $this->render('rights', [
					'users' => $users,
					'authItems' => $authItems,
					'operations' => $operations,
					'roles' => $roles,
					'dataProvider' => $dataProvider,
					'roleChild_assigment_error' => $authItemChildObj->errors,
	
				]);
			}else
				 return $this->redirect(['rights','role_id'=>$_GET['role_id']]);
		}
		// Update Role 
		if(!empty($_POST['edit_role_description'])){
			$authItemdObj  = AuthItem::find()->where("name='".$_GET['role_id']."' and type='2'")->one();
			if(!is_null($authItemdObj)){
			$authItemdObj->description = $_POST['edit_role_description'];
			$authItemdObj->save();
			///var_dump($authItemChildObj->errors);
			if(count($authItemdObj->errors)>0){
				return $this->render('rights', [
					'users' => $users,
					'authItems' => $authItems,
					'operations' => $operations,
					'roles' => $roles,
					'dataProvider' => $dataProvider,
					'roleChild_assigment_error' => $authItemdObj->errors,
	
				]);
			}else
				 return $this->redirect(['rights','role_id'=>$_GET['role_id']]);
			}
		}
		// Update Operation
		if(!empty($_POST['edit_operation_description'])){
			$authItemdObj  = AuthItem::find()->where("name='".$_GET['operation_id']."' and type='0'")->one();
			if(!is_null($authItemdObj)){
			$authItemdObj->description = $_POST['edit_operation_description'];
			$authItemdObj->save();
			///var_dump($authItemChildObj->errors);
			if(count($authItemdObj->errors)>0){
				return $this->render('rights', [
					'users' => $users,
					'authItems' => $authItems,
					'operations' => $operations,
					'roles' => $roles,
					'dataProvider' => $dataProvider,
					'operationChild_assigment_error' => $authItemdObj->errors,
	
				]);
			}else
				 return $this->redirect(['rights','operation_id'=>$_GET['operation_id']]);
			}
		}
		// Add operation
		if(!empty($_POST['operation_name']) && !empty($_POST['operation_description'])){
			$authItemObj = new AuthItem;
			$authItemObj->name = $_POST['operation_name'];
			$authItemObj->description = $_POST['operation_description'];
			$authItemObj->data = $_POST['operation_data'];
			$authItemObj->type = 0;
			$authItemObj->save();
			///var_dump($authItemObj->errors);
			if(count($authItemObj->errors)>0){
				return $this->render('rights', [
					'users' => $users,
					'authItems' => $authItems,
					'operations' => $operations,
					'roles' => $roles,
					'dataProvider' => $dataProvider,
					'operation_add_error' => $authItemObj->errors,
	
				]);
			}else
				 return $this->redirect(['rights']);
		}
		// Add operation child
		if(!empty($_POST['operation_child_auth_item'])){
			$authItemChildObj  = new AuthItemChild;
			
			$authItemChildObj->parent = urldecode($_GET['operation_id']);
			$authItemChildObj->child = urldecode($_POST['operation_child_auth_item']);
			$authItemChildObj->save();
			///var_dump($authItemChildObj->errors);
			if(count($authItemChildObj->errors)>0){
				return $this->render('rights', [
					'users' => $users,
					'authItems' => $authItems,
					'operations' => $operations,
					'roles' => $roles,
					'dataProvider' => $dataProvider,
					'operationChild_assigment_error' => $authItemChildObj->errors,
	
				]);
			}else
				 return $this->redirect(['rights','operation_id'=>$_GET['operation_id']]);
		}
		if(!empty($_GET['operation_del'])){
			$authItemObj  = AuthItem::find()->where("name='".urldecode($_GET[operation_del])."'")->one();
			
			$authItemObj->delete();
			return $this->redirect(['rights']);
		}
		if(!empty($_GET['role_del'])){
			$authItemObj  = AuthItem::find()->where("name='".urldecode($_GET[role_del])."'")->one();
			
			$authItemObj->delete();
			return $this->redirect(['rights']);
		}
		return $this->render('rights', [
				'users' => $users,
				'authItems' => $authItems,
				'operations' => $operations,
				'roles' => $roles,
				'dataProvider' => $dataProvider,
            	'searchModel' => $searchModel,
            ]);
			
	}
	public function actionImportData(){
		if(Yii::$app->params['user_role'] !='admin'){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		if (in_array('pmt',Yii::$app->params['modules'])){
		$projects =ProjectModel::find ()->orderBy ( 'project_name' )->asArray ()->all ();
		}
		
		$customers =Customer::find ()->orderBy ( 'customer_name' )->asArray ()->all ();
		
		return $this->render('import-data', [
				'projects' => $projects,
				'customers' => $customers,
		]);
	}
	public function actionMakeUsers(){
		$connection = \Yii::$app->db;
		$sql="select * from tbl_customer where email NOT IN(select email from tbl_user)";
		$command=$connection->createCommand($sql);
		$customers=$command->queryAll();
		return $this->render('make-users', [
				'customers' => $customers
		]);
	}
	public function actionDeleteUsers(){
		$connection = \Yii::$app->db;
		$sql="select * from tbl_customer where email IN(select email from tbl_user)";
		$command=$connection->createCommand($sql);
		$customers=$command->queryAll();
		return $this->render('delete-users', [
				'customers' => $customers
		]);
	}
	public function actionAjaxMakeUser(){
		$id = $_REQUEST['id'];
		$img = new ImageUpload();
		$emailObj = new SendEmail;
		if(!empty($id)){
			$customer = Customer::findOne($id);
				if(User::find()->where("email='".$customer->email."'")->count() > 0){
					 echo 'User can not be Created User Already Exists!';
				}else{
					$userModel = new User;
					$userModel->first_name = $customer->first_name;
					$userModel->last_name = $customer->last_name;
					$userModel->email = $customer->email;
					$userModel->username = $customer->email;
					$userModel->active = 1;
                    $userModel->user_type_id = UserTypeSearch::getCompanyUserType('Customer')->id;
					$userModel->entity_id = $id;
					$userModel->entity_type = 'customer';
					$userModel->added_at = strtotime(date('Y-m-d H:i:s'));
					$new_password = Yii::$app->security->generateRandomString (8);
					$userModel->password_hash=Yii::$app->security->generatePasswordHash ($new_password);
					$userModel->save();
					if(count($userModel->errors) >0){
						var_dump($userModel->errors);
					}else{
						$model = new AuthAssignment;
						$model->item_name = 'Customer';
						$model->user_id = $userModel->id;
						$model->save();
						$img->loadImage('../users/nophoto.jpg')->saveImage("../users/".$userModel->id.".png");
					$img->loadImage('../users/nophoto.jpg')->resize(30, 30)->saveImage("../users/user_".$userModel->id.".png");
					$emailObj->sendNewUserEmailTemplate($userModel->email,$userModel->first_name." ".$userModel->last_name, $userModel->username,$new_password);
					echo "User Created";
					}
				}
		}
	}
	public function actionAjaxDeleteUser($id){
		$customer = Customer::findOne($id);
        if (($model = User::find()->andwhere("email='".$customer->email."' and user_type_id=".UserTypeSearch::getCompanyUserType('Customer')->id)->one()) !== null) {
			$model->delete();
			echo "User Deleted";
		}else{
			echo "User Not Found !";
		}
		
	}
	public function actionRestoreDb(){
		if(!empty($_POST['restore'])){
				$db = file_get_contents('../restore_db/'.$_POST['filename']);
				$connection = \Yii::$app->db;
				$command=$connection->createCommand($db)->execute();
				return $this->redirect(['restore-db','msg'=>'Database Restore Successfully !']);
		}
		
		
		
		return $this->render('restore-db');	
	}
	public function actionBackupDb(){

		if(!empty($_POST['delete_backup_file']) && isset($_POST['delete_backup']) && !empty($_POST['delete_backup']) && $_POST['delete_backup'] != '')
		{
				$db = unlink('../restore_db/'.$_POST['delete_backup']);
				if($db)
				return $this->redirect(['backup-db','msg'=>Yii::t('app', 'Database Backup file Successfully Deleted !')]);
				else
				return $this->redirect(['backup-db','msg'=>Yii::t('app','Operation Failed! Database Backup file Not Deleted !')]);
		}
		
		return $this->render('backup-db');	
	}
	public function actionLicense(){
		return $this->render('license');	
	}
}

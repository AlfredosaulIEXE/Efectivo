<?php

namespace livefactory\modules\user\controllers;

use Yii;
use livefactory\models\User;
use livefactory\models\SessionDetails;
use livefactory\models\ImageUpload;
use livefactory\models\AddressModel;
use livefactory\models\ContactModel;
use livefactory\models\search\User as UserSearch;
use livefactory\models\search\History as HistorySearch;
use livefactory\models\search\SessionDetails as UserSessionSearch;
use livefactory\controllers\Controller;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use livefactory\models\SendEmail;
use livefactory\models\AuthAssignment;
use livefactory\models\search\CommonModel as SessionVerification;
use livefactory\models\search\UserType as UserTypeSearch;
use livefactory\models\Office;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
	public function init(){
		SessionVerification::checkSessionDestroy();
    	if(!isset(Yii::$app->user->identity->id)){
          $this->redirect(array('/site/login'));
		}
		
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

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
		if(!Yii::$app->user->can('User.Index')){
          $this->redirect(array('/site/index'));
		}
        $searchModel = new UserSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
		if(!empty($_REQUEST['multiple_del'])){

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
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->redirect(['update', 'id' => $id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->id]);
        } else {
        return $this->render('view', ['model' => $model]);
}
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $img = new ImageUpload();
        $model = new User;
		$emailObj = new SendEmail;

		// Default user type
        $model->password_hash = 'empty'; // Will be fill
        $model->user_type_id = 1;
        $model->active = 1;
        $office = Office::findOne($_POST['User']['office_id']);
        $_POST['User']['username']= $office->code.$_POST['User']['username'];

       // $model->generateAuthKey();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$model->added_at=time();

			// Password
			if(Yii::$app->params['AUTO_PASSWORD'] =='Yes'){
				$length = 8;
				$new_password = Yii::$app->security->generateRandomString ($length);
				$model->password_hash=Yii::$app->security->generatePasswordHash ($new_password);//$new_password;
			}else{
				$new_password = $_POST['User']['password_hash'];
				$model->password_hash=Yii::$app->security->generatePasswordHash ($new_password);
			}

			// Role Assign
            $auth_item = Yii::$app->request->post('auth_item');
            if($auth_item){
				$model1 = new AuthAssignment;
				$model1->item_name = $auth_item;
				$model1->user_id = $model->id;
				$model1->save();
			}

			// lets insert the user now
			$model->update();

            /* Begin changes to save address details and contact details with new lead creation */
            $_REQUEST = Yii::$app->request->post('address');
            AddressModel::addressInsert($model->id, 'user');

            $_REQUEST = Yii::$app->request->post('contact');
            $_REQUEST['first_name'] = $model->first_name;
            $_REQUEST['last_name'] = $model->last_name;
            $_REQUEST['middle_name'] = $model->middle_name;
            $_REQUEST['email'] = $model->email;
            ContactModel::contactInsert($model->id, 'user');

			// Send email
			$img->loadImage('../users/nophoto.jpg')->saveImage("../users/".$model->id.".png");
			$img->loadImage('../users/nophoto.jpg')->resize(30, 30)->saveImage("../users/user_".$model->id.".png");
			$emailObj->sendNewUserEmailTemplate($model->email,$model->first_name." ".$model->last_name, $model->username,$new_password);
			
			/*$adminUser = User::find()->where("username='admin'")->one();
			$link = '<a href="'.$_SESSION['base_url'].'?r=site/active-user&user_id=user'.$model->id.'">click here </a>';
			SendEmail::sendLiveEmail($adminUser->email,"A new user account is created with Username : ".$model->username."  and waiting for your approval. To approve ".$link, false,'New account is created');*/

            return $this->render('update', [
                'model' => $model,
                'new_password'=>$new_password,
                'is_new' => true
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $img = new ImageUpload();
		$emailObj = new SendEmail;
        $model = $this->findModel($id);
		if(!empty($_GET['img_del'])){
			unlink('../users/'.$model->id.'.png');
			unlink('../users/user_'.$model->id.'.png');
			return $this->redirect(['update', 'id' => $model->id]);
		}
		if(!empty($_GET['active'])){
			$status = $_GET['active']=='yes'?'1':'0';
			$userUpdate = User::findOne($model->id);
			$userUpdate->updated_at=strtotime(date('Y-m-d H:i:s'));
			$userUpdate->active = $status;
			$userUpdate->save();
			if($_GET['active']=='yes'){
			$emailObj->sendActivateUserEmailTemplate($userUpdate->email,$userUpdate->first_name." ".$userUpdate->last_name, $userUpdate->username,'*******','<a href="'.$_SESSION['base_url'].'?r=site/login">here</a>');
			}
			return $this->redirect(['view', 'id' => $model->id]);
		}
		if(!empty($_GET['reset_password'])){
			$new_password = Yii::$app->security->generateRandomString (8);
			$userUpdate = User::findOne($model->id);
			$userUpdate->password_hash= Yii::$app->security->generatePasswordHash ($new_password);///$new_password;
			$userUpdate->updated_at=strtotime(date('Y-m-d H:i:s'));
			$userUpdate->save();
			//Send an Email
			$emailObj->sendResetPasswordEmailTemplate($model->email,$model->first_name." ".$model->last_name,$new_password);
			//SendEmail::sendLiveEmail($model->email,"Dear ".$model->first_name." ".$model->last_name.", <br/>Your password has been changed by Admin <br/> <b>Your New password is:</b><br/>".$new_password."<br/> Thanks", false,"Your password has been changed");
			
			return $this->render('update', [
                'model' => $model,
				'new_password'=>$new_password,
            ]);	
			
		}
		if(!empty($_FILES['user_image']['tmp_name'])){
				$img->loadImage($_FILES['user_image']['tmp_name'])->saveImage("../users/".$model->id.".png");
				$img->loadImage($_FILES['user_image']['tmp_name'])->resize(30, 30)->saveImage("../users/user_".$model->id.".png");
			}

			$new_role = $_POST['auth_item'];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			// Role Assign
            if ( ! empty($new_role)) {
                $auth = AuthAssignment::findOne(['user_id' => $model->id]);

                if ($new_role != $auth->item_name) {
                    try {
                        $auth->delete();

                        $newAuth = new AuthAssignment();
                        $newAuth->item_name = $new_role;
                        $newAuth->user_id = $model->id;
                        $newAuth->save();
                    } catch (StaleObjectException $e) {
                    } catch (\Throwable $e) {
                    }
                }
            }
            /*if($model->user_type_id == UserTypeSearch::getCompanyUserType('Customer')->id){
				$model1 = new AuthAssignment;
				$model1->item_name = 'Customer';
				$model1->user_id = $model->id;
				$model1->save();
			}*/

			if(!empty($_FILES['user_image']['tmp_name'])){
				//move_uploaded_file($_FILES['user_image']['tmp_name'],'../users/'.$model->id.'.png');
				$img->loadImage($_FILES['user_image']['tmp_name'])->saveImage("../users/".$model->id.".png");
				$img->loadImage($_FILES['user_image']['tmp_name'])->resize(30, 30)->saveImage("../users/user_".$model->id.".png");
			}
			$unit = Yii::$app->request->post('unit_generate');
			if (isset($unit))
            {
                $model->unit_generate = $unit;
            }

			$model->updated_at = time();
			$model->update();


            /* Begin changes to save address details and contact details with new lead creation */
            $address_id = Yii::$app->request->post('address_id');
            $_REQUEST = Yii::$app->request->post('address');
            if ( ! empty($address_id)) {
                AddressModel::addressUpdate($address_id);
            } else {
                AddressModel::addressInsert($model->id, 'user');
            }

            $contact_id = Yii::$app->request->post('contact_id');
            $_REQUEST = Yii::$app->request->post('contact');
            $_REQUEST['first_name'] = $model->first_name;
            $_REQUEST['last_name'] = $model->last_name;
            $_REQUEST['middle_name'] = $model->middle_name;
            $_REQUEST['email'] = $model->email;

            if ( ! empty($contact_id)) {
                ContactModel::contactUpdate($contact_id);
            } else {
                ContactModel::contactInsert($model->id, 'user');
            }
            return $this->redirect(['view', 'id' => $model->id,'reload'=>'true']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
	public function actionChangePassword(){
		$msg='';
		$emailObj = new SendEmail;
		if(!empty($_REQUEST['password']) && !empty($_REQUEST['confirm_pass'])){
			$userUpdate = User::findOne(Yii::$app->user->identity->id);
			$userUpdate->password_hash = Yii::$app->security->generatePasswordHash ($_REQUEST['password']);//$_REQUEST['password'];
			$userUpdate->save();
			$msg='Your password has been changed. Please check your email!';
			//Send an Email
			$emailObj->sendResetPasswordEmailTemplate($userUpdate->email,$userUpdate->first_name." ".$userUpdate->last_name,$_REQUEST['password']);
			
			/* Below code was added to log out user after password change */
			date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
			$last_logged = time();
			$logged_out = time();
			$user_id=Yii::$app->user->identity->id;
			$sql="select * from tbl_session_details where user_id='$user_id'";
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
			if(count($dataReader) >0){
				$sql="update tbl_session_details set logged_out='$logged_out' where user_id='$user_id' and session_id='".session_id()."'";
				$connection = \Yii::$app->db;
				$command=$connection->createCommand($sql);
				$dataReader=$command->execute();
			}
			Yii::$app->user->logout ();
			return $this->goHome ();
		}
		return $this->render('change-password', [
                'msg' => $msg,
            ]);
	}
	public function actionUserSessions(){
		if(Yii::$app->user->identity->username !='admin'){
          $this->redirect(array('/site/index'));
		}
		if(isset($_GET['del_id'])){
			$sessionObj = SessionDetails::findOne($_GET['del_id']);
			$sessionObj->logged_out = time();
			$sessionObj->update();
			return $this->redirect(['index']);
		}
		$searchModel = new UserSessionSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('user-sessions', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
	}
	public function actionUserSessionDetail(){
		if(Yii::$app->user->identity->username !='admin'){
          $this->redirect(array('/site/index'));
		}
		$searchModel = new HistorySearch;
        $dataProvider = $searchModel->searchSessionActivities(Yii::$app->request->getQueryParams());

        return $this->render('user-session-detail', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
	}
	public function actionMailCompose(){
		$emailObj = new SendEmail;
		if(!empty($_REQUEST['to'])){
			//SendEmail::sendLiveEmail($_REQUEST['to'],$_REQUEST['email_body'], $_REQUEST['cc'],$_REQUEST['subject']);
			SendEmail::sendLiveEmail($_REQUEST['to'],$_REQUEST['email_body'], $_REQUEST['cc'],$_REQUEST['subject'], true, false, false);
			$msg = 'Email is sent';	
		}
		else
		{
			$msg = '';
		}

		$user = $this->findModel($_GET['id']);
        return $this->render('mail-compose', [
            'user' => $user,
			'msg'=>$msg
        ]);
	}
	public function actionUserAllReports()
    {
		if(!Yii::$app->user->can('Report.AllUser')){
			throw new NotFoundHttpException('You dont have permissions to view this page.');
		}
        return $this->render('user-all-reports');
    }
	public function actionNewUserReport()
    {
		if(!Yii::$app->user->can('Report.NewUser')){
			throw new NotFoundHttpException('You dont have permissions to view this page.');
		}
        return $this->render('new-user-report');
    }
	public function actionUserTypeReport()
    {
		if(!Yii::$app->user->can('Report.UserType')){
			throw new NotFoundHttpException('You dont have permissions to view this page.');
		}
        return $this->render('user-type-report');
    }
	public function actionUserStatusReport()
    {
		if(!Yii::$app->user->can('Report.UserStatus')){
			throw new NotFoundHttpException('You dont have permissions to view this page.');
		}
        return $this->render('user-status-report');
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
	public function actionAjexGetRoles($id){
		$connection = \Yii::$app->db;
		$sql="select auth_item.* from auth_item,auth_assignment where auth_item.type=2 and auth_assignment.user_id=$id and auth_assignment.item_name=auth_item.name";
		$command=$connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$roles ='<ul class="list-group">';
		if(count($dataReader) > 0){
			foreach($dataReader as $role){
				$roles.='<li class="list-group-item active">'.$role['name']."</li>";
			}
		}else{
			return '<div class="alert alert-danger">No Roles</div>';
		}
		
		return $roles."</ul>";
	}
    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		if(Yii::$app->user->identity->userType->type=="Customer")
		{
			if ($id == Yii::$app->user->identity->id) {
					if (($model = User::findOne($id)) !== null) {
					return $model;
				} else {
					throw new NotFoundHttpException('The requested page does not exist.');
				}
			} else {
				throw new NotFoundHttpException('The requested page does not exist.');
			}
		}
		else
		{
			if (($model = User::findOne($id)) !== null) {
				return $model;
			} else {
				throw new NotFoundHttpException('The requested page does not exist.');
			}
		}
    }
}

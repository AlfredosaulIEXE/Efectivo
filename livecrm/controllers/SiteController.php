<?php

namespace livecrm\controllers;

use livefactory\models\HistoryModel;
use livefactory\models\UserActivity;
use Yii;
use yii\filters\AccessControl;
use livefactory\controllers\Controller;
use livefactory\models\LoginForm;
use livefactory\models\SalesReport;
use livefactory\models\Timeclock;
use livefactory\models\ConfigItem;
use livefactory\models\Task as taskSearchResult;
use livefactory\models\Project as projectSearchResult;
use livefactory\models\Defect as defectSearchResult;
use livefactory\models\Ticket as ticketSearchResult;
use livefactory\models\Invoice as invoiceSearchResult;
use livefactory\models\Estimate as estimateSearchResult;
use livefactory\models\Lead as leadSearchResult;
use livefactory\models\Contact as contactSearchResult;
use yii\filters\VerbFilter;
use livefactory\models\SendEmail;
use livefactory\models\User;
use yii\helpers\Json;
use livefactory\models\ImageUpload;
use livefactory\models\search\CommonModel as SessionVerification;
use livefactory\models\search\UserType as UserTypeSearch;
use livefactory\models\AuthAssignment;
use livefactory\models\Lead;
/**
 * Site controller
 */
class SiteController extends Controller {
	/**
	 * @inheritdoc
	 */
	 public function init(){
		/* Check for existence of license file */
		/* If license file does not exists then redirect to get license page */
		
		/*
		if(!is_file("../config/license.dat"))
		{
			$this->redirect('index.php?r=liveobjects/app-license/get-license&msg=No valid license found! Please obtain new license!');
		}
		else
		{
			$lic_data = unserialize(base64_decode((file_get_contents('../config/license.dat'))));

			if (isset($lic_data['invoice_number']) && isset($lic_data['order_number']) && isset($lic_data['order_date']) && isset($lic_data['domain']) && isset($lic_data['module_name']) && isset($lic_data['expiry_date']))
			{
				if ($_SERVER['HTTP_HOST'] != $lic_data['domain'])
				{
					$this->redirect('index.php?r=liveobjects/app-license/get-license&msg=Your license is activated for a different domain! Please obtain a fresh license for new domain.');
				}
				else if(strtotime($lic_data['expiry_date'])+(24*60*60) < time())
				{
					$this->redirect('index.php?r=liveobjects/app-license/get-license&msg=Your license is expired! Please renew your license by sending a mail to contact@liveobjects.org.');
				}
				else
				{
					$allowed_mod_arr = array_map('trim', explode(",", $lic_data['module_name']));
					$module_name = '';

					if(in_array('pmt', Yii::$app->params['modules']))
						$module_name = $module_name.'pmt,';
					
					if(in_array('support', Yii::$app->params['modules']))
						$module_name = $module_name.'support,';

					if(in_array('sales', Yii::$app->params['modules']))
						$module_name = $module_name.'sales,';

					if(in_array('invoice', Yii::$app->params['modules']))
						$module_name = $module_name.'invoice,';

					if($module_name != '')
						$module_name = substr($module_name, 0, -1);

					$all_ok = false;

					$loaded_mod_arr = array_map('trim', explode(",", $module_name));

					foreach ($loaded_mod_arr as $mod)
					{
						if(in_array($mod, $allowed_mod_arr))
						{
							$all_ok = true;
						}
						else
						{
							$all_ok = false;
							break;
						}
					}

					if (!$all_ok)
					{
						$this->redirect('index.php?r=liveobjects/app-license/get-license&msg=You did not purchase this application! Please obtain a license first!');
					}
				}

			}
			else
			{
				$this->redirect('index.php?r=liveobjects/app-license/get-license&msg=Invalid license! Please obtain new license!');
			}
		}
		*/
		

		 /* If file if good then proceed ahead with the application */
		 SessionVerification::checkSessionDestroy();
	}

	public function updateUserSession($user_id){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$last_logged = time();
		$logged_out = time();
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
		unset($_SESSION['SessionDetailsId']);
	}

    /**
     * @return string
     */
    public function get_ip_address()
    {
        foreach (array('HTTP_CF_CONNECTING_IP', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe

                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }

	public function sessionHistory($user_id){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		/*$last_logged = time();
		$logged_in = time();*/
		$location_ip = $this->get_ip_address();
		$device = \Yii::getAlias('@device');
		/*$str_info = $device." ".$location_ip;

		$sql="insert into tbl_session_details  (
											user_id,
											last_logged,
											logged_in,
											location_ip,
											session_id
											) values (
											'$user_id',
											'$last_logged',
											'$logged_in',
											'$str_info',
											'".session_id()."'
											)";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$dataReader=$command->execute();
		$_SESSION ['SessionDetailsId']=session_id();*/

		HistoryModel::historyInsert('log', HistoryModel::ACTION_LOGIN, 'Nuevo inicio de sesión desde: <strong>'.$device.' <a href="https://es.geoipview.com/?q='.$location_ip.'" target="_blank">'.$location_ip.'</a></strong>');
	}

	public function behaviors() {
		return [ 
				'access' => [ 
						'class' => AccessControl::className (),
						
						'rules' => [ 
								[ 
										'actions' => [ 
												'forgot-password',
												'error' 
										],
										'allow' => true 
								],
								[ 
										'actions' => [ 
												'active-user',
												'error' 
										],
										'allow' => true 
								],
								[ 
										'actions' => [ 
												'search-results',
												'error' 
										],
										'allow' => true 
								],
								[ 
										'actions' => [ 
												'restore-db',
												'error' 
										],
										'allow' => true 
								],
								[ 
										'actions' => [ 
												'login',
												'error' 
										],
										'allow' => true 
								],
								[ 
										'actions' => [ 
												'auth',
												'error' 
										],
										'allow' => true 
								],
								[ 
										'actions' => [ 
												'logout',
												'index' 
										],
										'allow' => true,
										'roles' => [ 
												'@' 
										] 
								] 
						] 
				],
				'verbs' => [ 
						'class' => VerbFilter::className (),
						'actions' => [ 
								'logout' => [ 
										'post' 
								] 
						] 
				] 
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [ 
				'auth' => [
					'class' => 'yii\authclient\AuthAction',
					'successCallback' => [$this, 'successCallback'],
				],
				'error' => [ 
						'class' => 'yii\web\ErrorAction' 
				] 
		];
	}
	public function successCallback($client)
    {
		$img = new ImageUpload();
		$model = new LoginForm ();
        $attributes = $client->getUserAttributes();
		
         // user login or signup comes here
        $user =  User::find()->where(['email' => $attributes['email']])->one();
        if (!empty($user))
        {
			if($user->active > 0){
         		Yii::$app->user->login($user, 3600 * 24 * 30);
				return $this->goBack ();
			}else{
				header('location:index.php?r=site/login&msg=Your account is not active. Please contact system admin.');
				//$msg ='Your account is not active. Please contact system admin.';	
			}
        }
        else
        {
            $emailObj = new SendEmail;
			$user_model = new User();
			$user_model->first_name= $attributes['first_name'];
			$user_model->last_name= $attributes['last_name'];
			$user_model->email= $attributes['email'];
			$user_model->username= $attributes['email'];
			$user_model->user_type_id=UserTypeSearch::getCompanyUserType('Customer')->id;
			$new_password =Yii::$app->security->generateRandomString (8);
			$user_model->password_hash= Yii::$app->security->generatePasswordHash ($new_password);
			$user_model->added_at=strtotime(date('Y-m-d H:i:s'));
			$user_model->save();
			$authModel = new AuthAssignment;
			$authModel->item_name = 'Customer';
			$authModel->user_id = $user_model->id;
			$authModel->save();
			$img->loadImage('../users/nophoto.jpg')->saveImage("../users/".$userModel->id.".png");
			$img->loadImage('../users/nophoto.jpg')->resize(30, 30)->saveImage("../users/user_".$userModel->id.".png");
			$emailObj->sendNewUserEmailTemplate($user_model->email,$user_model->first_name." ".$user_model->last_name, $user_model->username,$new_password);
			header('location:index.php?r=site/login&msg=Your account is not active. Please contact system admin.');
        }
    }
	public function actionIndex() {

		$session_id=isset($_SESSION ['SessionDetailsId'])?$_SESSION ['SessionDetailsId']:'';
		$sql="select * from tbl_session_details where session_id='$session_id' and logged_out !=''";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$dataReader=$command->queryAll();
		if(count($dataReader) >0){
			$this->updateUserSession(Yii::$app->user->identity->id);
			Yii::$app->user->logout ();
			return $this->goHome ();
		}

		// Get vars
        $office_id = Yii::$app->request->getQueryParam('office_id');
        $agent_id = Yii::$app->request->getQueryParam('agent_id');
        $mean_id = Yii::$app->request->getQueryParam('mean_id');

        // No limit, then is a "superior" agent
		if ($office_id != 'true' && ! Yii::$app->user->can('Office.NoLimit')) {
		   $office_id = null;
        }

        // Agent
        if ( ! Yii::$app->user->can('Reports.ByUser')) {
		    $agent_id = null;
        }

        // My Stats
        if (empty($agent_id) && empty($office_id) && Yii::$app->user->can('Reports.My')) {
		    $agent_id = Yii::$app->user->id;
        }

        // Empty
        $office_id = empty($office_id) ? null : ($office_id == 'true' ? Yii::$app->user->identity->office_id : $office_id);
		$agent_id = empty($agent_id) ? null : $agent_id;

        list($start, $end) = SalesReport::getPeriodFromRequest(Yii::$app->request->getQueryParams());
        if (Yii::$app->user->can('CheckLead'))
        {
            return $this->redirect('?r=api/julito');
        }
		return $this->render ( 'index', [
		    'start' => $start,
            'end' => $end,
            'office_id' => $office_id,
            'agent_id' => $agent_id,
            'mean_id' => $mean_id
        ] );
	}

	public function actionAutoFrozen(){
	    //datenow
	    $date_now = new \DateTime();
	    $datadate =strtotime("-2 week",$date_now->getTimestamp());
	    //connection to db
	    $connection = \Yii::$app->db;
        $sql= "SELECT id, lead_status_id, payed, converted_at, office_id, service_status_id, service_owner_id,updated_at FROM `tbl_lead` WHERE  updated_at <=". $datadate;
        $command=$connection->createCommand($sql);
        $dataReader=$command->queryAll();
        $clientservice=[];
        //agroup in office
        foreach ($dataReader as $client)
        {
            $clientservice[$client['office_id']][]=$client;
        }
        //assign customers office with leads
        foreach($clientservice as $office_id => $clients)
        {
            foreach ($clients as $user)
            {
                //update to db
                $connection->createCommand("UPDATE tbl_lead SET lead_status_id=7"  . " WHERE id=".$user["id"])->execute();

            }
        }

    }

    public function actionAutomigrate()
    {
        //datenow
        $fecha = new \DateTime();
        //diff date and calculate date assignate
        $datafe = strtotime("- 34 days",$fecha->getTimestamp() );
        //connection to db
        $connection = \Yii::$app->db;
        //consult to leads with parameters
        $sql= "SELECT id, lead_status_id, active, converted_at, office_id, service_status_id,lead_master_status_id  FROM `tbl_lead` WHERE lead_status_id = 4  and converted_at <=". $datafe . " AND active = 1 and lead_master_status_id = 1 AND (valid_sales = 0 || valid_admin = 0 || valid_manager = 0)";
        $command=$connection->createCommand($sql);
        $dataReader=$command->queryAll();
        $clientservice=[];
        //consult with customer service and agroup  office_id
        /*$managers = [];
        foreach (User::find()->join('LEFT JOIN', 'auth_assignment', 'auth_assignment.user_id = tbl_user.id')
                     ->where("auth_assignment.item_name = 'Customer.Service'")
                     ->asArray()->all() as $key => $item) {
            $managers[$item['office_id']][] = $item['id'];
        }*/
        //agroup leads office_id
        foreach ($dataReader as $client)
        {
            $clientservice[$client['office_id']][]=$client;
        }
        //assign customers office with leads
        foreach($clientservice as $office_id => $clients)
        {
            $temp=[];
            foreach ($clients as $user)
            {
                // If empty managers
                /*if(empty($temp)) {
                    if ($managers[$office_id]) {
                        $temp=$managers[$office_id];
                    }
                    else
                    {
                        continue;
                    }
                }
                //
                $user['service_status_id']=0;
                $user['service_owner_id']=$temp[0];
                array_shift($temp);*/
                //update to db
                $connection->createCommand("UPDATE tbl_lead SET  lead_master_status_id = 2 WHERE id=".$user["id"])->execute();
                HistoryModel::historyInsert('lead',$user['id'],'Lead migrado en automático a seguros', 173);

            }
        }

    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionLogin() {
        //	Yii::$app->user->setReturnUrl('index.php?r=site/index');

        $this->actionAutomigrate();
		if (! \Yii::$app->user->isGuest) {
			return $this->goBack();
		}
		$img = new ImageUpload();
		$emailObj = new SendEmail;
		$model = new LoginForm ();
		$user_model = new User();
		$msg='';
		if ($user_model->load ( Yii::$app->request->post () ) && $user_model->save()) {
			if(Yii::$app->params['AUTO_PASSWORD'] =='Yes'){
				$new_password =Yii::$app->security->generateRandomString (8);
				$user_model->password_hash= Yii::$app->security->generatePasswordHash ($new_password);
			}else{
				$new_password = $user_model->password_hash;
				$user_model->password_hash= Yii::$app->security->generatePasswordHash ($new_password);
			}
			$user_model->added_at=time();
			$user_model->save();
			/*$authModel = new AuthAssignment;
			$authModel->item_name = 'Customer';
			$authModel->user_id = $user_model->id;
			$authModel->save();*/
			$img->loadImage('../users/nophoto.jpg')->saveImage("../users/".$user_model->id.".png");
			$img->loadImage('../users/nophoto.jpg')->resize(30, 30)->saveImage("../users/user_".$user_model->id.".png");
			$emailObj->sendNewUserEmailTemplate($user_model->email,$user_model->first_name." ".$user_model->last_name, $user_model->username,$new_password);
			
			$msg=Yii::t('app','Account Created Successfully. Please check you email!');
			
			$adminUser = User::find()->where("username='admin'")->one();
			$link = '<a href="'.$_SESSION['base_url'].'?r=site/active-user&user_id=user'.$user_model->id.'user@gmail.com">click here </a>';
			SendEmail::sendLiveEmail($adminUser->email,"A new user account is created with Username : ".$user_model->username." and waiting for your approval. To approve ".$link, false,'New account is created');
			
			return $this->render ( 'login', [ 
					'model' => $model,
					'user_model'=>$user_model,
					'msg'=>$msg  
			] );	
		}
		if ($model->load ( Yii::$app->request->post () ) && $model->login ()) {
		    // If mobile && is Sales person
//            if (strtolower(Yii::getAlias('@device')) == 'mobile' && AuthAssignment::find()->where('user_id = ' . Yii::$app->user->identity->id)->andWhere("item_name NOT IN ('Admin', 'Audit.Member', 'Director', 'Director.Assistant', 'Legal', 'Commercial.Manager')")->one()) {
//                Yii::$app->user->logout ();
//                include_once '../views/site/login_mobile.php';
//                exit;
//            }

            // Check user roles
		    $role = AuthAssignment::find()
                ->leftJoin('auth_item','auth_item.name = auth_assignment.item_name')
                ->where('auth_assignment.user_id = ' . Yii::$app->user->identity->id)
                ->andWhere("auth_assignment.item_name NOT IN ('Customer', 'Employee', 'Admin', 'Audit.Member', 'Director', 'Director.Assistant', 'Wall-e', 'Legal')")
                ->andWhere('auth_item.type = 2')
                ->one();

		    if ( $role) {
		        $week_day = date('N');
                // Role times
                $office_time = Timeclock::find()->where('office_id = ' . Yii::$app->user->identity->office_id . " AND role_id = '".$role->item_name."'" . ' AND week_day = ' . $week_day)->one();

                // Get office times
                if (is_null($office_time)) {
                    $office_time = Timeclock::find()->where('office_id = ' . Yii::$app->user->identity->office_id . ' AND week_day = ' . $week_day)->one();
                }

                // Access denied
                if ($office_time && $office_time->denied == 1) {
                    $this->updateUserSession(Yii::$app->user->identity->id);
                    Yii::$app->user->logout ();
                    include_once '../views/site/login_out_time.php';
                    exit;
                }

                // Current time
                $current_date = date_create();

                // Validate
                if ($office_time) {
                    $start_time = date_create_from_format('H:i:s', $office_time->start_time);
                    $end_time = date_create_from_format('H:i:s', $office_time->end_time);
                } else {
                    // Default not in databases times
                    $start_time = date_create_from_format('H:i:s', '09:00:00');
                    $end_time = date_create_from_format('H:i:s', '19:00:00');
                }

                // Validate time
                if ( ! ($start_time < $current_date && $end_time > $current_date)) {
                    $this->updateUserSession(Yii::$app->user->identity->id);
                    Yii::$app->user->logout ();
                    include_once '../views/site/login_out_time.php';
                    exit;
                }
            }

			$replace1=array(' ','.');
			$replace2=array('','');
			$_SESSION['username']=str_replace($replace1,$replace2,Yii::$app->user->identity->first_name)."_".trim(str_replace($replace1,$replace2,Yii::$app->user->identity->last_name))."_".Yii::$app->user->identity->id;
			$this->sessionHistory(Yii::$app->user->identity->id);
			//user activity login
            UserActivity::insertIfNotExists();
            //
			return $this->goBack ();
		} else {
			return $this->render ( 'login', [ 
					'model' => $model,
					'user_model'=>$user_model ,
					'msg'=>$msg
			] );
		}

	
	}
	public function actionForgotPassword(){
		$model = new LoginForm ();
		$error='';
		$msg='';
		if(!empty($_REQUEST['LoginForm']['email'])){
			if (($userModel = User::find()->where("email='".$_REQUEST['LoginForm']['email']."'")->one()) !== null) {
			$emailObj = new SendEmail;
			$length = 8;
			$new_password = Yii::$app->security->generateRandomString ( $length );
			SendEmail::sendLiveEmail($_REQUEST['LoginForm']['email'],$new_password, false,'Your  Password');
			$userModel->password_hash=Yii::$app->security->generatePasswordHash ($new_password); 
			$userModel->save();
			$msg=Yii::t('app','Your Password has been changed. Please check your email containing new password!');
		}else{
				$error=Yii::t('app','Email doesnt exist');
			}
		}
		if(isset($_REQUEST['LoginForm']['email']) && empty($_REQUEST['LoginForm']['email'])){
			$error=Yii::t('app','Please enter your email & click send');	
		}
		return $this->render ( 'forgot-password', [ 
					'model' => $model,
					'error'=>$error,
					'msg'=>$msg 
			] );
	}
	
	public function actionLogout() {
		$this->updateUserSession(Yii::$app->user->identity->id);
		//user activity logout
        UserActivity::logoutUserActivity();
		Yii::$app->user->logout ();
		
		return $this->goHome ();
	}
	public function actionUseractivity(){
        UserActivity::interactionUserActivity();
    }
	public function actionActiveUser() {
		$id = isset($_GET['user_id'])?$_GET['user_id']:'';
		$error=$success="";
		if(!empty($id)){
			list($user_string,$id) = explode('user',$id);	
			if (($userModel = User::find()->where("id='".$id."'")->one()) !== null) {
			$emailObj = new SendEmail;
			$userModel->active =1;
			$userModel->save();
			$success=$userModel->first_name." ".$userModel->last_name."( ".$userModel->username." ) now Activated !";
				return $this->render ( 'active-user', [ 
					'error' => $error,
					'success'=>$success
				] );
			}else{
				$error="User  doesn't exist";
				return $this->render ( 'active-user', [ 
					'error' => $error,
					'success'=>$success
				] );
			}
		}
		return $this->render ( 'active-user');
	}
	public function actionSearchResults(){
		//$taskModel='';
		//$projectModel='';
		$search=trim($_REQUEST['top_search']);
		if(!empty($search)){
			/*if(in_array('pmt',Yii::$app->params['modules'])){
			$taskModel = taskSearchResult::find()->andwhere("task_name like '%$search%' or task_description  like '%$search%' or task_id='$search'")->orderBy('id desc')->asArray()->all();
			$projectModel = projectSearchResult::find()->orderBy('id desc')->andwhere("project_name like '%$search%' or project_description  like '%$search%'")->asArray()->all();
			$defectModel = defectSearchResult::find()->orderBy('id desc')->andwhere("defect_name like '%$search%' or defect_description  like '%$search%' or defect_id='$search' ")->asArray()->all();
					}
			if(in_array('support',Yii::$app->params['modules'])){
			$ticketModel = ticketSearchResult::find()->andwhere("ticket_title like '%$search%' or ticket_description  like '%$search%' or ticket_id='$search'")->orderBy('id desc')->asArray()->all();
			}
			
			if(in_array('invoice',Yii::$app->params['modules'])){
			$invoiceModel = invoiceSearchResult::find()->andwhere("invoice_number like '%$search%' or notes  like '%$search%' ")->orderBy('id desc')->asArray()->all();
			}
			if(in_array('estimate',Yii::$app->params['modules'])){
			$estimateModel = estimateSearchResult::find()->andwhere("estimation_code like '%$search%' or notes  like '%$search%' ")->orderBy('id desc')->asArray()->all();
			}*/
			if(in_array('sales',Yii::$app->params['modules'])){
			    $search = str_replace(' ', '', $search);
                    $salesModel = leadSearchResult::find()
                        ->andwhere("replace(lead_name, ' ', '') like '%$search%'")->orderBy('id desc')->asArray()->all();

			if ( ! isset($salesModel) || empty($salesModel))
            {
                $salesModel = leadSearchResult::find()
                    ->andwhere("replace(c_control, ' ', '') like '%$search%'")->orderBy('id desc')->asArray()->all();
            }
                if ( !  isset($salesModel) || empty($salesModel))
                {
                    $salesModel = leadSearchResult::find()
                        ->andwhere("replace(phone, ' ', '') like '%$search%'")->orderBy('id desc')->asArray()->all();
                }
                if ( !  isset($salesModel) || empty($salesModel))
                {
                    $salesModel = leadSearchResult::find()
                        ->andwhere("replace(mobile, ' ', '') like '%$search%'")->orderBy('id desc')->asArray()->all();
                }
                if ( !  isset($salesModel) || empty($salesModel))
                {
                    $salesModel = leadSearchResult::find()
                        ->andwhere("replace(email, ' ', '') like '%$search%'")->orderBy('id desc')->asArray()->all();
                }

			}
			/*if(in_array('customer',Yii::$app->params['modules']) || in_array('sales',Yii::$app->params['modules'])){
			$contactModel = contactSearchResult::find()->andwhere("first_name like '%$search%' or last_name like '%$search%' or email='$search' or mobile='$search' or phone='$search'")->orderBy('id desc')->asArray()->all();
			}*/
		}
        if (Yii::$app->user->can('CheckLead'))
        {
            return $this->render('julito' ,
                [
                    'salesModel' => $salesModel
                ]);
        }
		return $this->render ( 'search-results', [
					//'taskModel' => $taskModel,
					//'projectModel'=>$projectModel,
					//'defectModel'=>$defectModel,
					//'ticketModel'=>$ticketModel,
					//'invoiceModel'=>$invoiceModel,
					//'estimateModel'=>$estimateModel,
					'salesModel'=>$salesModel,
					//'contactModel'=>$contactModel
			] );
	}
	public function actionRestoreDb(){
		$db = file_get_contents('../restore_db/livecrm_livecrm2.sql');
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($db)->execute();
		return $this->render('restore-db');	
	}
}

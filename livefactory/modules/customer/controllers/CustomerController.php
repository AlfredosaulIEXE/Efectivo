<?php

namespace livefactory\modules\customer\controllers;

use Yii;
use livefactory\models\AddressModel;
use livefactory\models\ContactModel;
use livefactory\models\Address;
use livefactory\models\Customer;
use livefactory\models\Estimate;
use livefactory\models\Contact;
use livefactory\models\search\Customer as CustomerSearch;
use livefactory\models\NoteModel;
use livefactory\models\FileModel;
use livefactory\models\AssignmentHistoryModel;
use livefactory\models\HistoryModel;
use livefactory\models\File;
use livefactory\models\Note;
use livefactory\models\History;
use livefactory\models\SendEmail;
use livefactory\models\User as UserDetail;
use livefactory\models\AssignmentHistory;
use livefactory\models\search\CommonModel as SessionVerification;
use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use livefactory\models\ImageUpload;
use livefactory\models\AuthAssignment;
use livefactory\models\search\UserType as UserTypeSearch;

/**

 * CustomerController implements the CRUD actions for Customer model.

 */

class CustomerController extends Controller

{

	public function init(){

    	if(!!empty(Yii::$app->user->identity->id)){

          $this->redirect(array('/site/login'));

		}

	}

	public $entity_type='customer';

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

     * Lists all Customer models.

     * @return mixed

     */

    public function actionIndex()

    {
		if(!Yii::$app->user->can('Customer.Index')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}

        $searchModel = new CustomerSearch;

        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

		$dataProviderBox=$searchModel->searchCustomer();

		if(!empty($_REQUEST['multiple_del'])){
			if(!Yii::$app->user->can('Customer.Delete')){
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

			'dataProviderBox'=>$dataProviderBox,

        ]);

    }



    /**

     * Displays a single Customer model.

     * @param integer $id

     * @return mixed

     */

    public function actionView($id)

    {
		if(!Yii::$app->user->can('Customer.View')){
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

     * Creates a new Customer model.

     * If creation is successful, the browser will be redirected to the 'view' page.

     * @return mixed

     */

    public function actionCreate()

    {
		if(!Yii::$app->user->can('Customer.Create')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		$img = new ImageUpload();
		$emailObj = new SendEmail;
        $model = new Customer;



        if ($model->load(Yii::$app->request->post()) && $model->save()) {

			$address_id = AddressModel::addressInsert($model->id,'customer');

			$updateCustomer =  Customer::findOne($model->id);

			$updateCustomer->added_at=strtotime(date('Y-m-d H:i:s'));

			$updateCustomer->update();

			//Customer Add Contact

			$contactae = new Contact();

			$contactae->first_name = $model->first_name;

			$contactae->last_name = $model->last_name;

			$contactae->email = $model->email;

			$contactae->phone = $model->phone;

			$contactae->mobile = $model->mobile;

			if($model->fax)

			$contactae->fax = $model->fax;

			$contactae->entity_id = $model->id;

			$contactae->entity_type = 'customer';
			$contactae->is_primary = 1;
			$contactae->added_at=strtotime(date('Y-m-d H:i:s'));
			
		
			$contactae->save();
			//Add History

			HistoryModel::historyInsert($this->entity_type,$model->id,'Customer Created  - (  <a href="index.php?r=customer/customer/customer-view&id='.$model->id.'">'.$model->first_name.' '.$model->last_name.'</a> )');
			// by default create users for all the customers
			//if(Yii::$app->params['ALLOW_CUSTOMER_LOGIN_TO_BACKEND'] =='Yes'){

				if(UserDetail::find()->where("email='".$model->email."'")->count() > 0){
					 return $this->redirect(['customer-view', 'id' => $model->id,'error'=>'User can not be Created Email Already Exists!']);
				}else{
					$userModel = new UserDetail;
					$userModel->first_name = $model->first_name;
					$userModel->last_name = $model->last_name;
					$userModel->email = $model->email;
					$userModel->username = $model->email;
					$userModel->active = 1;
                    $userModel->user_type_id = UserTypeSearch::getCompanyUserType('Customer')->id;
					$userModel->entity_id = $model->id;
					$userModel->entity_type = 'customer';
					$userModel->added_at = strtotime(date('Y-m-d H:i:s'));
					$new_password = Yii::$app->security->generateRandomString (8);
					$userModel->password_hash=Yii::$app->security->generatePasswordHash($new_password);
					$userModel->save();
					if(count($userModel->errors) >0){
						var_dump($userModel->errors);
					}
					$authModel = new AuthAssignment;
					$authModel->item_name = 'Customer';
					$authModel->user_id = $userModel->id;
					$authModel->save();
					$img->loadImage('../users/nophoto.jpg')->saveImage("../users/".$userModel->id.".png");
					$img->loadImage('../users/nophoto.jpg')->resize(30, 30)->saveImage("../users/user_".$userModel->id.".png");
					$emailObj->sendNewUserEmailTemplate($userModel->email,$userModel->first_name." ".$userModel->last_name, $userModel->username,$new_password);
				}

		//	}
            return $this->redirect(['customer-view', 'id' => $model->id]);

        } 
		
		/*elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                        'model' => $model
            ]);
        }*/
		
		
		else {

            return $this->render('create', [

                'model' => $model,

            ]);

        }

    }



    /**

     * Updates an existing Customer model.

     * If update is successful, the browser will be redirected to the 'view' page.

     * @param integer $id

     * @return mixed

     */

    public function actionUpdate($id)

    {
		if(!Yii::$app->user->can('Customer.Update')){
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

	public function actionCustomerView($id)

    {
		if(!Yii::$app->user->can('Customer.View')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		$img = new ImageUpload();
		$emailObj = new SendEmail;

        $model = $this->findModel($id);

		$addressModel = Address::find()->where("entity_type='customer' and entity_id='".$id."'")->one();
		
		/// Contact Primary
		if(!empty($_GET['primary'])){
			$contactModel = Contact::find()->where("entity_type='customer' and entity_id=".$model->id." and is_primary=1")->one();
			if(!is_null($contactModel)){
				$contactModel->is_primary=0;
				$contactModel->save();
				if (($obj = Contact::findOne($_GET['primary'])) !== null) {
					$obj->is_primary=1;
					$obj->save();
				}
			}else{
				if (($obj = Contact::findOne($_GET['primary'])) !== null) {
					$obj->is_primary=1;
					$obj->save();
				}
			}
			
			// updating customer when the primary contact is changed.
			$customer_model = Customer::findOne($id);
			$customer_model->first_name = $obj->first_name;
			$customer_model->last_name = $obj->last_name;
			$customer_model->email = $obj->email;
			$customer_model->phone = $obj->phone;
			$customer_model->mobile = $obj->mobile;
			$customer_model->fax = $obj->fax;
			$customer_model->save();
				

			return $this->redirect(['customer-view','id'=>$model->id]);
		}

		/// Address Primary
		if(!empty($_GET['address_primary'])){
			$addressModel = Address::find()->where("entity_type='customer' and entity_id=".$model->id." and is_primary=1")->one();
			if(!is_null($addressModel)){
				$addressModel->is_primary=0;
				$addressModel->save();
				if (($obj = Address::findOne($_GET['address_primary'])) !== null) {
					$obj->is_primary=1;
					$obj->save();
				}
			}else{
				if (($obj = Address::findOne($_GET['address_primary'])) !== null) {
					$obj->is_primary=1;
					$obj->save();
				}
			}
			return $this->redirect(['customer-view','id'=>$model->id]);
		}
		$attachModelR=$noteModelR=$sub_address_model='';
		if(!empty($_GET['cus_user_del'])){
			$contactObj = Contact::findOne($_GET['cus_user_del']);
			if(!is_null($contactObj)){
                $userDel = UserDetail::find()->andwhere("username='".$contactObj->email."' and user_type_id=".UserTypeSearch::getCompanyUserType('Customer')->id)->one();
				if(!is_null($userDel)){
					$userDel->delete();
					return $this->redirect(['customer-view','id'=>$model->id]);
				}
			}
		}
		if(!empty($_POST['con_ids'])){
			foreach($_POST['con_ids'] as $con_id){
				$contactObj = Contact::findOne($con_id);
				$userModel = new UserDetail;
				$userModel->first_name = $contactObj->first_name;
				$userModel->last_name = $contactObj->last_name;
				$userModel->email = $contactObj->email;
				$userModel->username = $contactObj->email;
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
					$authModel = new AuthAssignment;
					$authModel->item_name = 'Customer';
					$authModel->user_id = $userModel->id;
					$authModel->save();
					$img->loadImage('../users/nophoto.jpg')->saveImage("../users/".$userModel->id.".png");
					$img->loadImage('../users/nophoto.jpg')->resize(30, 30)->saveImage("../users/user_".$userModel->id.".png");
					$emailObj->sendNewUserEmailTemplate($userModel->email,$userModel->first_name." ".$userModel->last_name, $userModel->username,$new_password);
				}
			}
			return $this->redirect(['customer-view','id'=>$model->id]);
		}
		if(isset($_FILES['customer_image'])){
			 $files = array();
				move_uploaded_file($_FILES['customer_image']['tmp_name'],'../customers/'.$model->id.'.png');

			}

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$model->updated_at = time();
			$model->update();
			//AddressModel::addressUpdate($model->address_id);

			//Add History

			HistoryModel::historyInsert($this->entity_type,$model->id,'Customer is updated (  <a href="index.php?r=customer/customer/customer-view&id='.$model->id.'">'.$model->first_name.' '.$model->last_name.'</a> )');

			if(!empty($_FILES['customer_image']['tmp_name'])){

				move_uploaded_file($_FILES['customer_image']['tmp_name'],'../customers/'.$model->id.'.png');

			}

            return $this->redirect(['customer-view', 'id' => $model->id]);

        } else {

			if(!empty($_REQUEST['sendemaildesc'])){

				//Send an Email

				SendEmail::sendLiveEmail($_REQUEST['toemail'],$_REQUEST['sendemaildesc'], $_REQUEST['cc'], $_REQUEST['subject']);

				return $this->redirect(['customer-view', 'id' => $_REQUEST['id'], 'msg' => 'Email is sent']);

			}

			//Contact Model

			if(!empty($_REQUEST['contact_edit'])){

				$contact=Contact::findOne($_REQUEST['contact_edit']);

			}else{

				$contact= new Contact();

			}

			// Contact Add / Update

			if(!empty($_REQUEST['contactae'])){

				if(!empty($_REQUEST['first_name'])){

					if(!empty($_REQUEST['contact_id'])){

						ContactModel::contactUpdate($_REQUEST['contact_id']);

						//Add History
						HistoryModel::historyInsert($this->entity_type,$model->id,'Customer Contact Updated into  - (  <a href="index.php?r=customer/customer/customer-view&id='.$model->id.'">'.$model->first_name.' '.$model->last_name.'</a> )');

						return $this->redirect(['customer-view', 'id' => $_REQUEST['id']]);

					}else{

						$con_id=ContactModel::contactInsert($_REQUEST['id'],'customer');

						//Add History

						HistoryModel::historyInsert($this->entity_type,$model->id,'Customer New Contact Added into  - (  <a href="index.php?r=customer/customer/customer-view&id='.$model->id.'">'.$model->first_name.' '.$model->last_name.'</a> )');

					}

				}

			}

			// Contact Delete

			if(!empty($_REQUEST['contact_del'])){

				Contact::findOne($_REQUEST['contact_del'])->delete();

				//Add History

			HistoryModel::historyInsert($this->entity_type,$model->id,'Customer Contact Deleted from  - (  <a href="index.php?r=customer/customer/customer-view&id='.$model->id.'">'.$model->first_name.' '.$model->last_name.'</a> )');

				return $this->redirect(['customer-view', 'id' => $_REQUEST['id']]);

			}

			//Address Model

			if(!empty($_REQUEST['address_edit'])){

				$sub_address_model=Address::findOne($_REQUEST['address_edit']);

			}else{

				$sub_address_model= new Address();

			}

			// Address Delete

			if(!empty($_REQUEST['address_del'])){

				Address::findOne($_REQUEST['address_del'])->delete();

				//Add History

			HistoryModel::historyInsert($this->entity_type,$model->id,'Customer Address Deleted from  - (  <a href="index.php?r=customer/customer/customer-view&id='.$model->id.'">'.$model->first_name.' '.$model->last_name.'</a> )');

				return $this->redirect(['customer-view', 'id' => $_REQUEST['id']]);

			}

			// Estimate Delete

			if(!empty($_REQUEST['estimate_del'])){

				Estimate::findOne($_REQUEST['estimate_del'])->delete();

				//Add History

			HistoryModel::historyInsert($this->entity_type,$model->id,'Customer Estimate Deleted from  - (  <a href="index.php?r=customer/customer/customer-view&id='.$model->id.'">'.$model->first_name.' '.$model->last_name.'</a> )');

				return $this->redirect(['customer-view', 'id' => $_REQUEST['id']]);

			}

			// Address Add / Update

			if(!empty($_REQUEST['addressae'])){

				if(!empty($_REQUEST['sub_address_1'])){

					if(!empty($_REQUEST['address_id'])){

						AddressModel::subAddressUpdate($_REQUEST['address_id']);

						//Add History

			HistoryModel::historyInsert($this->entity_type,$model->id,'Customer Address Updated in  - (  <a href="index.php?r=customer/customer/customer-view&id='.$model->id.'">'.$model->first_name.' '.$model->last_name.'</a> )');

						return $this->redirect(['customer-view', 'id' => $_REQUEST['id']]);

					}else{

						//Add History

			HistoryModel::historyInsert($this->entity_type,$model->id,'Customer New Address Added into  - (  <a href="index.php?r=customer/customer/customer-view&id='.$model->id.'">'.$model->first_name.' '.$model->last_name.'</a> )');

						$sub_aid=AddressModel::subAddressInsert($model->id,'customer');

					}

				}

			}

			if(!empty($_REQUEST['send_attachment_file'])){

				//Send an Email

				SendEmail::sendLiveEmail($_REQUEST['uemail'],$_REQUEST['email_body'], $_REQUEST['cc'], $_REQUEST['subject']);

					return $this->redirect(['customer-view', 'id' => $_REQUEST['id']]);

			}

			// Delete  Attachment

			if(!empty($_REQUEST['attachment_del_id'])){
				$Attachmodel = File::findOne($_REQUEST['attachment_del_id']);
				if (!is_null($Attachmodel)) {
					$Attachmodel->delete();
				}

					//Add History

			HistoryModel::historyInsert($this->entity_type,$model->id,'Customer Attachment Deleted from  - (  <a href="index.php?r=customer/customer/customer-view&id='.$model->id.'">'.$model->first_name.' '.$model->last_name.'</a> )');

					return $this->redirect(['customer-view', 'id' => $_REQUEST['id']]);

			}

			// Delete  Notes

			if(!empty($_REQUEST['note_del_id'])){

					$NoteDel = Note::findOne($_REQUEST['note_del_id'])->delete();

					//Add History

			HistoryModel::historyInsert($this->entity_type,$model->id,'Customer Note Deleted from  - (  <a href="index.php?r=customer/customer/customer-view&id='.$model->id.'">'.$model->first_name.' '.$model->last_name.'</a> )');

					return $this->redirect(['customer-view', 'id' => $_REQUEST['id']]);

			}

			// Add Attachment for Customer

			if(!empty($_REQUEST['add_attach'])){

				$aid=FileModel::fileInsert($_REQUEST['entity_id'],$this->entity_type);
				
				if($aid > 0)
				{
					$link="<a href='".str_replace('web/index.php','',$_SESSION['base_url'])."attachments/".$aid.strrchr($_FILES['attach']['name'], ".")."'>".$_FILES['attach']['name']."</a>";

					$emailObj->sendAddAttachmentEmailTemplate($model->email,$model->customer_name,$link,'<a href="'.$_SESSION['base_url'].'?r=customer/customer/customer-view&id='.$model->id.'">'.$model->customer_name.'</a>');

					//SendEmail::sendLiveEmail($model->email,$link, false,$this->getLoggedUserFullName());

					//Add History

					HistoryModel::historyInsert($this->entity_type,$model->id,'Customer Attachment Added into  - (  <a href="index.php?r=customer/customer/customer-view&id='.$model->id.'">'.$model->first_name.' '.$model->last_name.'</a> )');

						return $this->redirect(['customer-view', 'id' => $_REQUEST['id']]);
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

					return $this->redirect(['customer-view', 'id' => $_REQUEST['id'], 'err_msg' => $msg]);
				}

			}

			// Customer Attachment get

			if(!empty($_REQUEST['attach_update'])){

				$attachModelR=File::findOne($_REQUEST['attach_update']);

			}

			// Customer Notes get

			if(!empty($_REQUEST['note_id'])){

				$noteModelR=Note::findOne($_REQUEST['note_id']);

			}

			// Customer Attachment Update

			if(!empty($_REQUEST['edit_attach'])){

					$file=FileModel::fileEdit();

					if(!empty($_FILES['attach']['name'])){

						$aid=$_REQUEST['att_id'];

						$link="<a href='".str_replace('web/index.php','',$_SESSION['base_url'])."attachments/".$aid.strrchr($_FILES['attach']['name'], ".")."'>".$_FILES['attach']['name']."</a>";

						//Send an Email

						$emailObj->sendUpdateAttachmentEmailTemplate($model->email,$model->customer_name,$link,'<a href="'.$_SESSION['base_url'].'?r=customer/customer/customer-view&id='.$model->id.'">'.$model->customer_name.'</a>');

						//SendEmail::sendLiveEmail($model->email,$link, false,$this->getLoggedUserFullName());

						//Add History

			HistoryModel::historyInsert($this->entity_type,$model->id,'Customer Attachment Updated in  - (  <a href="index.php?r=customer/customer/customer-view&id='.$model->id.'">'.$model->first_name.' '.$model->last_name.'</a> )');

					}

					return $this->redirect(['customer-view', 'id' => $_REQUEST['id']]);

			}

			

			// Add Notes

			if(!empty($_REQUEST['add_note_model'])){

				$nid = NoteModel::noteInsert($_REQUEST['id'],$this->entity_type);

				if(!empty($nid)){

					setcookie('inserted_notes'.$_REQUEST['id'],true,time()+7200);

				}

				//Send an Email

				$emailObj->sendNoteEmailTemplate($model->email,$model->customer_name,$this->getLoggedUserFullName()." <br>".$_REQUEST['notes'],'<a href="'.$_SESSION['base_url'].'?r=customer/customer/customer-view&id='.$model->id.'">'.$model->customer_name.'</a>');

				//SendEmail::sendLiveEmail($model->email,"A new note added by ".$this->getLoggedUserFullName()." ".$_REQUEST['notes'], false,'New Note Added');

				//Add History

			HistoryModel::historyInsert($this->entity_type,$model->id,'Customer Note Added into  - (  <a href="index.php?r=customer/customer/customer-view&id='.$model->id.'">'.$model->first_name.' '.$model->last_name.'</a> )');

				return $this->redirect(['customer-view', 'id' => $_REQUEST['id']]);

			}

			

			// Update Notes

			if(!empty($_REQUEST['edit_note_model'])){

				$nid = NoteModel::noteEdit();

				//Send an Email

				//SendEmail::sendLiveEmail($model->email,"Customer Notes Update by ".$this->getLoggedUserFullName()." ".$_REQUEST['notes'], false,'Customer Update');

				$emailObj->sendNoteUpdateEmailTemplate($model->email,$model->customer_name,$this->getLoggedUserFullName()." <br>".$_REQUEST['notes'],'<a href="'.$_SESSION['base_url'].'?r=customer/customer/customer-view&id='.$model->id.'">'.$model->customer_name.'</a>');

				//Add History

			HistoryModel::historyInsert($this->entity_type,$model->id,'Customer Note Updated in  - (  <a href="index.php?r=customer/customer/customer-view&id='.$model->id.'">'.$model->first_name.' '.$model->last_name.'</a> )');

				return $this->redirect(['customer-view', 'id' => $_REQUEST['id']]);

			}

            return $this->render('customer-view', [

                'model' => $model,

				'addressModel'=>$addressModel,

				'attachModel'=>$attachModelR,

				'noteModel'=>$noteModelR,

				'sub_address_model'=>$sub_address_model,

				'contact'=>$contact,

            ]);

        }

    }
	public function actionCustomerAllReports(){
		if(!Yii::$app->user->can('Report.CustomerAllReports')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		return $this->render('customer-all-reports');

	}

	public function actionCustomerTypeReport(){
		if(!Yii::$app->user->can('Report.CustomerType')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		return $this->render('customer-type-report');

	}

	public function actionCustomerCountryReport(){
		if(!Yii::$app->user->can('Report.CustomerCountry')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		return $this->render('customer-country-report');

	}

	public function actionNewCustomerReport(){
		if(!Yii::$app->user->can('Report.NewCustomer')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		return $this->render('new-customer-report');

	}
    /**

     * Deletes an existing Customer model.

     * If deletion is successful, the browser will be redirected to the 'index' page.

     * @param integer $id

     * @return mixed

     */

    public function actionDelete($id)

    {
		if(!Yii::$app->user->can('Customer.Delete')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		$this->findModel($id)->delete();
		$view = !empty($_GET['view'])?$_GET['view']:'';
		if(!empty($view)){
				 return $this->redirect(['index',
				 'view'=>$view]);
		}else{
			 return $this->redirect(['index']);
		}

   }



    /**

     * Finds the Customer model based on its primary key value.

     * If the model is not found, a 404 HTTP exception will be thrown.

     * @param integer $id

     * @return Customer the loaded model

     * @throws NotFoundHttpException if the model cannot be found

     */

    protected function findModel($id)

    {

        if (($model = Customer::findOne($id)) !== null) {

            return $model;

        } else {

            throw new NotFoundHttpException('The requested page does not exist.');

        }

    }

}


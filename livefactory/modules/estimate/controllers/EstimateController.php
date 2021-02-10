<?php

namespace livefactory\modules\estimate\controllers;

use Yii;
use livefactory\models\Estimate;
use livefactory\models\search\Estimate as EstimateSearch;
use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use livefactory\models\Tax;
use livefactory\models\Product;
use livefactory\models\EstimateDetails;
use livefactory\models\EstimateStatus;
use livefactory\models\Address;
use livefactory\models\Lead;
use livefactory\models\Customer;
use livefactory\models\Invoice;
use livefactory\models\InvoiceStatus;
use livefactory\models\InvoiceDetails;
use livefactory\models\HistoryModel;
use livefactory\models\User as UserDetail;
use livefactory\models\NoteModel;
use kartik\mpdf\Pdf;
use livefactory\models\SendEmail;
use \DateTime;
use \DateTimeZone;
/**
 * EstimateController implements the CRUD actions for Estimate model.
 */

class EstimateController extends Controller
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
	
	public static function getUserEmail($id){

		$userModel = UserDetail::findOne($id);	

		return $userModel->email;

	}

	public function getLoggedUserFullName(){
		$user = UserDetail::findOne(Yii::$app->user->identity->id);
		return $user->first_name." ".$user->last_name;	
	}
	public function getLoggedUserDetail(){
		$user = UserDetail::find()->where('id='.Yii::$app->user->identity->id)->asArray()->one();
		return $user;	
	}

    /**
     * Lists all Estimate models.
     * @return mixed
     */
    public function actionIndex()
    {
		if(!(Yii::$app->user->can('Customer.Estimate.Index') || Yii::$app->user->can('Sales.Estimate.Index'))){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}

        $searchModel = new EstimateSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Estimate model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		if(!(Yii::$app->user->can('Customer.Estimate.View') || Yii::$app->user->can('Sales.Estimate.View'))){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
        $model = $this->findModel($id);
		$estimateDetails = EstimateDetails::find()->where("estimate_id=".$id)->all();
		///var_dump($estimateDetails);
		$address = Address::find()->where("entity_id=".$model->customer_id." and entity_type='".$model->entity_type."' and is_primary=1")->one();
		
		if($model->entity_type == 'customer')
			$custlead = Customer::find()->where("id=".$model->customer_id)->one();
		else
			$custlead = Lead::find()->where("id=".$model->customer_id)->one();
		//$customer = Customer::findOne();
		
		///$message
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
       	 return $this->redirect(['view', 'id' => $model->id]);
        } else {
        return $this->render('view', 
			[
			'model' => $model,
			'estimateDetails'=>$estimateDetails,
			'address'=>$address,
			'custlead'=>$custlead
			]);
}
    }

	public function actionGenerateInvoice($id)
    {
		if(!Yii::$app->user->can('Invoice.Create')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
        $model = $this->findModel($id);
		$taxList = Tax::find()->all();
		foreach($taxList as $tax){
			$taxs[$tax->id]=$tax->tax_percentage;
		}
		$estimateDetails = EstimateDetails::find()->where("estimate_id=".$id)->all();
		
		$invoiceDetails = array();
		/* Copy All estimate details to invoice details */
		$count=0;
		foreach($estimateDetails as $row)
		{
			$invoiceDetails[$count]=new InvoiceDetails;
			
			$invoiceDetails[$count]->product_id = $row->product_id;
			$invoiceDetails[$count]->product_description = $row->product_description;
			$invoiceDetails[$count]->description = $row->description;
			$invoiceDetails[$count]->rate = $row->rate;
			$invoiceDetails[$count]->quantity = $row->quantity;
			$invoiceDetails[$count]->tax_id = $row->tax_id;
			$invoiceDetails[$count]->tax_amount = $row->tax_amount;
			$invoiceDetails[$count]->total = $row->total;
			$invoiceDetails[$count]->active = $row->active;

			$count++;
		}

		//var_dump($invoiceDetails);exit;

		$products = Product::find()->asArray()->all();
		$jSonList="[";
		$coma='';
		foreach($products as $pro){
			$jSonList .= $coma.'{"id":"'.$pro['id'].'","value":"'.$pro['product_name'].'","tax_id":"'.$pro['tax_id'].'","product_price":"'.$pro['product_price'].'"}';
			
			$coma=',';	
		}
		$jSonList.=']';

		$duplicate_model = new Invoice;
		if ($duplicate_model->invoice_status_id == '')
			$duplicate_model->invoice_status_id=InvoiceStatus::_NEW;	// Default status is New
        if ($duplicate_model->load(Yii::$app->request->post()) && $duplicate_model->save()) {

			$duplicate_model->invoice_number='INVCE'.str_pad($duplicate_model->id, 9, "0", STR_PAD_LEFT);
			
			$due_date = new DateTime($duplicate_model->date_due, new DateTimeZone(Yii::$app->params['TIME_ZONE']));
			$duplicate_model->date_due = $due_date->getTimestamp();
			
			$duplicate_model->invoice_status_id=InvoiceStatus::_UNPAID;// Unpaid
			
			if($duplicate_model->date_created != '')
			{
				$create_date = new DateTime($duplicate_model->date_created, new DateTimeZone(Yii::$app->params['TIME_ZONE']));
				$duplicate_model->date_created = $create_date->getTimestamp();
			}
			else
			{
				$duplicate_model->date_created = time();
			}
			$duplicate_model->save();
			
			$product_id = $_REQUEST['product_id'];
			$detail_id = $_REQUEST['detail_id'];
			$description = $_REQUEST['description'];
			$rate = $_REQUEST['rate'];
			$tax_id = $_REQUEST['tax_id'];
			$tax_amount = $_REQUEST['tax_amount'];
			$total = $_REQUEST['total'];
			$quantity = $_REQUEST['quantity'];
			
			if(count($description) > 0){
				for($i=0;$i<count($description);$i++){
					
					if($product_id[$i] ==''){
						$prodID = 0;
					}else{
						$prodID = $product_id[$i];
					}

					$objDetails = new InvoiceDetails();

					$objDetails->invoice_id = $duplicate_model->id;
					$objDetails->product_id = $prodID;
					$objDetails->product_description = $description[$i];		
					$objDetails->description = $description[$i];
					$objDetails->rate = $rate[$i];
					$objDetails->quantity=$quantity[$i];
					$objDetails->tax_id = intval($tax_id[$i]);
					$objDetails->tax_amount = $tax_amount[$i];
					$objDetails->total = $total[$i];
					$objDetails->active = 1;
					$objDetails->notes = '';
					$objDetails->added_at=time();
					$objDetails->updated_at=time();
					$objDetails->save();
				}
			}
            return $this->redirect(['/invoice/invoice/view', 'id' => $duplicate_model->id]);
        }


		$invoice_model = new Invoice;

		$invoice_model->generated_from_estimation = 1;
		$invoice_model->estimation_id = $model->id;
		if($model->entity_type == 'customer')
		{
			$invoice_model->customer_id = $model->customer_id;
		}
		else
		{
			$invoice_model->customer_id = Lead::findOne($model->customer_id)->customer_id;
		}
		$invoice_model->invoice_status_id = InvoiceStatus::_NEW;
		$invoice_model->linked_to_project = 0;
		$invoice_model->currency_id = $model->currency_id;
		$invoice_model->sub_total = $model->sub_total;
		$invoice_model->discount_type_id = $model->discount_type_id;
		$invoice_model->discount_figure = $model->discount_figure;
		$invoice_model->discount_amount = $model->discount_amount;
		$invoice_model->total_tax_amount = $model->total_tax_amount;
		$invoice_model->grand_total = $model->grand_total;
		$invoice_model->total_paid = 0;
		$invoice_model->notes = $model->notes;
		$invoice_model->date_created = time();
		
		$invoice_model->isNewRecord = true;
		return $this->render('../../../invoice/views/invoice/duplicate-invoice', [
			'model' => $invoice_model,
			'taxList'=>$taxList,
			'invoiceDetails'=>$invoiceDetails,
			'jSonList'=>$jSonList,
			'taxs'=>$taxs,
		]);
    }


	public function actionViewPdf($id)
    {
		if(!(Yii::$app->user->can('Customer.Estimate.View') || Yii::$app->user->can('Sales.Estimate.View'))){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
        $model = $this->findModel($id);
		$estimateDetails = EstimateDetails::find()->where("estimate_id=".$id)->all();
		$address = Address::find()->where("entity_id=".$model->customer_id." and entity_type='".$_REQUEST['entity_type']."' and is_primary=1")->one();
		
		if($_REQUEST['entity_type'] == 'customer')
			$custlead = Customer::find()->where("id=".$model->customer_id)->one();
		else
			$custlead = Lead::find()->where("id=".$model->customer_id)->one();

		$content = $this->renderPartial('view-pdf', 
			[
			'model' => $model,
			'estimateDetails'=>$estimateDetails,
			'address'=>$address,
			'custlead'=>$custlead
			]);
			$pdf = Yii::$app->pdf;

			$pdf->content = $content;
			return $pdf->output($content,"../pdf/".$model->estimation_code.".pdf",'F');
    }

    /**
     * Creates a new Estimate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		if(!(Yii::$app->user->can('Customer.Estimate.Create') || Yii::$app->user->can('Sales.Estimate.Create'))){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
        $model = new Estimate;
		//extract(Estimate::find()->select("max(id) max_estimation_code")->asArray()->one());
		//$model->estimation_code='ESTMT'.str_pad($max_estimation_code+1, 9, "0", STR_PAD_LEFT);

		$model->entity_type=$_REQUEST['entity_type'];

		//$model->date_issued = date('Y-m-d H:i:s');
		$model->date_issued = time();

		if($_REQUEST['customer_id'])
		{
			$model->customer_id=$_REQUEST['customer_id'];
		}
	
		$model->po_number='';
		$model->active='0';
		$model->estimate_status_id=EstimateStatus::_CREATED;	// Default status is created

//$model->load(Yii::$app->request->post());
//print_r($model);
		$taxList = Tax::find()->all();
		foreach($taxList as $tax){
			$taxs[$tax->id]=$tax->tax_percentage;
		}
		$products = Product::find()->asArray()->all();
		$jSonList="[";
		$coma='';
		foreach($products as $pro){
			$jSonList .= $coma.'{"id":"'.$pro['id'].'","value":"'.$pro['product_name'].'","tax_id":"'.$pro['tax_id'].'","product_price":"'.$pro['product_price'].'"}';
			$coma=',';	
		}
		$jSonList.=']';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
						//$detail = $_REQUEST['detail_id'];
			$model->estimation_code='ESTMT'.str_pad($model->id, 9, "0", STR_PAD_LEFT);
			$model->save();

			$product_id = $_REQUEST['product_id'];
			$description = $_REQUEST['description'];
			$rate = $_REQUEST['rate'];
			$tax_id = $_REQUEST['tax_id'];
			$tax_amount = $_REQUEST['tax_amount'];
			$total = $_REQUEST['total'];
			$quantity = $_REQUEST['quantity'];
			if(count($description) > 0){
				for($i=0;$i<count($description);$i++){
					/*if(is_null(Product::find()->where("product_name='".$description[$i]."'")->one())){
						$pObj = new Product();
						$pObj->product_name=$description[$i];
						$pObj->product_description=$description[$i];
						$pObj->product_category_id=0;
						$pObj->tax_id = intval($tax_id[$i]);
						$pObj->product_price=$rate[$i];
						$pObj->active=1;
						$pObj->added_at=time();
						$pObj->save();
					}
					if($pObj->id ==''){
						$projectID = $product_id[$i];
					}else{
						$projectID = $pObj->id;
					}*/
					if($product_id[$i] ==''){
						$projectID = 0;
					}else{
						$projectID = $product_id[$i];
					}

					$obj = new EstimateDetails();
					$obj->product_id = $projectID;
					$obj->product_description = $description[$i];
					$obj->rate = $rate[$i];
					$obj->description = $description[$i];
					$obj->tax_id = intval($tax_id[$i]);
					$obj->tax_amount = $tax_amount[$i];
					$obj->total = $total[$i];
					$obj->estimate_id = $model->id;
					$obj->quantity=$quantity[$i];
					$obj->added_at=time();
					$obj->save();
				}
			}
			
			
            return $this->redirect(['view', 'id' => $model->id, 'entity_type' => $model->entity_type]);
        } else {
            return $this->render('create', [
                'model' => $model,
				'taxList'=>$taxList,
				'jSonList'=>$jSonList,
				'taxs'=>$taxs
            ]);
        }
    }

    /**
     * Updates an existing Estimate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$model = $this->findModel($id);

		if(!(Yii::$app->user->can('Customer.Estimate.Update') || Yii::$app->user->can('Sales.Estimate.Update'))){
			 return $this->redirect(['view', 'id' => $_REQUEST['id'], 'entity_type' => $model->entity_type]);
			//throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}

		if(Yii::$app->user->identity->userType->type=="Customer")
		{
			if(!(isset($_REQUEST['rqst']) && ($_REQUEST['rqst'] == 'approve' || $_REQUEST['rqst'] == 'reject')))
			{
				 return $this->redirect(['view', 'id' => $_REQUEST['id'], 'entity_type' => $model->entity_type]);
			}
		}

        $taxList = Tax::find()->all();
		foreach($taxList as $tax){
			$taxs[$tax->id]=$tax->tax_percentage;
		}
		$estimateDetails = EstimateDetails::find()->where("estimate_id=".$id)->all();
		$products = Product::find()->asArray()->all();
		$customers = Customer::find()->asArray()->all();
		$jSonList="[";
		$coma='';
		foreach($products as $pro){
			$jSonList .= $coma.'{"id":"'.$pro['id'].'","value":"'.$pro['product_name'].'","tax_id":"'.$pro['tax_id'].'","product_price":"'.$pro['product_price'].'"}';
			
			$coma=',';	
		}
		$jSonList.=']';
		$cusjSonList="[";
		$coma1='';
		foreach($customers as $cus){
			$cusjSonList .= $coma1.'{"id":"'.$cus['id'].'","value":"'.$cus['customer_name'].'","email":"'.$cus['email'].'"}';
			
			$coma1=',';	
		}
		$cusjSonList.=']';

		if($_REQUEST['rqst'] == 'sendapprove'){

			$model->estimate_status_id = EstimateStatus::_SENTFORAPPROVAL;
				$model->update();

			if($model->entity_type == 'customer')
			{
				$uemail=$model->customer->email;
			}
			else
			{
				$uemail=$model->lead->email;
			}
			
			/*if (Yii::$app->params['SMTP_AUTH']=='Yes')
			{
				Yii::$app->mailer->compose()
					->setTo($uemail)
					->setFrom(Yii::$app->params['SYSTEM_EMAIL'])
					//->setCc($_REQUEST['cc'])
					->setSubject('Estimation attached for your approval')
					->setHtmlBody('Hi, Please find attached estimation copy for your approval.')
					->attach('../pdf/'.$model->estimation_code.".pdf")
					->send();
			}
			else
			{
				$email = new PHPMailer();
				$email->From      = Yii::$app->params['SYSTEM_EMAIL'];
				$email->FromName  = Yii::$app->params['company']['company_name'];
				$email->Subject   = 'Estimation attached for your approval';
				$email->Body      = 'Hi, Please find attached estimation copy for your approval.';
				$email->AddAddress($uemail );

				$file_to_attach = str_replace('web/index.php', 'pdf/', $_SESSION['base_url']).$model->estimation_code.'.pdf';

				$email->AddAttachment($file_to_attach, $model->estimation_code.'.pdf');

				$email->Send();
			}*/
			$body = 'Please find attached estimation copy for your approval.';
			$cc = false;
			$subject = 'Estimation attached for your approval';
			$from_system = true;
			$attachment = '../pdf/'.$model->estimation_code.'.pdf';
			$attachment_name = $model->estimation_code.'.pdf';
			SendEmail::sendLiveEmail ($uemail, $body, $cc, $subject, $from_system, $attachment, $attachment_name);
			
			HistoryModel::historyInsert($model->entity_type,$model->customer_id,'Estimate is sent for approval ( <a href="index.php?r=estimate/estimate/update&id='.$model->id.'">'.$model->estimation_code.'</a>)');

			//return $this->redirect(['update', 'id' => $_REQUEST['id']]);
			return $this->redirect(['view', 'id' => $model->id, 'entity_type' => $model->entity_type,'added' => 'yes']);
		}
		/*if(!empty($_GET['invoice'])){
			
		}*/
        if (Yii::$app->user->identity->userType->type!="Customer" && $model->load(Yii::$app->request->post()) && $model->save()) {

				$model->estimate_status_id = EstimateStatus::_CREATED;
				$model->update();


			$product_id = $_REQUEST['product_id'];
			$detail_id = $_REQUEST['detail_id'];
			$description = $_REQUEST['description'];
			$rate = $_REQUEST['rate'];
			$tax_id = $_REQUEST['tax_id'];
			$tax_amount = $_REQUEST['tax_amount'];
			$total = $_REQUEST['total'];
			$quantity = $_REQUEST['quantity'];
			if(count($description) > 0){
				for($i=0;$i<count($description);$i++){
					/*if(is_null(Product::find()->where("product_name='".$description[$i]."'")->one())){
						$pObj = new Product();
						$pObj->product_name=$description[$i];
						$pObj->product_description=$description[$i];
						$pObj->product_category_id=0;
						$pObj->product_price=$rate[$i];
						$pObj->tax_id = intval($tax_id[$i]);
						$pObj->active=1;
						$pObj->added_at=time();
						$pObj->save();
					}
					if($pObj->id ==''){
						$projectID = $product_id[$i];
					}else{
						$projectID = $pObj->id;
					}*/
					if($product_id[$i] ==''){
						$projectID = 0;
					}else{
						$projectID = $product_id[$i];
					}

					if(!empty($detail_id[$i])){
						$obj =  EstimateDetails::findOne($detail_id[$i]);
						$obj->product_id = $projectID;
						$obj->product_description = $description[$i];
						$obj->rate = $rate[$i];
						$obj->description = $description[$i];
						$obj->tax_id = intval($tax_id[$i]);
						$obj->tax_amount = $tax_amount[$i];
						$obj->total = $total[$i];
						//$obj->estimate_id = $model->id;
						$obj->quantity=$quantity[$i];
						$obj->updated_at=time();
						$obj->save();
					}else{
						$obj = new EstimateDetails();
						$obj->product_id = $projectID;
						$obj->product_description = $description[$i];
						$obj->rate = $rate[$i];
						$obj->description = $description[$i];
						$obj->tax_id = intval($tax_id[$i]);
						$obj->tax_amount = $tax_amount[$i];
						$obj->total = $total[$i];
						$obj->estimate_id = $model->id;
						$obj->quantity=$quantity[$i];
						$obj->added_at=time();
						$obj->save();
					}
				}
			}
			
			//Add History
			HistoryModel::historyInsert($model->entity_type,$model->customer_id,'Estimate is updated ( <a href="index.php?r=estimate/estimate/update&id='.$model->id.'">'.$model->estimation_code.'</a>)');

			$del_detail = $_REQUEST['del_detail'];
			if(!empty($del_detail)){
				$ids = explode(',',	$del_detail);
				foreach($ids as $id){
					 if (($edmodel = EstimateDetails::findOne($id)) !== null) {
							$edmodel->delete();
						}
				}
			}
            //return $this->redirect(['index']);
			//return $this->redirect(['update', 'id' => $_REQUEST['id']]);
			//return $this->redirect(['view', 'id' => $model->id, 'entity_type' => $model->entity_type]);
			return $this->redirect(['view', 'id' =>$_REQUEST['id'], 'entity_type' =>  $model->entity_type]);
        } else {

			if($_REQUEST['rqst'] == 'approve'){
				$model->estimate_status_id = EstimateStatus::_APPROVED;
				if($model->entity_type == 'customer')
			{
				$customerownerid = Customer::findOne($model->customer_id)->customer_owner_id;
				$uemail=$model->customer->email.','.$this->getUserEmail(Yii::$app->user->identity->id).','.$this->getUserEmail($customerownerid);
				
			}
			else
			{
				$leadownerid = Lead::findOne($model->customer_id)->lead_owner_id;
				$uemail=$model->lead->email.','.$this->getUserEmail(Yii::$app->user->identity->id).','.$this->getUserEmail($leadownerid);
			}
				$model->update();
				
				$description = "Estimate ".$model->estimation_code ." is approved by agent ".$this->getLoggedUserFullName(Yii::$app->user->identity->id)." <a href='index.php?r=estimate/estimate/update&id=".$model->id."'><span class='glyphicon glyphicon-file'></span></a>";
				$nid = NoteModel::note_Insert($model->id,$model->entity_type,$description);
				
				
		
			$body = 'Estimate '.$model->estimation_code.' has been approved by '.$this->getLoggedUserFullName(Yii::$app->user->identity->id);
			$cc = false;
			$subject = 'Estimate '.$model->estimation_code.' approved';
			$from_system = true;
			$attachment = '../pdf/'.$model->estimation_code.'.pdf';
			$attachment_name = $model->estimation_code.'.pdf';
			SendEmail::sendLiveEmail ($uemail, $body, $cc, $subject, $from_system, $attachment, $attachment_name);
				
				
				HistoryModel::historyInsert($model->entity_type,$model->customer_id,'Estimate is approved ( <a href="index.php?r=estimate/estimate/update&id='.$model->id.'">'.$model->estimation_code.'</a>)');

				if(Yii::$app->user->identity->userType->type!="Customer")
				{
					//return $this->redirect(['update', 'id' => $_REQUEST['id']]);
					return $this->redirect(['view', 'id' => $_REQUEST['id'], 'entity_type' =>  $model->entity_type]);
				}
				else
				{
					return $this->redirect(['view', 'id' => $_REQUEST['id'], 'entity_type' =>  $model->entity_type]);
				}
			}
			else if($_REQUEST['rqst'] == 'reject'){
				$model->estimate_status_id = EstimateStatus::_REJECTED;
				
				if($model->entity_type == 'customer')
			{
				$customerownerid = Customer::findOne($model->customer_id)->customer_owner_id;
				$uemail=$model->customer->email.','.$this->getUserEmail(Yii::$app->user->identity->id).','.$this->getUserEmail($customerownerid);
				
			}
			else
			{
				$leadownerid = Lead::findOne($model->customer_id)->lead_owner_id;
				$uemail=$model->lead->email.','.$this->getUserEmail(Yii::$app->user->identity->id).','.$this->getUserEmail($leadownerid);
			}
				
				$model->update();
				
				$description = "Estimate ".$model->estimation_code ." is rejected by agent ".$this->getLoggedUserFullName(Yii::$app->user->identity->id)." <a href='index.php?r=estimate/estimate/update&id=".$model->id."'><span class='glyphicon glyphicon-file'></span></a>";
				$nid = NoteModel::note_Insert($model->id,$model->entity_type,$description);
				
			$body = 'Estimate '.$model->estimation_code.' status has been updated to REJECTED ';
			$cc = false;
			$subject = 'Estimate '.$model->estimation_code.' Rejected';
			$from_system = true;
			$attachment = '../pdf/'.$model->estimation_code.'.pdf';
			$attachment_name = $model->estimation_code.'.pdf';
			SendEmail::sendLiveEmail ($uemail, $body, $cc, $subject, $from_system, $attachment, $attachment_name);
				
				HistoryModel::historyInsert($model->entity_type,$model->customer_id,'Estimate is rejected ( <a href="index.php?r=estimate/estimate/update&id='.$model->id.'">'.$model->estimation_code.'</a>)');
				
				if(Yii::$app->user->identity->userType->type!="Customer")
				{
					//return $this->redirect(['update', 'id' => $_REQUEST['id']]);
					return $this->redirect(['view', 'id' => $_REQUEST['id'], 'entity_type' =>  $model->entity_type]);
				}
				else
				{
					return $this->redirect(['view', 'id' => $_REQUEST['id'], 'entity_type' =>  $model->entity_type]);
				}
			}
			
			if(Yii::$app->user->identity->userType->type!="Customer")
			{
				return $this->render('update', [
					'model' => $model,
					'taxList'=>$taxList,
					'estimateDetails'=>$estimateDetails,
					'jSonList'=>$jSonList,
					'cusjSonList'=>$cusjSonList,
					'taxs'=>$taxs,
				]);
			}
			else
			{
				return $this->redirect(['view', 'id' => $_REQUEST['id'], 'entity_type' =>  $model->entity_type]);
			}
        }

		if(Yii::$app->user->identity->userType->type!="Customer")
		{
			return $this->render('update', [
				'model' => $model,
				'taxList'=>$taxList,
				'estimateDetails'=>$estimateDetails,
				'jSonList'=>$jSonList,
				'cusjSonList'=>$cusjSonList,
				'taxs'=>$taxs,
			]);
		}
		else
		{
			return $this->redirect(['view', 'id' => $_REQUEST['id'], 'entity_type' =>  $model->entity_type]);
		}
    }
	public function actionDuplicateEstimate($id)
    {
        $model = $this->findModel($id);
		$taxList = Tax::find()->all();
		foreach($taxList as $tax){
			$taxs[$tax->id]=$tax->tax_percentage;
		}
		$estimateDetails = EstimateDetails::find()->where("estimate_id=".$id)->all();
		$products = Product::find()->asArray()->all();
		$jSonList="[";
		$coma='';
		foreach($products as $pro){
			$jSonList .= $coma.'{"id":"'.$pro['id'].'","value":"'.$pro['product_name'].'","tax_id":"'.$pro['tax_id'].'","product_price":"'.$pro['product_price'].'"}';
			
			$coma=',';	
		}
		$jSonList.=']';
		$model->id=NULL;
		$model->estimation_code='';
		$duplicate_model = new Estimate;
        if ($duplicate_model->load(Yii::$app->request->post()) && $duplicate_model->save()) {
			$product_id = $_REQUEST['product_id'];
			$detail_id = $_REQUEST['detail_id'];
			//$description = $_REQUEST['description'];
			$rate = $_REQUEST['rate'];
			$tax_id = $_REQUEST['tax_id'];
			$tax_amount = $_REQUEST['tax_amount'];
			$total = $_REQUEST['total'];
			$quantity = $_REQUEST['quantity'];
			if(count($description) > 0){
				for($i=0;$i<count($description);$i++){
					if(is_null(Product::find()->where("product_name='".$description[$i]."'")->one())){
						$pObj = new Product();
						$pObj->product_name=$description[$i];
						$pObj->product_description=$description[$i];
						$pObj->product_category_id=0;
						$pObj->product_price=$rate[$i];
						$pObj->tax_id = intval($tax_id[$i]);
						$pObj->active=1;
						$pObj->added_at=time();
						$pObj->save();
					}
					if($pObj->id ==''){
						$projectID = $product_id[$i];
					}else{
						$projectID = $pObj->id;
					}
					/*if(!empty($detail_id[$i])){
						$obj =  EstimateDetails::findOne($detail_id[$i]);
						$obj->product_id = $projectID;
						$obj->product_description = $description[$i];
						$obj->rate = $rate[$i];
						$obj->description = $description[$i];
						$obj->tax_id = intval($tax_id[$i]);
						$obj->tax_amount = $tax_amount[$i];
						$obj->total = $total[$i];
						$obj->estimate_id = $model->id;
						$obj->quantity=$quantity[$i];
						$obj->updated_at=time();
						$obj->save();
					}else{*/
						$obj = new EstimateDetails();
						$obj->product_id = $projectID;
						$obj->product_description = $description[$i];
						$obj->rate = $rate[$i];
						$obj->description = $description[$i];
						$obj->tax_id = intval($tax_id[$i]);
						$obj->tax_amount = $tax_amount[$i];
						$obj->total = $total[$i];
						$obj->estimate_id = $duplicate_model->id;
						$obj->quantity=$quantity[$i];
						$obj->added_at=time();
						$obj->save();
					//}
				}
			}
			//var_dump($model->errors);
            return $this->redirect(['update', 'id' => $duplicate_model->id]);
        } else {
            return $this->render('duplicate-estimate', [
                'model' => $model,
				'taxList'=>$taxList,
				'estimateDetails'=>$estimateDetails,
				'jSonList'=>$jSonList,
				'taxs'=>$taxs,
            ]);
        }
    }

    /**
     * Deletes an existing Estimate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		if(!(Yii::$app->user->can('Customer.Estimate.Delete') || Yii::$app->user->can('Sales.Estimate.Delete'))){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		$model = $this->findModel($id);

        $this->findModel($id)->delete();

        return $this->redirect(['index', 'entity_type'=> $model->entity_type]);
    }
	public function actionAjaxDuplicateCheckInsertCase($code,$id){
		$model = Estimate::find()->where("estimation_code='".$code."' and id !='".$id."'")->asArray()->all();
		echo count($model);
	}
    /**
     * Finds the Estimate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Estimate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		if(Yii::$app->user->identity->userType->type=="Customer")
		{
			if (($model = Estimate::find()->where("id=$id and customer_id=".Yii::$app->user->identity->entity_id)->one()) !== null) {
			return $model;
			} else {
				throw new NotFoundHttpException('The requested page does not exist.');
			}
		}
		else
		{
			if (($model = Estimate::findOne($id)) !== null) {
				return $model;
			} else {
				throw new NotFoundHttpException('The requested page does not exist.');
			}
		}
    }
	
	public function actionDownload($id)
    {
		if(!Yii::$app->user->can('Sales.Estimate.Index') || !Yii::$app->user->can('Customer.Estimate.Index')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		$model = $this->findModel($id); 
		$path = Yii::getAlias('@livecrm').'/pdf/'.$model->estimation_code.'.pdf';
		if (file_exists($path)) {
        return Yii::$app->response->sendFile($path);
			}else{
		throw new NotFoundHttpException('The requested estimate file does not exist.');
			}
	}
	
	
}

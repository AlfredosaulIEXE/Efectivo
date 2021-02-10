<?php

namespace livefactory\modules\invoice\controllers;

use Yii;
use livefactory\models\Estimate;
use livefactory\models\EstimateDetails;  
use livefactory\models\PaymentDetails; 
use livefactory\models\Invoice;
use livefactory\models\InvoiceStatus;
use livefactory\models\InvoiceDetails;
use livefactory\models\Tax;
use livefactory\models\Product;
use livefactory\models\Address;
use livefactory\models\Customer;
use livefactory\models\SendEmail;
use livefactory\models\search\Invoice as InvoiceSearch;
use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \DateTime;
use \DateTimeZone;

/**
 * InvoiceController implements the CRUD actions for Invoice model.
 */
class InvoiceController extends Controller
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

    /**
     * Lists all Invoice models.
     * @return mixed
     */
    public function actionIndex()
    {
		if(!Yii::$app->user->can('Invoice.Index')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
        $searchModel = new InvoiceSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Invoice model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		if(!Yii::$app->user->can('Invoice.View')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
        $model = $this->findModel($id);
		$address = Address::find()->where("entity_id=".$model->customer_id." and entity_type='customer'")->one();
		$taxList = Tax::find()->all();
		foreach($taxList as $tax){
			$taxs[$tax->id]=$tax->tax_percentage;
		}
		$invoiceDetails = InvoiceDetails::find()->where("invoice_id=".$id)->all();
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
		if(!empty($_REQUEST['uemail'])){
			$uemail = $_REQUEST['uemail'];
			$from_system = true;
			$body = $_REQUEST['email_body'];
			$cc = $_REQUEST['cc'];
			$subject = $_REQUEST['subject'];		
			$attachment = '../pdf/'.$model->invoice_number.'.pdf';
			$attachment_name = $model->invoice_number.'.pdf';

			SendEmail::sendLiveEmail ($uemail, $body, $cc, $subject, $from_system, $attachment, $attachment_name);

			/*Yii::$app->mailer->compose()
            ->setTo($_REQUEST['uemail'])
            ->setFrom(Yii::$app->params['SYSTEM_EMAIL'])
			->setCc($_REQUEST['cc'])
            ->setSubject($_REQUEST['subject'])
            ->setHtmlBody($_REQUEST['email_body'])
			->attach('../pdf/'.$model->invoice_number.".pdf")
            ->send();	*/
		}

		///var_dump($_REQUEST);exit;

		if(!empty($_REQUEST['ipayment']))
		{
			$ipDet = new PaymentDetails();

			$ipDet->invoice_id=$model->id;
			$ipDet->amount=$_REQUEST['ipayment'];
			$ipDet->notes=$_REQUEST['inotes'];
			$ipDet->payment_method='Paypal';
			$ipDet->payment_date=time();

			$ipDet->save();

			$model->total_paid = floatval($model->total_paid) + floatval($_REQUEST['ipayment']);

			if (floatval($model->total_paid) < floatval($model->grand_total))
			{
			$model->invoice_status_id=InvoiceStatus::_PARTIALLYPAID; //Partially Paid
			/* email for partial payment */
			$uemail = $model->customer->email;	
			$body = 'Thankyou for your payment of '.$_REQUEST['ipayment'].''.$model->currency->alphabetic_code.' for Invoice '.$model->invoice_number;
			$cc = false;
			$subject = 'Thankyou for your payment '.$model->invoice_number;
			$from_system = true;
			//$attachment = '../pdf/'.$model->estimation_code.'.pdf';
			//$attachment_name = $model->estimation_code.'.pdf';
			SendEmail::sendLiveEmail ($uemail, $body, $cc, $subject, $from_systems);
			}
			else
			{
				$model->invoice_status_id=InvoiceStatus::_PAID; // Paid
			/* email for total outstanding payment */	
			$uemail = $model->customer->email;	
			$body = 'Thankyou for payment of your total outstanding amount of  '.$_REQUEST['ipayment'].''.$model->currency->alphabetic_code.' for Invoice '.$model->invoice_number;
			$cc = false;
			$subject = 'Thankyou for your payment '.$model->invoice_number;
			$from_system = true;
			//$attachment = '../pdf/'.$model->estimation_code.'.pdf';
			//$attachment_name = $model->estimation_code.'.pdf';
			SendEmail::sendLiveEmail ($uemail, $body, $cc, $subject, $from_systems);
			}

			if(!$model->save())
			{
				$ipDet->delete();
			}

			return $this->redirect(['view', 'id' => $model->id]);
		}

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
        } else {
			return $this->render('view', 
			[
			'model' => $model,
			'invoiceDetails'=>$invoiceDetails,
			'address'=>$address,
			'taxList'=>$taxList,
			'invoiceDetails'=>$invoiceDetails,
			'jSonList'=>$jSonList,
			'cusjSonList'=>$cusjSonList,
			'taxs'=>$taxs,
			]);
		}
		
    }

	public function actionPaypalPayment()
	{
		$id = $_GET['id'];
		$model = $this->findModel($id);
	
		if(Yii::$app->params['IS_DEMO'] == "Yes")
		{
			$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		}
		else
		{
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		}
		
		//var_dump($_REQUEST);
		?>
		<style>
			H1 {text-align:center;}
			P {text-align:center;}
		</style>
		
		<H1> <?=Yii::t('app', 'Please wait while we redirect you to Paypal Payment Gateway')?> </text></H1>
		<P> <?=Yii::t('app', 'Do not refresh this page while transaction is in progress')?> </text></P>
		<form id = "paypal_checkout" action = "<?=$paypal_url?>" method = "post">
			<input name = "cmd" value = "_cart" type = "hidden">
			<input name = "upload" value = "1" type = "hidden">
			<input name = "no_note" value = "0" type = "hidden">
			<input name = "tax" value = "0" type = "hidden">
			<input name = "rm" value = "0" type = "hidden"> <!-- Return method is GET -->
		 
			<input name = "business" value = "<?=Yii::$app->params['PAYPAL_USER'] ?>" type = "hidden">
			<input name = "handling_cart" value = "0" type = "hidden">
			<input name = "currency_code" value = "<?=$model->currency->alphabetic_code?>" type = "hidden">
			<input name = "return" value = "<?=$_SESSION['base_url']?>?r=invoice/invoice/view&id=<?=$model->id?>&ipayment=<?=$_REQUEST['amount_1']?>" type = "hidden">
			<input name = "cbt" value = "Return back to LiveCRM" type = "hidden"> <!-- Deprecated now -->
			<input name = "cancel_return" value = "<?=$_SESSION['base_url']?>?r=invoice/invoice/view&id=<?=$model->id?>" type = "hidden">
			<input name = "custom" value = "" type = "hidden">

			<div id = "item_1" class = "itemwrap">
				<input name = "item_name_1" value = "Payment of invoice: <?=$model->invoice_number?>" type = "hidden">
				<input name = "quantity_1" value = "1" type = "hidden">
				<input name = "amount_1" id = "amount_1" value = "<?=$_REQUEST['amount_1']?>" class="form-control amount_1" type="hidden">
				<input name = "shipping_1" value = "0" type = "hidden">
			</div>
		</form>

		<script>
			document.getElementById("paypal_checkout").submit();
		</script>

		<?php
	}

	public function actionViewPdf($id)
    {
		if(!Yii::$app->user->can('Invoice.View')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
        $model = $this->findModel($id);
		$invoiceDetails = InvoiceDetails::find()->where("invoice_id=".$id)->all();
		$address = Address::find()->where("entity_id=".$model->customer_id." and entity_type='customer' and is_primary=1")->one();

		$custlead = Customer::find()->where("id=".$model->customer_id)->one();

		$content = $this->renderPartial('view-pdf', 
			[
			'model' => $model,
			'invoiceDetails'=>$invoiceDetails,
			'address'=>$address,
			'custlead'=>$custlead
			]);
			$pdf = Yii::$app->pdf;
			$pdf->content = $content;
			return $pdf->output($content,"../pdf/".$model->invoice_number.".pdf",'F');
    }

	public function actionCreate() 
    {
		if(!Yii::$app->user->can('Invoice.Create')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		$model = new Invoice;
		$InvoiceDetails = new InvoiceDetails;

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

		if ($model->invoice_status_id == '')
			$model->invoice_status_id=InvoiceStatus::_NEW;	// Default status is New

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$model->invoice_number='INVCE'.str_pad($model->id, 9, "0", STR_PAD_LEFT);
			
			if(Yii::$app->params['TAX_NUMBER_PREFIX_FORMAT'])
			$model->tax_number=Yii::$app->params['TAX_NUMBER_PREFIX_FORMAT'].str_pad($model->id, 8, "0", STR_PAD_LEFT);
			else
			$model->tax_number='TAX'.str_pad($model->id, 8, "0", STR_PAD_LEFT);
			
			$due_date = new DateTime($model->date_due, new DateTimeZone(Yii::$app->params['TIME_ZONE']));
			$model->date_due = $due_date->getTimestamp();
			
			$model->invoice_status_id=InvoiceStatus::_UNPAID;// Unpaid
			
			if($model->date_created != '')
			{
				$create_date = new DateTime($model->date_created, new DateTimeZone(Yii::$app->params['TIME_ZONE']));
				$model->date_created = $create_date->getTimestamp();
			}
			else
			{
				$model->date_created = time();
			}
			$model->save();
			
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

					$objDetails->invoice_id = $model->id;
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
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render("create", [
								'model' => $model,
								'taxList'=>$taxList,
								'InvoiceDetails'=>$InvoiceDetails,
								'jSonList'=>$jSonList,
								'taxs'=>$taxs,
							]);
        }
    }
	
	public function actionDuplicateInvoice($id)
    {
		if(!Yii::$app->user->can('Invoice.Create')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
        $model = $this->findModel($id);
		$taxList = Tax::find()->all();
		foreach($taxList as $tax){
			$taxs[$tax->id]=$tax->tax_percentage;
		}
		$invoiceDetails = InvoiceDetails::find()->where("invoice_id=".$id)->all();
		$products = Product::find()->asArray()->all();
		$jSonList="[";
		$coma='';
		foreach($products as $pro){
			$jSonList .= $coma.'{"id":"'.$pro['id'].'","value":"'.$pro['product_name'].'","tax_id":"'.$pro['tax_id'].'","product_price":"'.$pro['product_price'].'"}';
			
			$coma=',';	
		}
		$jSonList.=']';
		$model->id=NULL;
		$model->isNewRecord = true;
		$model->invoice_number='';

		$duplicate_model = new Invoice;
		if ($duplicate_model->invoice_status_id == '')
			$duplicate_model->invoice_status_id=InvoiceStatus::_NEW;	// Default status is New
        if ($duplicate_model->load(Yii::$app->request->post()) && $duplicate_model->save()) {

			$duplicate_model->invoice_number='INVCE'.str_pad($duplicate_model->id, 9, "0", STR_PAD_LEFT);

			if(Yii::$app->params['TAX_NUMBER_PREFIX_FORMAT'])
			$duplicate_model->tax_number=Yii::$app->params['TAX_NUMBER_PREFIX_FORMAT'].str_pad($duplicate_model->id, 8, "0", STR_PAD_LEFT);
			else
			$duplicate_model->tax_number='TAX'.str_pad($duplicate_model->id, 8, "0", STR_PAD_LEFT);
			
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
            return $this->redirect(['view', 'id' => $duplicate_model->id]);
        } else {
			$model->isNewRecord = true;
			$model->invoice_status_id=InvoiceStatus::_NEW; //New
            return $this->render('duplicate-invoice', [
                'model' => $model,
				'taxList'=>$taxList,
				'invoiceDetails'=>$invoiceDetails,
				'jSonList'=>$jSonList,
				'taxs'=>$taxs,
            ]);
        }
    }

    /**
     * Updates an existing Invoice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		if(!Yii::$app->user->can('Invoice.Update')){
			//throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
			return $this->redirect(['view', 'id' => $_REQUEST['id']]);
		}
        $model = $this->findModel($id);

		if ($model->invoice_status_id != InvoiceStatus::_UNPAID) //Unpaid
		{
			return $this->redirect(['view', 'id'=>$model->id]);
		}

		$taxList = Tax::find()->all();
		foreach($taxList as $tax){
			$taxs[$tax->id]=$tax->tax_percentage;
		}
		$invoiceDetails = InvoiceDetails::find()->where("invoice_id=".$id)->all();
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

		if($_REQUEST['edit'] == 'cancel'){
			$model->invoice_status_id=InvoiceStatus::_CANCELLED; //Canceled
			$model->save();
			return $this->redirect(['view', 'id'=>$model->id]);
		}

		if(!empty($_REQUEST['uemail'])){
			Yii::$app->mailer->compose()
            ->setTo($_REQUEST['uemail'])
            ->setFrom(Yii::$app->params['SYSTEM_EMAIL'])
			->setCc($_REQUEST['cc'])
            ->setSubject($_REQUEST['subject'])
            ->setHtmlBody($_REQUEST['email_body'])
			->attach('../pdf/'.$model->invoice_number.".pdf")
            ->send();	
		}
		
		//$date_due_request = $_REQUEST['Invoice']['date_due'];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if (floatval($model->total_paid) == 0)
			{
				$model->invoice_status_id=InvoiceStatus::_UNPAID; //Unpaid
			}
			else if (floatval($model->total_paid) < floatval($model->grand_total))
			{
				$model->invoice_status_id=InvoiceStatus::_PARTIALLYPAID; //Partially Paid
			}
			else
			{
				$model->invoice_status_id=InvoiceStatus::_PAID; //Paid
			}
			$create_date = new DateTime($_REQUEST['Invoice']['date_created'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
			$model->date_created = $create_date->getTimestamp();

			$due_date = new DateTime($_REQUEST['Invoice']['date_due'], new DateTimeZone(Yii::$app->params['TIME_ZONE']));
			$model->date_due = $due_date->getTimestamp();

			$model->save();

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
						$obj =  InvoiceDetails::findOne($detail_id[$i]);
						$obj->product_id = $projectID;
						$obj->product_description = $description[$i];
						$obj->rate = $rate[$i];
						$obj->description = $description[$i];
						$obj->tax_id = intval($tax_id[$i]);
						$obj->tax_amount = $tax_amount[$i];
						$obj->total = $total[$i];
						//$obj->invoice_id = $model->id;
						$obj->quantity=$quantity[$i];
						$obj->updated_at=time();
						$obj->save();
					}else{
						$obj = new InvoiceDetails();
						$obj->product_id = $projectID;
						$obj->product_description = $description[$i];
						$obj->rate = $rate[$i];
						$obj->description = $description[$i];
						$obj->tax_id = intval($tax_id[$i]);
						$obj->tax_amount = $tax_amount[$i];
						$obj->total = $total[$i];
						$obj->invoice_id = $model->id;
						$obj->quantity=$quantity[$i];
						$obj->added_at=time();
						$obj->active=0;
						$obj->save();
					}
				}
			}
			$del_detail = $_REQUEST['del_detail'];
			if(!empty($del_detail)){
				$ids = explode(',',	$del_detail);
				foreach($ids as $id){
					 if (($model = InvoiceDetails::findOne($id)) !== null) {
							$model->delete();
						}
				}
			}
           // return $this->redirect(['view', 'id'=>$model->id]);
			return $this->redirect(['view', 'id' =>$_REQUEST['id']]);
        } else {
			//$model->date_created=date_format(date_create($model->date_created), 'Y/m/d');
			//$model->date_due=date_format(date_create($model->date_due), 'Y/m/d');
            return $this->render('update', [
                'model' => $model,
				'taxList'=>$taxList,
				'invoiceDetails'=>$invoiceDetails,
				'jSonList'=>$jSonList,
				'cusjSonList'=>$cusjSonList,
				'taxs'=>$taxs,
				
            ]);
        }
    }

    /**
     * Deletes an existing Invoice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		if(!Yii::$app->user->can('Invoice.Delete')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		if(Yii::$app->user->identity->userType->type=="Customer")
		{
			if (($model = Invoice::find()->where("id=$id and customer_id=".Yii::$app->user->identity->entity_id)->one()) !== null) {
			return $model;
			} else {
				throw new NotFoundHttpException('The requested page does not exist.');
			}
		}
		else
		{
			if (($model = Invoice::findOne($id)) !== null) {
				return $model;
			} else {
				throw new NotFoundHttpException('The requested page does not exist.');
			}
		}
    }
	
	public function actionAjaxDuplicateCheckInsertCase($code,$id){
		$model = Invoice::find()->where("invoice_number='".$code."' and id !='".$id."'")->asArray()->all();
		echo count($model);
	}
	
	 public function actionDownload($id)
    {
		if(!Yii::$app->user->can('Invoice.Index')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		$model = $this->findModel($id); 
		$path = Yii::getAlias('@livecrm').'/pdf/'.$model->invoice_number.'.pdf';
		if (file_exists($path)) {
        return Yii::$app->response->sendFile($path);
			}else{
		throw new NotFoundHttpException('The requested invoice file does not exist.');
			}
	}
}	

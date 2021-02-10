<?php

namespace livefactory\modules\sales\controllers;
use livefactory\models\Lead as LeadModel;
use livefactory\models\Appointment;
use livefactory\models\AppointmentModel;
use livefactory\models\LeadRecycle;
use livefactory\models\Office;
use livefactory\models\Payment;
use livefactory\models\PaymentModel;
use livefactory\models\PaymentRecycle;
use livefactory\models\search\LeadType;
use livefactory\models\UnitGenerate;
use livefactory\models\User;
use livefactory\models\UserActivity;
use Yii;
use livefactory\models\Lead;
use livefactory\models\LeadStatus;
use livefactory\models\search\Lead as LeadSearch;
use livefactory\models\search\LeadRecycle as LeadSearchRecycle;
use livefactory\models\search\Appointment as AppointmentSearch;
use livefactory\controllers\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use livefactory\models\search\UserType as UserTypeSearch;
use livefactory\models\ImageUpload;
use livefactory\models\SendEmail;
use livefactory\models\AddressModel;
use livefactory\models\ContactModel;
use livefactory\models\HistoryModel;
use livefactory\models\Address;
use livefactory\models\Contact;
use livefactory\models\Customer;
use livefactory\models\Estimate;
use livefactory\models\SalesReport;
use livefactory\models\User as UserDetail;
use livefactory\models\AuthAssignment;
use livefactory\models\search\CommonModel as SessionVerification;

use livefactory\models\NoteModel;
use livefactory\models\FileModel;
use livefactory\models\AssignmentHistoryModel;

use livefactory\models\File;
use livefactory\models\Note;
use livefactory\models\History;
use livefactory\models\AssignmentHistory;
use yii\db\Query;
use livefactory\models\search\History as HistorySearch;

/**
 * LeadController implements the CRUD actions for Lead model.
 */
class LeadController extends Controller
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

	public $entity_type='lead';

    public $print_type = null;

	public static function getUserEmail($id){

		$userModel = UserDetail::findOne($id);

		return $userModel->email;

	}

	public static function getUserFullName($id){

		$user = UserDetail::findOne($id);



		return $user->first_name." ".$user->last_name;

	}
	public static function getAliasUser($id){
	    $user = UserDetail::findOne($id);

	    return $user->alias;
    }

	public static function getLoggedUserFullName(){

		$user = UserDetail::findOne(Yii::$app->user->identity->id);

		return $user->first_name." ".$user->last_name;

	}

	public static function getLoggedUserDetail(){

		$user = UserDetail::find()->where('id='.Yii::$app->user->identity->id)->asArray()->one();

		return $user;

	}

    /**
     * Lists all Lead models.
     * @return mixed
     */
    /*Recycle lead*/
    public function actionRecycle($id)
    {
        $model = $this->findModelRecycle($id);
        $recyclemodel = new Lead;
        $recyclemodel->id = $model->id;
        $recyclemodel->office_id = $model->office_id;
        $recyclemodel->c_control = $model->c_control;
        $recyclemodel->c_contract = $model->c_contract;
        $recyclemodel->lead_name = $model->lead_name;
        $recyclemodel->lead_master_status_id = $model->lead_master_status_id;
        $recyclemodel->lead_insurance_id = $model->lead_insurance_id;
        $recyclemodel->lead_description = $model->lead_description;
        $recyclemodel->lead_type_id =$model->lead_type_id;
        $recyclemodel->lead_owner_id = $model->lead_owner_id;
        $recyclemodel->lead_status_id = $model->lead_status_id;
        $recyclemodel->lead_source_id = $model->lead_source_id;
        $recyclemodel->email = $model->email;
        $recyclemodel->first_name = $model->first_name;
        $recyclemodel->last_name = $model->last_name;
        $recyclemodel->middle_name = $model->middle_name;
        $recyclemodel->phone = $model->phone;
        $recyclemodel->mobile = $model->mobile;
        $recyclemodel->product_id = $model->product_id;
        $recyclemodel->loan_amount = $model->loan_amount;
        $recyclemodel->loan_interest = $model->loan_interest;
        $recyclemodel->loan_commission = $model->loan_commission;
        $recyclemodel->loan_term = $model->loan_term;
        $recyclemodel->payed = $model->payed;
        $recyclemodel->rfc = $model->rfc;
        $recyclemodel->curp = $model->curp;
        $recyclemodel->age = $model->age;
        $recyclemodel->birthdate = $model->birthdate;
        $recyclemodel->place_of_birth = $model->place_of_birth;
        $recyclemodel->civil_status = $model->civil_status;
        $recyclemodel->civil_status_regime = $model->civil_status_regime;
        $recyclemodel->spouse_job = $model->spouse_job;
        $recyclemodel->spouse_monthly_income = $model->spouse_monthly_income;
        $recyclemodel->monthly_income = $model->monthly_income;
        $recyclemodel->monthly_income2 = $model->monthly_income2;
        $recyclemodel->monthly_expenses = $model->monthly_expenses;
        $recyclemodel->home_status = $model->home_status;
        $recyclemodel->bureau_status = $model->bureau_status;
        $recyclemodel->bureau_status_desc = $model->bureau_status_desc;
        $recyclemodel->active_loans = $model->active_loans;
        $recyclemodel->economic_dep = $model->economic_dep;
        $recyclemodel->company_name = $model->company_name;
        $recyclemodel->job = $model->job;
        $recyclemodel->labor_old = $model->labor_old;
        $recyclemodel->c_cuenta = $model->c_cuenta;
        $recyclemodel->contract_date = $model->contract_date;
        $recyclemodel->valid_admin = $model->valid_admin;
        $recyclemodel->valid_manager = $model->valid_manager;
        $recyclemodel->valid_sales = $model->valid_sales;
        $recyclemodel->service_status_id = $model->service_status_id;
        $recyclemodel->service_owner_id = $model->service_owner_id;
        $recyclemodel->payment_folio = $model->payment_folio;
        $recyclemodel->added_at = $model->added_at;
        $recyclemodel->updated_at = $model->updated_at;
        $recyclemodel->converted_at = $model->converted_at;
        $recyclemodel->customer_id = $model->customer_id;
        $recyclemodel->user_id = $model->user_id;
        $recyclemodel->progress = $model->progress;
        $recyclemodel->active = $model->active;
        $recyclemodel->save();
        $payments = \livefactory\models\PaymentRecycle::find()->where('entity_id = ' . $model->id)->all();
        foreach ($payments as $payment) {
            $recyclepayment = new Payment;
            $recyclepayment->id = $payment->id;
            $recyclepayment->generator_id = $payment->generator_id;
            $recyclepayment->co_generator_id = $payment->co_generator_id;
            $recyclepayment->amount = $payment->amount;
            $recyclepayment->total_due = $payment->total_due;
            $recyclepayment->note = $payment->note;
            $recyclepayment->type = $payment->type;
            $recyclepayment->date = $payment->date;
            $recyclepayment->code = $payment->code;
            $recyclepayment->origin = $payment->origin;
            $recyclepayment->folio = $payment->folio;
            $recyclepayment->received = $payment->received;
            $recyclepayment->entity_id = $payment->entity_id;
            $recyclepayment->entity_type = $payment->entity_type;
            $recyclepayment->file_id = $payment->file_id;
            $recyclepayment->added_at = $payment->added_at;
            $recyclepayment->updated_at = $payment->updated_at;
            $recyclepayment->save();
            $payment->delete();
        }
        HistoryModel::historyInsert('log', HistoryModel::ACTION_DELETE, 'Lead eliminado <strong>'.$recyclemodel->c_control .'</strong>' );
        $model->save();
        $model->delete();
        //$model->active = 1;
        //$model->save();
        // $this->findModel($id)->delete();

        return $this->redirect(['deleted']);
    }

    public function actionMigrateone($id)
    {
        $managers = [];
        foreach (User::find()->join('LEFT JOIN', 'auth_assignment', 'auth_assignment.user_id = tbl_user.id')
                     ->where("auth_assignment.item_name = 'Customer.Service'")
                     ->asArray()->all() as $key => $item) {
            $managers[$item['office_id']][] = $item['id'];
        }
        $model = $this->findModel($id);
        //$model->save();
        // $this->findModel($id)->delete();

        return $this->redirect(['review']);
    }


    public function actionMigratemulti(){


        $searchModel = new LeadSearch;
        //$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        //connection to db
        $connection = \Yii::$app->db;
        $clientservice=[];
        //$dataProviderBox=$searchModel->searchLead();
        $managers = [];
        foreach (User::find()->join('LEFT JOIN', 'auth_assignment', 'auth_assignment.user_id = tbl_user.id')
                     ->where("auth_assignment.item_name = 'Customer.Service'")
                     ->asArray()->all() as $key => $item) {
            $managers[$item['office_id']][] = $item['id'];
        }
        if(!empty($_REQUEST['multiple_migrate'])){
            if(Yii::$app->user->can('Customer.Director')) {

                $rows = $_REQUEST['selection'];
                foreach ($rows as $user) {
                    $sql = "SELECT id, lead_status_id, payed, converted_at, office_id, service_status_id, service_owner_id, valid_manager, valid_sales, valid_admin FROM `tbl_lead` WHERE id=" . $user;
                    $command = $connection->createCommand($sql);
                    $dataReader = $command->queryAll();
                }

                //agroup leads office_id
                foreach ($dataReader as $client) {
                    $clientservice[$client['office_id']][] = $client;
                }
                //var_dump($clientservice);
                //assign customers office with leads
                foreach ($clientservice as $office_id => $clients) {
                    $temp = [];
                    foreach ($clients as $user) {
                        // If empty managers
                        if (empty($temp)) {
                            if ($managers[$office_id]) {
                                $temp = $managers[$office_id];
                            } else {
                                continue;
                            }
                        }
                        //
                        $user['service_status_id'] = 0;
                        $user['service_owner_id'] = $temp[0];
                        array_shift($temp);
                        //update to db
                        $connection->createCommand("UPDATE tbl_lead SET service_status_id=0 ,valid_manager=1, service_owner_id=" . $user['service_owner_id'] . " WHERE id=" . $user["id"])->execute();
                        //var_dump($user);

                    }
                }
                //$searchModel->save();
            }
        }

        return $this->redirect('index.php?r=sales/lead/review');
       }
    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
     public function actionRecycleMulti()
     {
         if(!Yii::$app->user->can('Lead.Index')){
             throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
         }
         $searchModel = new LeadSearch;
         $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
         //$dataProviderBox=$searchModel->searchLead();

         if(!empty($_REQUEST['multiple_rec'])){
             if(!Yii::$app->user->can('Lead.Delete')){
                 throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
             }

             $rows=$_REQUEST['selection'];

             for($i=0;$i<count($rows);$i++){

                 //$this->findModel($rows[$i])->active = 0;
                 $model = $this->findModelRecycle($rows[$i]);
                 //$model->active = 1;
                 //0$model->save();
                 $recyclemodel = new Lead;
                 $recyclemodel->id = $model->id;
                 $recyclemodel->office_id = $model->office_id;
                 $recyclemodel->c_control = $model->c_control;
                 $recyclemodel->c_contract = $model->c_contract;
                 $recyclemodel->lead_name = $model->lead_name;
                 $recyclemodel->lead_master_status_id = $model->lead_master_status_id;
                 $recyclemodel->lead_insurance_id = $model->lead_insurance_id;
                 $recyclemodel->lead_description = $model->lead_description;
                 $recyclemodel->lead_type_id =$model->lead_type_id;
                 $recyclemodel->lead_owner_id = $model->lead_owner_id;
                 $recyclemodel->lead_status_id = $model->lead_status_id;
                 $recyclemodel->lead_source_id = $model->lead_source_id;
                 $recyclemodel->email = $model->email;
                 $recyclemodel->first_name = $model->first_name;
                 $recyclemodel->last_name = $model->last_name;
                 $recyclemodel->middle_name = $model->middle_name;
                 $recyclemodel->phone = $model->phone;
                 $recyclemodel->mobile = $model->mobile;
                 $recyclemodel->product_id = $model->product_id;
                 $recyclemodel->loan_amount = $model->loan_amount;
                 $recyclemodel->loan_interest = $model->loan_interest;
                 $recyclemodel->loan_commission = $model->loan_commission;
                 $recyclemodel->loan_term = $model->loan_term;
                 $recyclemodel->payed = $model->payed;
                 $recyclemodel->rfc = $model->rfc;
                 $recyclemodel->curp = $model->curp;
                 $recyclemodel->age = $model->age;
                 $recyclemodel->birthdate = $model->birthdate;
                 $recyclemodel->place_of_birth = $model->place_of_birth;
                 $recyclemodel->civil_status = $model->civil_status;
                 $recyclemodel->civil_status_regime = $model->civil_status_regime;
                 $recyclemodel->spouse_job = $model->spouse_job;
                 $recyclemodel->spouse_monthly_income = $model->spouse_monthly_income;
                 $recyclemodel->monthly_income = $model->monthly_income;
                 $recyclemodel->monthly_income2 = $model->monthly_income2;
                 $recyclemodel->monthly_expenses = $model->monthly_expenses;
                 $recyclemodel->home_status = $model->home_status;
                 $recyclemodel->bureau_status = $model->bureau_status;
                 $recyclemodel->bureau_status_desc = $model->bureau_status_desc;
                 $recyclemodel->active_loans = $model->active_loans;
                 $recyclemodel->economic_dep = $model->economic_dep;
                 $recyclemodel->company_name = $model->company_name;
                 $recyclemodel->job = $model->job;
                 $recyclemodel->labor_old = $model->labor_old;
                 $recyclemodel->c_cuenta = $model->c_cuenta;
                 $recyclemodel->contract_date = $model->contract_date;
                 $recyclemodel->valid_admin = $model->valid_admin;
                 $recyclemodel->valid_manager = $model->valid_manager;
                 $recyclemodel->valid_sales = $model->valid_sales;
                 $recyclemodel->service_status_id = $model->service_status_id;
                 $recyclemodel->service_owner_id = $model->service_owner_id;
                 $recyclemodel->payment_folio = $model->payment_folio;
                 $recyclemodel->added_at = $model->added_at;
                 $recyclemodel->updated_at = $model->updated_at;
                 $recyclemodel->converted_at = $model->converted_at;
                 $recyclemodel->customer_id = $model->customer_id;
                 $recyclemodel->user_id = $model->user_id;
                 $recyclemodel->progress = $model->progress;
                 $recyclemodel->active = $model->active;
                 $recyclemodel->save();
                 $payments = \livefactory\models\PaymentRecycle::find()->where('entity_id = ' . $model->id)->all();
                 foreach ($payments as $payment) {
                     $recyclepayment = new Payment;
                     $recyclepayment->id = $payment->id;
                     $recyclepayment->generator_id = $payment->generator_id;
                     $recyclepayment->co_generator_id = $payment->co_generator_id;
                     $recyclepayment->amount = $payment->amount;
                     $recyclepayment->total_due = $payment->total_due;
                     $recyclepayment->note = $payment->note;
                     $recyclepayment->type = $payment->type;
                     $recyclepayment->date = $payment->date;
                     $recyclepayment->code = $payment->code;
                     $recyclepayment->origin = $payment->origin;
                     $recyclepayment->folio = $payment->folio;
                     $recyclepayment->received = $payment->received;
                     $recyclepayment->entity_id = $payment->entity_id;
                     $recyclepayment->entity_type = $payment->entity_type;
                     $recyclepayment->file_id = $payment->file_id;
                     $recyclepayment->added_at = $payment->added_at;
                     $recyclepayment->updated_at = $payment->updated_at;
                     $recyclepayment->save();
                     $payment->delete();
                 }
                 HistoryModel::historyInsert('log', HistoryModel::ACTION_DELETE, 'Lead eliminado <strong>'.$recyclemodel->c_control .'</strong>' );
                 $model->save();
                 $model->delete();
             }

             //$searchModel->save();
         }

         return $this->render('deleted', [
             'dataProvider' => $dataProvider,
             'searchModel' => $searchModel,
             //'dataProviderBox'=>$dataProviderBox,
         ]);
     }
     public function actionIndex()
     {
         $_GET['index']=true;
         if(!Yii::$app->user->can('Lead.AllLeads')){
             throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
         }
         // sales person view
//         if (Yii::$app->user->can('Sales Person'))
//         {
//             $this->redirect('index.php?r=sales/lead/my-leads');
//         }
         $searchModel = new LeadSearch;
         $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
         //$dataProviderBox=$searchModel->searchLead();
//         var_dump($dataProvider->query->limit(20)->all());

         if(!empty($_REQUEST['multiple_del'])){

             if(!Yii::$app->user->can('Lead.Delete')){
             throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
             }

             $rows=$_REQUEST['selection'];
             for($i=0;$i<count($rows);$i++){
                 //$this->findModel($rows[$i])->active = 0;

               $model = $this->findModel($rows[$i]);
                // $model->active = 0;
                // $model->save();

               $recyclemodel = new LeadRecycle;
               $recyclemodel->id = $model->id;
               $recyclemodel->office_id = $model->office_id;
               $recyclemodel->c_control = $model->c_control;
               $recyclemodel->c_contract = $model->c_contract;
               $recyclemodel->lead_name = $model->lead_name;
               $recyclemodel->lead_master_status_id = $model->lead_master_status_id;
               $recyclemodel->lead_insurance_id = $model->lead_insurance_id;
               $recyclemodel->lead_description = $model->lead_description;
               $recyclemodel->lead_type_id =$model->lead_type_id;
               $recyclemodel->lead_owner_id = $model->lead_owner_id;
               $recyclemodel->lead_status_id = $model->lead_status_id;
               $recyclemodel->lead_source_id = $model->lead_source_id;
               $recyclemodel->email = $model->email;
               $recyclemodel->first_name = $model->first_name;
               $recyclemodel->last_name = $model->last_name;
               $recyclemodel->middle_name = $model->middle_name;
               $recyclemodel->phone = $model->phone;
               $recyclemodel->mobile = $model->mobile;
               $recyclemodel->product_id = $model->product_id;
               $recyclemodel->loan_amount = $model->loan_amount;
               $recyclemodel->loan_interest = $model->loan_interest;
               $recyclemodel->loan_commission = $model->loan_commission;
               $recyclemodel->loan_term = $model->loan_term;
               $recyclemodel->payed = $model->payed;
               $recyclemodel->rfc = $model->rfc;
               $recyclemodel->curp = $model->curp;
               $recyclemodel->age = $model->age;
               $recyclemodel->birthdate = $model->birthdate;
               $recyclemodel->place_of_birth = $model->place_of_birth;
               $recyclemodel->civil_status = $model->civil_status;
               $recyclemodel->civil_status_regime = $model->civil_status_regime;
               $recyclemodel->spouse_job = $model->spouse_job;
               $recyclemodel->spouse_monthly_income = $model->spouse_monthly_income;
               $recyclemodel->monthly_income = $model->monthly_income;
               $recyclemodel->monthly_income2 = $model->monthly_income2;
               $recyclemodel->monthly_expenses = $model->monthly_expenses;
               $recyclemodel->home_status = $model->home_status;
               $recyclemodel->bureau_status = $model->bureau_status;
               $recyclemodel->bureau_status_desc = $model->bureau_status_desc;
               $recyclemodel->active_loans = $model->active_loans;
               $recyclemodel->economic_dep = $model->economic_dep;
               $recyclemodel->company_name = $model->company_name;
               $recyclemodel->job = $model->job;
               $recyclemodel->labor_old = $model->labor_old;
               $recyclemodel->c_cuenta = $model->c_cuenta;
               $recyclemodel->contract_date = $model->contract_date;
               $recyclemodel->valid_admin = $model->valid_admin;
               $recyclemodel->valid_manager = $model->valid_manager;
               $recyclemodel->valid_sales = $model->valid_sales;
               $recyclemodel->service_status_id = $model->service_status_id;
               $recyclemodel->service_owner_id = $model->service_owner_id;
               $recyclemodel->payment_folio = $model->payment_folio;
               $recyclemodel->added_at = $model->added_at;
               $recyclemodel->updated_at = $model->updated_at;
               $recyclemodel->converted_at = $model->converted_at;
               $recyclemodel->customer_id = $model->customer_id;
               $recyclemodel->user_id = $model->user_id;
               $recyclemodel->progress = $model->progress;
               $recyclemodel->active = $model->active;
               $recyclemodel->save();
               $payments = \livefactory\models\Payment::find()->where('entity_id = ' . $model->id)->all();
               foreach ($payments as $payment) {
                   $recyclepayment = new PaymentRecycle;
                   $recyclepayment->id = $payment->id;
                   $recyclepayment->generator_id = $payment->generator_id;
                   $recyclepayment->co_generator_id = $payment->co_generator_id;
                   $recyclepayment->amount = $payment->amount;
                   $recyclepayment->total_due = $payment->total_due;
                   $recyclepayment->note = $payment->note;
                   $recyclepayment->type = $payment->type;
                   $recyclepayment->date = $payment->date;
                   $recyclepayment->code = $payment->code;
                   $recyclepayment->origin = $payment->origin;
                   $recyclepayment->folio = $payment->folio;
                   $recyclepayment->received = $payment->received;
                   $recyclepayment->entity_id = $payment->entity_id;
                   $recyclepayment->entity_type = $payment->entity_type;
                   $recyclepayment->file_id = $payment->file_id;
                   $recyclepayment->added_at = $payment->added_at;
                   $recyclepayment->updated_at = $payment->updated_at;
                   $recyclepayment->save();
                   $payment->delete();
               }
                 //$model->save();
                    $model->delete();
                 HistoryModel::historyInsert('log', HistoryModel::ACTION_DELETE, 'Lead eliminado <strong>'.$recyclemodel->c_control .'</strong>' );
             }
             //exit;
                //var_dump($recyclemodel);
             //$searchModel->save();
         }
         return $this->render('index', [
             'dataProvider' => $dataProvider,
             'searchModel' => $searchModel,
             //'dataProviderBox'=>$dataProviderBox,
         ]);
     }

    public function actionExport()
    {
        $_GET['index']=true;
        if(!Yii::$app->user->can('Lead.Index')){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }
        $searchModel = new LeadSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        //$dataProviderBox=$searchModel->searchLead();

        if(!empty($_REQUEST['multiple_del'])){

            if(!Yii::$app->user->can('Lead.Delete')){
                throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
            }

            $rows=$_REQUEST['selection'];
            for($i=0;$i<count($rows);$i++){
                //$this->findModel($rows[$i])->active = 0;

                $model = $this->findModel($rows[$i]);
                // $model->active = 0;
                // $model->save();

                $recyclemodel = new LeadRecycle;
                $recyclemodel->id = $model->id;
                $recyclemodel->office_id = $model->office_id;
                $recyclemodel->c_control = $model->c_control;
                $recyclemodel->c_contract = $model->c_contract;
                $recyclemodel->lead_name = $model->lead_name;
                $recyclemodel->lead_master_status_id = $model->lead_master_status_id;
                $recyclemodel->lead_insurance_id = $model->lead_insurance_id;
                $recyclemodel->lead_description = $model->lead_description;
                $recyclemodel->lead_type_id =$model->lead_type_id;
                $recyclemodel->lead_owner_id = $model->lead_owner_id;
                $recyclemodel->lead_status_id = $model->lead_status_id;
                $recyclemodel->lead_source_id = $model->lead_source_id;
                $recyclemodel->email = $model->email;
                $recyclemodel->first_name = $model->first_name;
                $recyclemodel->last_name = $model->last_name;
                $recyclemodel->middle_name = $model->middle_name;
                $recyclemodel->phone = $model->phone;
                $recyclemodel->mobile = $model->mobile;
                $recyclemodel->product_id = $model->product_id;
                $recyclemodel->loan_amount = $model->loan_amount;
                $recyclemodel->loan_interest = $model->loan_interest;
                $recyclemodel->loan_commission = $model->loan_commission;
                $recyclemodel->loan_term = $model->loan_term;
                $recyclemodel->payed = $model->payed;
                $recyclemodel->rfc = $model->rfc;
                $recyclemodel->curp = $model->curp;
                $recyclemodel->age = $model->age;
                $recyclemodel->birthdate = $model->birthdate;
                $recyclemodel->place_of_birth = $model->place_of_birth;
                $recyclemodel->civil_status = $model->civil_status;
                $recyclemodel->civil_status_regime = $model->civil_status_regime;
                $recyclemodel->spouse_job = $model->spouse_job;
                $recyclemodel->spouse_monthly_income = $model->spouse_monthly_income;
                $recyclemodel->monthly_income = $model->monthly_income;
                $recyclemodel->monthly_income2 = $model->monthly_income2;
                $recyclemodel->monthly_expenses = $model->monthly_expenses;
                $recyclemodel->home_status = $model->home_status;
                $recyclemodel->bureau_status = $model->bureau_status;
                $recyclemodel->bureau_status_desc = $model->bureau_status_desc;
                $recyclemodel->active_loans = $model->active_loans;
                $recyclemodel->economic_dep = $model->economic_dep;
                $recyclemodel->company_name = $model->company_name;
                $recyclemodel->job = $model->job;
                $recyclemodel->labor_old = $model->labor_old;
                $recyclemodel->c_cuenta = $model->c_cuenta;
                $recyclemodel->contract_date = $model->contract_date;
                $recyclemodel->valid_admin = $model->valid_admin;
                $recyclemodel->valid_manager = $model->valid_manager;
                $recyclemodel->valid_sales = $model->valid_sales;
                $recyclemodel->service_status_id = $model->service_status_id;
                $recyclemodel->service_owner_id = $model->service_owner_id;
                $recyclemodel->payment_folio = $model->payment_folio;
                $recyclemodel->added_at = $model->added_at;
                $recyclemodel->updated_at = $model->updated_at;
                $recyclemodel->converted_at = $model->converted_at;
                $recyclemodel->customer_id = $model->customer_id;
                $recyclemodel->user_id = $model->user_id;
                $recyclemodel->progress = $model->progress;
                $recyclemodel->active = $model->active;
                $recyclemodel->save();
                $payments = \livefactory\models\Payment::find()->where('entity_id = ' . $model->id)->all();
                foreach ($payments as $payment) {
                    $recyclepayment = new PaymentRecycle;
                    $recyclepayment->id = $payment->id;
                    $recyclepayment->generator_id = $payment->generator_id;
                    $recyclepayment->co_generator_id = $payment->co_generator_id;
                    $recyclepayment->amount = $payment->amount;
                    $recyclepayment->total_due = $payment->total_due;
                    $recyclepayment->note = $payment->note;
                    $recyclepayment->type = $payment->type;
                    $recyclepayment->date = $payment->date;
                    $recyclepayment->code = $payment->code;
                    $recyclepayment->origin = $payment->origin;
                    $recyclepayment->folio = $payment->folio;
                    $recyclepayment->received = $payment->received;
                    $recyclepayment->entity_id = $payment->entity_id;
                    $recyclepayment->entity_type = $payment->entity_type;
                    $recyclepayment->file_id = $payment->file_id;
                    $recyclepayment->added_at = $payment->added_at;
                    $recyclepayment->updated_at = $payment->updated_at;
                    $recyclepayment->save();
                    $payment->delete();
                }
                HistoryModel::historyInsert('log', HistoryModel::ACTION_EXPORT, 'Lead exportado <strong>'.$recyclemodel->c_control .'</strong>' );
                //$model->save();
                $model->delete();
            }
            //exit;
            //var_dump($recyclemodel);
            //$searchModel->save();
        }

        return $this->render('export', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            //'dataProviderBox'=>$dataProviderBox,
        ]);
    }

    public function actionCrm()
    {
        if(!Yii::$app->user->can('Lead.Crm')){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }
        $_GET['crm'] = true;
        $searchModel = new LeadSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        //$dataProviderBox=$searchModel->searchLead();

        if(!empty($_REQUEST['multiple_del'])){
            if(!Yii::$app->user->can('Lead.Delete')){
                throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
            }

            $rows=$_REQUEST['selection'];

            for($i=0;$i<count($rows);$i++){

                //$this->findModel($rows[$i])->active = 0;
                $model = $this->findModel($rows[$i]);
              //  $model->active = 0;
              //  $model->save();
                $recyclemodel = new LeadRecycle;
                $recyclemodel->id = $model->id;
                $recyclemodel->office_id = $model->office_id;
                $recyclemodel->c_control = $model->c_control;
                $recyclemodel->c_contract = $model->c_contract;
                $recyclemodel->lead_name = $model->lead_name;
                $recyclemodel->lead_master_status_id = $model->lead_master_status_id;
                $recyclemodel->lead_insurance_id = $model->lead_insurance_id;
                $recyclemodel->lead_description = $model->lead_description;
                $recyclemodel->lead_type_id =$model->lead_type_id;
                $recyclemodel->lead_owner_id = $model->lead_owner_id;
                $recyclemodel->lead_status_id = $model->lead_status_id;
                $recyclemodel->lead_source_id = $model->lead_source_id;
                $recyclemodel->email = $model->email;
                $recyclemodel->first_name = $model->first_name;
                $recyclemodel->last_name = $model->last_name;
                $recyclemodel->middle_name = $model->middle_name;
                $recyclemodel->phone = $model->phone;
                $recyclemodel->mobile = $model->mobile;
                $recyclemodel->product_id = $model->product_id;
                $recyclemodel->loan_amount = $model->loan_amount;
                $recyclemodel->loan_interest = $model->loan_interest;
                $recyclemodel->loan_commission = $model->loan_commission;
                $recyclemodel->loan_term = $model->loan_term;
                $recyclemodel->payed = $model->payed;
                $recyclemodel->rfc = $model->rfc;
                $recyclemodel->curp = $model->curp;
                $recyclemodel->age = $model->age;
                $recyclemodel->birthdate = $model->birthdate;
                $recyclemodel->place_of_birth = $model->place_of_birth;
                $recyclemodel->civil_status = $model->civil_status;
                $recyclemodel->civil_status_regime = $model->civil_status_regime;
                $recyclemodel->spouse_job = $model->spouse_job;
                $recyclemodel->spouse_monthly_income = $model->spouse_monthly_income;
                $recyclemodel->monthly_income = $model->monthly_income;
                $recyclemodel->monthly_income2 = $model->monthly_income2;
                $recyclemodel->monthly_expenses = $model->monthly_expenses;
                $recyclemodel->home_status = $model->home_status;
                $recyclemodel->bureau_status = $model->bureau_status;
                $recyclemodel->bureau_status_desc = $model->bureau_status_desc;
                $recyclemodel->active_loans = $model->active_loans;
                $recyclemodel->economic_dep = $model->economic_dep;
                $recyclemodel->company_name = $model->company_name;
                $recyclemodel->job = $model->job;
                $recyclemodel->labor_old = $model->labor_old;
                $recyclemodel->c_cuenta = $model->c_cuenta;
                $recyclemodel->contract_date = $model->contract_date;
                $recyclemodel->valid_admin = $model->valid_admin;
                $recyclemodel->valid_manager = $model->valid_manager;
                $recyclemodel->valid_sales = $model->valid_sales;
                $recyclemodel->service_status_id = $model->service_status_id;
                $recyclemodel->service_owner_id = $model->service_owner_id;
                $recyclemodel->payment_folio = $model->payment_folio;
                $recyclemodel->added_at = $model->added_at;
                $recyclemodel->updated_at = $model->updated_at;
                $recyclemodel->converted_at = $model->converted_at;
                $recyclemodel->customer_id = $model->customer_id;
                $recyclemodel->user_id = $model->user_id;
                $recyclemodel->progress = $model->progress;
                $recyclemodel->active = $model->active;
                $recyclemodel->save();
                $payments = \livefactory\models\Payment::find()->where('entity_id = ' . $model->id)->all();
                foreach ($payments as $payment) {
                    $recyclepayment = new PaymentRecycle;
                    $recyclepayment->id = $payment->id;
                    $recyclepayment->generator_id = $payment->generator_id;
                    $recyclepayment->co_generator_id = $payment->co_generator_id;
                    $recyclepayment->amount = $payment->amount;
                    $recyclepayment->total_due = $payment->total_due;
                    $recyclepayment->note = $payment->note;
                    $recyclepayment->type = $payment->type;
                    $recyclepayment->date = $payment->date;
                    $recyclepayment->code = $payment->code;
                    $recyclepayment->origin = $payment->origin;
                    $recyclepayment->folio = $payment->folio;
                    $recyclepayment->received = $payment->received;
                    $recyclepayment->entity_id = $payment->entity_id;
                    $recyclepayment->entity_type = $payment->entity_type;
                    $recyclepayment->file_id = $payment->file_id;
                    $recyclepayment->added_at = $payment->added_at;
                    $recyclepayment->updated_at = $payment->updated_at;
                    $recyclepayment->save();
                    $payment->delete();
                }
                HistoryModel::historyInsert('log', HistoryModel::ACTION_DELETE, 'Lead eliminado <strong>'.$recyclemodel->c_control .'</strong>' );

                $model->delete();
            }

            //$searchModel->save();
        }
        return $this->render('lead-crm', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            //'dataProviderBox'=>$dataProviderBox,
        ]);
    }


     public function actionService()
     {
         if(!Yii::$app->user->can('Role.Manager')){
             throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
         }

         // As services
         $_GET['service'] = true;
         $_GET['master_status_id'] = isset($_GET['master_status_id']) ? $_GET['master_status_id'] : 3; // By default

         $searchModel = new LeadSearch;
         $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
         //$dataProviderBox=$searchModel->searchLead();

         if(!empty($_REQUEST['multiple_del'])){
             if(!Yii::$app->user->can('Lead.Delete')){
                 throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
             }

             $rows=$_REQUEST['selection'];

             for($i=0;$i<count($rows);$i++){

                 //$this->findModel($rows[$i])->active = 0;
                 $model = $this->findModel($rows[$i]);
                 $recyclemodel = new LeadRecycle;
                 $recyclemodel->id = $model->id;
                 $recyclemodel->office_id = $model->office_id;
                 $recyclemodel->c_control = $model->c_control;
                 $recyclemodel->c_contract = $model->c_contract;
                 $recyclemodel->lead_name = $model->lead_name;
                 $recyclemodel->lead_master_status_id = $model->lead_master_status_id;
                 $recyclemodel->lead_insurance_id = $model->lead_insurance_id;
                 $recyclemodel->lead_description = $model->lead_description;
                 $recyclemodel->lead_type_id =$model->lead_type_id;
                 $recyclemodel->lead_owner_id = $model->lead_owner_id;
                 $recyclemodel->lead_status_id = $model->lead_status_id;
                 $recyclemodel->lead_source_id = $model->lead_source_id;
                 $recyclemodel->email = $model->email;
                 $recyclemodel->first_name = $model->first_name;
                 $recyclemodel->last_name = $model->last_name;
                 $recyclemodel->middle_name = $model->middle_name;
                 $recyclemodel->phone = $model->phone;
                 $recyclemodel->mobile = $model->mobile;
                 $recyclemodel->product_id = $model->product_id;
                 $recyclemodel->loan_amount = $model->loan_amount;
                 $recyclemodel->loan_interest = $model->loan_interest;
                 $recyclemodel->loan_commission = $model->loan_commission;
                 $recyclemodel->loan_term = $model->loan_term;
                 $recyclemodel->payed = $model->payed;
                 $recyclemodel->rfc = $model->rfc;
                 $recyclemodel->curp = $model->curp;
                 $recyclemodel->age = $model->age;
                 $recyclemodel->birthdate = $model->birthdate;
                 $recyclemodel->place_of_birth = $model->place_of_birth;
                 $recyclemodel->civil_status = $model->civil_status;
                 $recyclemodel->civil_status_regime = $model->civil_status_regime;
                 $recyclemodel->spouse_job = $model->spouse_job;
                 $recyclemodel->spouse_monthly_income = $model->spouse_monthly_income;
                 $recyclemodel->monthly_income = $model->monthly_income;
                 $recyclemodel->monthly_income2 = $model->monthly_income2;
                 $recyclemodel->monthly_expenses = $model->monthly_expenses;
                 $recyclemodel->home_status = $model->home_status;
                 $recyclemodel->bureau_status = $model->bureau_status;
                 $recyclemodel->bureau_status_desc = $model->bureau_status_desc;
                 $recyclemodel->active_loans = $model->active_loans;
                 $recyclemodel->economic_dep = $model->economic_dep;
                 $recyclemodel->company_name = $model->company_name;
                 $recyclemodel->job = $model->job;
                 $recyclemodel->labor_old = $model->labor_old;
                 $recyclemodel->c_cuenta = $model->c_cuenta;
                 $recyclemodel->contract_date = $model->contract_date;
                 $recyclemodel->valid_admin = $model->valid_admin;
                 $recyclemodel->valid_manager = $model->valid_manager;
                 $recyclemodel->valid_sales = $model->valid_sales;
                 $recyclemodel->service_status_id = $model->service_status_id;
                 $recyclemodel->service_owner_id = $model->service_owner_id;
                 $recyclemodel->payment_folio = $model->payment_folio;
                 $recyclemodel->added_at = $model->added_at;
                 $recyclemodel->updated_at = $model->updated_at;
                 $recyclemodel->converted_at = $model->converted_at;
                 $recyclemodel->customer_id = $model->customer_id;
                 $recyclemodel->user_id = $model->user_id;
                 $recyclemodel->progress = $model->progress;
                 $recyclemodel->active = $model->active;
                 $recyclemodel->save();
                 $payments = \livefactory\models\Payment::find()->where('entity_id = ' . $model->id)->all();
                 foreach ($payments as $payment) {
                     $recyclepayment = new PaymentRecycle;
                     $recyclepayment->id = $payment->id;
                     $recyclepayment->generator_id = $payment->generator_id;
                     $recyclepayment->co_generator_id = $payment->co_generator_id;
                     $recyclepayment->amount = $payment->amount;
                     $recyclepayment->total_due = $payment->total_due;
                     $recyclepayment->note = $payment->note;
                     $recyclepayment->type = $payment->type;
                     $recyclepayment->date = $payment->date;
                     $recyclepayment->code = $payment->code;
                     $recyclepayment->origin = $payment->origin;
                     $recyclepayment->folio = $payment->folio;
                     $recyclepayment->received = $payment->received;
                     $recyclepayment->entity_id = $payment->entity_id;
                     $recyclepayment->entity_type = $payment->entity_type;
                     $recyclepayment->file_id = $payment->file_id;
                     $recyclepayment->added_at = $payment->added_at;
                     $recyclepayment->updated_at = $payment->updated_at;
                     $recyclepayment->save();
                     $payment->delete();
                 }
                 HistoryModel::historyInsert('log', HistoryModel::ACTION_DELETE, 'Lead eliminado <strong>'.$recyclemodel->c_control .'</strong>' );
                 $model->delete();
             }

             //$searchModel->save();
         }

         return $this->render('service', [
             'dataProvider' => $dataProvider,
             'searchModel' => $searchModel,
             //'dataProviderBox'=>$dataProviderBox,
         ]);
     }

     public function actionList()
     {
         if(!Yii::$app->user->can('Lead.Index')){
             throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
         }
         $searchModel = new LeadSearch;
         $dataProvider = $searchModel->filter(Yii::$app->request->getQueryParams());
         return $this->render('list', [
             'dataProvider' => $dataProvider,
             'searchModel' => $searchModel
         ]);
     }

     public function actionListappointments()
     {
         if(!Yii::$app->user->can('Admin')){
             throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
         }
         ini_set('memory_limit','2048M');

         $appointments = AppointmentModel::appointmentsUps();

         echo $appointments;


     }

     public function actionDeleted()
     {
         if(!Yii::$app->user->can('Lead.Index')){
             throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
         }

         $_GET['deleted'] = true;

         $searchModel = new LeadSearchRecycle;
         $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
         //$_REQUEST['multiple_rec'] = $_REQUEST['id'];
         if(!empty($_REQUEST['multiple_rec'])){
             if(!Yii::$app->user->can('Lead.Restore')){
                 throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
             }
             $rows=$_REQUEST['selection'];


             for($i=0;$i<count($rows);$i++){
                 //var_dump($rows[$i]);
                 //var_dump($this->findModelRecycle($rows[$i]));
                 $model = $this->findModelRecycle($rows[$i]);
                 //$model->active = 1;
                 //$model->save();
                 $recyclemodel = new Lead;
                 $recyclemodel->id = $model->id;
                 $recyclemodel->office_id = $model->office_id;
                 $recyclemodel->c_control = $model->c_control;
                 $recyclemodel->c_contract = $model->c_contract;
                 $recyclemodel->lead_name = $model->lead_name;
                 $recyclemodel->lead_master_status_id = $model->lead_master_status_id;
                 $recyclemodel->lead_insurance_id = $model->lead_insurance_id;
                 $recyclemodel->lead_description = $model->lead_description;
                 $recyclemodel->lead_type_id =$model->lead_type_id;
                 $recyclemodel->lead_owner_id = $model->lead_owner_id;
                 $recyclemodel->lead_status_id = $model->lead_status_id;
                 $recyclemodel->lead_source_id = $model->lead_source_id;
                 $recyclemodel->email = $model->email;
                 $recyclemodel->first_name = $model->first_name;
                 $recyclemodel->last_name = $model->last_name;
                 $recyclemodel->middle_name = $model->middle_name;
                 $recyclemodel->phone = $model->phone;
                 $recyclemodel->mobile = $model->mobile;
                 $recyclemodel->product_id = $model->product_id;
                 $recyclemodel->loan_amount = $model->loan_amount;
                 $recyclemodel->loan_interest = $model->loan_interest;
                 $recyclemodel->loan_commission = $model->loan_commission;
                 $recyclemodel->loan_term = $model->loan_term;
                 $recyclemodel->payed = $model->payed;
                 $recyclemodel->rfc = $model->rfc;
                 $recyclemodel->curp = $model->curp;
                 $recyclemodel->age = $model->age;
                 $recyclemodel->birthdate = $model->birthdate;
                 $recyclemodel->place_of_birth = $model->place_of_birth;
                 $recyclemodel->civil_status = $model->civil_status;
                 $recyclemodel->civil_status_regime = $model->civil_status_regime;
                 $recyclemodel->spouse_job = $model->spouse_job;
                 $recyclemodel->spouse_monthly_income = $model->spouse_monthly_income;
                 $recyclemodel->monthly_income = $model->monthly_income;
                 $recyclemodel->monthly_income2 = $model->monthly_income2;
                 $recyclemodel->monthly_expenses = $model->monthly_expenses;
                 $recyclemodel->home_status = $model->home_status;
                 $recyclemodel->bureau_status = $model->bureau_status;
                 $recyclemodel->bureau_status_desc = $model->bureau_status_desc;
                 $recyclemodel->active_loans = $model->active_loans;
                 $recyclemodel->economic_dep = $model->economic_dep;
                 $recyclemodel->company_name = $model->company_name;
                 $recyclemodel->job = $model->job;
                 $recyclemodel->labor_old = $model->labor_old;
                 $recyclemodel->c_cuenta = $model->c_cuenta;
                 $recyclemodel->contract_date = $model->contract_date;
                 $recyclemodel->valid_admin = $model->valid_admin;
                 $recyclemodel->valid_manager = $model->valid_manager;
                 $recyclemodel->valid_sales = $model->valid_sales;
                 $recyclemodel->service_status_id = $model->service_status_id;
                 $recyclemodel->service_owner_id = $model->service_owner_id;
                 $recyclemodel->payment_folio = $model->payment_folio;
                 $recyclemodel->added_at = $model->added_at;
                 $recyclemodel->updated_at = $model->updated_at;
                 $recyclemodel->converted_at = $model->converted_at;
                 $recyclemodel->customer_id = $model->customer_id;
                 $recyclemodel->user_id = $model->user_id;
                 $recyclemodel->progress = $model->progress;
                 $recyclemodel->active = $model->active;
                 $recyclemodel->save();
                 $payments = \livefactory\models\PaymentRecycle::find()->where('entity_id = ' . $model->id)->all();
                 foreach ($payments as $payment) {
                     $recyclepayment = new Payment;
                     $recyclepayment->id = $payment->id;
                     $recyclepayment->generator_id = $payment->generator_id;
                     $recyclepayment->co_generator_id = $payment->co_generator_id;
                     $recyclepayment->amount = $payment->amount;
                     $recyclepayment->total_due = $payment->total_due;
                     $recyclepayment->note = $payment->note;
                     $recyclepayment->type = $payment->type;
                     $recyclepayment->date = $payment->date;
                     $recyclepayment->code = $payment->code;
                     $recyclepayment->origin = $payment->origin;
                     $recyclepayment->folio = $payment->folio;
                     $recyclepayment->received = $payment->received;
                     $recyclepayment->entity_id = $payment->entity_id;
                     $recyclepayment->entity_type = $payment->entity_type;
                     $recyclepayment->file_id = $payment->file_id;
                     $recyclepayment->added_at = $payment->added_at;
                     $recyclepayment->updated_at = $payment->updated_at;
                     $recyclepayment->save();
                     $payment->delete();
                 }
                 HistoryModel::historyInsert('log', HistoryModel::ACTION_RESTORE, 'Lead restaurado <strong>'.$recyclemodel->c_control .'</strong>' );
                 //$model->save();
                 $model->delete();


             }

             //$searchModel->save();
         }

         return $this->render('deleted', [
             'dataProvider' => $dataProvider,
             'searchModel' => $searchModel
         ]);
     }

     /**
      * Payments
      */
    public function actionPayments()
    {
        if(!Yii::$app->user->can('Reports.Payments')){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }
        $office_id = Yii::$app->user->can('Office.NoLimit') ? Yii::$app->request->getQueryParam('office_id') : 0;
        $agent_id = Yii::$app->request->getQueryParam('agent_id');
        list($start, $end) = SalesReport::getPeriodFromRequest(Yii::$app->request->getQueryParams());
        $searchModel = new LeadSearch;
        $stats = [
            'payments' => 0,
            'efectivo' => 0,
            'transferencia' => 0,
            'deposito' => 0,
            'tarjeta' => 0,
            'total' => 0,
            'total-validate'=> 0,
            'validate' => 0,
            'declinated' => 0,
            'insurance' => ['total' => 0]
        ];
        $dataProvider = $searchModel->payments(Yii::$app->request->getQueryParams(), $stats);
        return $this->render(Yii::$app->user->identity->office_id == 2 ? 'payments' : 'payments', [
            'start' => $start,
            'end' => $end,
            'office_id' => $office_id,
            'agent_id' => $agent_id,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'stats' => $stats
        ]);
    }

//    report customer
    public function actionReport_customer()
    {
        $searchModel = new LeadSearch();
        $dataProvider = $searchModel->reportcustomer(Yii::$app->request->getQueryParams());
        $stats = [
            'total' => $dataProvider->query->count(),
        ];
        return $this->render('report-customer', [
            'stats' => $stats,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel

        ]);

    }

    public function actionValidate()
    {
        if( ! Yii::$app->user->can('Payment.Validate')){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }

        if ($_REQUEST['selection']) {
            $payment_status = (int) Yii::$app->request->post('payment_status');
            $rows=$_REQUEST['selection'];
            for($i=0;$i<count($rows);$i++){
                $payment_id = $rows[$i];
                $payment = Payment::findOne($payment_id);
                $old_status = $payment->status;
                $payment->status = $payment_status;
                $payment->save();

                HistoryModel::historyInsert($this->entity_type, $payment->entity_id,'Modificó el estado del pago <strong>#'.$payment->folio.'</strong> por <strong>$'.number_format($payment->amount).'</strong> de <strong>' . Payment::paymentStatus($old_status) . '</strong> a <strong>' . Payment::paymentStatus($payment_status) . '</strong>');
            }
        }

        // Search payments
        $searchModel = new LeadSearch;
        $dataProvider = $searchModel->payments_unvalidated(Yii::$app->request->getQueryParams());

        return $this->render('validate', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    public function actionInsuranceindex(){
        if(! (Yii::$app->user->can('Insurance.Director') || Yii::$app->user->can('Insurance.Customer') || Yii::$app->user->can('Insurance') || Yii::$app->user->can('Insurance.View') || Yii::$app->user->can('Admin'))){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }

        // As services
        $_GET['insurance-index'] = true;
//        $_GET['master_status_id'] = isset($_GET['master_status_id']) ? $_GET['master_status_id'] : 3; // By default

        $searchModel = new LeadSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        //$dataProviderBox=$searchModel->searchLead();

        if(!empty($_REQUEST['multiple_del'])){
            if(!Yii::$app->user->can('Lead.Delete')){
                throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
            }

            $rows=$_REQUEST['selection'];

            for($i=0;$i<count($rows);$i++){

                //$this->findModel($rows[$i])->active = 0;
                $model = $this->findModel($rows[$i]);
                $recyclemodel = new LeadRecycle;
                $recyclemodel->id = $model->id;
                $recyclemodel->office_id = $model->office_id;
                $recyclemodel->c_control = $model->c_control;
                $recyclemodel->c_contract = $model->c_contract;
                $recyclemodel->lead_name = $model->lead_name;
                $recyclemodel->lead_master_status_id = $model->lead_master_status_id;
                $recyclemodel->lead_insurance_id = $model->lead_insurance_id;
                $recyclemodel->lead_description = $model->lead_description;
                $recyclemodel->lead_type_id =$model->lead_type_id;
                $recyclemodel->lead_owner_id = $model->lead_owner_id;
                $recyclemodel->lead_status_id = $model->lead_status_id;
                $recyclemodel->lead_source_id = $model->lead_source_id;
                $recyclemodel->email = $model->email;
                $recyclemodel->first_name = $model->first_name;
                $recyclemodel->last_name = $model->last_name;
                $recyclemodel->middle_name = $model->middle_name;
                $recyclemodel->phone = $model->phone;
                $recyclemodel->mobile = $model->mobile;
                $recyclemodel->product_id = $model->product_id;
                $recyclemodel->loan_amount = $model->loan_amount;
                $recyclemodel->loan_interest = $model->loan_interest;
                $recyclemodel->loan_commission = $model->loan_commission;
                $recyclemodel->loan_term = $model->loan_term;
                $recyclemodel->payed = $model->payed;
                $recyclemodel->rfc = $model->rfc;
                $recyclemodel->curp = $model->curp;
                $recyclemodel->age = $model->age;
                $recyclemodel->birthdate = $model->birthdate;
                $recyclemodel->place_of_birth = $model->place_of_birth;
                $recyclemodel->civil_status = $model->civil_status;
                $recyclemodel->civil_status_regime = $model->civil_status_regime;
                $recyclemodel->spouse_job = $model->spouse_job;
                $recyclemodel->spouse_monthly_income = $model->spouse_monthly_income;
                $recyclemodel->monthly_income = $model->monthly_income;
                $recyclemodel->monthly_income2 = $model->monthly_income2;
                $recyclemodel->monthly_expenses = $model->monthly_expenses;
                $recyclemodel->home_status = $model->home_status;
                $recyclemodel->bureau_status = $model->bureau_status;
                $recyclemodel->bureau_status_desc = $model->bureau_status_desc;
                $recyclemodel->active_loans = $model->active_loans;
                $recyclemodel->economic_dep = $model->economic_dep;
                $recyclemodel->company_name = $model->company_name;
                $recyclemodel->job = $model->job;
                $recyclemodel->labor_old = $model->labor_old;
                $recyclemodel->c_cuenta = $model->c_cuenta;
                $recyclemodel->contract_date = $model->contract_date;
                $recyclemodel->valid_admin = $model->valid_admin;
                $recyclemodel->valid_manager = $model->valid_manager;
                $recyclemodel->valid_sales = $model->valid_sales;
                $recyclemodel->service_status_id = $model->service_status_id;
                $recyclemodel->service_owner_id = $model->service_owner_id;
                $recyclemodel->payment_folio = $model->payment_folio;
                $recyclemodel->added_at = $model->added_at;
                $recyclemodel->updated_at = $model->updated_at;
                $recyclemodel->converted_at = $model->converted_at;
                $recyclemodel->customer_id = $model->customer_id;
                $recyclemodel->user_id = $model->user_id;
                $recyclemodel->progress = $model->progress;
                $recyclemodel->active = $model->active;
                $recyclemodel->save();
                $payments = \livefactory\models\Payment::find()->where('entity_id = ' . $model->id)->all();
                foreach ($payments as $payment) {
                    $recyclepayment = new PaymentRecycle;
                    $recyclepayment->id = $payment->id;
                    $recyclepayment->generator_id = $payment->generator_id;
                    $recyclepayment->co_generator_id = $payment->co_generator_id;
                    $recyclepayment->amount = $payment->amount;
                    $recyclepayment->total_due = $payment->total_due;
                    $recyclepayment->note = $payment->note;
                    $recyclepayment->type = $payment->type;
                    $recyclepayment->date = $payment->date;
                    $recyclepayment->code = $payment->code;
                    $recyclepayment->origin = $payment->origin;
                    $recyclepayment->folio = $payment->folio;
                    $recyclepayment->received = $payment->received;
                    $recyclepayment->entity_id = $payment->entity_id;
                    $recyclepayment->entity_type = $payment->entity_type;
                    $recyclepayment->file_id = $payment->file_id;
                    $recyclepayment->added_at = $payment->added_at;
                    $recyclepayment->updated_at = $payment->updated_at;
                    $recyclepayment->save();
                    $payment->delete();
                }
                HistoryModel::historyInsert('log', HistoryModel::ACTION_DELETE, 'Lead eliminado <strong>'.$recyclemodel->c_control .'</strong>' );
                $model->delete();
            }

            //$searchModel->save();
        }

        return $this->render('insurance-index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            //'dataProviderBox'=>$dataProviderBox,
        ]);

    }
    ///****/// Payments Insurance
    ///
    public function actionInsurance()
    {
        if(!Yii::$app->user->can('Lead.Index')){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }
        $office_id = Yii::$app->user->can('Office.NoLimit') ? Yii::$app->request->getQueryParam('office_id') : 0;
        $agent_id = Yii::$app->request->getQueryParam('agent_id');
        list($start, $end) = SalesReport::getPeriodFromRequest(Yii::$app->request->getQueryParams());
        $searchModel = new LeadSearch;
//        var_dump($start,$end);
        $stats = [
            'payments' => 0,
            'efectivo' => 0,
            'transferencia' => 0,
            'deposito' => 0,
            'tarjeta' => 0,
            'total' => 0
        ];
//        var_dump(Yii::$app->request->getQueryParams());
        $dataProvider = $searchModel->payments(Yii::$app->request->getQueryParams(), $stats);

        return $this->render(Yii::$app->user->identity->office_id == 2 ? 'insurance' : 'insurance', [
            'start' => $start,
            'end' => $end,
            'office_id' => $office_id,
            'agent_id' => $agent_id,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'stats' => $stats
        ]);

    }

    /**
     *
     */
    public function actionMigrate($id)
    {
        $status = 'error';
        $history = 'El Lead fue migrado a Atención a Clientes.';
        $lead = Lead::findOne($id);

        if ($lead) {
            if (LeadStatus::canMigrate($lead)) {
//                if ( Yii::$app->user->can('Role.Sales')) {
//                    $lead->valid_sales = 1;
//                    $history = 'El Lead aprobado para migración de Ventas a Atención a Clientes.';
//                }
//
//                if ( Yii::$app->user->can('Role.Admin')) {
//                    $lead->valid_sales = 1;
//                    $lead->valid_admin = 1;
//                    $history = 'El Lead aprobado para migración a Atención a Clientes.';
//                }
//
//                if ( Yii::$app->user->can('Role.Manager')) {
//                    $lead->valid_sales = 1;
//                    $lead->valid_admin = 1;
//                    $lead->valid_manager = 1;
//                    $lead->service_status_id = 0;
//                    $lead->service_owner_id = Yii::$app->user->identity->getId();
//                    $history = 'El Lead fue migrado a Atención a Clientes.';
//                }

                //
                $lead->lead_master_status_id = LeadStatus::_MASTER_SERVICE;

                HistoryModel::historyInsert($this->entity_type,$lead->id,'Este lead fue migrado a Atención a Clientes');


                //
                if (Yii::$app->user->can('Lead.Migrate')) {
                    $lead->service_owner_id = (int) Yii::$app->request->post('service_owner_id');
                    HistoryModel::historyInsert($this->entity_type,$lead->id,'Se asigno manualmente un gestor');
                }

                if ($lead->save()) {
                    HistoryModel::historyInsert($this->entity_type,$lead->id,$history);
                    $status = 'success';
                }
            } else {
                $status = 'forbidden';
            }
        } else {
            $status = '404';
        }

        return $this->redirect(['view','id' => $id, 'migrated' => $status]);
    }
    //    Insurance add
    public function actionMigrateinsurance($id){
        $status = 'error';
        $history = 'El Lead fue migrado a Seguros';
        $lead = Lead::findOne($id);

        if ($lead)
        {
            if (LeadStatus::caMigrateInsurance($lead)){
                $lead->insurance_agent = (int) Yii::$app->request->post('insurance_id');
                HistoryModel::historyInsert($this->entity_type,$lead->id,'Se asigno manualmente un gestor');

            }
            if ($lead->save())
            {
                HistoryModel::historyInsert($this->entity_type,$lead->id,$history);
            }
            else{
                $status = 'forbidden';
            }

        }
        else{
            $status = '404';
        }

        return $this->redirect(['view', 'id' => $id, 'migratedinsurance' => $status]);
    }


	/**
     * Lists all Lead models.
     * @return mixed
     */
    public function actionMyLeads()
    {
        $_GET['my-leads'] = true;
		if(!Yii::$app->user->can('Lead.MyLead')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
        $searchModel = new LeadSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

		if(!empty($_REQUEST['multiple_del'])){
			$rows=$_REQUEST['selection'];
			for($i=0;$i<count($rows);$i++){
                $model = $this->findModel($rows[$i]);
             //   $model->active = 0;
             //   $model->save();
               $recyclemodel = new LeadRecycle;
                $recyclemodel->id = $model->id;
                $recyclemodel->office_id = $model->office_id;
                $recyclemodel->c_control = $model->c_control;
                $recyclemodel->c_contract = $model->c_contract;
                $recyclemodel->lead_name = $model->lead_name;
                $recyclemodel->lead_master_status_id = $model->lead_master_status_id;
                $recyclemodel->lead_insurance_id = $model->lead_insurance_id;
                $recyclemodel->lead_description = $model->lead_description;
                $recyclemodel->lead_type_id =$model->lead_type_id;
                $recyclemodel->lead_owner_id = $model->lead_owner_id;
                $recyclemodel->lead_status_id = $model->lead_status_id;
                $recyclemodel->lead_source_id = $model->lead_source_id;
                $recyclemodel->email = $model->email;
                $recyclemodel->first_name = $model->first_name;
                $recyclemodel->last_name = $model->last_name;
                $recyclemodel->middle_name = $model->middle_name;
                $recyclemodel->phone = $model->phone;
                $recyclemodel->mobile = $model->mobile;
                $recyclemodel->product_id = $model->product_id;
                $recyclemodel->loan_amount = $model->loan_amount;
                $recyclemodel->loan_interest = $model->loan_interest;
                $recyclemodel->loan_commission = $model->loan_commission;
                $recyclemodel->loan_term = $model->loan_term;
                $recyclemodel->payed = $model->payed;
                $recyclemodel->rfc = $model->rfc;
                $recyclemodel->curp = $model->curp;
                $recyclemodel->age = $model->age;
                $recyclemodel->birthdate = $model->birthdate;
                $recyclemodel->place_of_birth = $model->place_of_birth;
                $recyclemodel->civil_status = $model->civil_status;
                $recyclemodel->civil_status_regime = $model->civil_status_regime;
                $recyclemodel->spouse_job = $model->spouse_job;
                $recyclemodel->spouse_monthly_income = $model->spouse_monthly_income;
                $recyclemodel->monthly_income = $model->monthly_income;
                $recyclemodel->monthly_income2 = $model->monthly_income2;
                $recyclemodel->monthly_expenses = $model->monthly_expenses;
                $recyclemodel->home_status = $model->home_status;
                $recyclemodel->bureau_status = $model->bureau_status;
                $recyclemodel->bureau_status_desc = $model->bureau_status_desc;
                $recyclemodel->active_loans = $model->active_loans;
                $recyclemodel->economic_dep = $model->economic_dep;
                $recyclemodel->company_name = $model->company_name;
                $recyclemodel->job = $model->job;
                $recyclemodel->labor_old = $model->labor_old;
                $recyclemodel->c_cuenta = $model->c_cuenta;
                $recyclemodel->contract_date = $model->contract_date;
                $recyclemodel->valid_admin = $model->valid_admin;
                $recyclemodel->valid_manager = $model->valid_manager;
                $recyclemodel->valid_sales = $model->valid_sales;
                $recyclemodel->service_status_id = $model->service_status_id;
                $recyclemodel->service_owner_id = $model->service_owner_id;
                $recyclemodel->payment_folio = $model->payment_folio;
                $recyclemodel->added_at = $model->added_at;
                $recyclemodel->updated_at = $model->updated_at;
                $recyclemodel->converted_at = $model->converted_at;
                $recyclemodel->customer_id = $model->customer_id;
                $recyclemodel->user_id = $model->user_id;
                $recyclemodel->progress = $model->progress;
                $recyclemodel->active = $model->active;
                $recyclemodel->save();
                $payments = \livefactory\models\Payment::find()->where('entity_id = ' . $model->id)->all();
                foreach ($payments as $payment) {
                    $recyclepayment = new PaymentRecycle;
                    $recyclepayment->id = $payment->id;
                    $recyclepayment->generator_id = $payment->generator_id;
                    $recyclepayment->co_generator_id = $payment->co_generator_id;
                    $recyclepayment->amount = $payment->amount;
                    $recyclepayment->total_due = $payment->total_due;
                    $recyclepayment->note = $payment->note;
                    $recyclepayment->type = $payment->type;
                    $recyclepayment->date = $payment->date;
                    $recyclepayment->code = $payment->code;
                    $recyclepayment->origin = $payment->origin;
                    $recyclepayment->folio = $payment->folio;
                    $recyclepayment->received = $payment->received;
                    $recyclepayment->entity_id = $payment->entity_id;
                    $recyclepayment->entity_type = $payment->entity_type;
                    $recyclepayment->file_id = $payment->file_id;
                    $recyclepayment->added_at = $payment->added_at;
                    $recyclepayment->updated_at = $payment->updated_at;
                    $recyclepayment->save();
                    $payment->delete();
                }
                HistoryModel::historyInsert('log', HistoryModel::ACTION_DELETE, 'Lead eliminado <strong>'.$recyclemodel->c_control .'</strong>' );
                $model->delete();
			}
		}

		return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionMyLeadsa()
    {
        $_GET['my-leadsa'] = true;
//        if(!Yii::$app->user->can('Lead.MyLead')){
//            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
//        }
        $searchModel = new LeadSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        if(!empty($_REQUEST['multiple_del'])){
            $rows=$_REQUEST['selection'];
            for($i=0;$i<count($rows);$i++){
                $model = $this->findModel($rows[$i]);
                //   $model->active = 0;
                //   $model->save();
                $recyclemodel = new LeadRecycle;
                $recyclemodel->id = $model->id;
                $recyclemodel->office_id = $model->office_id;
                $recyclemodel->c_control = $model->c_control;
                $recyclemodel->c_contract = $model->c_contract;
                $recyclemodel->lead_name = $model->lead_name;
                $recyclemodel->lead_master_status_id = $model->lead_master_status_id;
                $recyclemodel->lead_insurance_id = $model->lead_insurance_id;
                $recyclemodel->lead_description = $model->lead_description;
                $recyclemodel->lead_type_id =$model->lead_type_id;
                $recyclemodel->lead_owner_id = $model->lead_owner_id;
                $recyclemodel->lead_status_id = $model->lead_status_id;
                $recyclemodel->lead_source_id = $model->lead_source_id;
                $recyclemodel->email = $model->email;
                $recyclemodel->first_name = $model->first_name;
                $recyclemodel->last_name = $model->last_name;
                $recyclemodel->middle_name = $model->middle_name;
                $recyclemodel->phone = $model->phone;
                $recyclemodel->mobile = $model->mobile;
                $recyclemodel->product_id = $model->product_id;
                $recyclemodel->loan_amount = $model->loan_amount;
                $recyclemodel->loan_interest = $model->loan_interest;
                $recyclemodel->loan_commission = $model->loan_commission;
                $recyclemodel->loan_term = $model->loan_term;
                $recyclemodel->payed = $model->payed;
                $recyclemodel->rfc = $model->rfc;
                $recyclemodel->curp = $model->curp;
                $recyclemodel->age = $model->age;
                $recyclemodel->birthdate = $model->birthdate;
                $recyclemodel->place_of_birth = $model->place_of_birth;
                $recyclemodel->civil_status = $model->civil_status;
                $recyclemodel->civil_status_regime = $model->civil_status_regime;
                $recyclemodel->spouse_job = $model->spouse_job;
                $recyclemodel->spouse_monthly_income = $model->spouse_monthly_income;
                $recyclemodel->monthly_income = $model->monthly_income;
                $recyclemodel->monthly_income2 = $model->monthly_income2;
                $recyclemodel->monthly_expenses = $model->monthly_expenses;
                $recyclemodel->home_status = $model->home_status;
                $recyclemodel->bureau_status = $model->bureau_status;
                $recyclemodel->bureau_status_desc = $model->bureau_status_desc;
                $recyclemodel->active_loans = $model->active_loans;
                $recyclemodel->economic_dep = $model->economic_dep;
                $recyclemodel->company_name = $model->company_name;
                $recyclemodel->job = $model->job;
                $recyclemodel->labor_old = $model->labor_old;
                $recyclemodel->c_cuenta = $model->c_cuenta;
                $recyclemodel->contract_date = $model->contract_date;
                $recyclemodel->valid_admin = $model->valid_admin;
                $recyclemodel->valid_manager = $model->valid_manager;
                $recyclemodel->valid_sales = $model->valid_sales;
                $recyclemodel->service_status_id = $model->service_status_id;
                $recyclemodel->service_owner_id = $model->service_owner_id;
                $recyclemodel->payment_folio = $model->payment_folio;
                $recyclemodel->added_at = $model->added_at;
                $recyclemodel->updated_at = $model->updated_at;
                $recyclemodel->converted_at = $model->converted_at;
                $recyclemodel->customer_id = $model->customer_id;
                $recyclemodel->user_id = $model->user_id;
                $recyclemodel->progress = $model->progress;
                $recyclemodel->active = $model->active;
                $recyclemodel->save();
                $payments = \livefactory\models\Payment::find()->where('entity_id = ' . $model->id)->all();
                foreach ($payments as $payment) {
                    $recyclepayment = new PaymentRecycle;
                    $recyclepayment->id = $payment->id;
                    $recyclepayment->generator_id = $payment->generator_id;
                    $recyclepayment->co_generator_id = $payment->co_generator_id;
                    $recyclepayment->amount = $payment->amount;
                    $recyclepayment->total_due = $payment->total_due;
                    $recyclepayment->note = $payment->note;
                    $recyclepayment->type = $payment->type;
                    $recyclepayment->date = $payment->date;
                    $recyclepayment->code = $payment->code;
                    $recyclepayment->origin = $payment->origin;
                    $recyclepayment->folio = $payment->folio;
                    $recyclepayment->received = $payment->received;
                    $recyclepayment->entity_id = $payment->entity_id;
                    $recyclepayment->entity_type = $payment->entity_type;
                    $recyclepayment->file_id = $payment->file_id;
                    $recyclepayment->added_at = $payment->added_at;
                    $recyclepayment->updated_at = $payment->updated_at;
                    $recyclepayment->save();
                    $payment->delete();
                }
                HistoryModel::historyInsert('log', HistoryModel::ACTION_DELETE, 'Lead eliminado <strong>'.$recyclemodel->c_control .'</strong>' );
                $model->delete();
            }
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    public function actionMyLeadsi()
    {
        $_GET['my-leadsi'] = true;
//        if( ! ( Yii::$app->user->can('Insurance')) || (Yii::$app->user->can('Insurance.Customer')) || (Yii::$app->user->can('Insurance.Director')) || (Yii::$app->user->can('Admin'))){
//            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
//        }
        $searchModel = new LeadSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        if(!empty($_REQUEST['multiple_del'])){
            $rows=$_REQUEST['selection'];
            for($i=0;$i<count($rows);$i++){
                $model = $this->findModel($rows[$i]);
                //   $model->active = 0;
                //   $model->save();
                $recyclemodel = new LeadRecycle;
                $recyclemodel->id = $model->id;
                $recyclemodel->office_id = $model->office_id;
                $recyclemodel->c_control = $model->c_control;
                $recyclemodel->c_contract = $model->c_contract;
                $recyclemodel->lead_name = $model->lead_name;
                $recyclemodel->lead_master_status_id = $model->lead_master_status_id;
                $recyclemodel->lead_insurance_id = $model->lead_insurance_id;
                $recyclemodel->lead_description = $model->lead_description;
                $recyclemodel->lead_type_id =$model->lead_type_id;
                $recyclemodel->lead_owner_id = $model->lead_owner_id;
                $recyclemodel->lead_status_id = $model->lead_status_id;
                $recyclemodel->lead_source_id = $model->lead_source_id;
                $recyclemodel->email = $model->email;
                $recyclemodel->first_name = $model->first_name;
                $recyclemodel->last_name = $model->last_name;
                $recyclemodel->middle_name = $model->middle_name;
                $recyclemodel->phone = $model->phone;
                $recyclemodel->mobile = $model->mobile;
                $recyclemodel->product_id = $model->product_id;
                $recyclemodel->loan_amount = $model->loan_amount;
                $recyclemodel->loan_interest = $model->loan_interest;
                $recyclemodel->loan_commission = $model->loan_commission;
                $recyclemodel->loan_term = $model->loan_term;
                $recyclemodel->payed = $model->payed;
                $recyclemodel->rfc = $model->rfc;
                $recyclemodel->curp = $model->curp;
                $recyclemodel->age = $model->age;
                $recyclemodel->birthdate = $model->birthdate;
                $recyclemodel->place_of_birth = $model->place_of_birth;
                $recyclemodel->civil_status = $model->civil_status;
                $recyclemodel->civil_status_regime = $model->civil_status_regime;
                $recyclemodel->spouse_job = $model->spouse_job;
                $recyclemodel->spouse_monthly_income = $model->spouse_monthly_income;
                $recyclemodel->monthly_income = $model->monthly_income;
                $recyclemodel->monthly_income2 = $model->monthly_income2;
                $recyclemodel->monthly_expenses = $model->monthly_expenses;
                $recyclemodel->home_status = $model->home_status;
                $recyclemodel->bureau_status = $model->bureau_status;
                $recyclemodel->bureau_status_desc = $model->bureau_status_desc;
                $recyclemodel->active_loans = $model->active_loans;
                $recyclemodel->economic_dep = $model->economic_dep;
                $recyclemodel->company_name = $model->company_name;
                $recyclemodel->job = $model->job;
                $recyclemodel->labor_old = $model->labor_old;
                $recyclemodel->c_cuenta = $model->c_cuenta;
                $recyclemodel->contract_date = $model->contract_date;
                $recyclemodel->valid_admin = $model->valid_admin;
                $recyclemodel->valid_manager = $model->valid_manager;
                $recyclemodel->valid_sales = $model->valid_sales;
                $recyclemodel->service_status_id = $model->service_status_id;
                $recyclemodel->service_owner_id = $model->service_owner_id;
                $recyclemodel->payment_folio = $model->payment_folio;
                $recyclemodel->added_at = $model->added_at;
                $recyclemodel->updated_at = $model->updated_at;
                $recyclemodel->converted_at = $model->converted_at;
                $recyclemodel->customer_id = $model->customer_id;
                $recyclemodel->user_id = $model->user_id;
                $recyclemodel->progress = $model->progress;
                $recyclemodel->active = $model->active;
                $recyclemodel->save();
                $payments = \livefactory\models\Payment::find()->where('entity_id = ' . $model->id)->all();
                foreach ($payments as $payment) {
                    $recyclepayment = new PaymentRecycle;
                    $recyclepayment->id = $payment->id;
                    $recyclepayment->generator_id = $payment->generator_id;
                    $recyclepayment->co_generator_id = $payment->co_generator_id;
                    $recyclepayment->amount = $payment->amount;
                    $recyclepayment->total_due = $payment->total_due;
                    $recyclepayment->note = $payment->note;
                    $recyclepayment->type = $payment->type;
                    $recyclepayment->date = $payment->date;
                    $recyclepayment->code = $payment->code;
                    $recyclepayment->origin = $payment->origin;
                    $recyclepayment->folio = $payment->folio;
                    $recyclepayment->received = $payment->received;
                    $recyclepayment->entity_id = $payment->entity_id;
                    $recyclepayment->entity_type = $payment->entity_type;
                    $recyclepayment->file_id = $payment->file_id;
                    $recyclepayment->added_at = $payment->added_at;
                    $recyclepayment->updated_at = $payment->updated_at;
                    $recyclepayment->save();
                    $payment->delete();
                }
                HistoryModel::historyInsert('log', HistoryModel::ACTION_DELETE, 'Lead eliminado <strong>'.$recyclemodel->c_control .'</strong>' );
                $model->delete();
            }
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionAppointmentinsert () {
        $id = $_GET['id'];

        $model = $this->findModel($id);
        if ( AppointmentModel::appointmentInsert($_POST['lead_id'], 'lead')) {
            HistoryModel::historyInsert($this->entity_type,$model->id,'Cita creada',0 , UserActivity::FIVE_MINUTES);
            return $this->redirect(['view','id'=>$id]);
        } else {
            return $this->redirect(['view','id'=>$id, 'nodate' => 1]);
        }
    }

    public function actionAppointmentdelete () {
        $id = $_GET['id'];
        $model = $this->findModel($id);

        Appointment::findOne($_GET['appointment_del'])->delete();
        //Add History

        HistoryModel::historyInsert($this->entity_type,$model->id,'Cita eliminada',0,UserActivity::FIVE_MINUTES);

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionAppointmentupdate () {
        $id = $_GET['id'];
        $model = $this->findModel($id);
        $statuses = [
            '-1' => 'Vencida',
            '0' => 'Vigente',
            '1' => 'Concretada',
            '2' => 'No concretada'
        ];
        $type = [0 => 'En llamada' , 1 => 'En oficina'];

        $note = 'Cita actualizada';
        $appointment = Appointment::findOne($_POST['appointment_id']);
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
        {
               $appointment = Appointment::findOne($_GET['appointment_edit']);
               AppointmentModel::appointmentUpdate($_GET['appointment_edit']);
               $_POST['appointment_id']=$_GET['appointment_edit'];
               if($_POST['type_appointment'] == 0)
               {
                   HistoryModel::historyInsert($appointment->entity_type,$appointment->entity_id, 'Se cambio el tipo de cita de <strong>'.$type[1] . '</strong> a <strong>' .$type[0] . '</strong>');
               }
               else
               {
                   HistoryModel::historyInsert($appointment->entity_type,$appointment->entity_id, 'Se cambio el tipo de cita de <strong>'.$type[0] .'</strong> a <strong>' .$type[1] . '</strong>');
               }
        }

        $old_status = $statuses[$appointment->status];
        $new_status = $statuses[$_POST['status']];
        if ($old_status != $new_status && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $note .= '. Cambió estado de: <strong>' . $old_status . '</strong> a <strong>'.$new_status.'</strong>';
        }

        AppointmentModel::appointmentUpdate($_POST['appointment_id']);
        //update appointment model
        HistoryModel::historyInsert($this->entity_type,$model->id, $note, 0 , UserActivity::FIVE_MINUTES);
        return $this->redirect(['view','id'=>$id]);
    }

    public function actionAppointmentstatus () {
        $id = $_GET['id'];
        $model = $this->findModel($id);
        AppointmentModel::appointmentConcreted($_GET['appointment_status']);
        HistoryModel::historyInsert($this->entity_type,$model->id,'Cita Concretada');
        return $this->redirect(['view','id'=>$id]);
    }

    public function actionPaymentinsert () {
        $id = $_GET['id'];
        $model = $this->findModel($id);
        //payment repayment or refused
        $amounts = $_POST['amount'];
        $type_payment = [6 => 'Devolución de ' , 7 => 'Pago Rechazado de '];
        if (PaymentModel::paymentInsert($_POST['lead_id'], 'lead')) {
            if ($_POST['type'] == 6 or $_POST['type'] == 7)
                HistoryModel::historyInsert($this->entity_type,$model->id, $type_payment[$_POST['type']] . '<strong> -' . $amounts . '</strong>',0,UserActivity::FIVE_MINUTES);
            else
            HistoryModel::historyInsert($this->entity_type,$model->id,'Por la cantidad de  <strong>' . $amounts . '</strong>', 0 , UserActivity::FIVE_MINUTES);
        }
        return $this->redirect(['view','id'=>$id]);
    }

    /**
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionPaymentdelete () {
        $id = $_GET['id'];
        $model = $this->findModel($id);

        $payment = Payment::findOne($_GET['payment_del']);
        $payment_amount = $payment->amount;
        if ($payment && $payment->delete()) {
            //Add History
            $model->payment_folio = $model->payment_folio - 1;
            $model->save();

            HistoryModel::historyInsert($this->entity_type,$model->id,'Pago eliminado por <strong>$' . number_format($payment_amount, 2) . '</strong>',0,UserActivity::FIVE_MINUTES);
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionPaymentupdate () {
        $id = $_GET['id'];
        PaymentModel::PaymentUpdate($_POST['payment_id']);
        return $this->redirect(['view','id'=>$id]);
    }

    /**
     * Displays a single Lead model.
     * @param integer $id
     * @return mixed
     */

    public function actionView($id)
    {
        $model = $this->findModel($id);
        //
        if (Yii::$app->request->getQueryParam('_pjax') == null)
            HistoryModel::historyInsert('log', HistoryModel::ACTION_VIEW_LEAD, 'Consultó el lead con folio <strong>'.$model->c_control.'</strong>');

        return $this->renderView($model);
    }

    public function actionViewRecycle($id)
    {
        return $this->renderView($this->findModelRecycle($id));
    }

    protected function renderView($model) {

		$img = new ImageUpload();
		$emailObj = new SendEmail;
        //$model = $this->findModel($id);
        if(!(Yii::$app->user->can('Lead.View') || Yii::$app->user->can('Lead.Update'))){
            return $this->render('forbidden', [
                'model' => $model
            ]);
        }

        if ( ! Yii::$app->user->can('Office.NoLimit') && Yii::$app->user->identity->office_id != $model->office_id) {
            //throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
            return $this->render('forbidden', [
                'model' => $model
            ]);
        }
//sales person and view lead
//        if (Yii::$app->user->can('Sales Person') && Yii::$app->user->id != $model['lead_owner_id'] )
//        {
//            return $this->render('forbidden',[
//                'model' => $model
//            ]);
//        }


		$addressModel = Address::find()->where("entity_type='lead' and entity_id='".$id."'")->one();

		/// Contact Primary
		if(!empty($_GET['primary'])){
			$contactModel = Contact::find()->where("entity_type='lead' and entity_id=".$model->id." and is_primary=1")->one();
			if(!is_null($contactModel)){
				$contactModel->is_primary=0;
				$contactModel->save();
				if (($obj = Contact::findOne($_GET['primary'])) !== null) {
					$obj->is_primary=1;
					$obj->save();

					// update lead contact details
					$lead_model = $this->findModel($obj->entity_id);
					$lead_model->first_name = mb_strtoupper($obj->first_name);
					$lead_model->last_name = mb_strtoupper($obj->last_name);
					$lead_model->email = $obj->email;
					$lead_model->phone = $obj->phone;
					$lead_model->mobile = $obj->mobile;
					$lead_model->fax = $obj->fax;
					$lead_model->save();
				}
			}else{
				if (($obj = Contact::findOne($_GET['primary'])) !== null) {
					$obj->is_primary=1;
					$obj->save();

					// update lead contact details
					$lead_model = $this->findModel($obj->entity_id);
					$lead_model->first_name = $obj->first_name;
					$lead_model->last_name = $obj->last_name;
					$lead_model->email = $obj->email;
					$lead_model->phone = $obj->phone;
					$lead_model->mobile = $obj->mobile;
					$lead_model->fax = $obj->fax;
					$lead_model->save();
				}
			}
			return $this->redirect(['view','id'=>$model->id]);
		}
		/// Address Primary
		if(!empty($_GET['address_primary'])){
			$addressModel = Address::find()->where("entity_type='lead' and entity_id=".$model->id." and is_primary=1")->one();
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
			return $this->redirect(['view','id'=>$model->id]);
		}
		$attachModelR=$noteModelR=$sub_address_model='';
		if(!empty($_GET['cus_user_del'])){
			$contactObj = Contact::findOne($_GET['cus_user_del']);
			if(!is_null($contactObj)){
                $userDel = UserDetail::find()->andwhere("username='".$contactObj->email."' and user_type_id=".UserTypeSearch::getCompanyUserType('Customer')->id)->one();
				if(!is_null($userDel)){
					$userDel->delete();
					return $this->redirect(['view','id'=>$model->id]);
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
				$userModel->entity_type = 'lead';
				$userModel->added_at = strtotime(date('Y-m-d H:i:s'));
				$new_password = Yii::$app->security->generateRandomString (8);
				$userModel->password_hash=Yii::$app->security->generatePasswordHash ($new_password);
				$userModel->save();
				if(count($userModel->errors) >0){
					var_dump($userModel->errors);
				}else{
					$authModel = new AuthAssignment;
					$authModel->item_name = 'Lead';
					$authModel->user_id = $userModel->id;
					$authModel->save();
					$img->loadImage('../leads/nophoto.jpg')->saveImage("../leads/".$userModel->id.".png");
					$img->loadImage('../leads/nophoto.jpg')->resize(30, 30)->saveImage("../leads/user_".$userModel->id.".png");
					$emailObj->sendNewUserEmailTemplate($userModel->email,$userModel->first_name." ".$userModel->last_name, $userModel->username,$new_password);
				}
			}
			return $this->redirect(['view','id'=>$model->id]);
		}
		if(isset($_FILES['lead_image'])){
			 $files = array();
				move_uploaded_file($_FILES['lead_image']['tmp_name'],'../leads/'.$model->id.'.png');

			}

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//AddressModel::addressUpdate($model->address_id);

			if ($model->lead_status_id == LeadStatus::_CONVERTED) // If lead status is converted then create a new customer
			{
				$customerObj = new Customer;
				$customerObj->customer_name = $model->lead_name;
				//$customerObj->customer_type_id = $model->lead_type_id;
				$customerObj->customer_owner_id = $model->lead_owner_id;
				$customerObj->email = $model->email;
				if(!empty($model->first_name))
				$customerObj->first_name = $model->first_name;
				else
				$customerObj->first_name = 'Not Given';

				if(!empty($model->last_name))
				$customerObj->last_name = $model->last_name;
				else
				$customerObj->last_name = 'Not Given';

				$customerObj->phone = $model->phone;
				$customerObj->mobile = $model->mobile;
				$customerObj->fax = $model->fax;
				$customerObj->added_at = time();

				$customerObj->save();

				$model->customer_id = $customerObj->id;


				// Add all address data to newly created customer
				$items = Address::find()->where("entity_type='lead' and entity_id=".$model->id)->all();

				foreach ($items as $item)
				{
					$addressObj = new Address;
					$addressObj->address_1 = $item->address_1;
					$addressObj->address_2 = $item->address_2;
					$addressObj->country_id = $item->country_id;
					$addressObj->state_id = $item->state_id;
					$addressObj->city_id = $item->city_id;
					$addressObj->zipcode = $item->zipcode;
					$addressObj->is_primary = $item->is_primary;
					$addressObj->entity_id = $customerObj->id;
					$addressObj->entity_type = 'customer';

					$addressObj->save();
				}

				// Add all contact data to newly created customer
				$items = Contact::find()->where("entity_type='lead' and entity_id=".$model->id)->all();

				foreach ($items as $item)
				{
					$contObj = new Contact;
					$contObj->first_name = $item->first_name;
					$contObj->last_name = $item->last_name;
					$contObj->email = $item->email;
					$contObj->phone = $item->phone;
					$contObj->mobile = $item->mobile;
					$contObj->fax = $item->fax;
					$contObj->is_primary = $item->is_primary;
					$contObj->entity_id = $customerObj->id;
					$contObj->entity_type = 'customer';

					$contObj->save();

					if($item->is_primary ==1)
					{
						$customerObj->first_name = $item->first_name;
						$customerObj->last_name = $item->last_name;
						$customerObj->email = $item->email;
						$customerObj->phone = $item->phone;
						$customerObj->mobile = $item->mobile;
						$customerObj->fax = $item->fax;
						$customerObj->update();
					}
				}
			}

			/* Update Lead Table */
			$model->updated_at = time();
			$model->update();

			//Add History
			HistoryModel::historyInsert($this->entity_type,$model->id,'Cliente creado para el Lead');


			if(!empty($_FILES['lead_image']['tmp_name'])){

				move_uploaded_file($_FILES['lead_image']['tmp_name'],'../leads/'.$model->id.'.png');

			}

            return $this->redirect(['index']);

        } else {

			if(!empty($_REQUEST['sendemaildesc'])){

				//Send an Email

				SendEmail::sendLiveEmail($_REQUEST['toemail'],$_REQUEST['sendemaildesc'], $_REQUEST['cc'], $_REQUEST['subject']);

				return $this->redirect(['view', 'id' => $_REQUEST['id'], 'msg' => 'Email is sent']);

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

			HistoryModel::historyInsert($this->entity_type,$model->id,'Contacto Actualizado');

						return $this->redirect(['view', 'id' => $_REQUEST['id']]);

					}else{

						$con_id=ContactModel::contactInsert($_REQUEST['id'],'lead');

						//Add History

			HistoryModel::historyInsert($this->entity_type,$model->id,'Contacto Agregado');

					}

				}

			}

			// Contact Delete

			if(!empty($_REQUEST['contact_del'])){

				Contact::findOne($_REQUEST['contact_del'])->delete();

				//Add History

			HistoryModel::historyInsert($this->entity_type,$model->id,'Contacto Eliminado');

				return $this->redirect(['view', 'id' => $_REQUEST['id']]);

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

			HistoryModel::historyInsert($this->entity_type,$model->id,'Dirección Eliminada');

				return $this->redirect(['view', 'id' => $_REQUEST['id']]);

			}

			// Address Add / Update

			if(!empty($_REQUEST['addressae'])){

				if(!empty($_REQUEST['sub_address_1'])){

					if(!empty($_REQUEST['address_id'])){

						AddressModel::subAddressUpdate($_REQUEST['address_id']);

						//Add History

			HistoryModel::historyInsert($this->entity_type,$model->id,'Dirección Actualizada');

						return $this->redirect(['view', 'id' => $_REQUEST['id']]);

					}else{

						//Add History

			HistoryModel::historyInsert($this->entity_type,$model->id,'Dirección Agregada');

						$sub_aid=AddressModel::subAddressInsert($model->id,'lead');

					}

				}

			}

            if(!empty($_REQUEST['appointment_edit'])){
                $appointment_model=Appointment::findOne($_REQUEST['appointment_edit']);
            }

            if(!empty($_REQUEST['payment_edit'])){
                $payment_model=Payment::findOne($_REQUEST['payment_edit']);
            }

			if(!empty($_REQUEST['send_attachment_file'])){

				//Send an Email

				SendEmail::sendLiveEmail($_REQUEST['uemail'],$_REQUEST['email_body'], $_REQUEST['cc'], $_REQUEST['subject']);

					return $this->redirect(['view', 'id' => $_REQUEST['id']]);

			}

			// Delete  Attachment

			if(!empty($_REQUEST['attachment_del_id'])){

					$Attachmodel = File::findOne($_REQUEST['attachment_del_id'])->delete();

					//Add History

			HistoryModel::historyInsert($this->entity_type,$model->id,'Archivo Adjunto Eliminado');

					return $this->redirect(['view', 'id' => $_REQUEST['id']]);

			}

			// Delete  Notes

			if(!empty($_REQUEST['note_del_id'])){

					$NoteDel = Note::findOne($_REQUEST['note_del_id'])->delete();

					//Add History

			HistoryModel::historyInsert($this->entity_type,$model->id,'Nota Eliminada',0 , UserActivity::THREE_MINUTES);

					return $this->redirect(['view', 'id' => $_REQUEST['id']]);

			}

			// Delete  Estimate
			if(!empty($_REQUEST['estimate_del'])){

					$EstimateDel = Estimate::findOne($_REQUEST['estimate_del'])->delete();

					//Add History

			HistoryModel::historyInsert($this->entity_type,$model->id,'Estimado Eliminado');

					return $this->redirect(['view', 'id' => $_REQUEST['id']]);

			}

			// Add Attachment for Lead

			if(!empty($_REQUEST['add_attach'])){

			    $entity_type = $this->entity_type . '.' . $_REQUEST['entity_type'];
				$aid=FileModel::fileInsert($_REQUEST['entity_id'], $entity_type);
				if($aid > 0)
				{
					$link="<a href='".str_replace('web/index.php','',$_SESSION['base_url'])."attachments/".$aid.strrchr($_FILES['attach']['name'], ".")."'>".$_FILES['attach']['name']."</a>";

					$emailObj->sendAddAttachmentEmailTemplate($this->getUserEmail($model->lead_owner_id),$this->getUserFullName($model->lead_owner_id),$link,'<a href="'.$_SESSION['base_url'].'?r=sales/lead/view&id='.$model->id.'">'.$model->lead_name.'</a>');

					//SendEmail::sendLiveEmail($model->email,$link, false,$this->getLoggedUserFullName());

					//Add History

					HistoryModel::historyInsert($this->entity_type,$model->id,'Archivo Adjunto Agregado');

						return $this->redirect(['view', 'id' => $_REQUEST['id']]);
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

					return $this->redirect(['view', 'id' => $_REQUEST['id'], 'err_msg' => $msg]);
				}

			}

			// Lead Attachment get

			if(!empty($_REQUEST['attach_update'])){

				$attachModelR=File::findOne($_REQUEST['attach_update']);

			}

			// Lead Notes get

			if(!empty($_REQUEST['note_id'])){

				$noteModelR=Note::findOne($_REQUEST['note_id']);

			}

			// Lead Attachment Update

			if(!empty($_REQUEST['edit_attach'])){

					$file=FileModel::fileEdit();

					if(!empty($_FILES['attach']['name'])){

						$aid=$_REQUEST['att_id'];

						$link="<a href='".str_replace('web/index.php','',$_SESSION['base_url'])."attachments/".$aid.strrchr($_FILES['attach']['name'], ".")."'>".$_FILES['attach']['name']."</a>";

						//Send an Email

						$emailObj->sendUpdateAttachmentEmailTemplate($model->email,$model->lead_name,$link,'<a href="'.$_SESSION['base_url'].'?r=sales/lead/view&id='.$model->id.'">'.$model->lead_name.'</a>');

						//SendEmail::sendLiveEmail($model->email,$link, false,$this->getLoggedUserFullName());

						//Add History

			HistoryModel::historyInsert($this->entity_type,$model->id,'Archivo Adjunto Actualizado');

					}

					return $this->redirect(['view', 'id' => $_REQUEST['id']]);

			}



			// Add Notes

			if(!empty($_REQUEST['add_note_model'])){

				$nid = NoteModel::noteInsert($_REQUEST['id'],$this->entity_type);

				if(!empty($nid)){

					setcookie('inserted_notes'.$_REQUEST['id'],true,time()+7200);

				}

				//Send an Email

				$emailObj->sendNoteEmailTemplate($this->getUserEmail($model->lead_owner_id),$this->getUserFullName($model->lead_owner_id),$this->getLoggedUserFullName()." <br>".$_REQUEST['notes'],'<a href="'.$_SESSION['base_url'].'?r=sales/lead/view&id='.$model->id.'">'.$model->lead_name.'</a>');

				//SendEmail::sendLiveEmail($model->email,"A new note added by ".$this->getLoggedUserFullName()." ".$_REQUEST['notes'], false,'New Note Added');

				//Add History

			HistoryModel::historyInsert($this->entity_type,$model->id,'Nota Agregada', 0 ,UserActivity::THREE_MINUTES);

				return $this->redirect(['view', 'id' => $_REQUEST['id']]);

			}



			// Update Notes

			if(!empty($_REQUEST['edit_note_model'])){
                $note = Note::find()->where('id = ' . $_REQUEST['note_id'])->one();
			    if ($note->user_id == Yii::$app->user->id)
                {
                    $nid = NoteModel::noteEdit();

                    //Send an Email

                    //SendEmail::sendLiveEmail($model->email,"Lead Notes Update by ".$this->getLoggedUserFullName()." ".$_REQUEST['notes'], false,'Lead Update');


                    $emailObj->sendNoteUpdateEmailTemplate($this->getUserEmail($model->lead_owner_id),$this->getUserFullName($model->lead_owner_id),$this->getLoggedUserFullName()." <br>".$_REQUEST['notes'],'<a href="'.$_SESSION['base_url'].'?r=sales/lead/view&id='.$model->id.'">'.$model->lead_name.'</a>');

                    //Add History

                    HistoryModel::historyInsert($this->entity_type,$model->id,'Nota Actualizada',0, UserActivity::THREE_MINUTES);

                    return $this->redirect(['view', 'id' => $_REQUEST['id']]);
                }
                else
                    return $this->redirect(['view', 'id' => $_REQUEST['id']]);

			}

            return $this->render('view', [

                'model' => $model,

				'addressModel'=>$addressModel,

				'attachModel'=>$attachModelR,

				'noteModel'=>$noteModelR,

				'sub_address_model'=>$sub_address_model,

                'appointment_model'=>$appointment_model,

                'payment_model'=>$payment_model,

				'contact'=>$contact,


            ]);

        }
    }

    // ---------------------------------------------------------------------

    /**
     * Lead Address
     */
    public function actionContact()
    {

        switch (Yii::$app->request->getQueryParam('type')) {
            case 'update':

                $contact = Contact::findOne(Yii::$app->request->post('contact_id'));
                $contactData = Yii::$app->request->post('contact');

                $history = false;

                if ($contact) {
                    $contact->phone = $contactData['phone'];
                    $contact->phone_ext = $contactData['phone_ext'];
                    $contact->mobile = $contactData['mobile'];
                    $contact->email = $contactData['email'];
                    $contact->updated_at = time();
                    $history = $contact->save();

                    if ($contact->is_primary == 1) {
                        $lead = Lead::findOne($contact->entity_id);
                        $lead->mobile = $contact->mobile;
                        $lead->phone = $contact->phone;
                        $lead->save();
                    }
                }

                $address = Address::findOne(Yii::$app->request->post('address_id'));
                $addressData = Yii::$app->request->post('address');

                if ($address) {
                    $address->address_1 = $addressData['address_1'];
                    $address->num_ext = $addressData['num_ext'];
                    $address->num_int = $addressData['num_int'];
                    $address->block = $addressData['block'];
                    $address->zipcode = $addressData['zipcode'];
                    $address->delegation = $addressData['delegation'];
                    $address->state_id = $addressData['state_id'];
                    $address->city_id = $addressData['city_id'];
                    $address->updated_at = time();

                    $history = $history && $address->save();
                }

                if ($history) {
                    HistoryModel::historyInsert('lead', $contact->entity_id, 'Actualizó los datos de Contacto / Domicilio');
                }

                // Update progress
                Lead::generateProgressLead($contact->entity_id, true);

                break;
        }
    }

    public function actionEconomic()
    {
        $lead = Lead::findOne(Yii::$app->request->post('lead_id'));

        if(! LeadStatus::canChange($lead, true)){
            echo 'forbidden';
            return;
        }

        // Update && save
        if ($lead->load(Yii::$app->request->post()) && $lead->save()) {
            HistoryModel::historyInsert('lead', $lead->id, 'Actualizó los datos Económicos / Empleo');
        }

        // Job Contact
        $contact_id = Yii::$app->request->post('contact_id');
        if (empty($contact_id)) {
            $contact = Contact::findOne(['entity_id' => $lead->id, 'entity_type' => 'lead.job']);

            if ($contact) {
                $contact_id = $contact->id;
            }
        }

        $_REQUEST = Yii::$app->request->post('contact');
        $_REQUEST['first_name'] = $lead->company_name;

        if ( ! empty($contact_id)) {
            ContactModel::contactUpdate($contact_id);
        } else {
            ContactModel::contactInsert($lead->id, 'lead.job');
        }

        // Job Address
        $address_id = Yii::$app->request->post('address_id');
        if (empty($address_id)) {
            $address = Address::findOne(['entity_id' => $lead->id, 'entity_type' => 'lead.job']);

            if ($address) {
                $address_id = $address->id;
            }
        }

        $_REQUEST = Yii::$app->request->post('address');

        if ( ! empty($address_id)) {
            AddressModel::addressUpdate($address_id);
        } else {
            AddressModel::addressInsert($lead->id, 'lead.job');
        }

        // Update progress
        Lead::generateProgressLead($lead->id, true);
    }

    /**
     *
     */
    public function actionSpouse()
    {
        $lead = Lead::findOne(Yii::$app->request->post('lead_id'));

        if(! LeadStatus::canChange($lead, true)){
            echo 'forbidden';
            return;
        }

        // Update && save
        if ($lead->load(Yii::$app->request->post()) && $lead->save()) {
            HistoryModel::historyInsert('lead', $lead->id, 'Actualizó los datos Estado Civil / Cónyuge');
        }

        $contact_id = Yii::$app->request->post('contact_id');

        $_REQUEST = Yii::$app->request->post('contact');

        if ( ! empty($contact_id)) {
            ContactModel::contactUpdate($contact_id);
        } else {
            ContactModel::contactInsert($lead->id, 'lead.spouse');
        }

        // Update progress
        Lead::generateProgressLead($lead->id, true);
    }

    public function actionGeneral()
    {
        $lead = Lead::findOne(Yii::$app->request->post('lead_id'));
        $lead->lead_name = $_REQUEST['Lead']['first_name'] . ' ' . $_REQUEST['Lead']['middle_name'] . ' ' . $_REQUEST['Lead']['last_name'];

        if(! LeadStatus::canChange($lead, true)){
            echo 'forbidden';
            return;
        }

        $loan_interest = $lead->loan_interest;
        $loan_commission = $lead->loan_commission;
        $loan_amount = $lead->loan_amount;
        $lead->qualify = Yii::$app->request->post('quality');
        $lead->update();
        // Update && save
        if ($lead->load(Yii::$app->request->post()) && $lead->save()) {
            HistoryModel::historyInsert('lead', $lead->id, 'Actualizó los datos generales');
        }

        $contact = Contact::findOne(['entity_id' => $lead->id, 'entity_type' => 'lead']);

        if ($contact && $contact->is_primary == 1) {
            $contact->first_name = $lead->first_name;
            $contact->last_name = $lead->last_name;
            $contact->middle_name = $lead->middle_name;
            $contact->save();
        }

        //
        if ($loan_amount != $lead->loan_amount)
        {
            HistoryModel::historyInsert('lead', $lead->id , 'Actualizó el monto de $' . number_format($loan_amount,2) . ' a $' . number_format($lead->loan_amount,2));
        }

        if($lead->loan_interest != $loan_interest) {

            HistoryModel::historyInsert('lead', $lead->id, 'Modificó el monto de la comisión de <strong>$'.number_format($loan_commission, 2).'</strong> a <strong>$'.number_format($lead->loan_commission, 2).'</strong>');

            echo 'reload';
        }

        // Update progress
        Lead::generateProgressLead($lead->id, true);
    }

    /**
     * Add or update reference data
     */
    public function actionReference()
    {
        $lead = Lead::findOne(Yii::$app->request->post('lead_id'));
        $entity_type = Yii::$app->request->post('entity_type');

        if(! LeadStatus::canChange($lead, true)){
            echo 'forbidden';
            return;
        }

        // Job Contact
        $contact_id = Yii::$app->request->post('contact_id');
        if (empty($contact_id)) {
            $contact = Contact::findOne(['entity_id' => $lead->id, 'entity_type' => $entity_type]);

            if ($contact) {
                $contact_id = $contact->id;
            }
        }

        $_REQUEST = Yii::$app->request->post('contact');

        if ( ! empty($contact_id)) {
            ContactModel::contactUpdate($contact_id);
        } else {
            ContactModel::contactInsert($lead->id, $entity_type);
        }

        // Job Address
        $address_id = Yii::$app->request->post('address_id');
        if (empty($address_id)) {
            $address = Address::findOne(['entity_id' => $lead->id, 'entity_type' => $entity_type]);

            if ($address) {
                $address_id = $address->id;
            }
        }

        $_REQUEST = Yii::$app->request->post('address');

        if ( ! empty($address_id)) {
            AddressModel::addressUpdate($address_id);
        } else {
            AddressModel::addressInsert($lead->id, $entity_type);
        }

        // Update progress
        Lead::generateProgressLead($lead->id, true);
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
    /**
     * Creates a new Lead model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        //|| Yii::$app->user->identity->office_id == 2
		if(!Yii::$app->user->can('Lead.Create') ){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		$img = new ImageUpload();
		$emailObj = new SendEmail;
		$ip = $this->get_ip_address();
        $model = new Lead;
        $office= isset($_POST['office_id']) ? $_POST['office_id'] : $_POST['Lead']['office_id'];
        $duplicated = Yii::$app->request->post('duplicated');

        if ($model->load(Yii::$app->request->post()))
		{
			if($model->lead_status_id == '')
				$model->lead_status_id = LeadStatus::_NEW;

			if ($model->lead_master_status_id == '')
			    $model->lead_master_status_id = LeadStatus::_MASTER_SALES;

			if ($model->lead_type_id == '')
			    $model->lead_type_id = 3;

            if ($model->lead_name == '')
                $model->lead_name = $model->first_name . ' ' . $model->last_name  . ' ' . $model->middle_name;

            $aux = $model->middle_name;
            $model->middle_name = $model->last_name;
            $model->last_name = $aux;

            if (empty($model->lead_owner_id))
                $model->lead_owner_id = Yii::$app->user->can('Lead.Office') ? 228 : Yii::$app->user->id;

            // Office is user office
            if(Yii::$app->user->id == 173 || $model->lead_owner_id == 228)
            {
                $model->office_id = $office;
            }
            else
            if ($model->lead_owner_id != Yii::$app->user->id) {
                $owner = User::findOne($model->lead_owner_id);
                $model->office_id = $owner->office_id;
            } else {
                $model->office_id = Yii::$app->user->identity->office_id;
            }

            // Save every time what user create this lead
            $model->ip_address = $ip;
            $model->active=1;
            $model->user_id = Yii::$app->user->id;
            $model->added_at=time();
            $model->qualify = Yii::$app->request->post('quality');


			if($model->save())
			{
				/* Begin changes to save address details and contact details with new lead creation */
				$address_id = AddressModel::addressInsert($model->id,'lead');

				$updateLead =  Lead::findOne($model->id);
				//$updateLead->added_at=time();
                $updateLead->c_control = $this->generateFolio($model);
				$updateLead->update();

				//Lead Add Contact
				$contactae = new Contact();

				$contactae->first_name = $model->first_name;
				$contactae->last_name = $model->last_name;
				$contactae->middle_name = $model->middle_name;

				$contactae->email = $model->email;

				$contactae->phone = $model->phone;
				$contactae->mobile = $model->mobile;

				$contactae->entity_id = $model->id;
				$contactae->entity_type = 'lead';
				$contactae->is_primary = 1;
				$contactae->added_at=time();

				$contactae->save();

				//Add History
				HistoryModel::historyInsert('lead',$model->id,'Lead Creado', 0, UserActivity::SIX_MINUTES);
				//Add History duplicated
                if ($duplicated == 1)
                HistoryModel::historyInsert('lead' , $model->id , 'Lead Creado con Datos de otro Lead');

                // Update progress
                Lead::generateProgressLead($model->id, true);

				/* End changes to save address details and contact details with new lead creation */
				return $this->redirect(['view', 'id' => $model->id]);
			} /*elseif (Yii::$app->request->isAjax) {
				return $this->renderAjax('_form', [
							'model' => $model
				]);
			}*/else {
				return $this->render('create', [
					'model' => $model,
				]);
			}
		}
		else
		{
			return $this->render('create', [
					'model' => $model,
				]);
		}
    }

    public function actionUpload()
    {
        if((!Yii::$app->user->can('Admin'))  or (!Yii::$app->user->id == 173)){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }
        $ip = $this->get_ip_address();
        $error = false;
        $repeats = 0;
        $registers = 0;
        $loan_amounts = ['60mila120mil' => "60000" , '120mila600mil' => "120000" , '600mila1.2millones' => "600000" , '1.2a3millones' => "1200000" , '3a6millones' => "3000000" , "msde6millones" => "6000000" ];
        if (Yii::$app->request->isPost && !empty($_FILES['excel']['tmp_name'])) {


            $office_id = Yii::$app->request->post('office_id');
            $source_id = Yii::$app->request->post('source_id');
            $loan_amount = Yii::$app->request->post('loan_amount');
            $type_csv = Yii::$app->request->post('type_csv');
            $csv = [1 => "\t" , 2 => "," ];
            if (($gestor = fopen($_FILES['excel']['tmp_name'], "r")) !== FALSE) {
                /// caracter separator
                while (($datos = fgetcsv($gestor, 1000, $csv[$type_csv], "'", "\\")) !== FALSE) {
                    $size = count($datos);
                    // Only 9 sizes

                    if ($size != 9 AND $size != 10 AND $size != 3)
                        continue;
                    //
                    $name = preg_replace("/[^A-Za-z0-9 ]/", '', utf8_decode(trim($datos[$size - 2])));;
                    $phone = preg_replace('/[^0-9]/', '', $datos[$size - 1]);
                    $phone = strlen($phone) > 10 ? substr($phone, 2) : $phone;
                    $phone = strlen($phone) == 11 && $phone[0] == "1" ? substr($phone, 1) : $phone;
                    if ($size == 10) {
                        $loan_amount_label = preg_replace("/[^A-Za-z0-9. ]/", '', utf8_decode(trim($datos[$size - 3])));
                    }
                    // Search for a previous lead
                    $query = (new Query())->select('c_control')->from('tbl_lead');
                    $query->where("replace(mobile, ' ', '') LIKE '%$phone%'");
                    $temp = strtok($name," ");
                    $count=0;
                    while($temp !== false) {
                    // En los tokens subsecuentes no se include el string $cadena
                        $temp1=$temp;
                        $temp = strtok(" \n\t");
                        $count++;
                    }
                    if ($query->count()) {
                        $repeats++;
                    } else {
                        $data['Lead'] = [];


                        if($count == 1)
                        {
                            $data['Lead']['first_name'] = $name;
                            $data['Lead']['last_name']='0';

                        }

                        else
                        {

                            $name=substr($name,0,-strlen($temp1));
                            $data['Lead']['first_name'] = $name;
                             $data['Lead']['last_name'] = $temp1;
                        }


                        $data['Lead']['product_id'] = 6;


                        // SELECT id FROM tbl_lead WHERE phone = $data['Lead']['phone']
                        $model = new Lead;
                        if ($model->load($data))
                        {
                            $model->lead_status_id = LeadStatus::_NEW;
                            $model->lead_type_id = 3;
                            $model->lead_name = $model->first_name . ' ' . $model->middle_name . ' ' . $model->last_name;
                            $model->lead_owner_id = 173;
                            $model->user_id= 173;

                            // Office is user office
                            $model->office_id = $office_id;
                            $model->lead_source_id=$source_id;
                            $model->loan_commission="0";
                            $model->middle_name="0";
                            $model->loan_amount=$size == 10 ? $loan_amounts[$loan_amount_label] : $loan_amount;
                            $model->lead_master_status_id = LeadStatus::_MASTER_SALES;

                            $model->loan_interest= "7";

                            $model->mobile=$phone;

                            // Save every time what user create this lead
                            $model->ip_address = $ip;
                            $model->active=1;
                            try {
                                if ($model->save()) {

                                    /* Begin changes to save address details and contact details with new lead creation */
                                     AddressModel::addressInsert($model->id, 'lead');

                                    $updateLead = Lead::findOne($model->id);
                                    $updateLead->added_at = time();
                                    $updateLead->c_control = $this->generateFolio($model);
                                    $updateLead->update();
                                    //Lead Add Contact
                                    $contactae = new Contact();

                                    $contactae->first_name = $model->first_name;
                                    $contactae->last_name = $model->last_name;
                                    $contactae->middle_name = "0";

                                    $contactae->email = $model->email;

                                    $contactae->phone = $data['Lead']['phoneNumber'];
                                    $contactae->mobile = $model->mobile;

                                    $contactae->entity_id = $model->id;
                                    $contactae->entity_type = 'lead';
                                    $contactae->is_primary = 1;
                                    $contactae->added_at = time();
                                    $contactae->save();

                                    //Add History
                                    HistoryModel::historyInsert('lead', $model->id, 'Lead Creado por CRM Bot', 173);

                                    // Update progress
                                    Lead::generateProgressLead($model->id, true);
                                    /* End changes to save address details and contact details with new lead creation */
                                    $registers++;
                                } else {
                                    var_dump($model->errors);
                                    exit;
                                }
                            } catch (\Exception $e) {
                                var_dump($e);exit;
                            }
                        }
                    }
                }
                fclose($gestor);
            }
        }

        return $this->render('upload', [
            'error' => $error,
            'repeats' => $repeats,
            'registers' => $registers
        ]);
    }

    public function actionChecking()
    {
        $email = Yii::$app->request->get('email'); // $_GET['email'] != null ? ' email = "' . $_GET['email'] . '"' : null;
        $phone = Yii::$app->request->get('phone'); // $_GET['phone'] != null ? ' phone = ' . $_GET['phone'] : null;
        $mobile = Yii::$app->request->get('mobile'); // $_GET['mobile'] != null ? ' mobile = ' . $_GET['mobile'] : null;
        $status = Yii::$app->request->get('position_data');
        $mobile_data = 0;
        $phone_data = 0;
        $email_data = 0 ;
        $sql = '';

        if ($status == ' ' or empty($status))
        {
            if ( ! empty($email) ) {
                $sql .= ' OR email = "' . $email . '"';
                $email_data = 1;
            }
            if ( ! empty($phone) ) {
                $sql .= ' OR phone = ' . $phone;
                $phone_data = 1;
            }
            if ( ! empty($mobile) ) {
                $sql .= ' OR mobile = ' . $mobile;
                $mobile_data = 1;
            }
        }
        else
        {
            if ( $status == 1) {
                $sql = ' OR email = "' . $email . '"';
                $email_data = 1;
            }
            if ( $status == 2 ) {
                $sql = ' OR phone = ' . $phone;
                $phone_data = 1;
            }
            if ( $status == 3 ) {
                $sql = ' OR mobile = ' . $mobile;
                $mobile_data = 1;
            }
        }
        $sql = substr($sql, 4);
        $lead = LeadModel::find()->where('active = 1 AND ('.$sql.')') ->all();
        $all = (int) (! empty($lead));
         //return (int) (! empty($lead));
        $leads =[];
        if ($all == 1){
            foreach ($lead as $l)
            {
                if ($mobile_data == 1)
                    $input = 'mobile_status';
                if ($phone_data == 1)
                    $input = 'phone_status';
                if ($email_data == 1)
                    $input = 'email_status';

                $leads['success'] = 1;
                $leads[$input]['lead_id'][] = $l['id'];
                $leads[$input]['mobile'][] = $l['mobile'];
                $leads[$input]['phone'][] = $l['phone'];
                $leads[$input]['email'][] = $l['email'];
                $leads[$input]['c_control'][] = 'Folio : '.$l['c_control'] .' Lead : ' .  $l['lead_name'] ;
                $leads['mobile_data'] = $mobile_data;
                $leads['phone_data'] = $phone_data;
                $leads['email_data'] = $email_data;
                $leads['input'] = $input;
                $leads['sql'] = $sql;
                $leads['status'] = $status;
            }
        }
        return json_encode($leads);
    }

	public function actionLeadAllReports(){
		if(!Yii::$app->user->can('Report.LeadAllReports')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
		return $this->render('lead-all-reports');

	}

	public function actionLeadTypeReport(){
		if(!Yii::$app->user->can('Report.LeadType')){
			throw new \yii\web\ForbiddenHttpException('You do not have permissions to view this page.');
		}
		return $this->render('lead-type-report');

	}

	public function actionLeadCountryReport(){
		if(!Yii::$app->user->can('Report.LeadCountry')){
			throw new \yii\web\ForbiddenHttpException('You do not have permissions to view this page.');
		}
		return $this->render('lead-country-report');

	}

	public function actionNewLeadReport(){
		if(!Yii::$app->user->can('Report.NewLead')){
			throw new \yii\web\ForbiddenHttpException('You do not have permissions to view this page.');
		}
		return $this->render('new-lead-report');

	}

	public function actionLeadStatusReport(){
		if(!Yii::$app->user->can('Report.LeadStatus')){
			throw new \yii\web\ForbiddenHttpException('You do not have permissions to view this page.');
		}
		return $this->render('lead-status-report');

	}

	public function actionLeadFunnelReport(){
		if(!Yii::$app->user->can('Report.LeadFunnel')){
			throw new \yii\web\ForbiddenHttpException('You do not have permissions to view this page.');
		}
		return $this->render('lead-funnel-report');

	}

    /**
     * Updates an existing Lead model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        //array list status
        $statuslead = [1 => 'Nuevo', 2 => 'Cita', 3 => 'UPS', 4 => 'Venta', 6 => 'Muerto', 8 => 'No Contesta', 9 => 'Seguimiento', 10 => 'T1', 11 => 'T1NI'];
        $statussource = [1 => 'LinkedIns', 2 => 'Google', 4 => 'Radio/TV', 5 => 'Prensa', 6 => 'SMS/WEB', 7 => 'Facebook'];
        $model = $this->findModel($id);
        $new_status = (int)$_REQUEST['Lead']['lead_status_id'];
        $new_owner = (int)$_REQUEST['Lead']['lead_owner_id'];
        $new_source = (int)$_REQUEST['Lead']['lead_source_id'];
        $flag = 0;

        if (isset($_REQUEST['wizard_model']))
        {
            $lead = $_REQUEST['Lead'];
            $contact1 = $_REQUEST['contactlead_ref_1'];
            $contact2 = $_REQUEST['contactlead_ref_2'];
            $entity_contact1 = $_REQUEST['contact_idlead_ref_1'];
            $entity_contact2 = $_REQUEST['contact_idlead_ref_2'];
            $model->first_name = $lead['first_name'];
            $model->last_name = $lead['last_name'];
            $model->middle_name = $lead['middle_name'];
            $model->rfc = $lead['rfc'];
            $model->curp = $lead['curp'];
            $model->mobile = $lead['mobile'];
            $model->phone = $lead['phone'];
            $model->monthly_income = str_replace('$','',str_replace(',','',$lead['monthly_income']));
            $model->monthly_income2 = str_replace('$','',str_replace(',','',$lead['monthly_income2']));
            $model->monthly_expenses = str_replace('$','',str_replace(',','',$lead['monthly_expenses']));
            $model->economic_dep = str_replace('$','',str_replace(',','',$lead['economic_dep']));
            $model->home_status = $lead['home_status'];
            $model->bureau_status = $lead['bureau_status'];
            $model->bureau_status_desc = $lead['bureau_status_desc'];
            $model->company_name = $lead['company_name'];
            $model->civil_status = $lead['civil_status'];
            $model->civil_status_regime = (int)$lead['civil_status_regime'];
            $model->spouse_job = $lead['spouse_job'];
            $model->spouse_monthly_income = str_replace('$','',str_replace(',','',$lead['spouse_monthly_income']));
            $model->product_id = $lead['product_id'];
            $model->loan_amount = str_replace('$','',str_replace(',','',$lead['loan_amount']));
            $model->loan_interest = $lead['loan_interest'];
            $model->loan_commission = str_replace('$','',str_replace(',','',$lead['loan_commission']));
           $model->loan_term = $lead['loan_term'];
           $model->qualify =  (integer) $_REQUEST['quality'];

            $model->update();
            if($entity_contact1 != '')
            {
               $contactae = Contact::findOne(['id' => $entity_contact1 ]);
                $contactae->first_name = $contact1['first_name'];
                $contactae->last_name = $contact1['last_name'];
                $contactae->middle_name = $contact1['middle_name'];


                $contactae->phone = $contact1['phone'];
                $contactae->mobile = $contact1['mobile'];

                $contactae->entity_id = $model->id;
                $contactae->entity_type = 'lead.ref.1';
                $contactae->is_primary = 1;
                $contactae->added_at = time();
                $contactae->update();
            }
            else
            {
                //Lead Add Contact
                $contactae = new Contact();

                $contactae->first_name = $contact1['first_name'];
                $contactae->last_name = $contact1['last_name'];
                $contactae->middle_name = $contact1['middle_name'];


                $contactae->phone = $contact1['phone'];
                $contactae->mobile = $contact1['mobile'];

                $contactae->entity_id = $model->id;
                $contactae->entity_type = 'lead.ref.1';
                $contactae->is_primary = 1;
                $contactae->added_at = time();
                $contactae->save();
            }
            if($entity_contact2 != '')
            {
                $contactae1 = Contact::findOne(['id' => $entity_contact2 ]);
                $contactae1->first_name = $contact2['first_name'];
                $contactae1->last_name = $contact2['last_name'];
                $contactae1->middle_name = $contact2['middle_name'];


                $contactae1->phone = $contact2['phone'];
                $contactae1->mobile = $contact2['mobile'];

                $contactae1->entity_id = $model->id;
                $contactae1->entity_type = 'lead.ref.2';
                $contactae1->is_primary = 1;
                $contactae1->added_at = time();
                $contactae1->update();
            }
            else
            {
                //Lead Add Contact
                $contactae1 = new Contact();

                $contactae1->first_name = $contact2['first_name'];
                $contactae1->last_name = $contact2['last_name'];
                $contactae1->middle_name = $contact2['middle_name'];


                $contactae1->phone = $contact2['phone'];
                $contactae1->mobile = $contact2['mobile'];

                $contactae1->entity_id = $model->id;
                $contactae1->entity_type = 'lead.ref.2';
                $contactae1->is_primary = 1;
                $contactae1->added_at = time();
                $contactae1->save();
            }
             return $this->redirect(['view', 'id' => $model->id]);
          
        }
        //appointments to UPS
        if ($model->lead_status_id == 2 && $new_status == 3) {
            $model->ups_type = 0;
            $ups_date = date('Y-m-d',time());
            $model->ups_date = $ups_date;
            $model->update();
            $appointments = Appointment::find()->where('entity_id =' . $model->id)->all();

            foreach ($appointments as $appointment) {
                if ($appointment['status'] == 0) {
                    $appointment['status'] = 1;
                    $appointment['updated_at'] = time();

                    $appointment->save();
                    HistoryModel::historyInsert($this->entity_type, $model->id, 'Cita actualizada');
                }
            }

        }


        // Prevent change status to down
        if ($model->lead_status_id != $new_status && ($new_status > $model->lead_status_id || $model->lead_status_id == LeadStatus::_NOCALL || $model->lead_status_id == LeadStatus::_TRACKING)) {
            HistoryModel::historyInsert($this->entity_type,$model->id,'Se cambio el status de lead de <strong>'. $statuslead[$model->lead_status_id].'</strong> a <strong>'.$statuslead[$new_status].'</strong>', 0, UserActivity::THREE_MINUTES);
            $model->lead_status_id = $new_status;
            $flag = 1;
        }
        else
            HistoryModel::historyInsert($this->entity_type,$model->id,'Lead actualizado',0 , UserActivity::FIVE_MINUTES);

        // Only Lead.Revive permission can downgrade status
        if ( $model->lead_status_id != $new_status && ($new_status < 4 || $new_status == LeadStatus::_TRACKING) && Yii::$app->user->can('Lead.Revive')  && $flag != 1 ) {
            HistoryModel::historyInsert($this->entity_type,$model->id,'Se cambio el status de lead de <strong>'. $statuslead[$model->lead_status_id].'</strong> a <strong>'.$statuslead[$new_status].'</strong>',0, UserActivity::THREE_MINUTES );
            $model->lead_status_id = $new_status;
        }
        //appointment date to lead count in dashboard
        if ($new_status == 2){
            if ($model->lead_status_id != $new_status)
            $model->appointment_date = date('Y-m-d');

        }

        //
        if ($model->lead_status_id == LeadStatus::_CONVERTED && empty($model->c_contract)) {
            $model->c_contract = Lead::generateContract($model->product_id);
            $model->converted_at = time();
        }


        if ($model->lead_owner_id != $new_owner) {
            if (Yii::$app->user->can('Lead.Owner')) {
                // Change office
                $old_owner_id=$model->lead_owner_id;
                $owner = User::findOne($new_owner);
                //$model->office_id = $owner->office_id;

                $model->lead_owner_id = $owner->id;
                HistoryModel::historyInsert($this->entity_type,$model->id,'Se cambio el propietario principal <strong>'.self::getAliasUser($old_owner_id).'</strong> a <strong>'.self::getAliasUser($new_owner).'</strong>',0,UserActivity::FIVE_MINUTES);

                // Change COVID
                $model->covid19 = 0;
            }
        }

        // TODO:
        if ($model->lead_source_id != $new_source) {

            HistoryModel::historyInsert($this->entity_type,$model->id,'Se cambio la fuente de lead <strong>'.$statussource[$model->lead_source_id] .'</strong> a <strong>'.$statussource[$new_source] .'</strong>',0,UserActivity::FIVE_MINUTES);
            $model->lead_source_id = $new_source;
        }
        //var_dump($model);exit;
        if ($model->save()) {
            Lead::generateProgressLead($model->id, true);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            var_dump($model->getErrors());exit;
        }
    }

    public function actionCustomer($id)
    {
        $model = $this->findModel($id);
        $service_status_id = (int) $_REQUEST['Lead']['service_status_id'];
        $service_owner_id = (int) $_REQUEST['Lead']['service_owner_id'];

        // TODO: Check this
        if (LeadStatus::canChange($model, true) && $model->service_status_id != $service_status_id) {
            $old_status_id = $model->service_status_id;
            $model->service_status_id = $service_status_id;
            HistoryModel::historyInsert($this->entity_type, $model->id, 'Se cambió el estado de Atención de Clientes de <strong>'.LeadStatus::getServiceStatusName($old_status_id).'</strong> a <strong>'.LeadStatus::getServiceStatusName($service_status_id).'</strong>');
        }

        if (Yii::$app->user->can('Lead.Migrate') && $model->service_owner_id != $service_owner_id) {
            $model->service_owner_id = $service_owner_id;
            HistoryModel::historyInsert($this->entity_type, $model->id, 'Se cambió el Gestor de Atención a Clientes');
        }

        if ($model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }
    public  function actionCustomerinsurance($id){
        $model = $this->findModel($id);
        $insurance_agent = (int) $_REQUEST['Lead']['insurance_agent'];



        if (Yii::$app->user->can('Lead.Migrate') && $model->service_owner_id != $insurance_agent) {
            $model->insurance_agent = $insurance_agent;
            HistoryModel::historyInsert($this->entity_type, $model->id, 'Se cambió el Gestor de Seguros');
        }

        if ($model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }


    /**
     * Deletes an existing Lead model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        /*$model = $this->findModel($id);
        $model->active = 0;
        $model->save();*/
        if(!Yii::$app->user->can('Lead.Delete')){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }
        $model = $this->findModel($id);
        $recyclemodel = new LeadRecycle;
        $recyclemodel->id = $model->id;
        $recyclemodel->office_id = $model->office_id;
        $recyclemodel->c_control = $model->c_control;
        $recyclemodel->c_contract = $model->c_contract;
        $recyclemodel->lead_name = $model->lead_name;
        $recyclemodel->lead_master_status_id = $model->lead_master_status_id;
        $recyclemodel->lead_insurance_id = $model->lead_insurance_id;
        $recyclemodel->lead_description = $model->lead_description;
        $recyclemodel->lead_type_id =$model->lead_type_id;
        $recyclemodel->lead_owner_id = $model->lead_owner_id;
        $recyclemodel->lead_status_id = $model->lead_status_id;
        $recyclemodel->lead_source_id = $model->lead_source_id;
        $recyclemodel->email = $model->email;
        $recyclemodel->first_name = $model->first_name;
        $recyclemodel->last_name = $model->last_name;
        $recyclemodel->middle_name = $model->middle_name;
        $recyclemodel->phone = $model->phone;
        $recyclemodel->mobile = $model->mobile;
        $recyclemodel->product_id = $model->product_id;
        $recyclemodel->loan_amount = $model->loan_amount;
        $recyclemodel->loan_interest = $model->loan_interest;
        $recyclemodel->loan_commission = $model->loan_commission;
        $recyclemodel->loan_term = $model->loan_term;
        $recyclemodel->payed = $model->payed;
        $recyclemodel->rfc = $model->rfc;
        $recyclemodel->curp = $model->curp;
        $recyclemodel->age = $model->age;
        $recyclemodel->birthdate = $model->birthdate;
        $recyclemodel->place_of_birth = $model->place_of_birth;
        $recyclemodel->civil_status = $model->civil_status;
        $recyclemodel->civil_status_regime = $model->civil_status_regime;
        $recyclemodel->spouse_job = $model->spouse_job;
        $recyclemodel->spouse_monthly_income = $model->spouse_monthly_income;
        $recyclemodel->monthly_income = $model->monthly_income;
        $recyclemodel->monthly_income2 = $model->monthly_income2;
        $recyclemodel->monthly_expenses = $model->monthly_expenses;
        $recyclemodel->home_status = $model->home_status;
        $recyclemodel->bureau_status = $model->bureau_status;
        $recyclemodel->bureau_status_desc = $model->bureau_status_desc;
        $recyclemodel->active_loans = $model->active_loans;
        $recyclemodel->economic_dep = $model->economic_dep;
        $recyclemodel->company_name = $model->company_name;
        $recyclemodel->job = $model->job;
        $recyclemodel->labor_old = $model->labor_old;
        $recyclemodel->c_cuenta = $model->c_cuenta;
        $recyclemodel->contract_date = $model->contract_date;
        $recyclemodel->valid_admin = $model->valid_admin;
        $recyclemodel->valid_manager = $model->valid_manager;
        $recyclemodel->valid_sales = $model->valid_sales;
        $recyclemodel->service_status_id = $model->service_status_id;
        $recyclemodel->service_owner_id = $model->service_owner_id;
        $recyclemodel->payment_folio = $model->payment_folio;
        $recyclemodel->added_at = $model->added_at;
        $recyclemodel->updated_at = $model->updated_at;
        $recyclemodel->converted_at = $model->converted_at;
        $recyclemodel->customer_id = $model->customer_id;
        $recyclemodel->user_id = $model->user_id;
        $recyclemodel->progress = $model->progress;
        $recyclemodel->active = $model->active;
        $recyclemodel->save();
        $payments = \livefactory\models\Payment::find()->where('entity_id = ' . $model->id)->all();
        foreach ($payments as $payment) {
            $recyclepayment = new PaymentRecycle;
            $recyclepayment->id = $payment->id;
            $recyclepayment->generator_id = $payment->generator_id;
            $recyclepayment->co_generator_id = $payment->co_generator_id;
            $recyclepayment->amount = $payment->amount;
            $recyclepayment->total_due = $payment->total_due;
            $recyclepayment->note = $payment->note;
            $recyclepayment->type = $payment->type;
            $recyclepayment->date = $payment->date;
            $recyclepayment->code = $payment->code;
            $recyclepayment->origin = $payment->origin;
            $recyclepayment->folio = $payment->folio;
            $recyclepayment->received = $payment->received;
            $recyclepayment->entity_id = $payment->entity_id;
            $recyclepayment->entity_type = $payment->entity_type;
            $recyclepayment->file_id = $payment->file_id;
            $recyclepayment->added_at = $payment->added_at;
            $recyclepayment->updated_at = $payment->updated_at;
            $recyclepayment->save();
            $payment->delete();
        }
        //$model->save();
        HistoryModel::historyInsert('log', HistoryModel::ACTION_DELETE, 'Lead eliminado <strong>'.$recyclemodel->c_control .'</strong>' );
        $model->delete();

        return $this->redirect(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ['index']);
    }

    // ----------------------------------------------------------------

    /**
     * Sales report
     *
     * @return string
     */
    public function actionReport()
    {
        //$office_id = Yii::$app->user->can('Office.NoLimit') ? Yii::$app->request->getQueryParam('office_id') : 0;
        $office_id = Yii::$app->user->can('Office.NoLimit') ? Yii::$app->request->getQueryParam('office_id') : Yii::$app->user->identity->office_id;
        $agent_id = Yii::$app->request->getQueryParam('agent_id');
        list($start, $end) = SalesReport::getPeriodFromRequest(Yii::$app->request->getQueryParams());

        return $this->render('reports/sales', [
            'start' => $start,
            'end' => $end,
            'office_id' => $office_id,
            'agent_id' => $agent_id
        ]);
    }

    // ----------------------------------------------------------------

    public function actionLog()
    {
        if(!Yii::$app->user->can('Reports.Log')){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }

        $searchModel = new HistorySearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('log', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }
    // ----------------------------------------------------------------

    public function actionLoginsurance()
    {
        if(!Yii::$app->user->can('Reports.Log')){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }
        ini_set('max_execution_time',300);
        ini_set('memory_limit','2048M');
        $searchModel = new HistorySearch;
        $dataProvider = $searchModel->searchLoginsurance(Yii::$app->request->getQueryParams());

        return $this->render('log', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }
    // ----------------------------------------------------------------

    public function actionLogcustomer(){
        if(!Yii::$app->user->can('Reports.Log')){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }
        ini_set('max_execution_time',300);
        ini_set('memory_limit','2048M');
        $searchModel = new HistorySearch;
        $dataProvider = $searchModel->searchLogcustomer(Yii::$app->request->getQueryParams());

        return $this->render('log', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);

    }

    // ----------------------------------------------------------------

    public function actionReview()
    {
        if(!Yii::$app->user->can('Review.Index')){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }

        // For search
        $_GET['review'] = true;

        $searchModel = new LeadSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('review', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            //'dataProviderBox'=>$dataProviderBox,
        ]);
    }

    public function actionEffectiveness()
    {
        // TODO: Add permission
        //if(!Yii::$app->user->can('Report.Sales')){
          //  throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        //}


        $office_id = Yii::$app->user->can('Office.NoLimit') ? Yii::$app->request->getQueryParam('office_id') : 0;
        $agent_id = Yii::$app->request->getQueryParam('agent_id');
        list($start, $end) = SalesReport::getPeriodFromRequest(Yii::$app->request->getQueryParams());

        $searchModel = new LeadSearch;
        $data = $searchModel->effectiveness(Yii::$app->request->getQueryParams());
        $dataSales = $searchModel->effectiveness_sales(Yii::$app->request->getQueryParams());

        return $this->render('effectiveness', [
            'start' => $start,
            'end' => $end,
            'office_id' => $office_id,
            'agent_id' => $agent_id,
            'data' => $data,
            'data_sales' => $dataSales
        ]);
    }

    public function actionRanking()
    {
        ///var not user payments
        $notPayments = [];
        // TODO: Add permission
        //if(!Yii::$app->user->can('Report.Sales')){
          //  throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        //}

        //
        $request = Yii::$app->request->getQueryParams();
        $searchModel = new LeadSearch;
        $data = $searchModel->ranking($request);
        //user id in not payments
        foreach ($data as $row )
        {
            $notPayments [] = $row['user_id'];
        }

        //query not payments
        $notgenQuery = ( new Query())
            ->select('tbl_user.id , tbl_user.username ,  tbl_user.first_name , tbl_user.middle_name ,tbl_user.last_name, tbl_user.office_id , tbl_office.code , auth_item.description , tbl_user.active')
            ->from('tbl_user')
            ->leftJoin('auth_assignment', 'tbl_user.id = auth_assignment.user_id')
            ->leftJoin('tbl_office', 'tbl_user.office_id = tbl_office.id')
            ->leftJoin('auth_item' , 'auth_assignment.item_name = auth_item.name')
            ->Where('tbl_user.active = 1 ')
            ->andWhere(empty($notPayments) ? '' : 'tbl_user.id NOT IN (' . implode(' , ' , $notPayments) .')')
            ->andWhere('auth_assignment.item_name = "Sales Manager" OR auth_assignment.item_name = "Sales Person" OR  auth_assignment.item_name = "Commercial.Manager" OR auth_assignment.item_name = "Capturist"')
            ->andWhere('tbl_office.active = 1 and tbl_office.id != 1' );
         $test = [];

         $not_user_payments = $notgenQuery->groupBy('tbl_user.id')->all();
        foreach ($not_user_payments as $row) {

            if ( ! isset($data[$row['id']])) {
                $data[$row['id']] = [
                    'user_id' => $row['id'],
                    'office_id' => $row['office_id'],
                    'office' => $row['code'],
                    'username' => $row['username'],
                    'name' => $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'],
                    'payments' => 0,
                    'ccin' => 0,
                    'total' => 0,
                    'role' => $row['description'],
                    'active' => $row['active']
                ];
            }
        }

        list($start, $end) = SalesReport::getPeriodFromRequest($request);
        $ranking_type = isset($request['ranking_type']) && ! empty($request['ranking_type']) ? $request['ranking_type'] : 'agent';
        return $this->render('ranking', [
            'data' => $data,
            'start' => $start,
            'end' => $end,
            'ranking_type' => $ranking_type
        ]);
    }

    //----------------------------------------------------------------
    public function actionRankinginsurance()
    {
        ///var not user payments
        $notPayments = [];
        // TODO: Add permission
        //if(!Yii::$app->user->can('Report.Sales')){
        //  throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        //}

        //
        $request = Yii::$app->request->getQueryParams();
        $searchModel = new LeadSearch;
        $data = $searchModel->rankinginsurance($request);
        //user id in not payments
        foreach ($data as $row )
        {
            $notPayments [] = $row['user_id'];
        }

        //query not payments
        $notgenQuery = ( new Query())
            ->select('tbl_user.id , tbl_user.username ,  tbl_user.first_name , tbl_user.middle_name ,tbl_user.last_name, tbl_user.office_id , tbl_office.code , auth_item.description , tbl_user.active')
            ->from('tbl_user')
            ->leftJoin('auth_assignment', 'tbl_user.id = auth_assignment.user_id')
            ->leftJoin('tbl_office', 'tbl_user.office_id = tbl_office.id')
            ->leftJoin('auth_item' , 'auth_assignment.item_name = auth_item.name')
            ->Where('tbl_user.active = 1 ')
            ->andWhere(empty($notPayments) ? '' : 'tbl_user.id NOT IN (' . implode(' , ' , $notPayments) .')')
            ->andWhere('auth_assignment.item_name = "Insurance" OR auth_assignment.item_name = "Insurance.Customer" OR  auth_assignment.item_name = "Insurance.Director" ')
            ->andWhere('tbl_office.active = 1 and tbl_office.id != 1' );
        $test = [];

        $not_user_payments = $notgenQuery->groupBy('tbl_user.id')->all();
        foreach ($not_user_payments as $row) {

            if ( ! isset($data[$row['id']])) {
                $data[$row['id']] = [
                    'user_id' => $row['id'],
                    'office_id' => $row['office_id'],
                    'office' => $row['code'],
                    'username' => $row['username'],
                    'name' => $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'],
                    'payments' => 0,
                    'ccin' => 0,
                    'total' => 0,
                    'role' => $row['description'],
                    'active' => $row['active']
                ];
            }
        }
//        var_dump($request['ranking_type']);

        list($start, $end) = SalesReport::getPeriodFromRequest($request);
        $ranking_type = isset($request['ranking_type']) && ! empty($request['ranking_type']) ? $request['ranking_type'] : 'agent';
        return $this->render('ranking', [
            'data' => $data,
            'start' => $start,
            'end' => $end,
            'ranking_type' => $ranking_type
        ]);
    }

    // ----------------------------------------------------------------
    public function actionHistory(){
        if(!Yii::$app->user->can('Lead.History')){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }
        $request = Yii::$app->request->getQueryParams();
        $searchModel = new LeadSearch;
        $data = $searchModel->history($request);
        return $this->render('history',[
            'dataProvider' => $data
        ]);
    }
    // ----------------------------------------------------------------

    public function actionAppointments()
    {

        if(!Yii::$app->user->can('Lead.Appointments')){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }

        ini_set('max_execution_time',300);
        ini_set('memory_limit','2048M');
//        AppointmentModel::appointmentsUps();

        $searchModel = new AppointmentSearch;
        $promises = [];
        $appointment_type = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(), false, $promises, $appointment_type);
        $unitGenerate = UnitGenerate::find()->where('active = 1')->all();
        list($start, $end) = SalesReport::getPeriodFromRequest(Yii::$app->request->getQueryParams());
//        var_dump($dataProvider->query->all());
        return $this->render('appointments', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'start' => $start,
            'end' => $end,
            'promises' => $promises,
            'agent_id' => Yii::$app->request->getQueryParam('agent_id'),
            'mean_id' => Yii::$app->request->getQueryParam('mean_id'),
            'customer' => false,
            'unitGenerate' => $unitGenerate,
            'type_period_check' => 0
        ]);
    }

    public function actionAppointmentsa()
    {

        if(!Yii::$app->user->can('Lead.Appointments')){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }

        $promises = [];
        $searchModel = new AppointmentSearch;
        $appointment_type = 2;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(), true, $promises, $appointment_type);
        $unitGenerate = UnitGenerate::find()->where('active = 1')->all();
        list($start, $end) = SalesReport::getPeriodFromRequest(Yii::$app->request->getQueryParams());
        return $this->render('appointments', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'promises' => $promises,
            'start' => $start,
            'end' => $end,
            'agent_id' => Yii::$app->request->getQueryParam('agent_id'),
            'mean_id' => Yii::$app->request->getQueryParam('mean_id'),
            'customer' => true,
            'unitGenerate' => $unitGenerate,
            'type_period_check' => 0
        ]);
    }
    public  function actionAppointmentsi(){
//        if( ! ( Yii::$app->user->can('Insurance')) || ! (Yii::$app->user->can('Insurance.Customer')) || (Yii::$app->user->can('Insurance.Director')) || (Yii::$app->user->can('Admin'))){
//            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
//        }

        $promises = [];
        $searchModel = new AppointmentSearch;
        $appointment_type = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(), true, $promises, $appointment_type);
        $unitGenerate = UnitGenerate::find()->where('active = 1')->all();
        list($start, $end) = SalesReport::getPeriodFromRequest(Yii::$app->request->getQueryParams());
        return $this->render('appointments', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'promises' => $promises,
            'start' => $start,
            'end' => $end,
            'agent_id' => Yii::$app->request->getQueryParam('agent_id'),
            'mean_id' => Yii::$app->request->getQueryParam('mean_id'),
            'customer' => true,
            'unitGenerate' => $unitGenerate,
            'type_period_check' => 0
        ]);
    }
    public function actionCalendar()
    {
        if(!Yii::$app->user->can('Lead.Appointments')){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }
        //get date from calendar
        $start=substr($_GET['start'],0,-9);
        $end=substr($_GET['end'],0,-9);
        //get lead and appointment
        $sql="SELECT tbl_lead.id , tbl_lead.c_control, tbl_lead.lead_name, tbl_lead.lead_status_id, tbl_appointment.entity_id, tbl_appointment.date,tbl_appointment.time, tbl_appointment.status FROM `tbl_lead`LEFT JOIN tbl_appointment ON tbl_lead.id = tbl_appointment.entity_id WHERE tbl_appointment.status !=1 and tbl_appointment.date>= '".$start. "' and tbl_appointment.date<='".$end."'";
        $connection = \Yii::$app->db;
        $command=$connection->createCommand($sql);
        $dataReader=$command->queryAll();

        foreach ($dataReader as $data)
        {
            $time=strtotime($data['date'].$data['time']);
            $arraydata=[
                'id'=>$data['id'],
                'start'=>date('Y-m-d\Th:i',$time),
                'end'=>date('Y-m-d\Th:i',$time),
                'title'=>$data['c_control']." ".$data['lead_name'],
                'url'=>'http://crmgestionfinanciera.com/livecrm/web/index.php?r=sales/lead/view&id='.$data['id'],
                'description'=>'Estado de cita:'.$data['status']==0 ? 'Vigente':'No concretada',
                'color'=>$data['status']==0 ? '#1ab394':'#ed5565',
            ];
            $arrayAppointments[] =$arraydata;

        }
        echo json_encode($arrayAppointments);
        exit;
        var_dump($start,$end,$dataReader);exit;
        foreach ($data as $row){
            foreach ($appointments as $data)
            {
                if ($data['entity_id'] == $row['id'])
                {

                    $arraydata=[
                        'id'=> $data['id'],
                        'title'=>$row['lead_name'],
                        'start'=>$data['date']."T13:13:55",
                        'end'=>$data['date']."T13:13:55",
                        'description'=>$row['c_control'],


                    ];
                    $arrayAppointments[] =$arraydata;
                    echo json_encode($arraydata);



                }
            }

        }
        echo json_encode($arrayAppointments);
    }
    public function actionAppointmentsCalendar()
    {
        if(!Yii::$app->user->can('Lead.Appointments')){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }

        $searchModel = new AppointmentSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('appointments-calendar', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    // ----------------------------------------------------------------

    public function actionPrint($id)
    {
        $this->layout = 'print';
        $lead = Lead::findOne($id);

        $type = Yii::$app->request->get('type');
        $this->print_type = $type;
        if ($type == 'letter_commitment')
        {

            HistoryModel::historyInsert($this->entity_type,$lead->id,'Carta Compromiso impresa');

        }
        if ($type == 'viability')
        {
            HistoryModel::historyInsert($this->entity_type,$lead->id,'Viabilidad impresa');

        }
        if ($type == 'contract')
        {
            HistoryModel::historyInsert($this->entity_type,$lead->id,'Contrato Impreso');

        }
        $model = $type !== 'receipt' ? $this->findModel($id) : Payment::findOne($id);
        $type = $type == 'contract' && $model->office_id == 5 ? $type.'-p2' : ($type == 'contract' && $model->office_id == 12 ? 'benefit_'.$type : ($type == 'contract' && $model->office_id == 11 ? $type.'-efectivida' : ($type == 'contract' && $model->office_id == 17 ? $type.'-efectivida': ($type == 'contract' && $model->office_id == 18 ? $type.'-efectivida': ($type == 'contract' && $model->office_id == 20 ? $type.'-efectivida': ($type == 'contract' && $model->office_id == 21 ? $type.'-efectivida': $type))))));
        //Add History
        HistoryModel::historyInsert('lead',$id,'Impresión realizada', 0, UserActivity::FIVE_MINUTES);
        return $this->render('print/' . $type, [
            'model' => $model,
            'type' => $type
        ]);
    }

    // ----------------------------------------------------------------

    /**
     * Finds the Lead model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lead the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lead::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /*************/
    protected function findModelRecycle($id){
        if (($model = LeadRecycle::findOne($id)) !== null){
            return $model;
        }else{
            throw new NotFoundHttpException(' page does not exist.');
        }
    }

    /**
     * Folio generado
     *
     * @param $model
     * @return string
     */
    protected function generateFolio($model)
    {
        $folio = '';

        $folio .= $model->office->code;

        $num = $model->office->folio+1;
        $size = strlen($num);
        $num = $size < 6 ? str_pad($num, 6, '0', STR_PAD_LEFT) : $num;

        $folio .= $num;

        $model->office->folio = $num;
        $model->office->save();



        return $folio;
    }

    public function actionTest()
    {
//        Lead::generateProgressLead(75341, true);
//        // build a DB query to get all articles with status = 1
        $query = Lead::find();
//
//// get the total number of articles (but do not fetch the article data yet)
        $count = $query->count();
//
//// create a pagination object with the total count
        $pagination = new Pagination(['totalCount' => $count]);
//
//// limit the query using the pagination and retrieve the articles
        $leads = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
//
//        //var_dump(count($leads));exit;
//
//        //
        foreach ($leads as $lead) {
            Lead::generateProgressLead($lead->id, true);
        }

        if (count($leads) < 20)
            exit('Success');

        $page = (int) $_REQUEST['page'];

        echo '<script>setTimeout(function() {
  window.location.href = "index.php?r=sales/lead/test&page='.(++$page).'";
}, 3000)</script>';
    }
    public function actionMigrateleads(){
        $_GET['migrateleads']=true;
        if(!Yii::$app->user->can('Admin')){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }
        $searchModel = new LeadSearch;
        $dataProvider = $searchModel->migrateLeads(Yii::$app->request->getQueryParams());
        $params = [];
        $leads = [];

        //$dataProviderBox=$searchModel->searchLead();

        if(!empty($_REQUEST['multiple_del'])){
//            var_dump('selection', $_REQUEST['selection']);

            if(!Yii::$app->user->can('Lead.Delete')){
                throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
            }
            $office = $_REQUEST['office_id'];

            $rows=$_REQUEST['selection'];
            for($i=0;$i<count($rows);$i++){
                $model = $this->findModel($rows[$i]);
                foreach (Yii::$app->request->getQueryParams() as $param => $key)
                {
                    if ($key != '')
                    $params[$param] = $key;
                }
                if ((int)$params['office_id'] != $model->office_id)
                {
//                    var_dump('office change');
                    if ($params['office_id'] != '')
                    {
                        $model->office_id =  $params['office_id'];
                        $model->c_control_old = $model->c_control;
//                    var_dump('c_control change');
                        $model->c_control = $this->generateFolio($model);
                        $model->save();
                    }
                }
                if ((int)$params['agent_id'] != $model->lead_owner_id)
                {
//                    var_dump('owner_id change');
                    if ($params['agent_id'] != '')
                    {
                        $model->lead_owner_id = $params['agent_id'];
                        $model->save();
                    }

                }
                if ((int)$params['mean_id'] != $model->user_id)
                {
//                    var_dump('user_id change');
                    if ($params['mean_id'] != '')
                    {
                        $model->user_id = $params['mean_id'];
                        $model->save();
                    }

                }
                if ((int)$params['lead_status'] != $model->lead_status_id)
                {
//                    var_dump('lead_status change');
                    if ($params['lead_status'] != '')
                    {
                        $model->lead_status_id = $params['lead_status'];
                        $model->save();
                    }

                }
                $model->save();
                // $model->active = 0;
                // $model->save();
               }
            //exit;
            //var_dump($recyclemodel);
            //$searchModel->save();
        }
        return $this->render('migrate-lead', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            //'dataProviderBox'=>$dataProviderBox,
        ]);
    }
    public function actionExportleads()
    {
        if ((! Yii::$app->user->can('exportMaster')) || !Yii::$app->user->id == 202){
//        if((!Yii::$app->user->id == 1 || !Yii::$app->user->id == 202)){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }
        $params = Yii::$app->request->getQueryParams();
        if (isset($params['export-lead']))
        {
            ini_set('max_execution_time',300);
            ini_set('memory_limit','2048M');
            $list = new Lead;
            $list->export($params);
        }


        return $this->render('export-lead');
    }


    public function actionUseractivity()
    {
        if(!Yii::$app->user->can('Reports.History')){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }
        $userActivity = new UserActivity;
        $dataProvider = $userActivity->search(Yii::$app->request->getQueryParams());
        return $this->render('useractivity', [
            'dataProvider' => $dataProvider,
            'searchModel' => $userActivity
        ]);

    }
    public function actionUseractivityinsurance(){
        if(!Yii::$app->user->can('Reports.History')){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }
        $userActivity = new UserActivity;
        $dataProvider = $userActivity->search(Yii::$app->request->getQueryParams());
        return $this->render('useractivity', [
            'dataProvider' => $dataProvider,
            'searchModel' => $userActivity
        ]);

    }
    public function actionUseractivityview()
    {
        if(!Yii::$app->user->can('Reports.log')){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }
        $params['user_id'] = Yii::$app->request->getQueryParam('id');
        $params['date_activity'] = Yii::$app->request->getQueryParam('date_activity');
        $total = 0;
        $userActivity = new UserActivity;
        $dataProvider = $userActivity->searchUserActivity($params);
        return $this->render('useractivityview' , [
            'dataProvider' => $dataProvider,
            'searchModel' =>$userActivity
        ]);

    }
    public function actionUploadleads()
    {
        if (Yii::$app->user->can('Lead.Upload')) {
            $ip = $this->get_ip_address();
            $error = false;
            $fails = 0 ;
            $repeats = 0;
            $registers = 0;
            $office_id = Yii::$app->request->post('office_id');
            $source_id = Yii::$app->request->post('source_id');
            $credit = Yii::$app->request->post('creditType');
            if (Yii::$app->request->post('lead-import')) {
                $list = new Lead;
                $list->exportlead();

            }
            if (($gestor = fopen($_FILES['excel']['tmp_name'], "r")) !== FALSE) {
                while (($datos = fgetcsv($gestor, 1000, ",", "'", "\\")) !== FALSE) {
                    $size = count($datos);
                    $data['Lead'] = [];
                    $data['Lead']['first_name'] = $datos[0];
                    $data['Lead']['last_name'] = $datos[2];
                    $data['Lead']['middle_name'] = $datos[1];
                    $data['Lead']['mobile'] = str_replace(' ','',$datos[3]);
                    $data['Lead']['mobile'] = preg_replace('/[^0-9]+/', '', $data['Lead']['mobile']);
                    $data['Lead']['email'] = $datos[4];
                    $data['Lead']['loan_amount'] = $datos[5];
                    if ($data['Lead']['first_name'] != 'Nombre') {
                        // Search for a previous lead
                        $query = (new Query())->select('c_control')->from('tbl_lead');
                        $query->where("replace(mobile, ' ', '') LIKE '%" . $data['Lead']['mobile'] . "%'");
                        $temp = strtok($data['Lead']['first_name'], " ");
                        $count = 0;
                        if ($query->count()) {
                            $repeats++;
                        } else
                        if (strlen($data['Lead']['mobile']) != 10) {
                            // ERROR de formato
                            $fails++;
                        }
                        else
                            if ($data['Lead']['first_name'] == '')
                            {
                                $fails++;
                            }
                            else {
                                $model = new Lead;
                            if ($model->load($data)) {
                                $model->lead_status_id = LeadStatus::_NEW;
                                $model->lead_type_id = 3;
                                $model->lead_name = $model->first_name . ' ' . $model->middle_name . ' ' . $model->last_name;
                                $model->lead_owner_id = 173;
                                $model->user_id = 173;
                                // Office is user office
                                $model->office_id = $office_id;
                                $model->lead_source_id = $source_id;
                                $model->loan_commission = "0";
                                $model->loan_interest = "7";
                                $model->lead_master_status_id = LeadStatus::_MASTER_SALES;
                                $model->product_id = $credit;
                                // Save every time what user create this lead
                                $model->ip_address = $ip;
                                $model->active = 1;
                                try {
                                    if ($model->save()) {
                                        /* Begin changes to save address details and contact details with new lead creation */
                                        AddressModel::addressInsert($model->id, 'lead');

                                        $updateLead = Lead::findOne($model->id);
                                        $updateLead->added_at = time();
                                        $updateLead->c_control = $this->generateFolio($model);
                                        $updateLead->update();
                                        //Lead Add Contact
                                        $contactae = new Contact();

                                        $contactae->first_name = $model->first_name;
                                        $contactae->last_name = $model->last_name;
                                        $contactae->middle_name = $model->middle_name;

                                        $contactae->email = $model->email;

                                        $contactae->phone = $data['Lead']['phoneNumber'];
                                        $contactae->mobile = $model->mobile;

                                        $contactae->entity_id = $model->id;
                                        $contactae->entity_type = 'lead';
                                        $contactae->is_primary = 1;
                                        $contactae->added_at = time();
                                        $contactae->save();

                                        //Add History
                                        HistoryModel::historyInsert('lead', $model->id, 'Lead Creado por CRM Bot mediante carga masiva', 173);

                                        // Update progress
                                        Lead::generateProgressLead($model->id, true);
                                        /* End changes to save address details and contact details with new lead creation */
                                        $registers++;
                                    } else {
                                        var_dump($model->errors);
                                        exit;
                                    }


                                } catch (\Exception $e) {
                                    var_dump($e);
                                    exit;
                                }
                            }
                        }
                    }


                }
                fclose($gestor);
            }

            return $this->render('uploadlead',
                [
                    'fails' => $fails,
                    'error' => $error,
                    'repeats' => $repeats,
                    'registers' => $registers

                ]);
        } else {
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }
        $error = false;
        $repeats = 0;
        $registers = 0;
        $office_id = Yii::$app->request->post('office_id');
        $source_id = Yii::$app->request->post('source_id');
        $credit = Yii::$app->request->post('creditType');
        if (Yii::$app->request->post('lead-import'))
        {
            $list = new Lead;
            $list->exportlead();

        }
        if (($gestor = fopen($_FILES['excel']['tmp_name'], "r")) !== FALSE) {
            while (($datos = fgetcsv($gestor, 1000, ",", "'", "\\")) !== FALSE) {
                    $size = count($datos);
                    $data['Lead'] = [];
                    $data['Lead']['first_name'] = $datos[0];
                    $data['Lead']['last_name'] = $datos[1];
                    $data['Lead']['middle_name'] = $datos[2];
                    $data['Lead']['mobile'] = $datos[3];
                    $data['Lead']['email'] = $datos[4];
                    $data['Lead']['loan_amount'] = $datos[5];
                    if ($data['Lead']['first_name'] != 'Nombre')
                    {
                        // Search for a previous lead
                        $query = (new Query())->select('c_control')->from('tbl_lead');
                        $query->where("replace(mobile, ' ', '') LIKE '%" . $data['Lead']['mobile'] . "%'");
                        $temp = strtok($data['Lead']['first_name']," ");
                        $count=0;
                        if ($query->count())
                        {
                            $repeats++;
                        }
                        else
                        {
                            $model = new Lead;
                            if ($model->load($data))
                            {
                                $model->lead_status_id = LeadStatus::_NEW;
                                $model->lead_type_id = 3;
                                $model->lead_name = $model->first_name . ' ' . $model->last_name . ' ' . $model->middle_name;
                                $model->lead_owner_id = 173;
                                $model->user_id= 173;
                                // Office is user office
                                $model->office_id = $office_id;
                                $model->lead_source_id=$source_id;
                                $model->loan_commission="0";
                                $model->loan_interest= "7";
                                $model->lead_master_status_id = LeadStatus::_MASTER_SALES;
                                $model->product_id =1;
                                // Save every time what user create this lead
                                $model->active=1;
                                try{
                                    if ($model->save()) {
                                        var_dump('lead');
                                        /* Begin changes to save address details and contact details with new lead creation */
                                        AddressModel::addressInsert($model->id, 'lead');

                                        $updateLead = Lead::findOne($model->id);
                                        $updateLead->added_at = time();
                                        $updateLead->c_control = $this->generateFolio($model);
                                        $updateLead->update();
                                        //Lead Add Contact
                                        $contactae = new Contact();

                                        $contactae->first_name = $model->first_name;
                                        $contactae->last_name = $model->last_name;
                                        $contactae->middle_name = "0";

                                        $contactae->email = $model->email;

                                        $contactae->phone = $data['Lead']['phoneNumber'];
                                        $contactae->mobile = $model->mobile;

                                        $contactae->entity_id = $model->id;
                                        $contactae->entity_type = 'lead';
                                        $contactae->is_primary = 1;
                                        $contactae->added_at = time();
                                        $contactae->save();

                                        //Add History
                                        HistoryModel::historyInsert('lead', $model->id, 'Lead Creado por CRM Bot', 173);

                                        // Update progress
                                        Lead::generateProgressLead($model->id, true);
                                        /* End changes to save address details and contact details with new lead creation */
                                        $registers++;
                                    } else {
                                        var_dump($model->errors);
                                        exit;
                                    }


                                } catch (\Exception $e)
                                {
                                    var_dump($e);exit;
                                }
                            }
                        }
                    }




            }
        fclose($gestor);
        }
        return $this->render('uploadlead',
            [
                'error' => $error,
                'repeats' => $repeats,
                'registers' => $registers

            ]);
    }

}

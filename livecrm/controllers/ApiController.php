<?php

namespace livecrm\controllers;

use livefactory\controllers\Controller;
use livefactory\models\AppointmentModel;
use livefactory\models\search\Appointment;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use livefactory\models\Lead;
use livefactory\models\LeadStatus;
use livefactory\models\AddressModel;
use livefactory\models\History;
use livefactory\models\HistoryModel;
use livefactory\models\AssignmentHistory;
use livefactory\models\Contact;
use livefactory\models\Lead as LeadModel;
use Yii;
class ApiController extends Controller {


    function cors() {

        // Allow from any origin
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                // may also be using PUT, PATCH, HEAD etc
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            exit(0);
        }

        //echo "You have CORS!";
    }
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
    public function actionLogin()
    {

        $this->cors();
        $ip = $this->get_ip_address();
        $sql = '';
        $bot=Yii::$app->request->post('token_id');
        $bot = empty($bot) ? 173 : $bot;
        //$creditType = [1=>'Tarjeta de Crédito',2=>'Préstamo Personal', 3=>'Crédito Automotriz', 4=>'Crédito Hipotecario', 5=>'Empresarial', 6=>'Otro'];
        $type = [1 => 1,2 => 4, 3 => 2, 4 => 5, 5 => 6, 6 => 6];
        $model = new Lead;
        $data['Lead'] = Yii::$app->request->post();
        $data['Lead']['first_name'] = $data['Lead']['firstName'];
        $data['Lead']['last_name'] = $data['Lead']['lastName'];
        $data['Lead']['middle_name'] = $data['Lead']['middleName'];
        $data['Lead']['product_id'] = $type[$data['Lead']['creditType']];
        if ($data['Lead']['lead_source_id'] == null)
            $data['Lead']['lead_source_id'] = 6;

        // SELECT id FROM tbl_lead WHERE phone = $data['Lead']['phone']
            if ($model->load($data))
            {

                if($model->lead_status_id == '')
                    $model->lead_status_id = LeadStatus::_NEW;

                if ($model->lead_type_id == '')
                    $model->lead_type_id = 3;

                $model->lead_master_status_id = LeadStatus::_MASTER_SALES;

                if ($model->lead_name == '')
                    $model->lead_name = $model->first_name . ' ' . $model->middle_name . ' ' . $model->last_name;
                if ($model->lead_owner_id == '')
                    $model->lead_owner_id = $bot ;
                $model->user_id = $bot;

                // Office is user office
                $model->office_id = (int) $data['Lead']['office_id'];
                $model->lead_source_id = $data['Lead']['lead_source_id'];
                $model->loan_commission = "0";
                if($model->loan_amount == null || $model->loan_amount == "")
                $model->loan_amount = "30000";


                $model->loan_interest = "7";
                $model->phone = $data['Lead']['phoneNumber'];
                $model->mobile = $data['Lead']['phoneNumber'];
                if ( ! empty($model->email) ) {
                    $sql .= ' OR email = "' . $model->email . '"';
                    $email_data = 1;
                }
                if ( ! empty($model->phone) ) {
                    $sql .= ' OR phone = ' . $model->phone;
                    $phone_data = 1;
                }
                if ( ! empty($model->mobile) ) {
                    $sql .= ' OR mobile = ' . $model->mobile;
                    $mobile_data = 1;
                }
                $model->ip_address = $ip;
                $model->active = 1;
                $sql = substr($sql, 4);
                $lead = LeadModel::find()->where('active = 1 AND ('.$sql.')')->all();
                $all = (int) (! empty($lead));
                if($all != 1)
                {
                    // Save every time what user create this lead

                    $model->save();
                    if($model->save())
                    {

                        /* Begin changes to save address details and contact details with new lead creation */
                        $address_id = AddressModel::addressInsert($model->id,'lead');

                        $updateLead =  Lead::findOne($model->id);
                        $updateLead->added_at=time();
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
                        HistoryModel::historyInsert('lead',$model->id,'Lead Creado por CRM Bot',$bot);

                        // Update progress
                        Lead::generateProgressLead($model->id, true);
                        /* End changes to save address details and contact details with new lead creation */
                    } else {
                        var_dump($model->getErrors());exit;
                    }
                }
                else
                {
                    echo "Lead duplicado";
                }





            }



        //exit('1');
    }

    public function actionJulito()
    {
        return $this->render('julito');
    }
    public function actionTest(){

    }


}
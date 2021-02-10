<?php

namespace livefactory\models\search;

use phpDocumentor\Reflection\Types\Object_;
use Yii;
use yii\db\Query;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\Lead as LeadModel;
use livefactory\models\SalesReport;
use livefactory\models\Payment;
/**
 * Lead represents the model behind the search form about `livefactory\models\Lead`.
 */
class Lead extends LeadModel
{
    public function rules()
    {
        return [
            [['id', 'office_id', 'lead_type_id', 'lead_owner_id', 'lead_status_id', 'lead_source_id', 'opportunity_amount', 'service_status_id', 'updated_at', 'user_id'], 'integer'],
            [['lead_name', 'lead_description', 'lead_status_description', 'lead_source_description', 'do_not_call', 'email', 'first_name', 'last_name', 'phone', 'mobile', 'fax', ], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
		$query = LeadModel::find();

        // Validation
        /*if ($params['review']) {

            $query->where('lead_status_id=' . LeadStatus::_CONVERTED);

            if (Yii::$app->user->can('Review.Sales')) {
                $query->andWhere('valid_sales = 0');
            } else if (Yii::$app->user->can('Review.Admin')) {
                $query
                    ->andWhere('valid_sales = 1')
                    ->andWhere('valid_admin = 0');
            } else if (Yii::$app->user->can('Review.Manager')) {
                $query
                    ->andWhere('valid_sales = 1')
                    ->andWhere('valid_admin = 1')
                    ->andWhere('valid_manager = 0');
            }

            // Delete for next
            unset($params['review']);
        } else if ($params['service']) {
            $query->andWhere('valid_sales = 1 OR valid_manager = 1 OR valid_admin = 1');
        } else {

            // Only show leads on "sales" process
            $query
                ->where('valid_sales = 0 OR valid_manager = 0 OR valid_admin = 0');
        }*/
        if ($params['master_status_id'] && (Yii::$app->user->can('Role.Insurance')  || Yii::$app->user->can('Role.Manager'))) {
            $query->andWhere('lead_master_status_id = ' . (int) $params['master_status_id'] );
        }

		// Limit by office
        if (! Yii::$app->user->can('Office.NoLimit')) {
            $query->andWhere('office_id = ' . Yii::$app->user->identity->office_id);
        }
        if (isset($params['insurance-index']))
        {
            $query->andWhere('insurance_agent is not null');
        }
        //
        //var_dump($params['deleted']);exit;
        //var_dump($params);exit;
        if($params['deleted']==null)
        {
            $query->andWhere('active=1');
        }
        else
        {
            $query->andWhere('active=0');
        }

        if($params['crm'])
        {
            if (Yii::$app->user->id == 173) {
                $query->andWhere('user_id=173');
            } else {
                $query->andWhere('lead_owner_id=173 AND user_id=173');
            }
        }


        // ????
        if ( ! empty($params['Lead']['added_at'])) {
            $query->andWhere('added_at > ' . strtotime($params['Lead']['added_at']) . ' AND added_at < ' . (strtotime($params['Lead']['added_at']) + 86400));
        }




            // Filter by period
            $request = Yii::$app->request->getQueryParams();
            if (!empty($request['start_date']) && !empty($request['end_date'])) {

                $start_date = $request['start_date'];
                $end_date = $request['end_date'];
                $start_time = empty($request['start_time']) ? '00:00:00' : $request['start_time'];
                $end_time = empty($request['end_time']) ? '23:59:59' : $request['end_time'];



                $start = strtotime($start_date . ' ' . $start_time);
                $end = strtotime($end_date . ' ' . $end_time);

                $query->andWhere('added_at >= ' . $start . ' AND added_at <= ' . $end);
            }
        // HACK:
        //$query->where('added_at >= 1543816825')
         //   ->andWhere('added_at <= 1544162425');

    //   if (empty($start_date) || empty($end_date)) {
    //       list($start_date, $end_date , $start_time , $end_time) = SalesReport::getPeriodFromRequest($params);
    //   }
    //   $startLead = strtotime($start_date);
    //           $endLead = strtotime($end_time);
    //           $query->where('added_at >= '.$startLead)-> andWhere('added_at <='.$endLead) ;




        // For my-leads action
        if ( isset($_GET['my-leads'])) {
            $this->lead_owner_id = Yii::$app->user->identity->id;
            $query->andWhere('lead_owner_id='.Yii::$app->user->identity->id);
            // COVID-19
            if (Yii::$app->user->can('Sales Person')) {
                $query->andWhere('covid19 = 0');
            }
        }
        //for my-lead customer
        if (isset($_GET['my-leadsa'])){
//            $this->service_owner_id = Yii::$app->user->identity->id;

            $query->andWhere('service_owner_id = ' . Yii::$app->user->id);
        }
        //for my leads insurance
        if (isset($_GET['my-leadsi'])){
//            $this->service_owner_id = Yii::$app->user->identity->id;

            $query->andWhere('insurance_agent = ' . Yii::$app->user->id);
        }
        // Order
        $query->orderBy('added_at DESC');

        //
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }



        $query->andFilterWhere([
            'id' => $this->id,
            'office_id' => $this->office_id,
            'lead_type_id' => $this->lead_type_id,
            'lead_owner_id' => $this->lead_owner_id,
            'lead_status_id' => $this->lead_status_id,
            'lead_source_id' => $this->lead_source_id,
            'opportunity_amount' => $this->opportunity_amount,
            'service_status_id' => $this->service_status_id,
            'user_id' => $this->user_id,
            //'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'lead_name', $this->lead_name])
            //->andFilterWhere(['like', 'lead_description', $this->lead_description])
            //->andFilterWhere(['like', 'lead_status_description', $this->lead_status_description])
            //->andFilterWhere(['like', 'lead_source_description', $this->lead_source_description])
            //->andFilterWhere(['like', 'do_not_call', $this->do_not_call])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'fax', $this->fax]);


        return $dataProvider;
    }

    public function filter($params)
    {
        if (isset($params['Lead'])) {
            $Lead = $params['Lead'];
            $params['office_id'] = $Lead['office_id'];
            $params['lead_owner_id'] = $Lead['lead_owner_id'];

        }
        $office = CommonModel::getOfficeSql($params['office_id']);
        $agent = CommonModel::getAgentSql($params['agent_id']);
        $mean = CommonModel::getMeanSql($params['mean_id']);
        list($start, $end) = SalesReport::getPeriodFromRequest($params);
        $query = LeadModel::find();
        if (isset($params['lead_status']))
        {
            $querylist = clone $query;
            $lead_status = isset($params['lead_status']) ? ($params['lead_status'] == '' ? '' : (' lead_status_id = ' . $params['lead_status'])) : '';
            $querylist = $querylist->where("DATE_FORMAT(FROM_UNIXTIME(added_at), '%Y-%m-%d') >='$start' and DATE_FORMAT(FROM_UNIXTIME(added_at), '%Y-%m-%d') <='$end'".$office.$agent)
            ->andWhere($lead_status)
            ;
            $dataProvider = new ActiveDataProvider([
                'query' => $querylist,
            ]);
            return $dataProvider;
        }
        switch ($params['type']) {

            case 'leads':
                $query = $query->where("DATE_FORMAT(FROM_UNIXTIME(added_at), '%Y-%m-%d') >='$start' and DATE_FORMAT(FROM_UNIXTIME(added_at), '%Y-%m-%d') <='$end'".$office.$agent.$mean);
                break;
            case 'appointments':
                //$query = LeadModel::find();
                $query->join('LEFT JOIN', 'tbl_appointment', 'tbl_lead.id = tbl_appointment.entity_id');
//                new filter appointment with column in tbl_lead "appointment_date"
                if($start >= '2020-03-15')
                {
                    $query->where("tbl_lead.appointment_date >='$start' and tbl_lead.appointment_date <='$end'".$office.$agent.$mean);
                }
                else
                {
                    $query->where("tbl_appointment.date >='$start' and tbl_appointment.date <='$end'".$office.$agent.$mean);
                }
                $query->groupBy('tbl_appointment.entity_id');


                break;
            case 'ups':
                /*$query->join('LEFT JOIN', 'tbl_appointment', 'tbl_lead.id = tbl_appointment.entity_id');
                $query->where("DATE_FORMAT(FROM_UNIXTIME(tbl_appointment.updated_at), '%Y-%m-%d') >='$start' and DATE_FORMAT(FROM_UNIXTIME(tbl_appointment.updated_at), '%Y-%m-%d') <='$end' and tbl_appointment.status!=2".$office.$agent.$mean);
                $query->groupBy('tbl_appointment.entity_id');*/
                if (isset($params['lead_owner_id']))
                    $query->andWhere( ' and tbl_lead.lead_owner_id = ' . $params['lead_owner_id']);
                    $query->andWhere('tbl_lead.ups_type = 0 and tbl_lead.ups_date >= "' . $start . '" and tbl_lead.ups_date <= "' . $end . '"' .$office.$agent.$mean);

//                $query2 = clone $query;
//                $query->where("DATE_FORMAT(FROM_UNIXTIME(added_at), '%Y-%m-%d') = DATE_FORMAT(FROM_UNIXTIME(converted_at), '%Y-%m-%d')");
//                $query->andWhere("DATE_FORMAT(FROM_UNIXTIME(added_at), '%Y-%m-%d') >='$start' and DATE_FORMAT(FROM_UNIXTIME(added_at), '%Y-%m-%d') <='$end'".$office.$agent.$mean);
//                $query2->join('LEFT JOIN', 'tbl_appointment', 'tbl_lead.id = tbl_appointment.entity_id');
//                $query2->where("DATE_FORMAT(FROM_UNIXTIME(tbl_appointment.updated_at), '%Y-%m-%d') >='$start' and DATE_FORMAT(FROM_UNIXTIME(tbl_appointment.updated_at), '%Y-%m-%d') <='$end' AND tbl_appointment.status=1".$office.$agent.$mean);
//                $query2->groupBy('tbl_appointment.entity_id');
//                //$query2->orderBy('tbl_appointment.updated_at ASC');
//                $query->union($query2);
                break;
            case 'converted':
                $query->where("DATE_FORMAT(FROM_UNIXTIME(converted_at), '%Y-%m-%d') >='$start' and DATE_FORMAT(FROM_UNIXTIME(converted_at), '%Y-%m-%d') <='$end' and lead_status_id = " . LeadStatus::_CONVERTED.$office.$agent.$mean);
                break;
            case 'charged':
                $agent_id = (int) $params['agent_id'];
                $query->join('LEFT JOIN', 'tbl_payment', 'tbl_lead.id = tbl_payment.entity_id');

                if (empty($agent_id)) {
                    $query->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end'".$office.$agent.$mean);
                } else {
                    $query2 = clone $query;
                    $query3 = clone $query;


                    // Propetary
                    $agent = ' and tbl_payment.generator_id = ' . $params['agent_id'];
                    $query->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and (co_generator_id IS NULL or co_generator_id = 0)".$office.$agent.$mean);

                    // With colaborator
                    $agent = ' and tbl_payment.generator_id = ' . $params['agent_id'];
                    $query2->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and (co_generator_id IS NOT NULL and co_generator_id > 0)".$office.$agent.$mean);

                    // As colaborator
                    $agent = ' and tbl_payment.co_generator_id = ' . $params['agent_id'];
                    $query3->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end'".$office.$agent.$mean);
                    $query4 = clone $query;
                    $query5 = clone $query;
                    $query6 = clone $query;
                    $query7 = clone $query;
                    $query->union($query2);
                    $query->union($query3);
                    $query->union($query4);
                    $query->union($query5);
                    $query->union($query6);
                    $query->union($query7);
                }
                break;
            case 'amount':
                $query->join('LEFT JOIN', 'tbl_payment', 'tbl_lead.id = tbl_payment.entity_id');
                $query->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and tbl_payment.type = ".Payment::_NEW_CONTRACT.$office.$agent.$mean)->groupBy('tbl_lead.id');
                break;
            case 'payment':
                $payment_type = $params['type_id'];
                $query->join('LEFT JOIN', 'tbl_payment', 'tbl_lead.id = tbl_payment.entity_id');
                if (empty($params['agent_id'])) {
                    $query->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and tbl_payment.type = ".$payment_type.$office.$agent.$mean);
                } else {
                    $query2 = clone $query;
                    $query3 = clone $query;


                    // Propetary
                    $agent = ' and tbl_payment.generator_id = ' . $params['agent_id'];
                    $query->where("STR_TO_DATE(tbl_payment.date, '%d-%m-%Y') >='$start' and STR_TO_DATE(tbl_payment.date, '%d-%m-%Y') <='$end' and (co_generator_id IS NULL or co_generator_id = 0) and tbl_payment.type = ".$payment_type.$office.$agent.$mean);

                    // With colaborator
                    $agent = ' and tbl_payment.generator_id = ' . $params['agent_id'];
                    $query2->where("STR_TO_DATE(tbl_payment.date, '%d-%m-%Y') >='$start' and STR_TO_DATE(tbl_payment.date, '%d-%m-%Y') <='$end' and (co_generator_id IS NOT NULL and co_generator_id > 0) and tbl_payment.type = ".$payment_type.$office.$agent.$mean);

                    // As colaborator
                    $agent = ' and tbl_payment.co_generator_id = ' . $params['agent_id'];
                    $query3->where("STR_TO_DATE(tbl_payment.date, '%d-%m-%Y') >='$start' and STR_TO_DATE(tbl_payment.date, '%d-%m-%Y') <='$end' and tbl_payment.type = ".$payment_type.$office.$agent.$mean);
                    $query->union($query2);
                    $query->union($query3);
//                    $query4 = clone $query;
//                    $query5 = clone $query;
//                    $query6 = clone $query;
//                    $query7 = clone $query;
//                    $query->union($query2);
//                    $query->union($query3);
//                    $query->union($query4);
//                    $query->union($query5);
//                    $query->union($query6);
//                    $query->union($query7);


                }

                break;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'office_id' => $this->office_id,
            'lead_type_id' => $this->lead_type_id,
            'lead_owner_id' => $this->lead_owner_id,
            'lead_status_id' => $this->lead_status_id,
            'lead_source_id' => $this->lead_source_id,
            'opportunity_amount' => $this->opportunity_amount,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'lead_name', $this->lead_name])
            //->andFilterWhere(['like', 'lead_description', $this->lead_description])
            //->andFilterWhere(['like', 'lead_status_description', $this->lead_status_description])
            //->andFilterWhere(['like', 'lead_source_description', $this->lead_source_description])
            //->andFilterWhere(['like', 'do_not_call', $this->do_not_call])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'fax', $this->fax]);



        return $dataProvider;
    }

    /**
     * Effectiveness
     *
     * @param $params
     * @return array|\yii\db\ActiveRecord[]
     */
    public function effectiveness($params)
    {
        $office = CommonModel::getOfficeSql($params['office_id'], 'tbl_lead');
        $agent =  CommonModel::getAgentSql($params['agent_id']) ;
        //$mean = CommonModel::getMeanSql($params['mean_id']);
        list($start, $end) = SalesReport::getPeriodFromRequest($params);

        // Primary query
        $query = LeadModel::find()
            ->where('lead_status_id != 4' . $office. $agent);

        // Compose dates
        $start = strtotime($start . ' 00:00:00');
        $end = strtotime($end . ' 23:59:59');

        //
        $query->andWhere('added_at >= ' . $start . ' AND added_at <= ' . $end);


        return $query->all();
    }

    /**
     *
     * @param $params
     * @return array|\yii\db\ActiveRecord[]
     */
    public function effectiveness_sales($params)
    {
        $office = CommonModel::getOfficeSql($params['office_id'], 'tbl_lead');
        $agent =  CommonModel::getAgentSql($params['agent_id']) ;
        //$mean = CommonModel::getMeanSql($params['mean_id']);
        list($start, $end) = SalesReport::getPeriodFromRequest($params);

        // Primary query
        $query = LeadModel::find()
            ->where("lead_status_id = " . LeadStatus::_CONVERTED.$office.$agent);

        // Compose dates
        $start = strtotime($start . ' 00:00:00');
        $end = strtotime($end . ' 23:59:59');

        //
        $query->andWhere('converted_at >= ' . $start . ' AND converted_at <= ' . $end);


        return $query->all();
    }

    public function ranking($params)
    {
        //
        list($start, $end) = SalesReport::getPeriodFromRequest($params);

        // Primary query
        $genQuery = ( new Query())
            ->select('tbl_payment.*,tbl_payment.status as status_payment , tbl_lead.office_id, tbl_user.first_name, tbl_user.last_name, tbl_user.middle_name, tbl_user.username, tbl_user.active , auth_item.name , auth_item.description AS role, tbl_office.code AS office_code')
            ->from('tbl_payment')
            ->leftJoin('tbl_lead', 'tbl_lead.id = tbl_payment.entity_id')
            ->leftJoin('tbl_user', 'tbl_user.id = tbl_payment.generator_id')
            ->leftJoin('auth_assignment', 'tbl_user.id = auth_assignment.user_id')
            ->leftJoin('auth_item', 'auth_item.name = auth_assignment.item_name')
            ->leftJoin('tbl_office', 'tbl_office.id = tbl_user.office_id')
            ->where("tbl_payment.date >='$start' and tbl_payment.date <='$end'")
            ->andWhere('tbl_payment.co_generator_id IS NULL OR tbl_payment.co_generator_id = 0')
            ->andWhere('auth_item.name != "Customer.Director" and auth_item.name != "Customer.Service" and auth_item.name != "Customer.Service2"')
            ->andWhere('tbl_payment.type != 5')
            ->andWhere('tbl_payment.status = 1');


        //
        $gen2Query = ( new Query())
            ->select('tbl_payment.*,tbl_payment.status as status_payment , tbl_lead.office_id, tbl_user.first_name, tbl_user.last_name, tbl_user.middle_name, tbl_user.username, tbl_user.active , auth_item.name ,auth_item.description AS role, tbl_office.code AS office_code')
            ->from('tbl_payment')
            ->leftJoin('tbl_lead', 'tbl_lead.id = tbl_payment.entity_id')
            ->leftJoin('tbl_user', 'tbl_user.id = tbl_payment.generator_id')
            ->leftJoin('auth_assignment', 'tbl_user.id = auth_assignment.user_id')
            ->leftJoin('auth_item', 'auth_item.name = auth_assignment.item_name')
            ->leftJoin('tbl_office', 'tbl_office.id = tbl_user.office_id')
            ->where("tbl_payment.date >='$start' and tbl_payment.date <='$end'")
            ->andWhere('tbl_payment.co_generator_id IS NOT NULL AND tbl_payment.co_generator_id > 0')
            ->andWhere('auth_item.name != "Customer.Director" and auth_item.name != "Customer.Service" and auth_item.name != "Customer.Service2"')
            ->andWhere('tbl_payment.type != 5')
            ->andWhere('tbl_payment.status = 1');


        //$gen2Query = clone $query;
        $cogQuery = ( new Query())
            ->select('tbl_payment.*,tbl_payment.status as status_payment , tbl_lead.office_id, tbl_user.first_name, tbl_user.last_name, tbl_user.middle_name, tbl_user.username, tbl_user.active ,auth_item.name , auth_item.description AS role, tbl_office.code AS office_code')
            ->from('tbl_payment')
            ->leftJoin('tbl_lead', 'tbl_lead.id = tbl_payment.entity_id')
            ->leftJoin('tbl_user', 'tbl_user.id = tbl_payment.co_generator_id')
            ->leftJoin('auth_assignment', 'tbl_user.id = auth_assignment.user_id')
            ->leftJoin('auth_item', 'auth_item.name = auth_assignment.item_name')
            ->leftJoin('tbl_office', 'tbl_office.id = tbl_user.office_id')
            ->where("tbl_payment.date >='$start' and tbl_payment.date <='$end'")
            ->andWhere('tbl_payment.co_generator_id IS NOT NULL AND tbl_payment.co_generator_id > 0')
            ->andWhere('auth_item.name != "Customer.Director" and auth_item.name != "Customer.Service" and auth_item.name != "Customer.Service2"')
            ->andWhere('tbl_payment.type != 5')
            ->andWhere('tbl_payment.status = 1');

        //
        $data = [];
        //
        $payments = $genQuery->groupBy('tbl_payment.id')->all();
        foreach ($payments as $row) {

            if ( ! isset($data[$row['generator_id']])) {
                $data[$row['generator_id']] = [
                    'user_id' => $row['generator_id'],
                    'office_id' => $row['office_id'],
                    'office' => $row['office_code'],
                    'username' => $row['username'],
                    'name' => $row['first_name'] . ' ' . $row['last_name'] . ' ' . $row['middle_name'],
                    'payments' => 0,
                    'ccin' => 0,
                    'total' => 0,
                    'role' => $row['role'],
                    'active' => $row['active']
                ];
            }
            // Sum
            $data[$row['generator_id']]['total'] += $row['amount'];
            $data[$row['generator_id']]['payments']++;
            if ($row['type'] == 1 OR $row['type'] == 2)
                $data[$row['generator_id']]['ccin']++;
        }

        //
        $payments = $gen2Query->groupBy('tbl_payment.id')->all();

        foreach ($payments as $row) {

            if ( ! isset($data[$row['generator_id']])) {
                $data[$row['generator_id']] = [
                    'user_id' => $row['generator_id'],
                    'office_id' => $row['office_id'],
                    'office' => $row['office_code'],
                    'username' => $row['username'],
                    'name' => $row['first_name'] . ' ' . $row['last_name'] . ' ' . $row['middle_name'],
                    'payments' => 0,
                    'ccin' => 0,
                    'total' => 0,
                    'role' => $row['role'],
                    'active' => $row['active']
                ];
            }

            // Sum
            $data[$row['generator_id']]['total'] += ($row['amount'] / 2);
            $data[$row['generator_id']]['payments']++;
            if ($row['type'] == 1 OR $row['type'] == 2)
                $data[$row['generator_id']]['ccin']++;
        }


//        echo '<pre>';
//        print_r($data);
//        echo '</pre>';exit;

        $payments = $cogQuery->all();

        foreach ($payments as $row)
        {
            if ( ! isset($data[$row['co_generator_id']])) {
                $data[$row['co_generator_id']] = [
                    'user_id' => $row['co_generator_id'],
                    'office_id' => $row['office_id'],
                    'office' => $row['office_code'],
                    'username' => $row['username'],
                    'name' => $row['first_name'] . ' ' . $row['last_name'] . ' ' . $row['middle_name'],
                    'payments' => 0,
                    'ccin' => 0,
                    'total' => 0,
                    'role' => $row['role'],
                    'active' => $row['active']
                ];
            }

            // Sum
            $data[$row['co_generator_id']]['total'] += ($row['amount'] / 2);
            $data[$row['co_generator_id']]['payments']++;
            if ($row['type'] == 1 OR $row['type'] == 2)
                $data[$row['co_generator_id']]['ccin']++;
        }
//        echo '<pre>';
//print_r($data);
//        echo '</pre>';
//        exit;

        usort($data, function ($a, $b) {
            return $b['total'] - $a['total'];
        });

        return $data;
    }

    public function rankinginsurance($params)
    {

        //
        list($start, $end) = SalesReport::getPeriodFromRequest($params);

        // Primary query
        $genQuery = ( new Query())
            ->select('tbl_payment.*,tbl_payment.status as status_payment , tbl_lead.office_id, tbl_user.first_name, tbl_user.last_name, tbl_user.middle_name, tbl_user.username, tbl_user.active , auth_item.name , auth_item.description AS role, tbl_office.code AS office_code')
            ->from('tbl_payment')
            ->leftJoin('tbl_lead', 'tbl_lead.id = tbl_payment.entity_id')
            ->leftJoin('tbl_user', 'tbl_user.id = tbl_payment.generator_id')
            ->leftJoin('auth_assignment', 'tbl_user.id = auth_assignment.user_id')
            ->leftJoin('auth_item', 'auth_item.name = auth_assignment.item_name')
            ->leftJoin('tbl_office', 'tbl_office.id = tbl_user.office_id')
            ->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end'")
            ->andWhere('tbl_payment.co_generator_id IS NULL OR tbl_payment.co_generator_id = 0')
            ->andWhere('auth_item.name != "Insurance" and auth_item.name != "Insurance.Customer" and auth_item.name != "Insurance.Director"')
            ->andWhere('tbl_payment.type = 5')
            ->andWhere('tbl_payment.status = 1');
        //
        $gen2Query = ( new Query())
            ->select('tbl_payment.*,tbl_payment.status as status_payment , tbl_lead.office_id, tbl_user.first_name, tbl_user.last_name, tbl_user.middle_name, tbl_user.username, tbl_user.active , auth_item.name ,auth_item.description AS role, tbl_office.code AS office_code')
            ->from('tbl_payment')
            ->leftJoin('tbl_lead', 'tbl_lead.id = tbl_payment.entity_id')
            ->leftJoin('tbl_user', 'tbl_user.id = tbl_payment.generator_id')
            ->leftJoin('auth_assignment', 'tbl_user.id = auth_assignment.user_id')
            ->leftJoin('auth_item', 'auth_item.name = auth_assignment.item_name')
            ->leftJoin('tbl_office', 'tbl_office.id = tbl_user.office_id')
            ->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end'")
            ->andWhere('tbl_payment.co_generator_id IS NOT NULL AND tbl_payment.co_generator_id > 0')
            ->andWhere('auth_item.name != "Insurance" and auth_item.name != "Insurance.Customer" and auth_item.name != "Insurance.Director"')
            ->andWhere('tbl_payment.type = 5')
            ->andWhere('tbl_payment.status = 1');


        //$gen2Query = clone $query;
        $cogQuery = ( new Query())
            ->select('tbl_payment.*,tbl_payment.status as status_payment , tbl_lead.office_id, tbl_user.first_name, tbl_user.last_name, tbl_user.middle_name, tbl_user.username, tbl_user.active ,auth_item.name , auth_item.description AS role, tbl_office.code AS office_code')
            ->from('tbl_payment')
            ->leftJoin('tbl_lead', 'tbl_lead.id = tbl_payment.entity_id')
            ->leftJoin('tbl_user', 'tbl_user.id = tbl_payment.co_generator_id')
            ->leftJoin('auth_assignment', 'tbl_user.id = auth_assignment.user_id')
            ->leftJoin('auth_item', 'auth_item.name = auth_assignment.item_name')
            ->leftJoin('tbl_office', 'tbl_office.id = tbl_user.office_id')
            ->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end'")
            ->andWhere('tbl_payment.co_generator_id IS NOT NULL AND tbl_payment.co_generator_id > 0')
            ->andWhere('auth_item.name != "Insurance" and auth_item.name != "Insurance.Customer" and auth_item.name != "Insurance.Director"')
            ->andWhere('tbl_payment.type = 5')
            ->andWhere('tbl_payment.status = 1');

        //
        $data = [];
        //
        $payments = $genQuery->groupBy('tbl_payment.id')->all();
        foreach ($payments as $row) {

            if ( ! isset($data[$row['generator_id']])) {
                $data[$row['generator_id']] = [
                    'user_id' => $row['generator_id'],
                    'office_id' => $row['office_id'],
                    'office' => $row['office_code'],
                    'username' => $row['username'],
                    'name' => $row['first_name'] . ' ' . $row['last_name'] . ' ' . $row['middle_name'],
                    'payments' => 0,
                    'ccin' => 0,
                    'total' => 0,
                    'role' => $row['role'],
                    'active' => $row['active']
                ];
            }
            // Sum
            $data[$row['generator_id']]['total'] += $row['amount'];
            $data[$row['generator_id']]['payments']++;
            if ($row['type'] == 1 OR $row['type'] == 2)
                $data[$row['generator_id']]['ccin']++;
        }

        //
        $payments = $gen2Query->groupBy('tbl_payment.id')->all();

        foreach ($payments as $row) {

            if ( ! isset($data[$row['generator_id']])) {
                $data[$row['generator_id']] = [
                    'user_id' => $row['generator_id'],
                    'office_id' => $row['office_id'],
                    'office' => $row['office_code'],
                    'username' => $row['username'],
                    'name' => $row['first_name'] . ' ' . $row['last_name'] . ' ' . $row['middle_name'],
                    'payments' => 0,
                    'ccin' => 0,
                    'total' => 0,
                    'role' => $row['role'],
                    'active' => $row['active']
                ];
            }

            // Sum
            $data[$row['generator_id']]['total'] += ($row['amount'] / 2);
            $data[$row['generator_id']]['payments']++;
            if ($row['type'] == 1 OR $row['type'] == 2)
                $data[$row['generator_id']]['ccin']++;
        }


//        echo '<pre>';
//        print_r($data);
//        echo '</pre>';exit;

        $payments = $cogQuery->all();

        foreach ($payments as $row)
        {
            if ( ! isset($data[$row['co_generator_id']])) {
                $data[$row['co_generator_id']] = [
                    'user_id' => $row['co_generator_id'],
                    'office_id' => $row['office_id'],
                    'office' => $row['office_code'],
                    'username' => $row['username'],
                    'name' => $row['first_name'] . ' ' . $row['last_name'] . ' ' . $row['middle_name'],
                    'payments' => 0,
                    'ccin' => 0,
                    'total' => 0,
                    'role' => $row['role'],
                    'active' => $row['active']
                ];
            }

            // Sum
            $data[$row['co_generator_id']]['total'] += ($row['amount'] / 2);
            $data[$row['co_generator_id']]['payments']++;
            if ($row['type'] == 1 OR $row['type'] == 2)
                $data[$row['co_generator_id']]['ccin']++;
        }
//        echo '<pre>';
//print_r($data);
//        echo '</pre>';
//        exit;

        usort($data, function ($a, $b) {
            return $b['total'] - $a['total'];
        });


        return $data;
    }
    /**************************
     */
    public function history($params){

        //history params
        $start = isset($params['start']) ? $params['start'] : '';
        $end = isset($params['end']) ? $params['end'] : '';
        $office = isset($params['office_id']) ? $params['office_id'] : '';
        $agent = isset($params['agent_id']) ? $params['agent_id'] : '';
        $sql_log = '';

        if ($start != '')
        {
            $start = strtotime($start . "00:00");
            $sql_log = 'tbl_history.added_at >= ' . $start;
        }
        if ($end != '')
        {
            $end = strtotime($end . "23:59");
            $sql_log = $sql_log . ' and tbl_history.added_at <= ' . $end;
        }
        if ($office != '')
        {
            if ($sql_log === ''){
                $sql_log = ' tbl_office.id = ' . $office ;
            }
            else{
                $sql_log = $sql_log . ' and tbl_office.id = ' . $office;
            }

        }
        if ($agent != '')
        {
            if ($sql_log === ''){
                $sql_log = ' tbl_user.id = ' . $agent ;
            }
            else{
                $sql_log = $sql_log . ' and tbl_user.id = ' . $agent;
            }


        }

        $user = (new Query())
            ->select("tbl_history.*, tbl_office.code , tbl_user.first_name , tbl_user.last_name , tbl_user.middle_name , tbl_history.id,tbl_user.office_id ,tbl_lead.c_control  , tbl_lead.lead_name , tbl_history.notes , tbl_history.added_at")
            ->from('tbl_history')
            ->leftJoin('tbl_user' , 'tbl_user.id = tbl_history.user_id')
            ->leftJoin('tbl_lead' , 'tbl_lead.id = tbl_history.entity_id')
            ->leftJoin('tbl_office' , 'tbl_office.id = tbl_user.office_id')
            ->where("tbl_history.entity_type = 'log' ")
            ->orderBy('tbl_history.added_at DESC');

        //history filter
        if (isset($params['r']) && $params['r'] === 'sales/lead/history')
        {
            $user->andWhere($sql_log);
        }

        if($params['office_id'] != null)
            $user->andWhere('tbl_user.office_id = ' . $params['office_id']);

        if (($params['agent_id'] != null) or ($params['agent_id']))
            $user->andWhere('tbl_user.id = ' . $params['agent_id']);

        $dataProvider = new ActiveDataProvider([
            'query' => $user
        ]);
//        $data = $user->all();
        $_POST['user_id'] = $params['agent_id'];
        return $dataProvider;

    }
    /**
     * Payments
     *
     * @param $params
     * @param $stats
     * @return ActiveDataProvider
     */
    public function payments($params, &$stats)
    {
//        var_dump($params);
        $office = CommonModel::getOfficeSql($params['office_id'], 'tbl_lead');
        // $agent = CommonModel::getAgentSql($params['agent_id']);
        $mean = CommonModel::getMeanSql($params['mean_id']);
        list($start, $end) = SalesReport::getPeriodFromRequest($params);
        // Primary query
        $query = Payment::find()
            ->leftJoin('tbl_lead', 'tbl_lead.id = tbl_payment.entity_id')
            ->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end'".$office.$mean);

            if ($params['r'] == 'sales/lead/insurance')
                $query->andWhere('tbl_payment.type = 5');
            else
            $query->andWhere('tbl_payment.type != 5 ');
            if ( ! Yii::$app->user->can('Payment.Insurance'))
            {
                $query->andWhere('tbl_payment.type = 1 or tbl_payment.type = 2 or tbl_payment.type = 3 or tbl_payment.type = 4 ');
            }


        // Agent filter
        if ($params['agent_id']) {
            $this->_sum_by_agent($query, $stats, $params['agent_id']);
            $query->andWhere('(tbl_payment.generator_id = ' . $params['agent_id'] . ' OR tbl_payment.co_generator_id = ' . $params['agent_id'] . ')');
        } else {
            $this->_sum_all_agents($query, $stats);
            $query->leftJoin('tbl_user', '(tbl_user.id = tbl_payment.generator_id OR tbl_user.id = tbl_payment.co_generator_id)');
        }
        if ($params['Lead']['added_at'])
        {
            $query->andWhere('  tbl_lead.added_at > '.((int) strtotime($params['Lead']['added_at'])).' AND tbl_lead.added_at < '.((int) (strtotime($params['Lead']['added_at']) + 86400)));


        }
        // Show all payments, enteros & divididos
        $stats['payments'] = $query->count();
        // Select for original query
        $query->select('tbl_payment.id as pid, tbl_payment.generator_id, tbl_payment.co_generator_id, tbl_payment.amount, tbl_payment.type, tbl_payment.date, tbl_payment.origin, tbl_payment.folio, tbl_payment.entity_id, tbl_payment.received ,tbl_payment.status');

            if ($params['payment_type'] == 1) {
                $query->andwhere('tbl_payment.type = 1 or tbl_payment.type = 2 or tbl_payment.type = 3 or tbl_payment.type = 4 ');
                $stats['payments'] = $query->count();
                $this->_sum_all_agents($query, $stats);
            }
            if ($params['payment_type'] == 2){
                $query->andwhere('tbl_payment.type = 5');
                $stats['payments'] = $query->count();
                $this->_sum_all_agents($query, $stats);
            }
            //
            if ($params['payment_state'] == 1)
            {
                $query->andWhere('tbl_payment.status = ' . Payment::UNVALIDATED);
                $stats['payments'] = $query->count();
                $this->_sum_all_agents($query, $stats);
            }
            if ($params['payment_state'] == 2)
            {
                $query->andWhere('tbl_payment.status = ' . Payment::VALIDATED);
                $stats['payments'] = $query->count();
                $this->_sum_all_agents($query, $stats);
            }
            if ($params['payment_state'] == 3)
            {
                $query->andWhere('tbl_payment.status = ' . Payment::DECLINED);
                $stats['payments'] = $query->count();
                $this->_sum_all_agents($query, $stats);
            }


        //
        $dataProvider = new ActiveDataProvider([
            'query' => $query,

        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'office_id' => $this->office_id,
            'c_control' => $this->c_control,
            'user_id' => $this->user_id
        ]);
        $query
            ->andFilterWhere(['like', 'lead_name', $this->lead_name])
            ->andFilterWhere(['like', 'c_control', $this->c_control])
            ->andFilterWhere(['like','lead_source_id',$this->lead_source_id]);
        return $dataProvider ;
    }

    public function payments_unvalidated($params)
    {
        $office = CommonModel::getOfficeSql($params['office_id'], 'tbl_lead');
        // $agent = CommonModel::getAgentSql($params['agent_id']);
        $mean = CommonModel::getMeanSql($params['mean_id']);
        list($start, $end) = SalesReport::getPeriodFromRequest($params);

        $query = Payment::find()
            ->leftJoin('tbl_lead', 'tbl_lead.id = tbl_payment.entity_id')
            ->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end'".$office.$mean);

        // Agent filter
        if ($params['agent_id']) {
            $query->andWhere('(tbl_payment.generator_id = ' . $params['agent_id'] . ' OR tbl_payment.co_generator_id = ' . $params['agent_id'] . ')');
        }

        $query
            ->andWhere('status = ' . Payment::UNVALIDATED);

        //
        $dataProvider = new ActiveDataProvider([
            'query' => $query,

        ]);

        return $dataProvider;
    }

    /**
     * Sum all stats
     *
     * @param $query
     * @param $stats
     */
    protected function _sum_all_agents($query, &$stats)
    {
        // Get stats
        $sumQuery = clone $query;
        $stats['efectivo'] = $sumQuery->andWhere('tbl_payment.origin = 1 and tbl_payment.type != 5 and tbl_payment.status = 1')->sum('amount');
        $sumQuery = clone  $query;
        $stats['transferencia'] = $sumQuery->andWhere('tbl_payment.origin = 2 and tbl_payment.type != 5 and tbl_payment.status = 1')->sum('amount');
        $sumQuery = clone  $query;
        $stats['deposito'] = $sumQuery->andWhere('tbl_payment.origin = 3 and tbl_payment.type != 5 and tbl_payment.status = 1')->sum('amount');
        $sumQuery = clone  $query;
        $stats['tarjeta'] = $sumQuery->andWhere('tbl_payment.origin = 4 and tbl_payment.type != 5 and tbl_payment.status = 1' )->sum('amount');
        $sumQuery = clone  $query;
        $stats['insurance']['total'] = $sumQuery->andWhere('tbl_payment.type = 5 and tbl_payment.status = 1')->sum('amount');
        $sumQuery = clone $query;
        $stats['validate'] = $sumQuery->andWhere('tbl_payment.status = 0')->sum('amount');
        $sumQuery = clone $query;
        $stats['declinated'] = $sumQuery->andWhere('tbl_payment.status = 2')->sum('amount');

        $stats['total-validate'] = $stats['efectivo'] + $stats['transferencia'] + $stats['deposito'] + $stats['tarjeta'];
        // Sum total
        $stats['total'] = $stats['efectivo'] + $stats['transferencia'] + $stats['deposito'] + $stats['tarjeta'] + $stats['insurance']['total'] + $stats['validate'] + $stats['declinated'];
    }

    /**
     * @param $query
     * @param $stats
     * @param $agent_id
     */
    protected function _sum_by_agent($query, &$stats, $agent_id) {

        $genQuery = clone $query;
        $gen2Query = clone $query;
        $cogQuery = clone $query;

        // Without co-generator
        $genQuery->andWhere('tbl_payment.generator_id = ' . $agent_id . ' AND (tbl_payment.co_generator_id IS NULL OR tbl_payment.co_generator_id = 0)');

        // With co-generator
        $gen2Query->andWhere('tbl_payment.generator_id = ' . $agent_id . ' AND (tbl_payment.co_generator_id IS NOT NULL AND tbl_payment.co_generator_id > 0)');

        // Only co-generator
        $cogQuery->andWhere('tbl_payment.co_generator_id = ' . $agent_id);

        //
        $this->_sum_payments($genQuery->all(), $stats);
        $this->_sum_payments($gen2Query->all(), $stats, true);
        $this->_sum_payments($cogQuery->all(), $stats, true);

        // Sum total
        $stats['total'] = $stats['efectivo'] + $stats['transferencia'] + $stats['deposito'] + $stats['tarjeta'];
    }

    /**
     * @param array $data Payments of db
     * @param array $stats
     * @param bool $divide If TRUE then divide amount
     */
    protected function _sum_payments($data, &$stats, $divide = false)
    {
        // helper
        $help = [1 => 'efectivo', 2 => 'transferencia', 3 => 'deposito', 4 => 'tarjeta'];

        if ($data) {
            foreach ($data as $item) {
                $index = $help[$item->origin];

                $stats[$index] += $divide === true ? $item->amount / 2 : $item->amount;
            }
        }
    }

	public function searchLead(){

		$sql ="select 			

					tbl_city.city,

					tbl_country.country,

					tbl_state.state,

					tbl_address.*,

					tbl_lead.*,

					tbl_customer_type.type 

				from 
					tbl_lead

					 
					LEFT JOIN tbl_customer_type
					ON tbl_lead.lead_type_id=tbl_customer_type.id
					
					LEFT JOIN tbl_address
					ON tbl_address.entity_id=tbl_lead.id and tbl_address.is_primary='1'
					
					LEFT JOIN tbl_country
					ON tbl_country.id=tbl_address.country_id
					
					LEFT JOIN tbl_state
					ON tbl_state.id=tbl_address.state_id
					
					LEFT JOIN tbl_city
					ON tbl_city.id=tbl_address.city_id
					";



			$connection = \Yii::$app->db;

			$command=$connection->createCommand($sql);

			$dataReader=$command->queryAll();



		return $dataReader;

	}


	public function searchMyLeads(){

		$query = LeadModel::find ()->where ( [
				'lead_owner_id' => Yii::$app->user->identity->id
		] );

		$dataProvider = new ActiveDataProvider ( [
				'query' => $query
		] );

		if (! ($this->load ( $params ) && $this->validate ()))
		{
			return $dataProvider;
		}

		return $dataProvider;


	}
	public function migrateLeads($params)
    {
        $c_control = str_replace(' ',"','",$params['leads-migrates']);
        if (!isset($c_control) || $c_control != '')
        {
            $c_control = "tbl_lead.c_control in ('" . $c_control . "')";
        }
//        $leads = (new Query())
////            ->select(['lead.id','lead.lead_name','lead.added_at','lead.c_control','lead.mobile','lead.lead_status_id','lead.loan_amount','lead.loan_commission','lead.user_id','lead.lead_owner_id','status.id as status_id','status.label','status.status','concat(capturist.first_name, " ",capturist.last_name, " ", capturist.middle_name) as name_capturist', 'concat(owner.first_name, " ",owner.last_name, " ", owner.middle_name) as name_owner'])
////            ->from('tbl_lead as lead')
////            ->leftJoin('tbl_lead_status as status','status.id = lead.lead_status_id')
////            ->leftJoin('tbl_user as capturist','  capturist.id = lead.user_id')
////            ->leftJoin('tbl_user as owner' ,'lead.lead_owner_id = owner.id')
////            ->andWhere($c_control)->orderBy('lead.id ASC')
////
////            ;
        $query = LeadModel::find();

        $query->leftJoin('tbl_lead_status as status','status.id = tbl_lead.lead_status_id');
        $query->leftJoin('tbl_user as capturist','  capturist.id = tbl_lead.user_id');
        $query->leftJoin('tbl_user as owner' ,'tbl_lead.lead_owner_id = owner.id');
        $query->andWhere($c_control)->orderBy('tbl_lead.id ASC');


        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $query->andFilterWhere([
            'id' => $this->id,
            'office_id' => $this->office_id,
            'lead_type_id' => $this->lead_type_id,
            'lead_owner_id' => $this->lead_owner_id,
            'lead_status_id' => $this->lead_status_id,
            'lead_source_id' => $this->lead_source_id,
            'opportunity_amount' => $this->opportunity_amount,
            'service_status_id' => $this->service_status_id,
            'user_id' => $this->user_id,
            //'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'lead_name', $this->lead_name])
            //->andFilterWhere(['like', 'lead_description', $this->lead_description])
            //->andFilterWhere(['like', 'lead_status_description', $this->lead_status_description])
            //->andFilterWhere(['like', 'lead_source_description', $this->lead_source_description])
            //->andFilterWhere(['like', 'do_not_call', $this->do_not_call])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'fax', $this->fax]);

        return $dataProvider;

    }
    public function reportcustomer($params)
    {
//        var_dump($params);
        $query = LeadModel::find();
        $query->where('tbl_lead.service_owner_id is not null');
//        var_dump($query->count());
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        return $dataProvider;
    }
}

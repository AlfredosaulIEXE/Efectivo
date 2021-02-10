<?php

namespace livefactory\models\search;

use Yii;
use yii\db\Query;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\LeadRecycle as LeadModel;
use livefactory\models\SalesReport;
use livefactory\models\PaymentRecycle as Payment;
/**
 * Lead represents the model behind the search form about `livefactory\models\Lead`.
 */
class LeadRecycle extends LeadModel
{
    public function rules()
    {
        return [
            [['id', 'office_id', 'lead_type_id', 'lead_owner_id', 'lead_status_id', 'lead_source_id', 'opportunity_amount', 'service_status_id', 'updated_at', 'user_id'], 'integer'],
            [['lead_name', 'lead_description', 'lead_status_description', 'lead_source_description', 'do_not_call', 'email', 'first_name', 'last_name', 'phone', 'mobile', 'fax', 'added_at'], 'safe'],
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
        if ($params['review']) {

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
        }

		// Limit by office
        if (! Yii::$app->user->can('Office.NoLimit')) {
            $query->andWhere('office_id = ' . Yii::$app->user->identity->office_id);
        }

        //
        //var_dump($params['deleted']);exit;
        //var_dump($params);exit;
        /*if($params['deleted']==null)
        {
            $query->andWhere('active=1');
        }
        else
        {
            $query->andWhere('active=0');
        }*/

        if($params['crm'])
        {
            if (Yii::$app->user->id == 173) {
                $query->andWhere('user_id=173');
            } else {
                $query->andWhere('lead_owner_id=173 AND user_id=173');
            }
        }


        if ( ! empty($params['Lead']['added_at'])) {
            $query->andWhere('added_at > ' . strtotime($params['Lead']['added_at']) . ' AND added_at < ' . (strtotime($params['Lead']['added_at']) + 86400));
        }

        // HACK:
  //      $query = LeadModel::find()
  //          ->Where('added_at >= 1542428219')
  //          ->andWhere('added_at <= 1543637819');

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
        $office = CommonModel::getOfficeSql($params['office_id']);
        $agent = CommonModel::getAgentSql($params['agent_id']);
        $mean = CommonModel::getMeanSql($params['mean_id']);
        list($start, $end) = SalesReport::getPeriodFromRequest($params);
        $query = LeadModel::find();

        switch ($params['type']) {

            case 'leads':
                $query = $query->where("DATE_FORMAT(FROM_UNIXTIME(added_at), '%Y-%m-%d') >='$start' and DATE_FORMAT(FROM_UNIXTIME(added_at), '%Y-%m-%d') <='$end'".$office.$agent.$mean);
                break;
            case 'appointments':
                //$query = LeadModel::find();
                $query->join('LEFT JOIN', 'tbl_appointment', 'tbl_lead.id = tbl_appointment.entity_id');
                $query->where("DATE_FORMAT(FROM_UNIXTIME(tbl_appointment.added_at), '%Y-%m-%d') >='$start' and DATE_FORMAT(FROM_UNIXTIME(tbl_appointment.added_at), '%Y-%m-%d') <='$end'".$office.$agent.$mean);
                $query->groupBy('tbl_appointment.entity_id');
                break;
            case 'ups':
                /*$query->join('LEFT JOIN', 'tbl_appointment', 'tbl_lead.id = tbl_appointment.entity_id');
                $query->where("DATE_FORMAT(FROM_UNIXTIME(tbl_appointment.updated_at), '%Y-%m-%d') >='$start' and DATE_FORMAT(FROM_UNIXTIME(tbl_appointment.updated_at), '%Y-%m-%d') <='$end' and tbl_appointment.status!=2".$office.$agent.$mean);
                $query->groupBy('tbl_appointment.entity_id');*/
                $query2 = clone $query;
                $query->where("DATE_FORMAT(FROM_UNIXTIME(added_at), '%Y-%m-%d') = DATE_FORMAT(FROM_UNIXTIME(converted_at), '%Y-%m-%d')");
                $query->andWhere("DATE_FORMAT(FROM_UNIXTIME(added_at), '%Y-%m-%d') >='$start' and DATE_FORMAT(FROM_UNIXTIME(added_at), '%Y-%m-%d') <='$end'".$office.$agent.$mean);

                $query2->join('LEFT JOIN', 'tbl_appointment', 'tbl_lead.id = tbl_appointment.entity_id');
                $query2->where("DATE_FORMAT(FROM_UNIXTIME(tbl_appointment.updated_at), '%Y-%m-%d') >='$start' and DATE_FORMAT(FROM_UNIXTIME(tbl_appointment.updated_at), '%Y-%m-%d') <='$end' AND tbl_appointment.status!=2".$office.$agent.$mean);
                $query2->groupBy('tbl_appointment.entity_id');
                $query->union($query2);
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

                    $query->union($query2);
                    $query->union($query3);
                }
                break;
            case 'amount':
                $query->join('LEFT JOIN', 'tbl_payment', 'tbl_lead.id = tbl_payment.entity_id');
                $query->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and tbl_payment.type = ".Payment::_NEW_CONTRACT.$office.$agent.$mean);
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
                    $query->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and (co_generator_id IS NULL or co_generator_id = 0) and tbl_payment.type = ".$payment_type.$office.$agent.$mean);

                    // With colaborator
                    $agent = ' and tbl_payment.generator_id = ' . $params['agent_id'];
                    $query2->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and (co_generator_id IS NOT NULL and co_generator_id > 0) and tbl_payment.type = ".$payment_type.$office.$agent.$mean);

                    // As colaborator
                    $agent = ' and tbl_payment.co_generator_id = ' . $params['agent_id'];
                    $query3->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and tbl_payment.type = ".$payment_type.$office.$agent.$mean);

                    $query->union($query2);
                    $query->union($query3);
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

    public function payments($params)
    {
        $office = CommonModel::getOfficeSql($params['office_id'], 'tbl_lead');
        $agent = CommonModel::getAgentSql($params['agent_id']);
        $mean = CommonModel::getMeanSql($params['mean_id']);
        list($start, $end) = SalesReport::getPeriodFromRequest($params);


        $query = Payment::find()
            ->select('tbl_payment.id as pid, tbl_payment.generator_id, tbl_payment.co_generator_id, tbl_payment.amount, tbl_payment.type, tbl_payment.date, tbl_payment.origin, tbl_payment.folio, tbl_payment.entity_id, tbl_payment.received')
            ->leftJoin('tbl_user', '(tbl_user.id = tbl_payment.generator_id OR tbl_user.id = tbl_payment.co_generator_id)')
            ->leftJoin('tbl_lead', 'tbl_lead.id = tbl_payment.entity_id')
            ->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end'".$office.$agent.$mean);

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
            ->andFilterWhere(['like', 'c_control', $this->c_control]);



        return $dataProvider;
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
}

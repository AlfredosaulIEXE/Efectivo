<?php

namespace livefactory\models\search;

use Yii;
use yii\db\Query;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\Appointment as AppointmentModel;
use livefactory\models\Lead as LeadModel;
use livefactory\models\SalesReport;
use livefactory\models\Payment;

/**
 * Lead represents the model behind the search form about `livefactory\models\Lead`.
 */
class Appointment extends LeadModel
{
    public function rules()
    {
        return [
            [['id', 'lead_type_id', 'lead_source_id', 'opportunity_amount', 'added_at', 'updated_at'], 'integer'],
            [[ 'lead_description', 'lead_status_description', 'lead_source_description', 'do_not_call', 'email', 'first_name', 'last_name', 'phone', 'mobile', 'fax'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params, $customer = false, &$promises, &$appointment_type)
    {
        var_dump($params);
        $current_date = date('Y-m-d');
        list($start, $end) = SalesReport::getPeriodFromRequest($params);
        //
        $query = AppointmentModel::find();
        $query->select('tbl_appointment.*,tbl_appointment.user_id as appointment_capturist, tbl_lead.office_id, tbl_lead.lead_owner_id, tbl_lead.lead_source_id');
        $query->join('LEFT JOIN', 'tbl_lead', 'tbl_appointment.entity_id = tbl_lead.id');
        $query->join('LEFT JOIN', 'tbl_user', 'tbl_appointment.user_id = tbl_user.id');
        // Filters for leads
        //var_dump($params);
        $query->where("tbl_lead.active=1");
        if ($appointment_type == 0)
        {
            $query->andWhere('tbl_appointment.appointment_type = 0');
        }
        if ($appointment_type == 1)
        {
            $query->andWhere('tbl_appointment.appointment_type = 1');
        }
        if ($appointment_type == 2)
        {
            $query->andWhere('tbl_appointment.appointment_type = 2');
        }
        /////
        if (  isset($params['office_id']) && (!empty($params['office_id']))) {
            $query->andwhere('tbl_lead.office_id = ' . $params['office_id']);
        }
        /////
        if (  isset($params['lead_status']) && (!empty($params['lead_status']))) {
            $query->andwhere('tbl_lead.lead_status_id = ' . $params['lead_status']);
        }
        /////
        if (  isset($params['agent_id']) && (!empty($params['agent_id']))) {
            $query->andwhere('tbl_lead.lead_owner_id = ' . $params['agent_id']);
        }
        /////
        if (  isset($params['mean_id']) && (!empty($params['mean_id']))) {
            $query->andwhere('tbl_appointment.user_id = ' . $params['mean_id']);
        }
        /////

        if ( isset($params['type_appointment'])  )
        {
            $query->andWhere('tbl_appointment.type =' . $params['type_appointment']);
        }
        ///////
        if ( isset($params['unitGenerate']) && (!empty($params['unitGenerate'])))
        {


            $query->andWhere('tbl_user.unit_generate = ' . $params['unitGenerate']) ;

        }
        // Date
        if ($params['today']) {
            $query->andWhere("tbl_appointment.date = '$current_date' and tbl_appointment.status = 0 ");
        } else {
            if ($params['type_period_check'] != null)
            {
                if ($params['type_period_check'] == 1)
                {
                    $start_create = date_create_from_format('d/m/Y', $params['start'] )->format('Y-m-d ');
                    $end_create = date_create_from_format('d/m/Y', $params['end'])->format('Y-m-d ');
                    $query->andWhere('tbl_appointment.added_at >= ' . strtotime($start_create . '00:00') . ' AND tbl_appointment.added_at <= ' . strtotime($end_create . '23:59') . '');
                }
                else{
                    $query->andWhere('tbl_appointment.date >= \'' . $start . '\' AND tbl_appointment.date <= \'' . $end . '\'');
                }
            }
            else{
                $query->andWhere('tbl_appointment.date >= \'' . $start . '\' AND tbl_appointment.date <= \'' . $end . '\'');
            }

        }
        // Status
        if (isset($params['status_appointment']) && $params['status_appointment'] != '') {
            $query->andWhere("tbl_appointment.status=".$params['status_appointment']);
        }
        // Only my appointments
        if (Yii::$app->user->can('Notify.MyAppointments')) {
            $query->andWhere('tbl_lead.lead_owner_id = ' . Yii::$app->user->id);
            // Only office appointments
        } else if ( ! Yii::$app->user->can('Office.NoLimit')) {
            $query->andWhere('tbl_lead.office_id = ' .  Yii::$app->user->identity->office_id);
        }

        // Customer service dates
        $sql = "SELECT auth_assignment.user_id FROM auth_assignment WHERE auth_assignment.item_name = 'Customer.Service' OR auth_assignment.item_name = 'Customer.Director'";
        $connection = \Yii::$app->db;
        $command=$connection->createCommand($sql);
        $dataReader = $command->queryAll();
        $users = [];
        foreach ($dataReader as $row) {
            $users[] = (int) $row['user_id'];
        }
        // Customer service appointments
//        $condition = $customer == true ? 'IN' : 'NOT IN';
//        $query->andWhere([$condition, 'tbl_appointment.user_id', $users]);

        // Order
        $query->orderBy('tbl_appointment.entity_id');
        //
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query
            ]
        );
        $query->andFilterWhere([
            'tbl_lead.id' => $this->id,
            'tbl_lead.office_id' => $this->office_id,
            'tbl_lead.lead_type_id' => $this->lead_type_id,
            'tbl_lead.lead_owner_id' => $this->lead_owner_id,
            'tbl_lead.lead_status_id' => $this->lead_status_id,
            'tbl_lead.lead_source_id' => $this->lead_source_id,
            'tbl_lead.opportunity_amount' => $this->opportunity_amount,
            'tbl_lead.added_at' => $this->added_at,
            'tbl_lead.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'tbl_lead.lead_name', $this->lead_name])
            ->andFilterWhere(['like', 'tbl_lead.lead_description', $this->lead_description])
            ->andFilterWhere(['like', 'tbl_lead.lead_status_description', $this->lead_status_description])
            ->andFilterWhere(['like', 'tbl_lead.lead_source_description', $this->lead_source_description])
            ->andFilterWhere(['like', 'tbl_lead.email', $this->email])
            ->andFilterWhere(['like', 'tbl_lead.first_name', $this->first_name])
            ->andFilterWhere(['like', 'tbl_lead.last_name', $this->last_name])
            ->andFilterWhere(['like', 'tbl_lead.phone', $this->phone])
            ->andFilterWhere(['like', 'tbl_lead.mobile', $this->mobile]);

        return $dataProvider;
    }

    public function searchsss($params, $customer = false, &$promises = [])
    {
        $current_date = date("Y-m-d");

        //$office = CommonModel::getOfficeSql((Yii::$app->user->can('Office.NoLimit') ? null : Yii::$app->user->identity->office_id), 'tbl_lead');
		$query = LeadModel::find();

		//$converted = (int) \livefactory\models\LeadStatus::_CONVERTED;
        // Validation
        $query->join('LEFT JOIN', 'tbl_appointment', 'tbl_lead.id = tbl_appointment.entity_id');
        //$query->where("tbl_appointment.status != 1");
        //$query->groupBy('tbl_appointment.entity_id');

        // Filter by period
        $request = Yii::$app->request->getQueryParams();
        if (!empty($request['start_date']) && !empty($request['end_date'])) {

            $start_date = $request['start_date'];
            $end_date = $request['end_date'];

            $start_time = empty($request['start_time']) ? '00:00' : $request['start_time'];
            $end_time = empty($request['end_time']) ? '00:00' : $request['end_time'];

            //$start = strtotime($start_date . ' ' . $start_time);
            //$end = strtotime($end_date . ' ' . $end_time);

            $query->andWhere('tbl_appointment.date >= \'' . $start_date . '\' AND tbl_appointment.date <= \'' . $end_date . '\'');
            //var_dump('STR_TO_DATE(tbl_appointment.date, \'%Y-%m-%d\') >= \'' . $start_date . '\' AND STR_TO_DATE(tbl_appointment.date, \'%Y-%m-%d\') <= \'' . $end_date . '\'');exit;
        } else if ($request['today']) {
            $query->andWhere("tbl_appointment.date = '$current_date' and tbl_appointment.status = 0 ");
        } else {
            $query->andWhere("tbl_appointment.date >= '$current_date'");
        }

        //
        if (isset($request['status_appointment']) && $request['status_appointment'] != '') {
            $query->andWhere("tbl_appointment.status=".$request['status_appointment']);
        }

        // Only my appointments
        if (Yii::$app->user->can('Notify.MyAppointments')) {
            $query->andWhere('tbl_lead.lead_owner_id = ' . Yii::$app->user->id);
            // Only office appointments
        } else if ( ! Yii::$app->user->can('Office.NoLimit')) {
            $query->andWhere('tbl_lead.office_id = ' .  Yii::$app->user->identity->office_id);
        }

        // Customer service dates
        $sql = "SELECT auth_assignment.user_id FROM auth_assignment WHERE auth_assignment.item_name = 'Customer.Service' OR auth_assignment.item_name = 'Customer.Director'";
        $connection = \Yii::$app->db;
        $command=$connection->createCommand($sql);
        $dataReader = $command->queryAll();
        $users = [];
        foreach ($dataReader as $row) {
            $users[] = (int) $row['user_id'];
        }

        // Customer service appointments
        $condition = $customer == true ? 'IN' : 'NOT IN';
        $query->andWhere([$condition, 'tbl_appointment.user_id', $users]);

        //
        //$promises = [];
        //var_dump($leads,$promises);exit;
        // Order
        $query->orderBy('tbl_appointment.date DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!($this->load($params) && $this->validate())) {
            $leads = $query->all();
                foreach ($leads as $row)
                {
                    $appointment = \livefactory\models\Appointment::find()->where('entity_id = ' . $row->id )->orderBy('id DESC')->one();
                    $promises[$row->id] = $appointment;
                    //var_dump($row);
                }


            return $dataProvider;
        }

        $query->andFilterWhere([
            'tbl_lead.id' => $this->id,
            'tbl_lead.office_id' => $this->office_id,
            'tbl_lead.lead_type_id' => $this->lead_type_id,
            'tbl_lead.lead_owner_id' => $this->lead_owner_id,
            'tbl_lead.lead_status_id' => $this->lead_status_id,
            'tbl_lead.lead_source_id' => $this->lead_source_id,
            'tbl_lead.opportunity_amount' => $this->opportunity_amount,
            'tbl_lead.added_at' => $this->added_at,
            'tbl_lead.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'tbl_lead.lead_name', $this->lead_name])
            ->andFilterWhere(['like', 'tbl_lead.lead_description', $this->lead_description])
            ->andFilterWhere(['like', 'tbl_lead.lead_status_description', $this->lead_status_description])
            ->andFilterWhere(['like', 'tbl_lead.lead_source_description', $this->lead_source_description])
            ->andFilterWhere(['like', 'tbl_lead.email', $this->email])
            ->andFilterWhere(['like', 'tbl_lead.first_name', $this->first_name])
            ->andFilterWhere(['like', 'tbl_lead.last_name', $this->last_name])
            ->andFilterWhere(['like', 'tbl_lead.phone', $this->phone])
            ->andFilterWhere(['like', 'tbl_lead.mobile', $this->mobile]);

        $leads = $query->all();
        foreach ($leads as $row)
        {
            $appointment = \livefactory\models\Appointment::find()->where('entity_id = ' . $row->id )->orderBy('id DESC')->one();
            $promises[$row->id] = $appointment;
            //var_dump($row);
        }
        return $dataProvider;
    }
}

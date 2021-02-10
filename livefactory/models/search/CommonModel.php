<?php

namespace livefactory\models\search;
use Yii;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\ConfigItem as configItemModel;
use livefactory\models\File as FileModel;
use livefactory\models\Note as NoteModel;
use livefactory\models\History as HistoryModel;
use livefactory\models\Address as AddressModel;
use livefactory\models\Contact as ContactModel;
use livefactory\models\Appointment as AppointmentModel;
use livefactory\models\Payment;
use livefactory\models\Task as TaskModel;
use livefactory\models\Lead as LeadModel;
//use livefactory\models\LeadStatus;
//use livefactory\models\DefectStatus;
//use livefactory\models\TaskStatus;
//use livefactory\models\TicketStatus;
//use livefactory\models\ProjectStatus;
//use livefactory\models\InvoiceStatus;
//use livefactory\models\EstimateStatus;
use livefactory\models\Defect as DefectModel;
use livefactory\models\Invoice as InvoiceModel;
use livefactory\models\Ticket as TicketModel;
use livefactory\models\User as UserCommonModel;
use livefactory\models\Project as ProjectModel;
use livefactory\models\Customer as CustomerModel;
use livefactory\models\Product as ProductModel;
use livefactory\models\ProjectUser;
use livefactory\models\TimeEntry as TimeEntryModel;
use yii\helpers\ArrayHelper;


use livefactory\models\AssignmentHistory as AssignmentHistoryModel;

/**
 * Task represents the model behind the search form about `livefactory\models\Task`.
 */
class CommonModel extends \yii\db\ActiveRecord
{
	/**
     * @inheritdoc
     */
    /*public static function tableName()
    {
        return '';
    }*/


	public static  function searchAttachments($params, $entity_id,$entity_type)
	{
		$query = FileModel::find ()->where ( [
				'entity_type' => $entity_type,
				'entity_id' => $entity_id
		] );

		$dataProvider = new ActiveDataProvider ( [
				'query' => $query
		] );

		/*if (! ($this->load ( $params ) && $this->validate ()))
		{
			return $dataProvider;
		}*/

		return $dataProvider;
	}

	public static  function searchNotes($params, $entity_id,$entity_type)
	{
		$query = NoteModel::find ()->where ( [
				'entity_type' => $entity_type,
				'entity_id' => $entity_id
		] )->orderBy("id desc")->all();

		$dataProvider = new ActiveDataProvider ( [
				'query' => $query
		] );

		/*if (! ($this->load ( $params ) && $this->validate ()))
		{
			return $dataProvider;
		}*/

		return $query;
	}
	public static  function searchHistory($params, $entity_id,$entity_type)
	{
		$query = HistoryModel::find ()->where ( [
				'entity_type' => $entity_type,
				'entity_id' => $entity_id
		] )->orderBy('id desc');

		$dataProvider = new ActiveDataProvider ( [
				'query' => $query
		] );

		/*if (! ($this->load ( $params ) && $this->validate ()))
		{
			return $dataProvider;
		}*/

		return $dataProvider;
	}
	public static  function searchAssignedHistory($params, $entity_id,$entity_type)
	{
		$query = AssignmentHistoryModel::find ()->where ( [
				'entity_type' => $entity_type,
				'entity_id' => $entity_id
		] )->orderBy('id desc');

		$dataProvider = new ActiveDataProvider ( [
				'query' => $query
		] );

		/*if (! ($this->load ( $params ) && $this->validate ()))
		{
			return $dataProvider;
		}*/

		return $dataProvider;
	}
	public static  function searchActivity($params, $entity_id,$entity_type)
	{

		$query = HistoryModel::find()->where("entity_id=$entity_id and entity_type='$entity_type' and notes  like '%Update%'");

							/*'LIKE', 'notes', 'Update',
							'entity_type'=>'project',
							'entity_id'=>$entity_id]);*/

		$dataProvider = new ActiveDataProvider ( [
				'query' => $query
		] );

		/*if (! ($this->load ( $params ) && $this->validate ()))
		{
			return $dataProvider;
		}
		var_dump($dataProvider);*/
		return $dataProvider;
	}
	public static  function searchAddresses($params, $entity_id,$entity_type)
	{

		$query = AddressModel::find()->where("entity_id=$entity_id and entity_type='$entity_type'");

							/*'LIKE', 'notes', 'Update',
							'entity_type'=>'project',
							'entity_id'=>$entity_id]);*/

		$dataProvider = new ActiveDataProvider ( [
				'query' => $query
		] );

		/*if (! ($this->load ( $params ) && $this->validate ()))
		{
			return $dataProvider;
		}*/
		return $dataProvider;
	}

	public static function getAddress($entity_id, $entity_type) {
	    $address = AddressModel::findOne(['entity_id' => $entity_id, 'entity_type' => $entity_type]);

	    return $address ? $address : new Address();
    }

    public static function getContact($entity_id, $entity_type) {
	    $contact = ContactModel::findOne(['entity_id' => $entity_id, 'entity_type' => $entity_type]);

	    return $contact ? $contact : new Contact();
    }

	public static  function searchContacts($params, $entity_id,$entity_type)
	{

		$query = Contact::find()->where("entity_id=$entity_id and entity_type='$entity_type'");

							/*'LIKE', 'notes', 'Update',
							'entity_type'=>'project',
							'entity_id'=>$entity_id]);*/

		$dataProvider = new ActiveDataProvider ( [
				'query' => $query
		] );

		/*if (! ($this->load ( $params ) && $this->validate ()))
		{
			return $dataProvider;
		}*/
		return $dataProvider;
	}
    public static  function searchAppointments($params, $entity_id,$entity_type)
    {

        $query = AppointmentModel::find()->where("entity_id=$entity_id and entity_type='$entity_type'");
        /*'LIKE', 'notes', 'Update',
        'entity_type'=>'project',
        'entity_id'=>$entity_id]);*/

        $dataProvider = new ActiveDataProvider ( [
            'query' => $query
        ] );
        /*if (! ($this->load ( $params ) && $this->validate ()))
        {
            return $dataProvider;
        }*/
        return $dataProvider;
    }

    public static  function searchPayment($params, $entity_id,$entity_type)
    {

        $query = Payment::find()->where("entity_id=$entity_id and entity_type='$entity_type' ");

        /*'LIKE', 'notes', 'Update',
        'entity_type'=>'project',
        'entity_id'=>$entity_id]);*/

        $dataProvider = new ActiveDataProvider ( [
            'query' => $query
        ] );

        /*if (! ($this->load ( $params ) && $this->validate ()))
        {
            return $dataProvider;
        }*/
        return $dataProvider;
    }

    /////
    public static  function searchPaymentInsurance($params, $entity_id,$entity_type)
    {

        $query = Payment::find()->where("entity_id=$entity_id and entity_type='$entity_type' and type = 5");

        /*'LIKE', 'notes', 'Update',
        'entity_type'=>'project',
        'entity_id'=>$entity_id]);*/

        $dataProvider = new ActiveDataProvider ( [
            'query' => $query
        ] );

        /*if (! ($this->load ( $params ) && $this->validate ()))
        {
            return $dataProvider;
        }*/
        return $dataProvider;
    }

	public function searchTimeEntry($params, $entity_id,$entity_type)
	{


		if(!empty($_GET['approved'])){
			$query = TimeEntryModel::find()->where (['entity_id' => $entity_id,'entity_type'=>$entity_type,'approved'=>'1'] )->orderBy('end_time DESC');
		}else if(!empty($_GET['pending'])){
			$query = TimeEntryModel::find()->where (['entity_id' => $entity_id,'entity_type'=>$entity_type,'approved'=>'0'] )->orderBy('end_time DESC');
		}else if(!empty($_GET['rejected'])){
			$query = TimeEntryModel::find()->where (['entity_id' => $entity_id,'entity_type'=>$entity_type,'approved'=>'-1'] )->orderBy('end_time DESC');
		}else{
			$query = TimeEntryModel::find()->where ( [
					'entity_id' => $entity_id,
					'entity_type'=>$entity_type
			] )->orderBy('end_time DESC');
		}
		$dataProvider = new ActiveDataProvider ( [
				'query' => $query
		] );

		if (! ($this->load ( $params ) && $this->validate ()))
		{
			return $dataProvider;
		}

		return $dataProvider;
	}
	public  static function getAllProject(){
		if(Yii::$app->params['user_role'] !='admin'){
			return ProjectModel::find()->where("EXISTS(Select *
FROM tbl_project_user  WHERE project_id =tbl_project.id and user_id=".Yii::$app->user->identity->id.")")->asArray()->all();
		}else{
		return ProjectModel::find()->asArray()->all();
		}
	}
	public static  function getTimeLine(){
		if(Yii::$app->params['user_role'] !='admin'){
			$obj = ProjectModel::find()->where("EXISTS(Select *
FROM tbl_project_user  WHERE project_id =tbl_project.id and user_id=".Yii::$app->user->identity->id.")")->asArray()->one();
		}else{
			$obj = ProjectModel::find()->asArray()->one();
		}
		$entity_id=isset($_GET['entity_id'])?$_GET['entity_id']:$obj['id'];

		$sql = "SELECT * FROM tbl_project,tbl_history where tbl_project.id=tbl_history.entity_id and entity_type='project' and entity_id='$entity_id' order by tbl_history.id desc limit 5";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$row=$command->queryAll();
		return $row;
	}
	public static  function getOpenedTask($date){
		$query = TaskModel::find()->where("from_unixtime(expected_start_datetime, '%Y-%m-%d') ='$date'")->count();
		return $query;
	}
	public static  function getClosedTask($date){
		$query = TaskModel::find()->where("task_status_id='".TaskStatus::_COMPLETED."' and from_unixtime(actual_end_datetime, '%Y-%m-%d') ='$date'")->count();
		return $query;
	}
	public static  function getTotalClosedTask(){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$thisMonthFirstDate = strtotime('first day of this month');
		$thisMonthLastDate = strtotime('last day of this month');
		$query = TaskModel::find()->where("task_status_id='".TaskStatus::_COMPLETED."' and (actual_end_datetime) >= '$thisMonthFirstDate'  and (actual_end_datetime)<='$thisMonthLastDate'")->count();
		return $query;
	}
	public static  function getTotalOpenTask(){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$thisMonthFirstDate = strtotime('first day of this month');
		$thisMonthLastDate = strtotime('last day of this month');
		//echo $thisMonthFirstDate."<br/>".$thisMonthLastDate;
//echo "date(expected_end_datetime) >='$thisMonthFirstDate' and date(expected_end_datetime) <='$thisMonthLastDate'";
		$query = TaskModel::find()->where("(expected_end_datetime) >='$thisMonthFirstDate' and (expected_end_datetime) <='$thisMonthLastDate'")->count();
		return $query;
	}

	public static function getStatsLink($start, $end, $office_id = null, $agent_id = null, $mean_id = null, $type = null, $type_id = null)
    {
        //$url = 'index.php?r=sales/lead/' . ($type == 'charged' ? 'payments' : 'list');
        //all leads
        if($type=='leads')
        {
            if($office_id and $agent_id and $mean_id)
            {
                $url='index.php?';
                $url .= '&r=sales/lead';
                $url .= '&start_date=' . date('Y-m-d', strtotime($start)) . '&end_date=' .date('Y-m-d', strtotime($end));
            }
            else{
                $url = 'index.php?';
                $url .='Lead[office_id]='.$office_id;
                $url .='&Lead[lead_owner_id]='.$agent_id;
                $url .='&Lead[lead_source_id]='.$mean_id;
                $url .= '&r=sales/lead';
                $url .= '&start_date=' . date('Y-m-d', strtotime($start)) . '&end_date=' .date('Y-m-d', strtotime($end));
            }


        }
        else
            if($type == 'charged')
            {
                $url ='index.php?';
                $url .='&r=sales/lead/payments';
                $url .= '&start=' . date('d/m/Y', strtotime($start)) . '&end=' .date('d/m/Y', strtotime($end));
                $url .= '&office_id=' . $office_id . '&agent_id='.$agent_id . '&mean_id=' . $mean_id;
            }
            else
                {
                    //leads in appointments , ups , vent
                    $url = 'index.php?' ;//. ($type == 'charged' ? 'list' : 'list');
                    $url .= 'r=sales/lead/list';
                    $url .= '&start=' . date('d/m/Y', strtotime($start)) . '&end=' .date('d/m/Y', strtotime($end));
                    $url .= '&office_id=' . $office_id . '&agent_id='.$agent_id . '&mean_id=' . $mean_id;
                    $url .= '&type=' . $type;
        }

        if ($type_id !== null) {
            $url .= '&type_id=' . $type_id;
        }
        if ($type == 'validate')
        {
            $url = 'index.php?';
            $url .= 'r=sales/lead/validate';
            $url .= '&start=' . date('d/m/Y' , strtotime($start)) . '&end=' . date('d/m/Y' , strtotime($end)) . '&office_id=' . $office_id . '&agent_id=' . $agent_id;
        }
        return $url;
    }

    /**
     * Get office sql
     *
     * @param string $office_id
     * @param string $as Table alias
     * @return string
     */
	public static function getOfficeSql($office_id = null, $as = null)
    {
        $sql = '';
        $as = $as == null ? '' : $as.'.';

        // Not SQL
        if (Yii::$app->user->can('Office.NoLimit') && empty($office_id))
            return $sql;

        //
        if (Yii::$app->user->can('Office.NoLimit')) {
            $sql = ' and '.$as.'office_id = ' . $office_id;
        } elseif ( ! Yii::$app->user->can('Office.NoLimit')) {
            $sql = ' and '.$as.'office_id = ' . Yii::$app->user->identity->office_id;
        }

        return $sql;
    }

    /**
     * @param null $user_id
     * @param null $as
     * @return string
     */
    public static function getAgentSql($user_id = null, $as = null)
    {
        $sql = '';
        $as = $as == null ? '' : $as.'.';

        if (empty($user_id))
            return $sql;

        if ($user_id == Yii::$app->user->id || Yii::$app->user->can('Reports.ByUser')) {
            $sql = ' and '.$as.'lead_owner_id = ' . $user_id;
        }

        return $sql;
    }

    /**
     * @param $mean_id
     * @param null $as
     * @param bool $force
     * @return string
     */
    public static function getMeanSql($mean_id, $as = null, $force = false)
    {
        $sql = '';
        $as = $as == null ? '' : $as.'.';

        if (empty($mean_id))
            return $sql;

        if (Yii::$app->user->can('Reports.ByMeans') || $force === true) {
            $sql = ' and '.$as.'lead_source_id = ' . $mean_id;
        }

        return $sql;
    }

    // TODO: Filter by entity_type if we use payments or appointments to other entities
	public static  function getAllLeads($start, $end, $office_id = null, $agent_id = null, $mean_id = null){
        $office = CommonModel::getOfficeSql($office_id);
        $agent = CommonModel::getAgentSql($agent_id);
        $mean = CommonModel::getMeanSql($mean_id);
        $start = strtotime($start. ' 00:00:00');
        $end = strtotime($end . ' 23:59:59');
		$query = LeadModel::find()->where("added_at >='$start' and added_at <='$end'".$office.$agent.$mean." AND active=1 AND (valid_sales = 0 OR valid_manager = 0 OR valid_admin = 0)");
		return $query->count();
	}

	public static function getAllAppointments($start, $end, $office_id = null, $agent_id = null, $mean_id = null)
    {
        $office = CommonModel::getOfficeSql($office_id, 'tbl_lead');
        $agent = CommonModel::getAgentSql($agent_id, 'tbl_lead');
        $mean = CommonModel::getMeanSql($mean_id);
        $query = (new Query())->select('c_control')->from('tbl_lead');
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
        return $query->count();
    }

    public static function getReAppointments($start, $end, $office_id = null, $agent_id = null, $mean_id = null)
    {
        $office = CommonModel::getOfficeSql($office_id, 'tbl_lead');
        $agent = CommonModel::getAgentSql($agent_id, 'tbl_lead');
        $mean = CommonModel::getMeanSql($mean_id);
        $query = (new Query())->select('c_control')->from('tbl_lead');
        $query->join('LEFT JOIN', 'tbl_appointment', 'tbl_lead.id = tbl_appointment.entity_id');
        $query->where("DATE_FORMAT(FROM_UNIXTIME(tbl_appointment.added_at), '%Y-%m-%d') >='$start' and DATE_FORMAT(FROM_UNIXTIME(tbl_appointment.added_at), '%Y-%m-%d') <='$end'".$office.$agent.$mean);
        $query->groupBy('tbl_appointment.entity_id');
        $query->having('count(tbl_appointment.id) > 1');

        return $query->count();
    }

    public static function getAllIncrements($start, $end, $office_id = null, $agent_id = null)
    {
        $payment_type = Payment::_INCREASE;
        $mean_id = null;
        $office = CommonModel::getOfficeSql($office_id, 'tbl_lead');
        $agent = CommonModel::getAgentSql($agent_id, 'tbl_lead');
        $mean = CommonModel::getMeanSql($mean_id, 'tbl_lead');
        $query = (new Query())->from('tbl_lead');
        $query->join('LEFT JOIN', 'tbl_payment', 'tbl_lead.id = tbl_payment.entity_id');
        if ($agent_id == null) {
            $query->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and tbl_payment.type = ".$payment_type.$office.$agent.$mean);
            $sum = $query->count();
        } else {
            //$query2 = clone $query;
            $query3 = clone $query;

            // Propetary
            $query->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and (co_generator_id IS NULL or co_generator_id = 0) and tbl_payment.type = ".$payment_type.$office.$agent.$mean);
            $sum1 = $query->count();

            // With colaborator
            /*$agent = ' and tbl_payment.generator_id = ' . $agent_id;
            $query2->where("STR_TO_DATE(tbl_payment.date, '%d-%m-%Y') >='$start' and STR_TO_DATE(tbl_payment.date, '%d-%m-%Y') <='$end' and (co_generator_id IS NOT NULL and co_generator_id > 0) and tbl_payment.type = ".$payment_type.$office.$agent.$mean);
            $sum2 = $query2->count();*/

            // As colaborator
            $agent = ' and tbl_payment.co_generator_id = ' . $agent_id;
            $query3->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and tbl_payment.type = ".$payment_type.$office.$agent.$mean);
            $sum3 = $query3->count();

            //$sum = $sum1 + ($sum2 / 2) + ($sum3 / 2);
            $sum = $sum1 + $sum3;
        }

        return $sum;
    }

	public static  function getLeadsInflow(){
		$query = LeadModel::find();
        if (! Yii::$app->user->can('Office.NoLimit')) {
            $query->andWhere('office_id = ' . Yii::$app->user->identity->office_id);
        }
		return $query->count();
	}

	public static  function getLeadsConverted(){
		$query = LeadModel::find()->where('lead_status_id='.LeadStatus::_CONVERTED);
        if (! Yii::$app->user->can('Office.NoLimit')) {
            $query->andWhere('office_id = ' . Yii::$app->user->identity->office_id);
        }
		return $query->count();
	}

	public static  function getConvertedLeads(){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
        $thisMonthFirstDate = strtotime('monday this week');
        $thisMonthLastDate = strtotime('sunday this week');
		$query = LeadModel::find()->where("(added_at) >='$thisMonthFirstDate' and (added_at) <='$thisMonthLastDate' and lead_status_id=".LeadStatus::_CONVERTED)->count();
		return $query;
	}

	public static  function getAllInteractions(){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
        $thisMonthFirstDate = strtotime('monday this week');
        $thisMonthLastDate = strtotime('sunday this week');
        if (! Yii::$app->user->can('Office.NoLimit')) {
            $office = ' and office_id = ' . Yii::$app->user->identity->office_id;
        }
		$query = LeadModel::find()->where("(added_at) >='$thisMonthFirstDate' and (added_at) <='$thisMonthLastDate' and lead_status_id = ".LeadStatus::_INPROCESS);
		return $query->count();
	}

	public static  function getAllOpportunities($start, $end, $office_id = null, $agent_id = null, $mean_id = null, $force = false){
        $office = CommonModel::getOfficeSql($office_id, 'tbl_lead');
        $agent = CommonModel::getAgentSql($agent_id, 'tbl_lead');
        $mean = CommonModel::getMeanSql($mean_id, 'tbl_lead', $force);
//        var_dump($start);
        // Appointment status = concreted from lead ups_date
        $query = (new Query())->select('tbl_lead.id, tbl_lead.ups_date')->from('tbl_lead')
        ->where('tbl_lead.ups_type = 0 and  tbl_lead.ups_date >= "' . $start . '" and tbl_lead.ups_date <= "' . $end . '"' .$office.$agent.$mean);
//        var_dump($query->all());
        $concreted = $query->count();
//        // Appointment status = concreted
//        $query = (new Query())->select('tbl_lead.id')->from('tbl_lead');
//        $query->join('LEFT JOIN', 'tbl_appointment', 'tbl_lead.id = tbl_appointment.entity_id');
//        $query->where("DATE_FORMAT(FROM_UNIXTIME(tbl_appointment.updated_at), '%Y-%m-%d') >='$start' and DATE_FORMAT(FROM_UNIXTIME(tbl_appointment.updated_at), '%Y-%m-%d') <='$end' AND tbl_appointment.status=1".$office.$agent.$mean);
//        $query->groupBy('tbl_appointment.entity_id');
//        $query->orderBy('tbl_appointment.updated_at');
//        $concreted = $query->count();
        $result = $query->all();
        $leads = [];
        foreach ($result as $lead)
            $leads[] = $lead['id'];

        $office = CommonModel::getOfficeSql($office_id);
        $agent = CommonModel::getAgentSql($agent_id);
        $mean = CommonModel::getMeanSql($mean_id);

        $query = (new Query())->from('tbl_lead');
        $query->where("DATE_FORMAT(FROM_UNIXTIME(added_at), '%Y-%m-%d') = DATE_FORMAT(FROM_UNIXTIME(converted_at), '%Y-%m-%d')");
        $query->andWhere("DATE_FORMAT(FROM_UNIXTIME(added_at), '%Y-%m-%d') >='$start' and DATE_FORMAT(FROM_UNIXTIME(added_at), '%Y-%m-%d') <='$end'".$office.$agent.$mean);

        //
        if (!empty($leads))
            $query->andWhere('id NOT IN ('.implode(',', $leads).')');

        $nodates = $query->count();

        return $concreted + $nodates;
	}

	public static  function getAllConverted($start, $end, $office_id = null, $agent_id = null, $mean_id = null){
        $office = CommonModel::getOfficeSql($office_id);
        $agent = CommonModel::getAgentSql($agent_id);
        $mean = CommonModel::getMeanSql($mean_id);
        $query = LeadModel::find()->where("DATE_FORMAT(FROM_UNIXTIME(converted_at), '%Y-%m-%d') >='$start' and DATE_FORMAT(FROM_UNIXTIME(converted_at), '%Y-%m-%d') <='$end' and lead_status_id = " . LeadStatus::_CONVERTED.$office.$agent.$mean);
        return $query->count();
	}

	public static function getAmount($start, $end, $office_id = null, $agent_id = null, $mean_id = null)
    {
        $office = CommonModel::getOfficeSql($office_id, 'tbl_lead');
        $agent = CommonModel::getAgentSql($agent_id, 'tbl_lead');
        $mean = CommonModel::getMeanSql($mean_id, 'tbl_lead');
        $query = (new Query())->from('tbl_lead');
        $query->join('LEFT JOIN', 'tbl_payment', 'tbl_lead.id = tbl_payment.entity_id');
        $query->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and tbl_payment.type = ".Payment::_NEW_CONTRACT.$office.$agent.$mean);
        $query->andWhere('tbl_payment.type != 5')->orderBy('tbl_payment.entity_id')->groupBy('tbl_lead.id');

        $sum = 0;
        foreach ($query->all() as $lead)
        {
                $sum = $sum + $lead['loan_amount'];
        }
        //$sum = $query->sum('tbl_lead.loan_amount');
        return $sum;
    }

    public static function getCommission($start, $end, $office_id = null, $agent_id = null, $mean_id = null)
    {
        $office = CommonModel::getOfficeSql($office_id);
        $agent = CommonModel::getAgentSql($agent_id);
        $mean = CommonModel::getMeanSql($mean_id);
        $query = LeadModel::find();
        $query->where("DATE_FORMAT(FROM_UNIXTIME(converted_at), '%Y-%m-%d') >='$start' and DATE_FORMAT(FROM_UNIXTIME(converted_at), '%Y-%m-%d') <='$end' and lead_status_id = " . LeadStatus::_CONVERTED.$office.$agent.$mean);
        $sum = $query->sum('loan_commission');

        return $sum;
    }

    /**
     * @param $start
     * @param $end
     * @param null $office_id
     * @param null $agent_id
     * @param null $mean_id
     * @return int
     * @throws \yii\db\Exception
     */
    public static function getAddemdums($start, $end, $office_id = null, $agent_id = null, $mean_id = null)
    {
        $office = CommonModel::getOfficeSql($office_id);
        $agent = CommonModel::getAgentSql($agent_id);
        $mean = CommonModel::getMeanSql($mean_id);

        $sql = "
SELECT 
    id,
    c_control,
    loan_commission AS charge,
    IFNULL(SUM(payments.payment), 0) AS payment,
    loan_commission - IFNULL(SUM(payments.payment), 0) AS balance,
    DATE_FORMAT(FROM_UNIXTIME(converted_at), '%Y-%m-%d') as converted
FROM
    tbl_lead
        LEFT OUTER JOIN
    (SELECT 
        entity_id, SUM(amount) AS payment
    FROM
        tbl_payment
    GROUP BY entity_id) AS payments ON id = entity_id
WHERE
    DATE_FORMAT(FROM_UNIXTIME(converted_at), '%Y-%m-%d') >='$start' and DATE_FORMAT(FROM_UNIXTIME(converted_at), '%Y-%m-%d') <='$end' and lead_status_id = " . LeadStatus::_CONVERTED.$office.$agent.$mean;
$sql .= " GROUP BY id";
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();

        $sum = 0;
        foreach ($result as $row) {
            $sum += $row['balance'];
        }

        return $sum;
    }

    /**
     * @param null $office_id
     * @param null $agent_id
     * @param null $mean_id
     * @return int
     * @throws \yii\db\Exception
     */
    public static function getTotalAddemdums($office_id = null, $agent_id = null, $mean_id = null)
    {
        $office = CommonModel::getOfficeSql($office_id);
        $agent = CommonModel::getAgentSql($agent_id);
        $mean = CommonModel::getMeanSql($mean_id);
        $today = date('Y-m-d');
        $sql = "
SELECT 
    id,
    c_control,
    loan_commission AS charge,
    IFNULL(SUM(payments.payment), 0) AS payment,
    loan_commission - IFNULL(SUM(payments.payment), 0) AS balance,
    DATE_FORMAT(FROM_UNIXTIME(converted_at), '%Y-%m-%d') as converted
FROM
    tbl_lead
        LEFT OUTER JOIN
    (SELECT 
        entity_id, SUM(amount) AS payment
    FROM
        tbl_payment
    GROUP BY entity_id) AS payments ON id = entity_id
WHERE
    DATE_FORMAT(FROM_UNIXTIME(converted_at), '%Y-%m-%d') <='$today' and lead_status_id = " . LeadStatus::_CONVERTED.$office.$agent.$mean;
        $sql .= " GROUP BY id";
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();

        $sum = 0;
        foreach ($result as $row) {
            $sum += $row['balance'];
        }

        return $sum;
    }

    public static function getQuality($start, $end, $office_id = null, $agent_id = null, $mean_id = null) {
        $office = CommonModel::getOfficeSql($office_id, 'tbl_lead');
        $agent = CommonModel::getAgentSql($agent_id, 'tbl_lead');
        $mean = CommonModel::getMeanSql($mean_id, 'tbl_lead');
        $start = strtotime($start. ' 00:00:00');
        $end = strtotime($end . ' 23:59:59');

        // Query
        $query = (new Query())->from('tbl_lead')->where("added_at >='$start' and added_at <='$end'".$office.$agent.$mean." AND active=1 AND (valid_sales = 0 OR valid_manager = 0 OR valid_admin = 0)");

        $query = $query->all();
        $data = [
            /*0 => [],*/
            1 => [],
            2 => [],
            3 => [],
            4 => [],
            5 => [],
            6 => []
        ] ;

        // Compose by offices
        $office_id = $office_id === null && ! Yii::$app->user->can('Office.NoLimit') ? Yii::$app->user->identity->office_id : $office_id;
        $offices = ArrayHelper::map(Office::find()->where($office_id === null && Yii::$app->user->can('Office.NoLimit') ? "active=1 AND id > 1" : "id = " . $office_id)->orderBy('description')->asArray()->all(), 'id', 'description');
        foreach ($data as $key => $row) {
            foreach ($offices as $office_id => $office) {
                $data[$key][$office_id] = 0;
            }
        }

        //
        foreach ($query as $row) {
            $range = 0; // Less than
            $amount = floatval($row['loan_amount']);

            /*if ($amount < 60000) {
                $range = 0;
            } else*/if ($amount < 60000 || $amount < 120000) {
                $range = 1;
            } elseif ($amount < 600000) {
                $range = 2;
            } elseif ($amount < 1200000) {
                $range = 3;
            } elseif ($amount < 3000000) {
                $range = 4;
            } elseif ($amount < 6000000) {
                $range = 5;
            } else {
                $range = 6;
            }

            $data[$range][$row['office_id']]++;
        }

        return [$data, $offices];
    }

    public static function getTotalSales($start, $end, $office_id = null, $agent_id = null, $mean_id = null)
    {
        $office = CommonModel::getOfficeSql($office_id, 'tbl_lead');
        $agent = CommonModel::getAgentSql($agent_id, 'tbl_lead');
        $mean = CommonModel::getMeanSql($mean_id, 'tbl_lead');

        // Query
        $query = (new Query())->from('tbl_lead');
        $query->join('LEFT JOIN', 'tbl_payment', 'tbl_lead.id = tbl_payment.entity_id');
        $query->where('tbl_payment.type !=5  and tbl_payment.status = 1');
        if ($agent_id == null) {
            $query->andWhere("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end'".$office.$agent.$mean);
            $sum = $query->sum('tbl_payment.amount');
        } else {
            $query2 = clone $query;
            $query3 = clone $query;

            // Propetary
            $agent = ' and tbl_payment.generator_id = ' . $agent_id;
            $query->andWhere("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and (co_generator_id IS NULL or co_generator_id = 0)".$office.$agent.$mean);
            $sum1 = $query->sum('tbl_payment.amount');

            // With colaborator
            $agent = ' and tbl_payment.generator_id = ' . $agent_id;
            $query2->andWhere("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and (co_generator_id IS NOT NULL and co_generator_id > 0)".$office.$agent.$mean);
            $sum2 = $query2->sum('tbl_payment.amount');

            // As colaborator
            $agent = ' and tbl_payment.co_generator_id = ' . $agent_id;
            $query3->andWhere("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end'".$office.$agent.$mean);
            $sum3 = $query3->sum('tbl_payment.amount');

            $sum = $sum1 + ($sum2 / 2) + ($sum3 / 2);
        }
        return $sum;
    }

    public static function getTotalSales1($start, $end, $office_id = null, $agent_id = null, $mean_id = null)
    {
        $office = CommonModel::getOfficeSql($office_id, 'tbl_lead');
        $agent = CommonModel::getAgentSql($agent_id, 'tbl_lead');
        $mean = CommonModel::getMeanSql($mean_id, 'tbl_lead');

        // Query
        $query = (new Query())->from('tbl_lead');
        $query->join('LEFT JOIN', 'tbl_payment', 'tbl_lead.id = tbl_payment.entity_id');
        $query->where('tbl_payment.type !=5  and tbl_payment.status = 0');
        if ($agent_id == null) {
            $query->andWhere("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end'".$office.$agent.$mean);
            $sum = $query->sum('tbl_payment.amount');
        } else {
            $query2 = clone $query;
            $query3 = clone $query;

            // Propetary
            $agent = ' and tbl_payment.generator_id = ' . $agent_id;
            $query->andWhere("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and (co_generator_id IS NULL or co_generator_id = 0)".$office.$agent.$mean);
            $sum1 = $query->sum('tbl_payment.amount');

            // With colaborator
            $agent = ' and tbl_payment.generator_id = ' . $agent_id;
            $query2->andWhere("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and (co_generator_id IS NOT NULL and co_generator_id > 0)".$office.$agent.$mean);
            $sum2 = $query2->sum('tbl_payment.amount');

            // As colaborator
            $agent = ' and tbl_payment.co_generator_id = ' . $agent_id;
            $query3->andWhere("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end'".$office.$agent.$mean);
            $sum3 = $query3->sum('tbl_payment.amount');

            $sum = $sum1 + ($sum2 / 2) + ($sum3 / 2);
        }
        return $sum;
    }
    public static function getTotalSales2($start, $end, $office_id = null, $agent_id = null, $mean_id = null)
    {
        $office = CommonModel::getOfficeSql($office_id, 'tbl_lead');
        $agent = CommonModel::getAgentSql($agent_id, 'tbl_lead');
        $mean = CommonModel::getMeanSql($mean_id, 'tbl_lead');

        // Query
        $query = (new Query())->from('tbl_lead');
        $query->join('LEFT JOIN', 'tbl_payment', 'tbl_lead.id = tbl_payment.entity_id');
        $query->where('tbl_payment.type !=5  and tbl_payment.status = 2');
        if ($agent_id == null) {
            $query->andWhere("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end'".$office.$agent.$mean);
            $sum = $query->sum('tbl_payment.amount');
        } else {
            $query2 = clone $query;
            $query3 = clone $query;

            // Propetary
            $agent = ' and tbl_payment.generator_id = ' . $agent_id;
            $query->andWhere("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and (co_generator_id IS NULL or co_generator_id = 0)".$office.$agent.$mean);
            $sum1 = $query->sum('tbl_payment.amount');

            // With colaborator
            $agent = ' and tbl_payment.generator_id = ' . $agent_id;
            $query2->andWhere("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and (co_generator_id IS NOT NULL and co_generator_id > 0)".$office.$agent.$mean);
            $sum2 = $query2->sum('tbl_payment.amount');

            // As colaborator
            $agent = ' and tbl_payment.co_generator_id = ' . $agent_id;
            $query3->andWhere("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end'".$office.$agent.$mean);
            $sum3 = $query3->sum('tbl_payment.amount');

            $sum = $sum1 + ($sum2 / 2) + ($sum3 / 2);
        }
        return $sum;
    }

    public static function getSales($start, $end, $office_id = null, $agent_id = null, $mean_id = null, $payment_type = Payment::_NEW_CONTRACT)
    {
        $office = CommonModel::getOfficeSql($office_id, 'tbl_lead');
        $agent = CommonModel::getAgentSql($agent_id, 'tbl_lead');
        $mean = CommonModel::getMeanSql($mean_id, 'tbl_lead');
        $query = (new Query())->select('tbl_payment.id, tbl_payment.amount')->from('tbl_lead');
        $query->join('LEFT JOIN', 'tbl_payment', 'tbl_lead.id = tbl_payment.entity_id');
        if ($agent_id == null) {
            $query->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and tbl_payment.type = ".$payment_type.$office.$agent.$mean . ' and tbl_payment.status = 1');
            $sum = $query->sum('tbl_payment.amount');
        } else {
            $query2 = clone $query;
            $query3 = clone $query;

            // Propetary
            $agent = ' and tbl_payment.generator_id = ' . $agent_id;
            $query->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and (co_generator_id IS NULL or co_generator_id = 0) and tbl_payment.type = ".$payment_type.$office.$agent.$mean);
            $sum1 = $query->sum('tbl_payment.amount');

            // With colaborator
            $agent = ' and tbl_payment.generator_id = ' . $agent_id;
            $query2->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and (co_generator_id IS NOT NULL and co_generator_id > 0) and tbl_payment.type = ".$payment_type.$office.$agent.$mean);
            $sum2 = $query2->sum('tbl_payment.amount');

            // As colaborator
            $agent = ' and tbl_payment.co_generator_id = ' . $agent_id;
            $query3->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$start' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$end' and tbl_payment.type = ".$payment_type.$office.$agent.$mean);
            $sum3 = $query3->sum('tbl_payment.amount');

            $sum = $sum1 + ($sum2 / 2) + ($sum3 / 2);
        }

        return $sum;
    }

    /**
     * Colocación
     *
     * @param $start
     * @param $end
     * @param null $office_id
     * @param null $agent_id
     * @param null $mean_id
     * @return float|int
     */
    public static function getPlacement($start, $end, $office_id = null, $agent_id = null, $mean_id = null)
    {
        $office = CommonModel::getOfficeSql($office_id, 'tbl_lead');
        $agent = CommonModel::getAgentSql($agent_id, 'tbl_lead');
        $mean = CommonModel::getMeanSql($mean_id, 'tbl_lead');
        $thisMonthFirstDate = $start;
        $thisMonthLastDate = $end;
        $query = (new Query())->from('tbl_lead');
        $query->join('LEFT JOIN', 'tbl_payment', 'tbl_lead.id = tbl_payment.entity_id');
        $query->where("STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') >='$thisMonthFirstDate' and STR_TO_DATE(tbl_payment.date, '%Y-%m-%d') <='$thisMonthLastDate'".$office.$agent.$mean);
        $query->andWhere('tbl_payment.type != 5');
        $sales = $query->sum('tbl_payment.amount');

        return $sales > 0 ? $sales / 0.07 : 0;
    }

	public static  function getTotalTask(){
		$query = TaskModel::find()->count();
		return $query;
	}
	public static  function getTotalDoneTask(){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$thisMonthFirstDate = strtotime('first day of this month');
		$thisMonthLastDate = strtotime('last day of this month');
		$query = TaskModel::find()->where("task_status_id='".TaskStatus::_COMPLETED."' and (expected_end_datetime) >='$thisMonthFirstDate' and (expected_end_datetime) <='$thisMonthLastDate'")->count();
		return $query;
	}
	public static  function getTotalPenddingTask(){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$thisMonthFirstDate = strtotime('first day of this month');
		$thisMonthLastDate = strtotime('last day of this month');
		$query = TaskModel::find()->where("(task_status_id='".TaskStatus::_NEEDSACTION."' or task_status_id='".TaskStatus::_INPROCESS."') and (expected_end_datetime) >='$thisMonthFirstDate' and (expected_end_datetime) <='$thisMonthLastDate'")->count();
		return $query;
	}
	public static  function getTotalCancelledTask(){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$thisMonthFirstDate = strtotime('first day of this month');
		$thisMonthLastDate = strtotime('last day of this month');
		$query = TaskModel::find()->where("task_status_id=".TaskStatus::_CANCELLED." and (expected_end_datetime) >='$thisMonthFirstDate' and (expected_end_datetime) <='$thisMonthLastDate'")->count();
		return $query;
	}
	public static  function getCustomers(){
		$sql="select UCASE(tbl_country.country_code) country_code,tbl_customer.* from tbl_customer,tbl_country,tbl_address where tbl_country.id=tbl_address.country_id and tbl_address.entity_id=tbl_customer.id and tbl_address.is_primary='1' and tbl_address.entity_type='customer'";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$dataReader=$command->queryAll();
		return $dataReader;
	}
	public static  function getCustomerList(){
		$sql="select tbl_customer.*,tbl_country.country from tbl_customer,tbl_address,tbl_country where tbl_address.entity_id=tbl_customer.id and tbl_address.is_primary='1' and tbl_address.entity_type='customer' and tbl_address.country_id=tbl_country.id order by id DESC";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$dataReader=$command->queryAll();
		return $dataReader;
	}
	public static  function getCustomerCountry($entity_id){
		$sql="select * from tbl_address,tbl_country where entity_type='customer' and entity_id='$entity_id' and tbl_country.id = tbl_address.country_id";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$countries=array();
		foreach($dataReader as $row){
			$countries[]=$row['country'];
		}
		return implode(',',$countries);
	}
	public static  function getOpenedMonthlyTask($year,$month){
		$month = str_pad($month, 2, "0", STR_PAD_LEFT);
		$sql = "SELECT count(id) tot FROM tbl_task where from_unixtime(expected_start_datetime, '%m') ='$month' and from_unixtime(expected_start_datetime, '%Y') ='$year'";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$row=$command->queryOne();
		return $row['tot']?$row['tot']:0;
	}
	public static  function getClosedMonthlyTask($year,$month){
		$month = str_pad($month, 2, "0", STR_PAD_LEFT);
		$sql = "SELECT count(id) tot FROM tbl_task where from_unixtime(actual_end_datetime, '%m') ='$month' and from_unixtime(actual_end_datetime, '%Y') ='$year' and task_status_id='".TaskStatus::_COMPLETED."'";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$row=$command->queryOne();
		return $row['tot']?$row['tot']:0;
	}
	public static  function getAllTaskYears(){

		$sql = "SELECT from_unixtime(expected_start_datetime, '%Y')  year_name FROM tbl_task where from_unixtime(expected_start_datetime, '%Y') not in(0,1970) group by from_unixtime(expected_start_datetime, '%Y')";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$row=$command->queryAll();
		//var_dump($row);
		//array_push($row[0],2010,2011,2012,2013);
		return $row;
	}
	public static  function getClosedYearlyTask($year){
		$sql = "SELECT count(id) tot FROM tbl_task where from_unixtime(actual_end_datetime, '%Y') ='$year' and task_status_id='".TaskStatus::_COMPLETED."'";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$row=$command->queryOne();
		return $row['tot']?$row['tot']:0;
	}
	public static  function getOpenedYearlyTask($year){
		$sql = "SELECT count(id) tot FROM tbl_task where from_unixtime(expected_start_datetime, '%Y') ='$year'";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$row=$command->queryOne();
		return $row['tot']?$row['tot']:0;
	}




	/// -----------------------------------------
    /// Notifications
    /// -----------------------------------------


    public static function getUnassignedLeads()
    {
        // Allow to view notifications?
        if ( ! Yii::$app->user->can('Notify.Leads')) {
            return 0;
        }

        //
        $query = (new Query())->from('tbl_lead');

        $query->where('lead_owner_id = 173 AND active = 1');

        // Filter by logged user office
        if ( ! Yii::$app->user->can('Office.NoLimit')) {
            $query->andWhere('office_id = ' .  Yii::$app->user->identity->office_id);
        }

        // Get total unassigned leads
        $total = $query->count();

        if ($total > 0)
        {
            return $total;
        }

        // Only if more than 10
        return 0;
    }

    /**
     * @return int|string
     */
    public static function getUnassignedLeadsIntmp()
    {
        // Allow to view notifications?
        if ( ! Yii::$app->user->can('Notify.Leads')) {
            return 0;
        }

        //
        $query = (new Query())->from('tbl_lead');

        $query->where('lead_owner_id  = 228 AND active = 1');

        // Filter by logged user office
        if ( ! Yii::$app->user->can('Office.NoLimit')) {
            $query->andWhere('office_id = ' .  Yii::$app->user->identity->office_id);
        }

        // Get total unassigned leads
        $total = $query->count();

        if ($total > 0)
        {
            return $total;
        }

        // Only if more than 10
        return 0;
    }

    public static function getTodayAppointments()
    {
        // Allow to view notifications?
        if ( ! Yii::$app->user->can('Notify.Appointments') && ! Yii::$app->user->can('Notify.MyAppointments')) {
            return 0;
        }

        $current = date('Y-m-d');

        $query = (new Query())->select('c_control')->from('tbl_appointment');
        $query->join('LEFT JOIN', 'tbl_lead', 'tbl_lead.id = tbl_appointment.entity_id');
        $query->where("tbl_appointment.date = '$current' AND tbl_appointment.status = 0");

        // Only my appointments
        if (Yii::$app->user->can('Notify.MyAppointments')) {
            $query->andWhere('tbl_lead.lead_owner_id = ' . Yii::$app->user->id);
        // Only office appointments
        } else if ( ! Yii::$app->user->can('Office.NoLimit')) {
            $query->andWhere('tbl_lead.office_id = ' .  Yii::$app->user->identity->office_id);
        }

        $query->groupBy('tbl_appointment.entity_id');

        // Get total today appointments and passed
        return $query->count();
    }


	public  static function getPendingTaksCount(){
		if(Yii::$app->params['user_role'] !='admin'){
			return TaskModel::find()->joinWith('taskStatus')->joinWith('taskPriority')->orderBy('tbl_task_status.sort_order,tbl_task_priority.sort_order')->where(" EXISTS(Select *
FROM tbl_project_user  WHERE project_id =tbl_task.project_id and user_id=".Yii::$app->user->identity->id.") and (task_status_id='".TaskStatus::_NEEDSACTION."' or task_status_id='".TaskStatus::_INPROCESS."')")->asArray()->all();
		}else{
			return TaskModel::find()->where("task_status_id='".TaskStatus::_NEEDSACTION."' or task_status_id='".TaskStatus::_INPROCESS."'")->asArray()->all();
		}
	}
	public  static function getPendingDefectCount(){
		if(Yii::$app->params['user_role'] !='admin'){
			return DefectModel::find()->where("user_assigned_id=".Yii::$app->user->identity->id."  and (defect_status_id='".DefectStatus::_NEEDSACTION."' or defect_status_id='".DefectStatus::_INPROCESS."')")->asArray()->all();
		}else{
			return DefectModel::find()->where("defect_status_id='".DefectStatus::_NEEDSACTION."' or defect_status_id='".DefectStatus::_INPROCESS."'")->asArray()->all();
		}
	}
	public  static function getPendingTicketCount(){
		if(Yii::$app->params['user_role'] !='admin'){
			return TicketModel::find()->where("user_assigned_id=".Yii::$app->user->identity->id." and (ticket_status_id='".TicketStatus::_NEEDSACTION."' or ticket_status_id='".TicketStatus::_INPROCESS."' or ticket_status_id='".TicketStatus::_REOPENED."')")->asArray()->all();
		}else{
			return TicketModel::find()->where("ticket_status_id='".TicketStatus::_NEEDSACTION."' or ticket_status_id='".TicketStatus::_INPROCESS."' or ticket_status_id='".TicketStatus::_REOPENED."'")->asArray()->all();
		}
	}
	public  static function getToDo(){
		$thisMonthFirstDate = strtotime('first day of this month');
		$thisMonthLastDate = strtotime('last day of this month');
		//echo $thisMonthFirstDate."<br/>".$thisMonthLastDate;
		$query = TaskModel::find()->where("(expected_end_datetime) >='$thisMonthFirstDate' and (expected_end_datetime) <='$thisMonthLastDate' and task_status_id not in (".TaskStatus::_COMPLETED.", ".TaskStatus::_CANCELLED.")")->count();
		if(Yii::$app->params['user_role'] !='admin'){

		/*	return TaskModel::find()->joinWith('taskStatus')->joinWith('taskPriority')->orderBy('tbl_task_status.sort_order,tbl_task_priority.sort_order')->where(" EXISTS(Select *
FROM tbl_project_user  WHERE project_id =tbl_task.project_id and user_id=".Yii::$app->user->identity->id.") and date(expected_end_datetime) >='$thisMonthFirstDate' and date(expected_end_datetime) <='$thisMonthLastDate'")->asArray()->orderBy('id desc')->all();*/
		return TaskModel::find()->joinWith('taskStatus')->joinWith('taskPriority')->orderBy('tbl_task_status.sort_order,tbl_task_priority.sort_order')->where("	  task_status_id not in (".TaskStatus::_COMPLETED.", ".TaskStatus::_CANCELLED.") and user_assigned_id=".Yii::$app->user->identity->id)->asArray()->orderBy('id desc')->all();
		}else{
			return TaskModel::find()->where(" (expected_end_datetime) >='$thisMonthFirstDate' and (expected_end_datetime) <='$thisMonthLastDate'  and task_status_id not in (".TaskStatus::_COMPLETED.", ".TaskStatus::_CANCELLED.")")->orderBy('id desc')->asArray()->all();
		}
	}


	public static  function getThemeSetting(){
		 $dataProvider = configItemModel::find()->where("config_item_value like 'theme%'")->asArray()->all();
		 $array=array();
		 foreach($dataProvider as $row){
			$array[$row['config_item_name']]=$row['active'];
		 }
		 return $array;
	}
	public static  function getInactiveUsers(){
		return UserCommonModel::find()->where("updated_at='0' and active !='1'")->count();
	}
	public static  function getTotalInactiveUsers(){
		return UserCommonModel::find()->where("active !='1'")->count();
	}
	public static  function getTotalUsers(){
		return UserCommonModel::find()->count();
	}
	public static  function getTotalProducts(){
		return ProductModel::find()->count();
	}
	public static  function getTotalCustomers(){
		return CustomerModel::find()->count();
	}
	public static  function checkSession(){
		$session_id=$_SESSION ['SessionDetailsId'];
		$sql="select * from tbl_session_details where session_id='$session_id' and logged_out !=''";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$dataReader=$command->queryAll();
		if(count($dataReader) >0){
			if($_GET['r']!=="site%2Flogin"){
				header('location:index.php?r=site%2Flogin');
				exit();
			}
		}
		if(!Yii::$app->user->identity->id){
			if(!$_GET['r']=='site/login'){
			header('location:index.php?r=site%2Flogin');
			exit();
			}
		}
	}
	public static  function getAllUsers(){
		/*if(Yii::$app->params['user_role'] !='admin'){
		//Get User's Projects
		$projects = ProjectModel::find()->where("EXISTS(Select *

	FROM tbl_project_user  WHERE project_id =tbl_project.id and user_id=".Yii::$app->user->identity->id.")")->asArray()->all();
		$user_ids = array();
		$user_ids[0]=0;
		if(count($projects)>0){
			foreach($projects as $project){
				$user_ids[$project['project_owner_id']]	= $project['project_owner_id'];
				$project_ids[]=$project['id'];
			}

			$p_ids = implode(',',$project_ids);

			/// Get Project's Users
			$connection = \Yii::$app->db;
			$sql="Select * FROM tbl_project_user  WHERE project_id IN($p_ids)";
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
			foreach($dataReader as $data){
				$user_ids[$data['user_id']]	= $data['user_id'];
			}
		}
		unset($user_ids[Yii::$app->user->identity->id]);
		$userIds = array_keys($user_ids);
		$users = UserCommonModel::find()->where("id IN(".implode(',',$userIds).") and active=1")->asArray()->all();
		}else{*/
			$users = UserCommonModel::find()->where("id !=".Yii::$app->user->identity->id." and active=1")->asArray()->all();
		//}
		return $users;
	}
	public static  function checkUserLoggedIn($id){
		$sql="select * from tbl_session_details where user_id='$id' and logged_out =''";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$dataReader=$command->queryAll();
		return count($dataReader);
	}
	public static  function destroyUserSessionStatus(){
		/*if(!isset($_SESSION))
		{
			session_start();
		}*/
		//print_r(session_id());exit;
		$now=time();
		$interval = Yii::$app->params['SESSION_TIMEOUT_PERIOD'];
		if($interval == '0')
			$interval = time();
		if(isset($_SESSION['SessionDetailsId']))
		{
			$session_id = $_SESSION['SessionDetailsId'];
			$user_id=Yii::$app->user->identity->id?Yii::$app->user->identity->id:0;
			//$sql="update  tbl_session_details set logged_out='$now' where date_add(from_unixtime(logged_in), INTERVAL 9 HOUR) < now() and session_id='".session_id()."' and  user_id='$user_id'";
			//$sql="update  tbl_session_details set logged_out='$now' where date_add(from_unixtime(logged_in), INTERVAL 9 HOUR) < now() and user_id='$user_id' and logged_out=0";
			$sql="update  tbl_session_details set logged_out='$now' where date_add(from_unixtime(logged_in), INTERVAL $interval SECOND) < now() and user_id='$user_id' and session_id='$session_id' and logged_out=0";
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->execute();
			if($dataReader > 0)
			{
				Yii::$app->user->logout ();
				$_SESSION['SessionDetailsId'] = session_id();
				Yii::$app->getResponse()->redirect(array('/site/login'));
			}
		}
		else
		{
			$sql="update  tbl_session_details set logged_out='$now' where date_add(from_unixtime(logged_in), INTERVAL $interval SECOND) < now() and logged_out=0";
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->execute();

			Yii::$app->user->logout ();
			$_SESSION['SessionDetailsId'] = session_id();
			Yii::$app->getResponse()->redirect(array('/site/login'));
		}
	}
	public static function checkSessionDestroy(){
		/*if(!isset($_SESSION))
		{
			session_start();
		}*/

		/*if(isset($_SESSION['SessionDetailsId']))
		{
			$session_id=$_SESSION ['SessionDetailsId'];
			$sql="select * from tbl_session_details where session_id='$session_id' and logged_out !='0'";
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryAll();
			if(count($dataReader) >0){
				$now=time();
				$sql="update tbl_session_details set logged_out='$now' where session_id='$session_id' and logged_out =0";
				$connection = \Yii::$app->db;
				$command=$connection->createCommand($sql);
				$command->execute();
				//Yii::$app->user->logout ();
				$_SESSION['SessionDetailsId'] = session_id();
				Yii::$app->getResponse()->redirect(array('/site/login'));
				//unset($_SESSION['SessionDetailsId']);
				//header('location:index.php?r=site%2Flogin');
			}
		}*/
	}
	public static  function getProjectTasksCount($project_id){
		$query = TaskModel::find()->where("project_id=$project_id")->count();
		return $query;
	}
	public static  function getProjectDefectsCount($project_id){
		$query = DefectModel::find()->where("project_id=$project_id")->count();
		return $query;
	}
	public static  function getProjectTicketsCount($project_id){
		$query = TicketModel::find()->where("project_id=$project_id")->count();
		return $query;
	}
	public static  function getUserPendingTasksCount($user_id){
///
		$query = TaskModel::find()->where("(task_status_id='".TaskStatus::_NEEDSACTION."' or task_status_id='".TaskStatus::_INPROCESS."') and user_assigned_id=$user_id")->count();
		return $query;
	}
	public static  function getUserDefectsCount($user_id){
		$query = DefectModel::find()->where("(defect_status_id='".DefectStatus::_NEEDSACTION."' or defect_status_id='".DefectStatus::_INPROCESS."') and user_assigned_id=$user_id")->count();
		return $query;
	}
	public static  function getUserTicketsCount($user_id){
///
		$query = TicketModel::find()->where("(ticket_status_id='".TicketStatus::_NEEDSACTION."' or ticket_status_id='".TicketStatus::_INPROCESS."' or ticket_status_id='".TicketStatus::_REOPENED."') and user_assigned_id=$user_id")->count();
		return $query;
	}
	public static  function getUserProjectsCount($user_id){
//
		$query = ProjectModel::find()->where(" (EXISTS(Select *
	FROM tbl_project_user  WHERE project_id =tbl_project.id and user_id =$user_id) or project_owner_id=$user_id)  and  (project_status_id='1' or project_status_id='2')")->count();
		return $query;
	}
	public static function getProjectUsersCount($entity_id){

	return 	$query = ProjectUser::find ()->where ( [
				'project_id' => $entity_id
		] )->count();
	}
	public static function getProjectOwnerId($id){
		$projectModel = ProjectModel::findOne($id);

		return $projectModel->project_owner_id;
	}
	public  static function getPendingDefectCountLabel(){
			return DefectModel::find()->where("user_assigned_id=".Yii::$app->user->identity->id."  and (defect_status_id='".DefectStatus::_NEEDSACTION."' or defect_status_id='".DefectStatus::_INPROCESS."')")->count();//."/".DefectModel::find()->where("user_assigned_id=".Yii::$app->user->identity->id)->count();
	}
	public  static function getPendingTicketCountLabel(){
			return TicketModel::find()->where("user_assigned_id=".Yii::$app->user->identity->id." and (ticket_status_id='".TicketStatus::_NEEDSACTION."' or ticket_status_id='".TicketStatus::_INPROCESS."' or ticket_status_id='".TicketStatus::_REOPENED."')")->count();//."/".TicketModel::find()->where("user_assigned_id=".Yii::$app->user->identity->id)->count();
	}
	public  static function getPendingTaksCountLabel(){
			return TaskModel::find()->where("user_assigned_id=".Yii::$app->user->identity->id." and (task_status_id='".TaskStatus::_NEEDSACTION."' or task_status_id='".TaskStatus::_INPROCESS."')")->count();//."/".TaskModel::find()->where("user_assigned_id=".Yii::$app->user->identity->id)->count();

	}

	public static  function getOpenedTicket($date){
		$query = TicketModel::find()->where("from_unixtime(added_at, '%Y-%m-%d') ='$date'")->count();
		return $query;
	}
	public static  function getClosedTicket($date){
		$query = TicketModel::find()->where("ticket_status_id='".TicketStatus::_CLOSED."' and from_unixtime(updated_at, '%Y-%m-%d') ='$date'")->count();
		return $query;
	}
	public static  function getTotalClosedTicket(){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$thisMonthFirstDate = strtotime('first day of this month');
		$thisMonthLastDate = strtotime('last day of this month');
		$query = TicketModel::find()->where("ticket_status_id='".TicketStatus::_CLOSED."' and (updated_at) >= '$thisMonthFirstDate'  and (updated_at)<='$thisMonthLastDate'")->count();
		return $query;
	}
	public static  function getTotalOpenTicket(){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$thisMonthFirstDate = strtotime('first day of this month');
		$thisMonthLastDate = strtotime('last day of this month');
		$query = TicketModel::find()->where("(added_at) >='$thisMonthFirstDate' and (added_at) <='$thisMonthLastDate'")->count();
		return $query;
	}

	public static  function getAllTicketYears(){
		$sql = "SELECT from_unixtime(added_at, '%Y')  year_name FROM tbl_ticket where from_unixtime(added_at, '%Y') not in(0,1970) group by from_unixtime(added_at, '%Y')";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$row=$command->queryAll();
		return $row;
	}

	public static  function getClosedYearlyTicket($year){
		$sql = "SELECT count(id) tot FROM tbl_ticket where from_unixtime(updated_at, '%Y') ='$year' and ticket_status_id='".TicketStatus::_CLOSED."'";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$row=$command->queryOne();
		return $row['tot']?$row['tot']:0;
	}
	public static  function getOpenedYearlyTicket($year){
		$sql = "SELECT count(id) tot FROM tbl_ticket where from_unixtime(added_at, '%Y') ='$year'";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$row=$command->queryOne();
		return $row['tot']?$row['tot']:0;
	}

	public static  function getTotalDoneTicket(){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$thisMonthFirstDate = strtotime('first day of this month');
		$thisMonthLastDate = strtotime('last day of this month');
		$query = TicketModel::find()->where("ticket_status_id='".TicketStatus::_CLOSED."' and (updated_at) >='$thisMonthFirstDate' and (updated_at) <='$thisMonthLastDate'")->count();
		return $query;
	}

	public static  function getTotalPenddingTicket(){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$thisMonthFirstDate = strtotime('first day of this month');
		$thisMonthLastDate = strtotime('last day of this month');
		$query = TicketModel::find()->where("(ticket_status_id in ('".TicketStatus::_NEEDSACTION."', '".TicketStatus::_INPROCESS."', '".TicketStatus::_REOPENED."')) and (added_at) >='$thisMonthFirstDate' and (added_at) <='$thisMonthLastDate'")->count();
		return $query;
	}
	public static  function getTotalCancelledTicket(){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$thisMonthFirstDate = strtotime('first day of this month');
		$thisMonthLastDate = strtotime('last day of this month');
		$query = TicketModel::find()->where("ticket_status_id=".TicketStatus::_CANCELLED." and (added_at) >='$thisMonthFirstDate' and (added_at) <='$thisMonthLastDate'")->count();
		return $query;
	}

	public static  function getOpenedMonthlyTicket($year,$month){
		$month = str_pad($month, 2, "0", STR_PAD_LEFT);
		$sql = "SELECT count(id) tot FROM tbl_ticket where from_unixtime(added_at, '%m') ='$month' and from_unixtime(added_at, '%Y') ='$year'";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$row=$command->queryOne();
		return $row['tot']?$row['tot']:0;
	}

	public static  function getClosedMonthlyTicket($year,$month){
		$month = str_pad($month, 2, "0", STR_PAD_LEFT);
		$sql = "SELECT count(id) tot FROM tbl_ticket where from_unixtime(updated_at, '%m') ='$month' and from_unixtime(updated_at, '%Y') ='$year' and ticket_status_id='".TicketStatus::_CLOSED."'";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$row=$command->queryOne();
		return $row['tot']?$row['tot']:0;
	}

	public static  function getOpenedInvoice($date){
		$query = InvoiceModel::find()->where("from_unixtime(added_at, '%Y-%m-%d') ='$date'")->count();
		return $query;
	}
	public static  function getClosedInvoice($date){
		$query = InvoiceModel::find()->where("invoice_status_id='".InvoiceStatus::_PAID."' and from_unixtime(updated_at, '%Y-%m-%d') ='$date'")->count();
		return $query;
	}
	public static  function getTotalClosedInvoice(){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$thisMonthFirstDate = strtotime('first day of this month');
		$thisMonthLastDate = strtotime('last day of this month');
		$query = InvoiceModel::find()->where("invoice_status_id='".InvoiceStatus::_PAID."' and (updated_at) >= '$thisMonthFirstDate'  and (updated_at)<='$thisMonthLastDate'")->count();
		return $query;
	}
	public static  function getTotalOpenInvoice(){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$thisMonthFirstDate = strtotime('first day of this month');
		$thisMonthLastDate = strtotime('last day of this month');
		$query = InvoiceModel::find()->where("(added_at) >='$thisMonthFirstDate' and (added_at) <='$thisMonthLastDate'")->count();
		return $query;
	}

	public static  function getAllInvoiceYears(){
		$sql = "SELECT from_unixtime(added_at, '%Y')  year_name FROM tbl_invoice where from_unixtime(added_at, '%Y') not in(0,1970) group by from_unixtime(added_at, '%Y')";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$row=$command->queryAll();
		return $row;
	}

	public static  function getClosedYearlyInvoice($year){
		$sql = "SELECT count(id) tot FROM tbl_invoice where from_unixtime(updated_at, '%Y') ='$year' and invoice_status_id='".InvoiceStatus::_PAID."'";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$row=$command->queryOne();
		return $row['tot']?$row['tot']:0;
	}
	public static  function getOpenedYearlyInvoice($year){
		$sql = "SELECT count(id) tot FROM tbl_invoice where from_unixtime(added_at, '%Y') ='$year'";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$row=$command->queryOne();
		return $row['tot']?$row['tot']:0;
	}

	public static  function getTotalDoneInvoice(){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$thisMonthFirstDate = strtotime('first day of this month');
		$thisMonthLastDate = strtotime('last day of this month');
		$query = InvoiceModel::find()->where("invoice_status_id='".InvoiceStatus::_PAID."' and (updated_at) >='$thisMonthFirstDate' and (updated_at) <='$thisMonthLastDate'")->count();
		return $query;
	}

	public static  function getTotalPenddingInvoice(){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$thisMonthFirstDate = strtotime('first day of this month');
		$thisMonthLastDate = strtotime('last day of this month');
		$query = InvoiceModel::find()->where("(invoice_status_id not in ('".InvoiceStatus::_PAID."', '".InvoiceStatus::_CANCELLED."')) and (added_at) >='$thisMonthFirstDate' and (added_at) <='$thisMonthLastDate'")->count();
		return $query;
	}
	public static  function getTotalCancelledInvoice(){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$thisMonthFirstDate = strtotime('first day of this month');
		$thisMonthLastDate = strtotime('last day of this month');
		$query = InvoiceModel::find()->where("invoice_status_id=".InvoiceStatus::_CANCELLED." and (added_at) >='$thisMonthFirstDate' and (added_at) <='$thisMonthLastDate'")->count();
		return $query;
	}

	public static  function getOpenedMonthlyInvoice($year,$month){
		$month = str_pad($month, 2, "0", STR_PAD_LEFT);
		$sql = "SELECT count(id) tot FROM tbl_invoice where from_unixtime(added_at, '%m') ='$month' and from_unixtime(added_at, '%Y') ='$year'";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$row=$command->queryOne();
		return $row['tot']?$row['tot']:0;
	}

	public static  function getClosedMonthlyInvoice($year,$month){
		$month = str_pad($month, 2, "0", STR_PAD_LEFT);
		$sql = "SELECT count(id) tot FROM tbl_invoice where from_unixtime(updated_at, '%m') ='$month' and from_unixtime(updated_at, '%Y') ='$year' and invoice_status_id='".InvoiceStatus::_PAID."'";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$row=$command->queryOne();
		return $row['tot']?$row['tot']:0;
	}

	public static function getTimezoneList()
	{
		static $regions = array(
			\DateTimeZone::AFRICA,
			\DateTimeZone::AMERICA,
			\DateTimeZone::ANTARCTICA,
			\DateTimeZone::ASIA,
			\DateTimeZone::ATLANTIC,
			\DateTimeZone::AUSTRALIA,
			\DateTimeZone::EUROPE,
			\DateTimeZone::INDIAN,
			\DateTimeZone::PACIFIC,
		);

		$timezones = array();
		foreach( $regions as $region )
		{
			$timezones = array_merge( $timezones, \DateTimeZone::listIdentifiers( $region ) );
		}

		$timezone_offsets = array();
		foreach( $timezones as $timezone )
		{
			$tz = new \DateTimeZone($timezone);
			$timezone_offsets[$timezone] = $tz->getOffset(new \DateTime);
		}

		// sort timezone by offset
		asort($timezone_offsets);

		$timezone_list = array();
		foreach( $timezone_offsets as $timezone => $offset )
		{
			$offset_prefix = $offset < 0 ? '-' : '+';
			$offset_formatted = gmdate( 'H:i', abs($offset) );

			$pretty_offset = "UTC${offset_prefix}${offset_formatted}";

			$timezone_list[$timezone] = "(${pretty_offset}) $timezone";
		}

		return $timezone_list;
	}
}

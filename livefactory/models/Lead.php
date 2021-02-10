<?php

namespace livefactory\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use livefactory\models\search\Lead as Leads;

/**
 * This is the model class for table "{{%tbl_lead}}".
 *
 * @property integer $id
 * @property integer $office_id
 * @property string $lead_name
 * @property string $lead_description
 * @property integer $lead_type_id
 * @property integer $lead_owner_id
 * @property integer $lead_status_id
 * @property integer $lead_master_status_id
 * @property integer $converted_at
 * @property string $lead_status_description
 * @property integer $lead_source_id
 * @property string $lead_source_description
 * @property integer $opportunity_amount
 * @property string $do_not_call
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $phone
 * @property string $mobile
 * @property string $fax
 * @property integer $age
 * @property float $loan_amount
 * @property float $loan_interest
 * @property float $loan_commission
 * @property float $spouse_monthly_income
 * @property float $monthly_income
 * @property float $monthly_income2
 * @property float $monthly_expenses
 * @property string $curp
 * @property string $rfc
 * @property string $payment_folio
 * @property integer $added_at
 * @property integer $updated_at
 * @property integer $job
 * @property integer $place_of_birth
 * @property integer $spouse_job
 * @property integer $company_name
 * @property integer $active
 * @property integer $service_status_id
 * @property integer $service_owner_id
 * @property integer $covid19
 *
 */
class Lead extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tbl_lead}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lead_status_id', 'first_name', 'last_name', 'middle_name', 'product_id', 'loan_amount', 'loan_interest', 'lead_source_id', 'mobile','lead_owner_id'], 'required'],
            [['lead_description'], 'string'],
            [['lead_master_status_id', 'office_id', 'payed' ,'lead_type_id', 'lead_owner_id', 'lead_status_id', 'lead_source_id', 'product_id', 'opportunity_amount', 'added_at', 'updated_at', 'converted_at', 'age', 'economic_dep', 'bureau_status', 'home_status', 'civil_status', 'civil_status_regime', 'loan_term', 'valid_admin', 'valid_manager', 'valid_sales', 'payment_folio', 'service_status_id','qualify', 'covid19'], 'integer'],
            [['lead_name', 'first_name', 'last_name', 'middle_name', 'phone', 'mobile', 'fax', 'company_name', 'job', 'labor_old', 'monthly_income', 'monthly_income2', 'monthly_expenses', 'bureau_status_desc', 'spouse_job', 'spouse_monthly_income', 'loan_amount', 'loan_interest', 'loan_commission', 'rfc', 'curp', 'birthdate', 'place_of_birth'], 'string', 'max' => 255],
			[['email'], 'email'],
            [['do_not_call'], 'string', 'max' => 1],
            ['mobile', 'onePhone'],
          // ['mobile' , 'compare' , 'compareValue' =>  1, 'message' => 'You must accept terms of use']
        ];
    }

    public function onePhone($attribute, $params) {
        if ( ! $this->mobile && ! $this->phone)
            $this->addError($attribute, 'A phone is required');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'lead_name' => Yii::t('app', 'Lead Title'),
            'lead_description' => Yii::t('app', 'Lead Description'),
            'lead_type_id' => Yii::t('app', 'Lead Type'),
            'lead_owner_id' => Yii::t('app', 'Lead Owner'),
            'lead_status_id' => Yii::t('app', 'Lead Status'),
            'lead_master_status_id' => 'Departamento',
            'lead_status_description' => Yii::t('app', 'Lead Status Description'),
            'lead_source_id' => Yii::t('app', 'Lead Source'),
            'lead_source_description' => Yii::t('app', 'Lead Source Description'),
            'payed' => Yii::t('app', 'Payed'),
            'opportunity_amount' => Yii::t('app', 'Opportunity Amount'),
            'do_not_call' => Yii::t('app', 'Contactable'),
            'email' => Yii::t('app', 'Email'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'phone' => Yii::t('app', 'Phone'),
            'mobile' => Yii::t('app', 'Mobile'),
            'fax' => Yii::t('app', 'Fax'),
            'service_status_id' => 'Estado',
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
	
	public function beforeSave($insert) {
		$this->lead_name = mb_strtoupper(trim($this->lead_name));
		$this->lead_description = Html::encode(mb_strtoupper($this->lead_description));
		//$this->lead_status_description = Html::encode($this->lead_status_description);
		//$this->lead_source_description = Html::encode($this->lead_source_description);
		//$this->do_not_call = Html::encode($this->do_not_call);
		$this->email = Html::encode($this->email);
		$this->first_name = mb_strtoupper(trim($this->first_name));
		$this->last_name = mb_strtoupper(trim($this->last_name));
        $this->middle_name = mb_strtoupper(trim($this->middle_name));
		$this->phone = Html::encode($this->phone);
		$this->mobile = Html::encode($this->mobile);
		//$this->fax = Html::encode($this->fax);
		/*if($this->opportunity_amount == NULL)
			$this->opportunity_amount = 0;*/
		$this->loan_amount = $this->currency($this->loan_amount);
		$this->loan_commission = $this->currency($this->loan_commission);
		$this->monthly_income = $this->currency($this->monthly_income);
		$this->monthly_income2 = $this->currency($this->monthly_income2);
		$this->monthly_expenses = $this->currency($this->monthly_expenses);
		$this->spouse_monthly_income = $this->currency($this->spouse_monthly_income);
        $this->place_of_birth = Html::encode(mb_strtoupper($this->place_of_birth));
        $this->spouse_job = Html::encode(mb_strtoupper($this->spouse_job));
        $this->company_name = Html::encode(mb_strtoupper($this->company_name));
        $this->bureau_status_desc = Html::encode(mb_strtoupper($this->bureau_status_desc));
        $this->job = Html::encode(mb_strtoupper($this->job));
		$this->rfc = mb_strtoupper($this->rfc);
		$this->curp = mb_strtoupper($this->curp);
		$this->updated_at = time();

		return parent::beforeSave($insert);
	}

    /**
     * Parses amount
     *
     * @param $amount
     * @return mixed
     */
	private function currency($amount) {
        return str_replace(array('$', ' ', ','), '', $amount);
    }

    /**
     * @return float
     */
    public function getInterest()
    {
        if ($this->loan_amount && $this->loan_commission) {
            return ($this->loan_commission / $this->loan_amount) * 100;
        }

        return 0.0;
    }

	public function getLeadType()
    {
    	return $this->hasOne(LeadType::className(), ['id' => 'lead_type_id']);
    }

	public function getLeadStatus()
    {
    	return $this->hasOne(LeadStatus::className(), ['id' => 'lead_status_id']);
    }

	public function getLeadSource()
    {
    	return $this->hasOne(LeadSource::className(), ['id' => 'lead_source_id']);
    }

	public function getUser()
    {
    	return $this->hasOne(User::className(), ['id' => 'lead_owner_id']);
    }

    public function getAgent()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getOffice()
    {
        return $this->hasOne(Office::className(), ['id' => 'office_id']);
    }

	public function afterDeleteManual()
	{
		/* Delete associated files from disk */
		$file1 = Yii::$app->getBasePath()."\\leads\\".$this->id.".png";
		$file2 = Yii::$app->getBasePath()."\\attachments\\lead_".$this->id.".zip";
		if(file_exists($file1))
		{
			unlink($file1);
		}
		if(file_exists($file2))
		{
			unlink($file2);
		}

		/*Delete Contacts */
		foreach (Contact::find()->where(['entity_id'=> $this->id, 'entity_type' => 'lead'])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Addresses */
		foreach (Address::find()->where(['entity_id'=> $this->id, 'entity_type' => 'lead'])->all() as $record) 
		{
			$record->delete();
		}
		
		/*Delete Attachments */
		foreach (File::find()->where(['entity_id'=> $this->id, 'entity_type' => 'lead'])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Notes */
		foreach (Note::find()->where(['entity_id'=> $this->id, 'entity_type' => 'lead'])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Activities */
		foreach (History::find()->where(['entity_id'=> $this->id, 'entity_type' => 'lead'])->all() as $record) 
		{
			$record->delete();
		}
		
		/*Delete Estimates */
		if(in_array('estimate',Yii::$app->params['modules']))
		{
			foreach (Estimate::find()->where(['customer_id'=> $this->id, 'entity_type' => 'lead'])->all() as $record) 
			{
				$record->delete();
			}
		}

		return parent::afterDelete();
	}

    /**
     * @param $loanId
     * @return string
     */
	public static function generateContract($loanId) {

        $current = ConfigItem::findByName('CONTRACT_COUNTER');
        $counter = (int) $current->config_item_value;
        $loan = Loan::findOne($loanId);

        $contract = $loan->code;
        $contract .= str_pad($counter, 8, '0', STR_PAD_LEFT);

        $mt1 = [];
        for ($i = 0; $i < 9; $i++) {

            $val = (int) $contract[$i];

            if (($i % 2) == 0) {
                $mt1[$i] = $val * 1;
            } else {
                $mt1[$i] = $val * 2;
            }
        }
        $rest = (array_sum($mt1) % 10);

        $checksum = $rest > 0 ? 10 - $rest : $rest;
        $contract .= $checksum;

        $current->config_item_value = ($counter + 1) . '';
        $current->save();

        return $contract;
    }

    //custom progress
    //progress old
//    public static function generateProgressLead($id, $save = false)
//    {
//        $progress = 0;
//        $total = 0;
//        $total1 = 0;
//        $total2 = 0;
//        $total3 = 0;
//        $total4 = 0;
//        //total fields 55
//        $total_fields = 49;
//        $lead = Lead::findOne($id);
//        $contactprogress = Contact::find()->where('entity_id = ' . $id . " AND entity_type LIKE 'lead%'")->asArray()->all();
//        $addressprogress = Address::find()->where('entity_id = ' . $id . " AND entity_type LIKE 'lead%'")->asArray()->all();
//        $fileprogress = ArrayHelper::map(File::find()->where('entity_id = ' . $id . " AND entity_type LIKE 'lead%'")->asArray()->all(), 'entity_type', 'entity_id');
//
//        //var_dump($Leadprogress);
//        $fields = [ 'birthdate', 'place_of_birth', 'rfc', 'curp', 'product_id', 'loan_term', 'loan_commission', 'loan_amount', 'monthly_income', 'monthly_income2', 'monthly_expenses', 'economic_dep', 'home_status', 'bureau_status', 'bureau_status_desc', 'company_name', 'job', 'labor_old', 'civil_status', 'company_name'];
//        $fields_contact = ['first_name', 'middle_name', 'last_name', 'email', 'phone', 'mobile'];
//        $fields_address = ['address_1', 'num_ext', 'block', 'state_id', 'delegation', 'zipcode'];
//        $fields_files = ['lead.id', 'lead.curp', 'lead.entry', 'lead.home', 'lead.signed', 'lead.birth'];
//        $arrayField=['civil_status_regime','spouse_job','spouse_monthly_income'];
//        //consulta de cliente informacion
//        if ($lead->civil_status == 3) {
//            //echo 'casado';
//            foreach ($arrayField as $field)
//            {
//                if (!empty(trim($lead->{$field}))) {
//                    $progress++;
//                } else {
//                    //echo $field;
//                }
//                $total++;
//                $total_fields++;
//            }
//        }
//
//
//        foreach ($fields as $field) {
//            //var_dump($Leadprogress->{$field});
//
//            if (!empty(trim($lead->{$field}))) {
//                $progress++;
//            } else {
////                echo $field;
//            }
//
//            $total++;
//            $total_fields++;
//             //echo $Leadprogress->{$field}, $field;
//        }
//
//
//        //echo $progress, "\n \n", $total, "lead \n \n";
//        //consulta de contactos de cliente
//        foreach ($contactprogress as $row) {
//
//            // Break
//            if ($lead->civil_status != 3 && $row['entity_type'] == 'lead.spouse') {
//                continue;
//            }
//
//                foreach ($fields_contact as $field) {
//                    //var_dump($row[$field]);
//                    if (!empty(trim($row[$field]))) {
//                        $progress++;
//                    } else if (in_array($field, ['middle_name', 'last_name', 'mobile']) && $row['entity_type'] == 'lead.job') {
//                        $total--;
//                    } else {
////                        echo $field;
//                    }
//                    $total1++;
//                    $total++;
//
//
//
//                    //
//                    if ($row['entity_type'] == 'lead.spouse') {
//                        $progress++;
//                        $total++;
//                        $total_fields++;
//                    }
//                }
//
//        }
//        //echo $progress, "\n \n", $total, "\n \n", $total1, "\n \n", "contactos \n \n";
//        //var_dump($contactprogress);
//        //consulta de direccion de cliente y referencias
//        foreach ($addressprogress as $row) {
//            foreach ($fields_address as $field) {
//                //var_dump($row[$field]);
//                if (!empty(trim($row[$field]))) {
//                    $progress++;
//                } else {
//          //          echo $field;
//                }
//                $total2++;
//                $total++;
//
//            }
//        }
//
//        //echo $progress, "\n \n", $total, "\n \n", $total2, "\n \n direccion \n \n";
//        //var_dump($addressprogress);
//        //consulta de archivos de cliente
//        // var_dump($fileprogress);
//        foreach ($fields_files as $field) {
//            //var_dump($row[$field]);
//            if (isset($fileprogress[$field])) {
//
////                $progress++;
//                $total4++;
//            }
//
//            //$total++;
//        }
//
//       //echo $progress;
//        //echo '/' . $total_fields;
//        //exit;
//        //echo $progress, "\n \n", $total, "\n \n", $total4, "\n \n archivos \n \n";
//        //var_dump($total,$total4,$total_fields);
//        $total=($progress/$total_fields)*35;
//        $total=round($total,0);
//        $total5=($total4/6)*65;
//        $total5=round($total5);
//        $total=$total+$total5;
//        //echo $total;
//        /*$lead->progress = $total;
//        $lead->save();*/
//        if ($save === true)
//        {
//            $lead->progress = $total;
//            return $lead->save();
//        }
//        return $total;
//
//    }
// generate progress lead with information of wizard
    public static function generateProgressLead($id, $save = false)
    {
//        var_dump($id);
        $total = 0;
        $total_fields = 33;
        $total_fields_files = 6;
        $field_files = 0;
        $progress = 0;
        $lead_update = Lead::find()->where('id = ' . $id)->one();
        $lead = Lead::find()->select('id, first_name, last_name, middle_name, rfc, curp, phone, mobile, monthly_income, monthly_income2, monthly_expenses, economic_dep, home_status, bureau_status, bureau_status_desc, company_name, product_id, loan_term, loan_amount, loan_interest, loan_commission, civil_status')->where('id = ' . $id)->one();
        $fileprogress = ArrayHelper::map(File::find()->where('entity_id = ' . $id . " AND entity_type LIKE 'lead.birth%'")->union(File::find()->where('entity_id = ' . $id . " AND entity_type LIKE 'lead.curp%'"))->union(File::find()->where('entity_id = ' . $id . " AND entity_type LIKE 'lead.entry%'"))->union(File::find()->where('entity_id = ' . $id . " AND entity_type LIKE 'lead.home%'"))->union(File::find()->where('entity_id = ' . $id . " AND entity_type LIKE 'lead.id%'"))->union(File::find()->where('entity_id = ' . $id . " AND entity_type LIKE 'lead.signed%'"))->asArray()->all(), 'entity_type', 'entity_id');





        if ($lead != null){
            $refer1 = Contact::find()->select('first_name, middle_name, last_name, email, phone, mobile, entity_id, entity_type')->where('entity_id = ' . $lead->id . " and entity_type = 'lead.ref.1'")->one();
            $refer2 = Contact::find()->select('first_name, middle_name, last_name, email, phone, mobile, entity_id, entity_type')->where('entity_id = ' . $lead->id . " and entity_type = 'lead.ref.2'")->one();
            foreach ($lead as $field=>$key)
            {

                if ($key != null)
                {
                    if ($field != 'id')
                    {
//                        echo $field , $key . '<br>';
                        $lead_a[][$field] = $key;
                        $progress++;
                    }

                }

            }
            if ($refer1 != null)
            {
                foreach ($refer1 as $field=>$key)
                {

                    if ($key != null)
                    {
                        if ($field != 'entity_type')
                        {
                            if ($field != 'entity_id')
                            {
//                                echo $field , $key . '<br>';
                                $refer1_a[][$field] = $key;
                                $progress++;
                            }
                        }


                    }
                }

            }
            if ($refer2 != null)
            {
                foreach ($refer2 as $field=>$key)
                {

                    if ($key != null)
                    {
                        if ($field != 'entity_type')
                        {
                            if ($field != 'entity_id')
                            {
//                                echo $field , $key . '<br>';
                                $refer2_a[][$field] = $key;
                                $progress++;
                            }
                        }
                    }
                }

            }
            if ($fileprogress != null)
            {
                foreach ($fileprogress as $file)
                {
                    if ($file != null)
                    $field_files++;
                }
            }
        }
        if ($field_files > $total_fields_files)
            $field_files = $total_fields_files;

        $total1 = ($field_files / $total_fields_files) * 65;
        $total = ($progress / $total_fields) * 35;
        $total = $total + $total1;
//        var_dump($total);
        /*$lead->progress = $total;
//        $lead->save();*/
        if ($save === true)
        {
            $lead_update->progress = $total;
            return $lead_update->save();
        }
        return round($total);
    }
    public static function export($params)
    {
//        $connection = \Yii::$app->db;
//        $sql = "SELECT lead.id , office.code ,lead.c_control , lead.lead_name ,status_lead.label as status, lead.mobile , lead.phone , lead.email , lead.loan_amount , lead_source.label as source, capturist.username , capturist.alias , concat(capturist.first_name , \" \" , capturist.middle_name , \" \" , capturist.last_name ),  FROM_UNIXTIME(lead.added_at ) , owner.username, owner.alias, concat(owner.first_name , \" \" , owner.middle_name , \" \" , owner.last_name ) from tbl_lead as lead left join tbl_office as office on office.id = lead.office_id left join tbl_lead_status as status_lead on status_lead.id = lead.lead_status_id left join tbl_user as capturist on capturist.id = lead.user_id left join tbl_lead_source as lead_source on lead_source.id = lead.lead_source_id left join tbl_user as owner on owner.id = lead.lead_owner_id where lead.active = 1 OR lead.mobile != null OR lead.phone != null
//ORDER BY `lead`.`id`  DESC";
//        $command = $connection->createCommand($sql);
//        $list = $command->queryAll();
        ini_set('max_execution_time',300);
        ini_set('memory_limit','2048M');
        $date_start = '';
        $office = '';
        $agent = '';
        if ($params['start'] != '')
        {
            $start =strtotime($params['start'] . ' 0:00');
            $date_start = "  lead.added_at >= '" . $start . "'";
        }
        if ($params['end'] != '')
        {
            $end = strtotime($params['end'] . '23:59');
            $date_start .= "  and lead.added_at <= '" . $end . "'";
        }
        if ($params['office_id'] != '')
        {
            $office = ' lead.office_id = ' . $params['office_id'];
        }
        if ($params['agent_id'] != '')
        {
            $agent = ' tbl_user.id = ' . $params['agent_id'];
        }
        $listlead = (new Query())
            ->select(['lead.id','office.code','lead.c_control','lead.lead_name','status.label as status','lead.mobile','lead.phone','lead.email','lead.loan_amount','source.label as source','capturist.username as capturistusername','capturist.alias as capturistalias','concat(capturist.first_name , " " , capturist.last_name  , " " , capturist.middle_name ) as capturistfullname','FROM_UNIXTIME(lead.added_at )','owner.username','owner.alias','concat(owner.first_name , " " , owner.last_name  , " " , owner.middle_name) as ownerfullname','service_owner.username as service_ownerusername','service_owner.alias as service_owneralias','concat(service_owner.first_name , " " ,  service_owner.last_name  , " " , service_owner.middle_name) as service_ownerfullname',])
            ->from('tbl_lead as lead')
            ->leftJoin('tbl_office as office', 'office.id = lead.office_id')
            ->leftJoin('tbl_lead_status as status','status.id = lead.lead_status_id')
            ->leftJoin('tbl_user as capturist','capturist.id = lead.user_id')
            ->leftJoin('tbl_lead_source as source','source.id = lead.lead_source_id')
            ->leftJoin('tbl_user as owner','owner.id = lead.lead_owner_id')
            ->leftJoin('tbl_user as service_owner', 'service_owner.id = lead.service_owner_id')
            ->where('lead.active = 1 OR lead.mobile != null OR lead.phone != null')
            ->andWhere($date_start)
            ->andWhere($office)
            ->orderBy('lead.added_at DESC')

        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $listlead
        ]);
        $list = $dataProvider->query->all();
        $listalllead = [];
        foreach ($list as $lead)
        {
            $payments = \livefactory\models\Payment::find()->where('entity_id = ' . $lead['id'])->asArray()->all();
            $total = 0;
            if (count($payments)) {
                foreach ($payments as $payment) {
                    $total += $payment['amount'];
                }
            }
            $lead['totalsale'] = $total;
            $listalllead[] = $lead;
        }
        $filename = "leadsexport" . time() . ".csv";
        header( 'Content-Type: application/csv' );
        header( 'Content-Disposition: attachment; filename="' . $filename . '";' );

        // clean output buffer
        ob_end_clean();

        $handle = fopen( 'php://output', 'w' );



        $keys = array('Id' => 0,'Oficina' => 1, 'Numero de control' => 2, 'Lead Nombre' => 3,'Status' => 4, 'Movil' => 5, 'Telefono' => 6, 'Correo' => 7,'Monto' => 8, 'Medio' => 9, 'CapturistaId' => 10, 'CapturistaAlias' => 11, 'Capturista' => 12, 'Fecha de creacion' => 13, 'PropietarioId' => 14, 'PropietarioAlias' => 15, 'Propietario' => 16, 'AtencionId' => 17, 'AtencionAlias' => 18, 'Atencion' => 19, 'Venta' => 20);
        // use keys as column titles
        fputcsv( $handle, array_keys( $keys ) );
        if ( ! empty($listalllead))
        {
            foreach ( $listalllead as $value ) {
                fputcsv( $handle, $value , ',' );
            }
        }

        fclose( $handle );
        exit();
    }
    public function exportlead()
    {
        $filename = "leadsexport" . time() . ".csv";
        header( 'Content-Type: application/csv' );
        header( 'Content-Disposition: attachment; filename="' . $filename . '";' );
        // clean output buffer
        ob_end_clean();
        $handle = fopen( 'php://output', 'w' );
        $keys = array('Nombre' => 0 , 'ApellidoPaterno' => 1, 'ApellidoMaterno' => 2, 'Celular' => 3, 'CorreoElectronico' => 4, 'MontoaSolicitar' => 5);
        // use keys as column titles
        fputcsv( $handle, array_keys( $keys ) );
        if ( ! empty($listalllead))
        {
            foreach ( $listalllead as $value ) {
                fputcsv( $handle, $value , ',' );
            }
        }

        fclose( $handle );
        exit();
    }

    public function import()
    {
        $lead = array();
        $count = 0;
        $repeats = 0;
        if (! empty($_FILES))
        {
            $file = $_FILES['file']['tmp_name'];
            $handle = fopen($file, "r");
            if ($file == NULL)
            {
                error_log('error');
            }
            else{

                while($filesop = fgetcsv($handle , 1000 , ",")){
                    if (($filesop[1] == 'Nombre' or $filesop[1] == null) or $filesop[3] == 'Email' or $filesop[2] == 'Numero de contacto'){
                    }
                    else
                    {

                        $source = $this->Sources_model->getByName($filesop[7]);
                        $status = $this->Settings_model->getByName($filesop[8]);
                        $credit = $this->Credit_model->getByName($filesop[5]);
                        if(!empty($source))
                        {
                            $source_id = $source['id'];
                        }
                        else
                            $source_id = 1;

                        if(!empty($status))
                        {
                            $status_id = $status['id'];
                        }
                        else
                            $status_id = 1;

                        if(!empty($credit))
                        {
                            $credit_id = $credit['idcredit'];
                        }
                        else
                        {
                            if((int)$filesop[5] == 0)

                                $credit_id = 5;

                            else
                                $credit_id = (int)$filesop[5];
                        }


                        $lead = array(
                            'status_id' => $status_id,
                            'lead_source' => $source_id,
                            'lead_name' => $filesop[1],
                            'lead_email' => $filesop[3],
                            'lead_credit' => $credit_id,
                            'loan_amount' => $filesop[4],
                            'lead_phone' => $filesop[2],
                            'created_at' => strtotime($filesop[0]),
                        );

                    }

                    if (! empty($lead)){
                        if ($this->Leads_model->getByPhone($lead['lead_phone']) > 0)
                        {
                            $repeats++;
                        }
                        else{
                            $this->Leads_model->postAdd($lead);
                            $lead['lead_id'] = $this->Leads_model->last_id();
                            $lead['tracking'] = utf8_encode($filesop[4]);
                            $this->History_model->postAdd($lead['lead_id'] , $this->session->user_id , 'Added Lead');
                            $this->Leads_model->postAddTracking($lead);
                            $count++;
                        }
                    }
                }
            }
            redirect('leads/import?count='.$count.'&repeats='.$repeats);
        }
        $this->load->View('leads/import');
    }
    public static function findLead($id){
        $lead = (new Query())->select('*')->from('tbl_lead')->where('id = ' . $id);

        $dataProvider = new ActiveDataProvider([
            'query' => $lead
        ]);
        return $dataProvider;
    }
}

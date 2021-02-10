<?php

namespace livefactory\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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
 */
class LeadRecycle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tbl_lead_recycle}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lead_status_id', 'first_name', 'last_name', 'middle_name', 'product_id', 'loan_amount', 'loan_interest', 'lead_source_id', 'mobile','lead_owner_id'], 'required'],
            [['lead_description'], 'string'],
            [['office_id', 'payed' ,'lead_type_id', 'lead_owner_id', 'lead_status_id', 'lead_source_id', 'product_id', 'opportunity_amount', 'added_at', 'updated_at', 'converted_at', 'age', 'economic_dep', 'bureau_status', 'home_status', 'civil_status', 'civil_status_regime', 'loan_term', 'valid_admin', 'valid_manager', 'valid_sales', 'payment_folio', 'service_status_id'], 'integer'],
            [['lead_name', 'first_name', 'last_name', 'middle_name', 'phone', 'mobile', 'fax', 'company_name', 'job', 'labor_old', 'monthly_income', 'monthly_income2', 'monthly_expenses', 'bureau_status_desc', 'spouse_job', 'spouse_monthly_income', 'loan_amount', 'loan_interest', 'loan_commission', 'rfc', 'curp', 'birthdate', 'place_of_birth'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['do_not_call'], 'string', 'max' => 1],
            ['mobile', 'onePhone']
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

    public static function generateProgressLead($id, $save = false)
    {
        $progress = 0;
        $total = 0;
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $total4 = 0;
        $total_fields = 55;
        $lead = LeadRecycle::findOne($id);
        $contactprogress = Contact::find()->where('entity_id = ' . $id . " AND entity_type LIKE 'lead%'")->asArray()->all();
        $addressprogress = Address::find()->where('entity_id = ' . $id . " AND entity_type LIKE 'lead%'")->asArray()->all();
        $fileprogress = ArrayHelper::map(File::find()->where('entity_id = ' . $id . " AND entity_type LIKE 'lead%'")->asArray()->all(), 'entity_type', 'entity_id');

        //var_dump($Leadprogress);
        $fields = [ 'birthdate', 'place_of_birth', 'rfc', 'curp', 'product_id', 'loan_term', 'loan_commission', 'loan_amount', 'monthly_income', 'monthly_income2', 'monthly_expenses', 'economic_dep', 'home_status', 'bureau_status', 'bureau_status_desc', 'company_name', 'job', 'labor_old', 'civil_status', 'company_name'];
        $fields_contact = ['first_name', 'middle_name', 'last_name', 'email', 'phone', 'mobile'];
        $fields_address = ['address_1', 'num_ext', 'block', 'state_id', 'delegation', 'zipcode'];
        $fields_files = ['lead.id', 'lead.curp', 'lead.entry', 'lead.home', 'lead.signed', 'lead.birth'];
        $arrayField=['civil_status_regime','spouse_job','spouse_monthly_income'];
        //consulta de cliente informacion
        if ($lead->civil_status == 3) {
            //echo 'casado';
            foreach ($arrayField as $field)
            {
                if (!empty(trim($lead->{$field}))) {
                    $progress++;
                } else {
                    //echo $field;
                }
                $total++;
                $total_fields++;
            }
        }
        foreach ($fields as $field) {
            //var_dump($Leadprogress->{$field});
            if (!empty(trim($lead->{$field}))) {
                $progress++;
            } else {
                //echo $field;
            }

            $total++;
            $total_fields++;
            //echo $Leadprogress->{$field}, $field;
        }
        //echo $progress, "\n \n", $total, "lead \n \n";
        //consulta de contactos de cliente
        foreach ($contactprogress as $row) {

            // Break
            if ($lead->civil_status != 3 && $row['entity_type'] == 'lead.spouse') {
                continue;
            }

            foreach ($fields_contact as $field) {
                //var_dump($row[$field]);
                if (!empty(trim($row[$field]))) {
                    $progress++;
                } else if (in_array($field, ['middle_name', 'last_name', 'mobile']) && $row['entity_type'] == 'lead.job') {
                    $total--;
                } else {
                    //          echo $field;
                }
                $total1++;
                $total++;



                //
                if ($row['entity_type'] == 'lead.spouse') {
                    $progress++;
                    $total++;
                    $total_fields++;
                }
            }

        }
        //echo $progress, "\n \n", $total, "\n \n", $total1, "\n \n", "contactos \n \n";
        //var_dump($contactprogress);
        //consulta de direccion de cliente y referencias
        foreach ($addressprogress as $row) {
            foreach ($fields_address as $field) {
                //var_dump($row[$field]);
                if (!empty(trim($row[$field]))) {
                    $progress++;
                } else {
                    //          echo $field;
                }
                $total2++;
                $total++;

            }
        }
        //echo $progress, "\n \n", $total, "\n \n", $total2, "\n \n direccion \n \n";
        //var_dump($addressprogress);
        //consulta de archivos de cliente
        // var_dump($fileprogress);
        foreach ($fields_files as $field) {
            //var_dump($row[$field]);
            if (isset($fileprogress[$field])) {

                $progress++;
                $total4++;
            }

            $total++;
        }
        //echo $progress;
        //echo '/' . $total_fields;
        //exit;
        //echo $progress, "\n \n", $total, "\n \n", $total4, "\n \n archivos \n \n";
        $total=($progress/$total_fields)*100;
        $total=round($total,0);
        //echo $total;
        /*$lead->progress = $total;
        $lead->save();*/

        if ($save === true)
        {
            $lead->progress = $total;
            return $lead->save();
        }

        return $total;

    }
}

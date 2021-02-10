<?php

namespace livefactory\models;

use Yii;
use \DateTime;
use \DateTimeZone;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_invoice".
 *
 * @property integer $id
 * @property string $invoice_number
 * @property integer $generated_from_estimation
 * @property integer $estimation_id
 * @property integer $date_created
 * @property integer $date_due
 * @property string $po_number
 * @property integer $customer_id
 * @property string $invoice_status_id
 * @property integer $linked_to_project
 * @property integer $project_id
 * @property integer $currency_id
 * @property string $sub_total
 * @property integer $discount_type_id
 * @property string $discount_figure
 * @property string $discount_amount
 * @property string $total_tax_amount
 * @property string $grand_total
 * @property string $total_paid
 * @property string $notes
 * @property integer $active
 * @property integer $created_by_user_id
 * @property integer $updated_by_user_id
 * @property integer $added_at
 * @property integer $updated_at
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_invoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_due', 'customer_id',  'currency_id', 'sub_total', 'discount_type_id', 'invoice_status_id', 'discount_figure', 'discount_amount', 'total_tax_amount', 'grand_total'], 'required'],
            [['generated_from_estimation', 'estimation_id', 'customer_id', 'linked_to_project', 'project_id', 'currency_id', 'discount_type_id', 'active', 'created_by_user_id', 'updated_by_user_id', 'invoice_status_id', 'added_at', 'updated_at'], 'integer'],
            [['date_created', 'date_due'], 'safe'],
            [['sub_total', 'discount_figure', 'discount_amount', 'total_tax_amount', 'grand_total', 'total_paid'], 'number'],
            [['invoice_number', 'tax_number', 'po_number', 'notes'], 'string', 'max' => 255],
			[['invoice_number'],'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'invoice_number' => Yii::t('app', 'Invoice Number'),
			'tax_number' => Yii::t('app', 'Tax Number'),
            'generated_from_estimation' => Yii::t('app', 'Generated From Estimation'),
            'estimation_id' => Yii::t('app', 'Estimation ID'),
            'date_created' => Yii::t('app', 'Date Created'),
            'date_due' => Yii::t('app', 'Date Due'),
            'po_number' => Yii::t('app', 'PO Number'),
            'customer_id' => Yii::t('app', 'Customer'),
            'invoice_status_id' => Yii::t('app', 'Invoice Status'),
            'linked_to_project' => Yii::t('app', 'Linked To Project'),
            'project_id' => Yii::t('app', 'Project'),
            'currency_id' => Yii::t('app', 'Currency'),
            'sub_total' => Yii::t('app', 'Sub Total'),
            'discount_type_id' => Yii::t('app', 'Discount Type'),
            'discount_figure' => Yii::t('app', 'Discount Figure'),
            'discount_amount' => Yii::t('app', 'Discount Amount'),
            'total_tax_amount' => Yii::t('app', 'Total Tax Amount'),
            'grand_total' => Yii::t('app', 'Grand Total'),
            'total_paid' => Yii::t('app', 'Total Paid'),
            'notes' => Yii::t('app', 'Notes'),
            'active' => Yii::t('app', 'Active'),
            'created_by_user_id' => Yii::t('app', 'Created By'),
			'updated_by_user_id' => Yii::t('app', 'Updated By'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
	public function beforeSave($insert) {
		if($this->id == NULL)
		{			
			$this -> added_at = time();
			//$this -> updated_at = time();
			$this -> created_by_user_id = Yii::$app->user->identity->id;
			$this -> updated_by_user_id = Yii::$app->user->identity->id;
			$this -> generated_from_estimation = 0;
			$this -> estimation_id = null;
			//$this -> invoice_status_id = 0;
			$this -> linked_to_project = 0;
			$this -> project_id = null;
			$this -> total_paid = 0;
			$this -> active = 1;
		}
		else
		{
			$this -> updated_at = time();
		}
		
		$this->po_number = Html::encode($this->po_number);
		//$this->notes = Html::encode($this->notes);
		return parent::beforeSave($insert);
	}
	public function getEstimationCode()
    {
    	return $this->hasOne(Estimate::className(), ['id' => 'estimation_id']);
	} 
	public function getCustomer(){
		return $this->hasOne(Customer::ClassName(),['id'=>'customer_id']);
	}
	public function getCurrency(){
		return $this->hasOne(Currency::ClassName(),['id'=>'currency_id']);
	}
	public function getDiscountType(){
		return $this->hasOne(DiscountType::ClassName(),['id'=>'discount_type_id']);
	}
	public function getInvoiceStatus(){
	return $this->hasOne(InvoiceStatus::ClassName(),['id'=>'invoice_status_id']);
	}

	public function afterDelete()
	{
		$file1 = Yii::$app->getBasePath()."\\pdf\\".$this->invoice_number.".pdf";
		if(file_exists($file1))
		{
			unlink($file1);
		}

		/*Delete Invoice Details */
		foreach (InvoiceDetails::find()->where(['invoice_id'=> $this->id])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Payment Details */
		foreach (PaymentDetails::find()->where(['invoice_id'=> $this->id])->all() as $record) 
		{
			$record->delete();
		}

		return parent::afterDelete();
	}
}

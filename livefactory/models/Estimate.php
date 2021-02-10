<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_estimate".
 *
 * @property integer $id
 * @property string $estimation_code
 * @property integer $date_issued
 * @property string $entity_type
 * @property string $po_number
 * @property integer $customer_id
 * @property integer $currency_id
 * @property integer $estimate_status_id
 * @property string $sub_total
 * @property integer $discount_type_id
 * @property string $discount_figure
 * @property string $discount_amount
 * @property string $total_tax_amount
 * @property string $grand_total
 * @property string $notes
 * @property integer $active
 * @property integer $added_at
 * @property integer $updated_at
 */
class Estimate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_estimate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_type', 'customer_id', 'currency_id', 'sub_total', 'discount_type_id', 'discount_figure', 'discount_amount', 'total_tax_amount', 'grand_total', 'estimate_status_id'], 'required'],
            [['date_issued'], 'safe'],
            [['customer_id', 'currency_id', 'discount_type_id', 'active', 'added_at', 'updated_at', 'estimate_status_id'], 'integer'],
            [['sub_total', 'discount_figure', 'discount_amount', 'total_tax_amount', 'grand_total'], 'number'],
            [['po_number', 'notes', 'entity_type', 'estimation_code'], 'string', 'max' => 255],
			[['estimation_code'],'unique'] 
        ];
    }
	public function beforeSave($insert) {
		if($this->id == NULL) 
			$this->added_at = time();
		if($this->id != NULL) 
			$this->updated_at = time();
		if($this->id == NULL){
			$this->created_by = Yii::$app->user->identity->id;
		}

		$this->po_number = Html::encode($this->po_number);
		//$this->notes = Html::encode($this->notes);
		//$this->entity_type = Html::encode($this->entity_type);
		//$this->estimation_code = Html::encode($this->estimation_code);
		return parent::beforeSave($insert);
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'estimation_code' => Yii::t('app', 'Estimation Code'),
            'date_issued' => Yii::t('app', 'Date Issued'),
            'po_number' => Yii::t('app', 'PO Number'),
            'customer_id' => Yii::t('app', 'Customer/Lead'),
			'entity_type' => Yii::t('app', 'Entity Type'),
            'currency_id' => Yii::t('app', 'Currency'),
            'sub_total' => Yii::t('app', 'Sub Total'),
			'discount_type_id' => Yii::t('app', 'Discount Type'),
            'estimate_status_id' => Yii::t('app', 'Estimate Status'),
            'discount_figure' => Yii::t('app', 'Discount Figure'),
            'discount_amount' => Yii::t('app', 'Discount Amount'),
            'total_tax_amount' => Yii::t('app', 'Total Tax Amount'),
            'grand_total' => Yii::t('app', 'Grand Total'),
            'notes' => Yii::t('app', 'Note'),
            'active' => Yii::t('app', 'Active'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
	public function getCustomer(){
		return $this->hasOne(Customer::ClassName(),['id'=>'customer_id']);
	}
	public function getLead(){
		return $this->hasOne(Lead::ClassName(),['id'=>'customer_id']);
	}
	public function getCurrency(){
		return $this->hasOne(Currency::ClassName(),['id'=>'currency_id']);
	}
	public function getDiscountType(){
		return $this->hasOne(DiscountType::ClassName(),['id'=>'discount_type_id']);
	}
	public function getEstimateStatus(){
	return $this->hasOne(EstimateStatus::ClassName(),['id'=>'estimate_status_id']);
	}

	public function afterDelete()
	{
		$file1 = Yii::$app->getBasePath()."\\pdf\\".$this->estimation_code.".pdf";
		if(file_exists($file1))
		{
			unlink($file1);
		}

		/*Delete Estimate Details */
		foreach (EstimateDetails::find()->where(['estimate_id'=> $this->id])->all() as $record) 
		{
			$record->delete();
		}

		return parent::afterDelete();
	}
}

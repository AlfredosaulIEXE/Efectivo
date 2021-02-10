<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_payment_details".
 *
 * @property integer $id
 * @property integer $invoice_id
 * @property string $payment_date
 * @property string $amount
 * @property string $payment_method
 * @property string $notes
 * @property integer $added_at
 * @property integer $updated_at
 */
class PaymentDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_payment_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoice_id', 'payment_date', 'amount', 'payment_method'], 'required'],
            [['invoice_id', 'added_at', 'updated_at'], 'integer'],
            [['payment_date'], 'safe'],
            [['amount'], 'number'],
            [['payment_method'], 'string', 'max' => 25],
            [['notes'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'invoice_id' => Yii::t('app', 'Invoice ID'),
            'payment_date' => Yii::t('app', 'Payment Date'),
            'amount' => Yii::t('app', 'Amount'),
            'payment_method' => Yii::t('app', 'Payment Method'),
            'notes' => Yii::t('app', 'Notes'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

	
	public function getInvoice(){
	return $this->hasOne(Invoice::ClassName(),['id'=>'invoice_id']);
	}

	public function beforeSave($insert) {
		$this->payment_method = Html::encode($this->payment_method);
		$this->notes = Html::encode($this->notes);
		return parent::beforeSave($insert);
	}
}

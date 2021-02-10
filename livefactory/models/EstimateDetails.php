<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_estimate_details".
 *
 * @property integer $id
 * @property integer $estimate_id
 * @property integer $product_id
 * @property integer $product_description
 * @property string $description
 * @property string $rate
 * @property string $quantity
 * @property integer $tax_id
 * @property string $tax_amount
 * @property string $total
 * @property integer $active
 * @property integer $added_at
 * @property integer $updated_at
 */
class EstimateDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_estimate_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['estimate_id', 'product_id', 'product_description', 'description', 'rate', 'quantity', 'tax_id', 'tax_amount', 'total'], 'required'],
            [['estimate_id', 'product_id', 'tax_id', 'active', 'added_at', 'updated_at'], 'integer'],
            [['rate', 'quantity', 'tax_amount', 'total'], 'number'],
            [[ 'product_description','description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'estimate_id' => Yii::t('app', 'Estimate ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'product_description' => Yii::t('app', 'Product Description'),
            'description' => Yii::t('app', 'Description'),
            'rate' => Yii::t('app', 'Rate'),
            'quantity' => Yii::t('app', 'Quantity'),
            'tax_id' => Yii::t('app', 'Tax ID'),
            'tax_amount' => Yii::t('app', 'Tax Amount'),
            'total' => Yii::t('app', 'Total'),
            'active' => Yii::t('app', 'Active'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
	public function getTax()
    {
    	return $this->hasOne(Tax::className(), ['id' => 'tax_id']);
    }
	
	public function beforeSave($insert) {
		$this->product_description = Html::encode($this->product_description);
		$this->description = Html::encode($this->description);
		return parent::beforeSave($insert);
	}
}

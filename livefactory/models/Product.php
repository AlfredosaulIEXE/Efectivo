<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_product".
 *
 * @property integer $id
 * @property string $product_name
 * @property string $product_description
 * @property integer $product_category_id
 * @property double $product_price
 * @property integer $added_at
 * @property integer $updated_at
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_name','product_category_id', 'product_price', 'product_description', 'active'], 'required'],
            [['product_description'], 'string'],
            [['product_category_id', 'added_at', 'updated_at', 'active'], 'integer'],
            [['product_price'], 'number'],
            [['product_name'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_name' => Yii::t('app', 'Product Name'),
			'active' => Yii::t('app', 'Active'),
            'product_description' => Yii::t('app', 'Product Description'),
            'product_category_id' => Yii::t('app', 'Product Category'),
            'product_price' => Yii::t('app', 'Product Price'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
	public function getProductCategory()
    {
    	return $this->hasOne(productCategory::className(), ['id' => 'product_category_id']);
    }
	public function getTax()
    {
    	return $this->hasOne(Tax::className(), ['id' => 'tax_id']);
    }

	public function beforeSave($insert) {
		$this->product_name = Html::encode($this->product_name);
		//$this->product_description = Html::encode($this->product_description);
		return parent::beforeSave($insert);
	}
}

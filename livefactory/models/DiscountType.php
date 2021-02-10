<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_discount_type".
 *
 * @property integer $id
 * @property string $discount_type
 * @property integer $active
 * @property integer $added_at
 * @property integer $updated_at
 */
class DiscountType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_discount_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['discount_type', 'active', 'added_at', 'updated_at'], 'required'],
            [['active', 'added_at', 'updated_at'], 'integer'],
            [['discount_type'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'discount_type' => Yii::t('app', 'Discount Type'),
            'active' => Yii::t('app', 'Active'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

	public function beforeSave($insert) {
		$this->discount_type = Html::encode($this->discount_type);
		return parent::beforeSave($insert);
	}
}

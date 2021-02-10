<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_invoice_status".
 *
 * @property integer $id
 * @property string $status
 * @property string $label
 * @property integer $active
 * @property integer $sort_order
 * @property integer $added_at
 * @property integer $updated_at
 */
class InvoiceStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

	const _NEW = '1';
	const _UNPAID = '2';
	const _PARTIALLYPAID = '3';
	const _PAID = '4';
	const _CANCELLED = '5';

    public static function tableName()
    {
        return 'tbl_invoice_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'label', 'active', 'sort_order', 'added_at', 'updated_at'], 'required'],
            [['active', 'sort_order', 'added_at', 'updated_at'], 'integer'],
            [['status', 'label'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app', 'Status'),
            'label' => Yii::t('app', 'Label'),
            'active' => Yii::t('app', 'Active'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

	public function beforeSave($insert) {
		$this->status = Html::encode($this->status);
		$this->label = Html::encode($this->label);
		return parent::beforeSave($insert);
	}
}

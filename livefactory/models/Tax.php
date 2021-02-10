<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_tax".
 *
 * @property integer $id
 * @property string $name
 * @property string $tax_percentage
 * @property integer $active
 * @property integer $added_at
 * @property integer $updated_at
 */
class Tax extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_tax';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'tax_percentage', 'active', 'sort_order'], 'required'],
            [['tax_percentage'], 'number'],
            [['active', 'added_at', 'updated_at', 'sort_order' ], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'tax_percentage' => Yii::t('app', 'Tax Percentage'),
			'sort_order' => Yii::t('app', 'Sort Order'),
            'active' => Yii::t('app', 'Active'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

	public function beforeSave($insert) {
		$this->name = Html::encode($this->name);
		return parent::beforeSave($insert);
	}
}

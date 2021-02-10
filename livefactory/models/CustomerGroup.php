<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_customer_group".
 *
 * @property integer $id
 * @property string $name
 * @property string $label
 * @property integer $active
 * @property integer $sort_order
 * @property integer $added_at
 * @property integer $updated_at
 */
class CustomerGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_customer_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active', 'sort_order', 'added_at', 'updated_at'], 'integer'],
            [['name', 'label','active', 'sort_order'], 'required'],
            [['name', 'label'], 'string', 'max' => 100]
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
            'label' => Yii::t('app', 'Label'),
            'active' => Yii::t('app', 'Active'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

	public function beforeSave($insert) {
		$this->name=Html::encode($this->name);
		$this->label=Html::encode($this->label);
		return parent::beforeSave($insert);
	}
}

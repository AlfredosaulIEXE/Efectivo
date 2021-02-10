<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_item".
 *
 * @property integer $id
 * @property string $name
 * @property integer $rate
 * @property integer $tax_id
 * @property integer $active
 * @property integer $added_at
 * @property integer $updated_at
 */
class Item extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'rate', 'tax_id', 'active', 'added_at', 'updated_at'], 'required'],
            [['rate', 'tax_id', 'active', 'added_at', 'updated_at'], 'integer'],
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
            'rate' => Yii::t('app', 'Rate'),
            'tax_id' => Yii::t('app', 'Tax ID'),
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

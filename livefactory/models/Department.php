<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_department".
 *
 * @property integer $id
 * @property string $name
 * @property string $label
 * @property integer $active
 * @property string $description
 * @property integer $added_at
 * @property integer $updated_at
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_department';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','active'], 'required'],
            [['active', 'added_at','sort_order', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['name', 'label'], 'string', 'max' => 255]
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
            'description' => Yii::t('app', 'Description'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

	public function beforeSave($insert) {
		$this->name = Html::encode($this->name);
		$this->label = Html::encode($this->label);
		return parent::beforeSave($insert);
	}
}

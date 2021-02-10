<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_ticket_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $label
 * @property integer $active
 * @property string $description
 * @property integer $parent_id
 * @property integer $department_id
 * @property integer $sort_order
 * @property integer $added_at
 * @property integer $updated_at
 */
class TicketCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_ticket_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'label', 'active', 'department_id'], 'required'],
            [['active', 'parent_id', 'department_id', 'sort_order', 'added_at', 'updated_at'], 'integer'],
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
            'parent_id' => Yii::t('app', 'Parent'),
            'department_id' => Yii::t('app', 'Department'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
	public function getDepartment(){
		return $this->hasOne(Department::className(),['id'=>'department_id']);
	}
	public function getSub(){
		return $this->hasOne(TicketCategory::className(),['id'=>'parent_id']);
	}

	public function beforeSave($insert) {
		$this->name = Html::encode($this->name);
		$this->label = Html::encode($this->label);
		return parent::beforeSave($insert);
	}
}

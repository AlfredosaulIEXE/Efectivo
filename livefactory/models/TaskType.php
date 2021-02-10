<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_task_type".
 *
 * @property integer $id
 * @property string $type
 * @property string $label
 * @property integer $active
 * @property integer $added_at
 * @property integer $updated_at
 */
class TaskType extends \yii\db\ActiveRecord
{
	const STATUS_ACTIVE = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_task_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'label', 'active','sort_order'], 'required'],
            [['active','sort_order', 'added_at', 'updated_at'], 'integer'],
            [['type', 'label'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'label' => Yii::t('app', 'Label'),
            'active' => Yii::t('app', 'Active'),
            'added_at' => Yii::t('app', 'Added At'),
			'sort_order' => Yii::t('app', 'Sort Order'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

	public function beforeSave($insert) {
		$this->type = Html::encode($this->type);
		$this->label = Html::encode($this->label);
		return parent::beforeSave($insert);
	}

	public function getTaskTypeIDByName($task_type){
		return static::findOne ( [ 
				'type' => $task_type,
				'active' => self::STATUS_ACTIVE 
		] );	
	}
}

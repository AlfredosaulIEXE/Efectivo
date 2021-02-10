<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_project_status".
 *
 * @property integer $id
 * @property string $status
 * @property string $label
 * @property integer $active
 * @property integer $added_at
 * @property integer $updated_at
 */
class ProjectStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

	const _NEEDSACTION = '1';
	const _INPROCESS = '2';
	const _COMPLETED = '3';
	const _CANCELLED = '4';
	const STATUS_ACTIVE = 1;

    public static function tableName()
    {
        return 'tbl_project_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'label', 'active','sort_order'], 'required'],
            [['active','sort_order', 'added_at', 'updated_at'], 'integer'],
            [['status', 'label'], 'string', 'max' => 255]
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

	public function getProjectStatusIDByName($project_status){
		return static::findOne ( [ 
				'status' => $project_status,
				'active' => self::STATUS_ACTIVE 
		] );
	}
}

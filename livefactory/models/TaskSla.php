<?php

namespace livefactory\models;
use livefactory\models\TaskPriority;
use livefactory\models\TaskType;

use Yii;

/**
 * This is the model class for table "tbl_task_sla".
 *
 * @property integer $id
 * @property integer $task_priority_id
 * @property integer $task_type_id
 * @property integer $start_sla
 * @property integer $end_sla
 */
class TaskSla extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_task_sla';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_priority_id', 'task_type_id', 'start_sla', 'end_sla'], 'required'],
            [['task_priority_id', 'task_type_id', 'start_sla', 'end_sla'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'task_priority_id' => Yii::t('app', 'Task Priority'),
			'task_type_id' => Yii::t('app', 'Task Type'),
            'start_sla' => Yii::t('app', 'Start Sla'),
            'end_sla' => Yii::t('app', 'End Sla'),
        ];
    }

	public function getTaskPriority()
    {
    	return $this->hasOne(TaskPriority::className(), ['id' => 'task_priority_id']);
    }

	public function getTaskType()
    {
    	return $this->hasOne(TaskType::className(), ['id' => 'task_type_id']);
    }
}

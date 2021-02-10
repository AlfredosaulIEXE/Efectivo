<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_task".
 *
 * @property integer $id
 * @property string $task_id
 * @property string $task_name
 * @property string $task_description
 * @property integer $task_type_id
 * @property integer $task_status_id
 * @property integer $task_priority_id
 * @property integer $user_assigned_id
 * @property string $task_progress
 * @property integer $project_id
 * @property integer $parent_id
 * @property string $time_spent
 * @property integer $added_at
 * @property integer $updated_at
 * @property integer $expected_start_datetime
 * @property integer $expected_end_datetime
 * @property integer $actual_start_datetime
 * @property integer $actual_end_datetime
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_name','task_status_id', 'task_priority_id', 'task_type_id', 'user_assigned_id', 'project_id'], 'required'],
            [['task_description'], 'string'],
            [['task_type_id', 'task_status_id', 'task_priority_id', 'user_assigned_id', 'project_id', 'parent_id', 'added_at', 'updated_at','added_by_user_id','last_updated_by_user_id', 'task_progress'], 'integer'],
            [['expected_start_datetime', 'expected_end_datetime', 'actual_start_datetime', 'actual_end_datetime'], 'safe'],
            [['task_id'], 'string', 'max' => 40],
            [['task_name'], 'string', 'max' => 255],
            [['time_spent'], 'string', 'max' => 70],
		//	['expected_start_datetime', 'compare', 'compareAttribute' => 'expected_end_datetime', 'operator' => '<', 'enableClientValidation' => true],
		//	['actual_start_datetime', 'compare', 'compareAttribute' => 'actual_end_datetime', 'operator' => '<', 'enableClientValidation' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'task_id' => Yii::t('app', 'Task ID'),
            'task_name' => Yii::t('app', 'Task Name'),
            'task_description' => Yii::t('app', 'Task Description'),
            'task_type_id' => Yii::t('app', 'Task Type'),
            'task_status_id' => Yii::t('app', 'Task Status'),
            'task_priority_id' => Yii::t('app', 'Task Priority'),
            'user_assigned_id' => Yii::t('app', 'Assigned User'),
            'task_progress' => Yii::t('app', 'Progress'),
            'project_id' => Yii::t('app', 'Project'),
            'parent_id' => Yii::t('app', 'Parent'),
            'time_spent' => Yii::t('app', 'Time Spent'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'expected_start_datetime' => Yii::t('app', 'Expected Start'),
            'expected_end_datetime' => Yii::t('app', 'Expected End'),
            'actual_start_datetime' => Yii::t('app', 'Actual Start'),
            'actual_end_datetime' => Yii::t('app', 'Actual End'),
        ];
    }
	public function beforeSave($insert) {
		if($this->task_type_id == NULL) 
			$this->task_type_id = 0;
		/*if($this->actual_start_datetime == NULL) 
			$this->actual_start_datetime = 0;
		if($this->actual_end_datetime == NULL) 
			$this->actual_end_datetime = 0;
		if($this->task_status_id == 2) 
			$this->task_progress = 100;
		if($this->task_progress == 100) 
			$this->task_status_id = 2;*/
		if($this->id == NULL){
			$this->added_by_user_id = Yii::$app->user->identity->id;
		}

		$this->task_name = Html::encode($this->task_name);
		//$this->task_description = Html::encode($this->task_description);

		return parent::beforeSave($insert);
	}
	public function getUser()

    {

    	return $this->hasOne(User::className(), ['id' => 'user_assigned_id']);

    }
	public function getAddedByUser()

    {

    	return $this->hasOne(User::className(), ['id' => 'added_by_user_id']);

    }
	public function getLastUpdateUser()

    {

    	return $this->hasOne(User::className(), ['id' => 'last_updated_by_user_id']);

    }

	public function getTaskPriority()

    {

    	return $this->hasOne(TaskPriority::className(), ['id' => 'task_priority_id']);

    }

	public function getTaskStatus()

    {

    	return $this->hasOne(TaskStatus::className(), ['id' => 'task_status_id']);

    }

	public function getProject()

    {

    	return $this->hasOne(Project::className(), ['id' => 'project_id']);

    }

	public function getTask()

    {

    	return $this->hasOne(Task::className(), ['id' => 'entity_id']);

    }

	public function getTaskParent()

    {

    	return $this->hasOne(Task::className(), ['id' => 'parent_id']);

    }
	
		/* previous / next button addded by deepak on 14 dec 2015 */
	 public function getNext()
    {
        if(Yii::$app->params['user_role'] =='admin'){
		return Task::find()
             ->andwhere(['>', 'id', $this->id])
			 ->orderBy(['id' => SORT_ASC])
             ->one();	
		}else{
			return Task::find()
             ->andwhere(['>', 'id', $this->id])
			 ->andwhere(['user_assigned_id' => Yii::$app->user->identity->id])
             ->orderBy(['id' => SORT_ASC])
             ->one();
		}
		
		
		
    }
	
	public function getPrev()
    {
		if(Yii::$app->params['user_role'] =='admin'){
        return Task::find()
             ->andwhere(['<', 'id', $this->id])
			 ->orderBy(['id' => SORT_DESC])
             ->one();
		}else{
			return Task::find()
             ->andwhere(['<', 'id', $this->id])
			 ->andwhere(['user_assigned_id' => Yii::$app->user->identity->id])
             ->orderBy(['id' => SORT_DESC])
             ->one();
		}
    }
	
	/* previous / next button addded by deepak on 14 dec 2015 */

	public function afterDelete()
	{
		$file1 = Yii::$app->getBasePath()."\\attachments\\task_".$this->id.".zip";
		if(file_exists($file1))
		{
			unlink($file1);
		}

		/*Delete Attachments */
		foreach (File::find()->where(['entity_id'=> $this->id, 'entity_type' => 'task'])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Notes */
		foreach (Note::find()->where(['entity_id'=> $this->id, 'entity_type' => 'task'])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Sub Tasks */
		foreach (Task::find()->where(['parent_id'=> $this->id])->all() as $record) 
		{
			$record->delete();
		}


		/*Delete Timesheet */
		foreach (TimeEntry::find()->where(['entity_id'=> $this->id, 'entity_type' => 'task'])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Assignment History */
		foreach (AssignmentHistory::find()->where(['entity_id'=> $this->id, 'entity_type' => 'task'])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Activities */
		foreach (History::find()->where(['entity_id'=> $this->id, 'entity_type' => 'task'])->all() as $record) 
		{
			$record->delete();
		}

		return parent::afterDelete();
	}
}

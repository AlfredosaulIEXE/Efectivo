<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_defect".
 *
 * @property integer $id
 * @property string $defect_id
 * @property string $defect_name
 * @property string $defect_description
 * @property string $time_spent
 * @property integer $user_assigned_id
 * @property integer $project_id
 * @property integer $defect_status_id
 * @property integer $defect_priority_id
 * @property integer $expected_start_datetime
 * @property integer $expected_end_datetime
 * @property integer $actual_start_datetime
 * @property integer $actual_end_datetime
 * @property integer $parent_id
 * @property integer $progress
 * @property integer $added_at
 * @property integer $updated_at
 */
class Defect extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_defect';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['defect_name', 'user_assigned_id', 'project_id','user_assigned_id', 'defect_status_id', 'defect_priority_id', 'defect_type_id'], 'required'],
            [['user_assigned_id','defect_type_id', 'project_id', 'defect_status_id', 'defect_priority_id', 'parent_id', 'defect_progress', 'added_at', 'updated_at','added_by_user_id','last_updated_by_user_id'], 'integer'],
            [['expected_start_datetime', 'expected_end_datetime', 'actual_start_datetime', 'actual_end_datetime'], 'safe'],
            [['defect_id'], 'string', 'max' => 40],
            [['defect_name'], 'string', 'max' => 255],
			[['defect_description'], 'string'],
            [['time_spent'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'defect_id' => Yii::t('app', 'Defect ID'),
            'defect_name' => Yii::t('app', 'Defect Title'),
            'defect_description' => Yii::t('app', 'Defect Description'),
            'time_spent' => Yii::t('app', 'Time Spent'),
            'user_assigned_id' => Yii::t('app', 'Assigned User'),
            'project_id' => Yii::t('app', 'Project'),
            'defect_status_id' => Yii::t('app', 'Defect Status'),
			'defect_type_id' => Yii::t('app', 'Defect Type'),
            'defect_priority_id' => Yii::t('app', 'Defect Priority'),
            'expected_start_datetime' => Yii::t('app', 'Expected Start'),
            'expected_end_datetime' => Yii::t('app', 'Expected End'),
            'actual_start_datetime' => Yii::t('app', 'Actual Start'),
            'actual_end_datetime' => Yii::t('app', 'Actual End'),
            'parent_id' => Yii::t('app', 'Parent'),
            'defect_progress' => Yii::t('app', 'Progress'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
	public function beforeSave($insert) {
		/*if($this->defect_type_id == NULL) 
			$this->defect_type_id = 0;
		if($this->defect_status_id == 2) 
			$this->defect_progress = 100;
		if($this->defect_progress == 100) 
			$this->defect_status_id = 2;*/
		if($this->id == NULL){
			$this->added_by_user_id = Yii::$app->user->identity->id;
		}
		$this->defect_name = Html::encode($this->defect_name);
		//$this->defect_description = Html::encode($this->defect_description);
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
	public function getDefectPriority()
    {
    	return $this->hasOne(DefectPriority::className(), ['id' => 'defect_priority_id']);
    }
	public function getDefectStatus()
    {
    	return $this->hasOne(DefectStatus::className(), ['id' => 'defect_status_id']);
    }
	public function getProject()
    {
    	return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }
	public function getDefect()
    {
    	return $this->hasOne(Defect::className(), ['id' => 'entity_id']);
    }
	public function getDefectParent()
    {
    	return $this->hasOne(Defect::className(), ['id' => 'parent_id']);
    }
	
		/* previous / next button addded by deepak on 14 dec 2015 */
	 public function getNext()
    {
        if(Yii::$app->params['user_role'] =='admin'){
		return Defect::find()
             ->andwhere(['>', 'id', $this->id])
			 ->orderBy(['id' => SORT_ASC])
             ->one();	
		}else{
			return Defect::find()
             ->andwhere(['>', 'id', $this->id])
			 ->andwhere(['user_assigned_id' => Yii::$app->user->identity->id])
             ->orderBy(['id' => SORT_ASC])
             ->one();
		}
	}
	
	public function getPrev()
    {
		if(Yii::$app->params['user_role'] =='admin'){
        return Defect::find()
             ->andwhere(['<', 'id', $this->id])
			 ->orderBy(['id' => SORT_DESC])
             ->one();
		}else{
			return Defect::find()
             ->andwhere(['<', 'id', $this->id])
			 ->andwhere(['user_assigned_id' => Yii::$app->user->identity->id])
             ->orderBy(['id' => SORT_DESC])
             ->one();
		}
    }

	public function afterDelete()
	{
		$file1 = Yii::$app->getBasePath()."\\attachments\\defect_".$this->id.".zip";
		if(file_exists($file1))
		{
			unlink($file1);
		}

		/*Delete Attachments */
		foreach (File::find()->where(['entity_id'=> $this->id, 'entity_type' => 'defect'])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Notes */
		foreach (Note::find()->where(['entity_id'=> $this->id, 'entity_type' => 'defect'])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Timesheet */
		foreach (TimeEntry::find()->where(['entity_id'=> $this->id, 'entity_type' => 'defect'])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Assignment History */
		foreach (AssignmentHistory::find()->where(['entity_id'=> $this->id, 'entity_type' => 'defect'])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Activities */
		foreach (History::find()->where(['entity_id'=> $this->id, 'entity_type' => 'defect'])->all() as $record) 
		{
			$record->delete();
		}

		return parent::afterDelete();
	}
	
	/* previous / next button addded by deepak on 14 dec 2015 */
}

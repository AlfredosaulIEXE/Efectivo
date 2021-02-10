<?php

namespace livefactory\models;
use yii\helpers\Html;

use Yii;
use livefactory\models\ProjectUser as ProjectUserModel;
use livefactory\models\User as  UserModel;
use livefactory\models\DefectStatus;
use livefactory\models\TaskStatus;

/**
 * This is the model class for table "tbl_project".
 *
 * @property integer $id
 * @property string $project_name
 * @property string $project_description
 * @property integer $project_type_id
 * @property integer $project_status_id
 * @property integer $project_priority_id
 * @property integer $project_currency_id
 * @property integer $customer_id
 * @property integer $project_owner_id
 * @property string $project_budget
 * @property string $project_progress
 * @property integer $added_at
 * @property integer $updated_at
 * @property integer $expected_start_datetime
 * @property integer $expected_end_datetime
 * @property integer $actual_start_datetime
 * @property integer $actual_end_datetime
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_name','expected_start_datetime', 'expected_end_datetime', 'project_type_id', 'project_currency_id', 'project_status_id', 'project_priority_id', 'customer_id', 'project_owner_id'], 'required'],
            [['project_description', 'project_id'], 'string'],
            [['project_type_id', 'project_status_id', 'project_source_id','project_priority_id', 'project_currency_id', 'customer_id', 'project_owner_id','project_progress', 'added_at', 'updated_at','added_by_user_id','last_updated_by_user_id','project_items'], 'integer'],
			 [['project_budget','project_cost','project_margin'], 'number'],
            [['expected_start_datetime', 'expected_end_datetime', 'actual_start_datetime', 'actual_end_datetime'], 'safe'],
            [['project_name'], 'string', 'max' => 255],
			[['project_description'], 'string'],
			[['project_id'],'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'project_name' => Yii::t('app', 'Project Name'),
            'project_description' => Yii::t('app', 'Project Description'),
            'project_type_id' => Yii::t('app', 'Project Type'),
            'project_status_id' => Yii::t('app', 'Project Status'),
            'project_priority_id' => Yii::t('app', 'Project Priority'),
            'project_currency_id' => Yii::t('app', 'Project Currency'),
			'project_source_id' => Yii::t('app', 'Project Source'),
            'customer_id' => Yii::t('app', 'Customer'),
            'project_owner_id' => Yii::t('app', 'Project Owner'),
			'project_progress' => Yii::t('app', 'Progress'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),

            
			'project_budget' => Yii::t('app', 'Project Budget'),
			'project_cost' => Yii::t('app', 'Project Cost (Execution,Resources etc.)'),
			'project_margin' => Yii::t('app', 'Margin (Gross Margin/Profit Margin)'),
		    'project_items' => Yii::t('app', 'Number of Items'),
            'expected_start_datetime' => Yii::t('app', 'Expected Start Date'),
            'expected_end_datetime' => Yii::t('app', 'Expected Completion Date'),
            'actual_start_datetime' => Yii::t('app', 'Actual Start Date'),
            'actual_end_datetime' => Yii::t('app', 'Actual Completion Date'),
			
			/*
			'project_budget' => Yii::t('app', 'Public Sale Price'),
			'project_cost' => Yii::t('app', 'Cost Price'),
			'project_margin' => Yii::t('app', 'Sales Margin'),
		    'project_items' => Yii::t('app', 'Number of People'),
            'expected_start_datetime' => Yii::t('app', 'Service Request Date'),
            'expected_end_datetime' => Yii::t('app', 'Budget Expiration Date'),
            'actual_start_datetime' => Yii::t('app', 'Service Start Date'),
            'actual_end_datetime' => Yii::t('app', 'End of Service Date'),
			*/
        ];
    }
	public function beforeSave($insert) {
		/*if($this->project_type_id == NULL) 
			$this->project_type_id = 0;
		if($this->expected_end_datetime == NULL) 
			$this->expected_end_datetime = 0;
		if($this->project_currency_id == NULL) 
			$this->project_currency_id = 0;
		if($this->project_status_id == 3) 
			$this->project_progress = 100;
		if($this->project_progress == 100) 
			$this->project_status_id = 3;*/

		$this->project_name = Html::encode($this->project_name);
		//$this->project_description = Html::encode($this->project_description);
		$this->project_budget = Html::encode($this->project_budget);

		if($this->id == NULL){
			$this->added_by_user_id = Yii::$app->user->identity->id;
		}
		return parent::beforeSave($insert);
	}
	public function getUser()

    {

    	return $this->hasOne(User::className(), ['id' => 'project_owner_id']);

    }

	public function getUser1()

    {

    	return $this->hasOne(User::className(), ['id' => 'project_owner_id']);

    }
	public function getAddedByUser()

    {

    	return $this->hasOne(User::className(), ['id' => 'added_by_user_id']);

    }
	public function getLastUpdateUser()

    {

    	return $this->hasOne(User::className(), ['id' => 'last_updated_by_user_id']);

    }
	public function getStatus()

    {

    	return $this->hasOne(ProjectStatus::className(), ['id' => 'project_status_id']);

    }
	public function getProjectStatus()

    {

    	return $this->hasOne(ProjectStatus::className(), ['id' => 'project_status_id']);

    }
	public function getProjectPriority()

    {

    	return $this->hasOne(ProjectPriority::className(), ['id' => 'project_priority_id']);

    }

	public function getProjectSource()

    {

    	return $this->hasOne(ProjectSource::className(), ['id' => 'project_psource_id']);

    }

	public function getCustomer()

    {

    	return $this->hasOne(Customer::className(), ['id' => 'customer_id']);

    }

	public function getType()

    {

    	return $this->hasOne(ProjectType::className(), ['id' => 'project_type_id']);

    }

	public function getOpentask()

	{

		return $this->hasMany(Task::className(), ['project_id' => 'id'])->andWhere('task_status_id='.TaskStatus::_NEEDSACTION.' OR task_status_id='.TaskStatus::_INPROCESS)->count();

	}
	public function getOpendefect()

	{

		return $this->hasMany(Defect::className(), ['project_id' => 'id'])->andWhere('defect_status_id='.DefectStatus::_NEEDSACTION.' OR defect_status_id='.DefectStatus::_INPROCESS)->count();

	}

	public function getUsers()

	{

		return $this->hasMany(ProjectUser::className(), ['project_id' => 'id'])->count();

	}

	public static  function getProjectUsers($entity_id)

	{

		$dataProvider = ProjectUserModel::find ()->where ( [

				'project_id' => $entity_id 

		] )->asArray()->all();

		

		return $dataProvider;

	}
	public static  function getUserName($entity_id)

	{

		$dataProvider = UserModel::find ()->where ( [

				'id' => $entity_id 

		] )->asArray()->one();

		

		return $dataProvider['first_name']." ".$dataProvider['last_name']." (".$dataProvider['username'].")";

	}
	public static  function getUserDetail($entity_id)

	{

		$dataProvider = UserModel::find ()->where ( [

				'id' => $entity_id 

		] )->one();

		

		return $dataProvider;

	}
	public  function getTax()

	{

		return $this->hasOne(Tax::className(),['id'=>'tax_id']);

	}
	
	/* previous / next button addded by deepak on 14 dec 2015 */
	 public function getNext()
    {
		if(Yii::$app->user->identity->userType->type=="Customer")
		{
			return Project::find()
				 ->where(['>', 'id', $this->id])
				 ->andwhere(['=', 'customer_id', Yii::$app->user->identity->entity_id])
				 ->orderBy(['id' => SORT_ASC])
				 ->one();
		}
		else
		{
			return Project::find()
				 ->where(['>', 'id', $this->id])
				 ->orderBy(['id' => SORT_ASC])
				 ->one();
		}
    }
	
	public function getPrev()
    {
		if(Yii::$app->user->identity->userType->type=="Customer")
		{
			return Project::find()
				 ->where(['<', 'id', $this->id])
				 ->andwhere(['=', 'customer_id', Yii::$app->user->identity->entity_id])
				 ->orderBy(['id' => SORT_DESC])
				 ->one();
		}
		else
		{
			return Project::find()
				 ->where(['<', 'id', $this->id])
				 ->orderBy(['id' => SORT_DESC])
				 ->one();
		}
    }
	/* previous / next button addded by deepak on 14 dec 2015 */

	public function afterDelete()
	{
		$file1 = Yii::$app->getBasePath()."\\attachments\\project_".$this->id.".zip";
		if(file_exists($file1))
		{
			unlink($file1);
		}

		/*Delete Attachments */
		foreach (File::find()->where(['entity_id'=> $this->id, 'entity_type' => 'project'])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Notes */
		foreach (Note::find()->where(['entity_id'=> $this->id, 'entity_type' => 'project'])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Assignment History */
		foreach (AssignmentHistory::find()->where(['entity_id'=> $this->id, 'entity_type' => 'project'])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Tasks */
		foreach (Task::find()->where(['project_id'=> $this->id])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Defects */
		foreach (Defect::find()->where(['project_id'=> $this->id])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Project Users */
		foreach (ProjectUser::find()->where(['project_id'=> $this->id])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Activities */
		foreach (History::find()->where(['entity_id'=> $this->id, 'entity_type' => 'project'])->all() as $record) 
		{
			$record->delete();
		}

		return parent::afterDelete();
	}
}

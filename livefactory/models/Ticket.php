<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_ticket".
 *
 * @property integer $id
 * @property string $ticket_title
 * @property string $ticket_description
 * @property integer $ticket_type_id
 * @property integer $ticket_priority_id
 * @property integer $ticket_impact_id
 * @property integer $queue_id
 * @property integer $due_date
 * @property integer $assigned_user_id
 * @property integer $referenced_ticket_id
 * @property integer $ticket_status_id
 * @property string $escalated_flag
 * @property integer $added_at
 * @property integer $updated_at
 * @property integer $created_by
 */
class Ticket extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_ticket';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['ticket_title', 'ticket_customer_id','ticket_priority_id', 'ticket_category_id_1', 'queue_id', 'ticket_impact_id', 'ticket_status_id', 'department_id'], 'required'],
            [['ticket_description','ticket_id','time_spent'], 'string'],
            [['ticket_type_id','ticket_customer_id','ticket_category_id_1','ticket_category_id_2','department_id', 'ticket_priority_id', 'ticket_impact_id', 'queue_id', 'user_assigned_id','referenced_ticket_id', 'ticket_status_id', 'added_by_user_id','last_updated_by_user_id','added_at', 'updated_at', 'created_by'], 'integer'],
			[['due_date'], 'safe'],
            [['ticket_title', 'escalated_flag'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ticket_title' => Yii::t('app', 'Ticket Subject'),
			'ticket_customer_id'=>Yii::t('app', 'Customer'),
            'department_id'=>Yii::t('app','Department'),
			'due_date'=>Yii::t('app', 'Due Date'),
            'ticket_description' => Yii::t('app', 'Description'),
            'ticket_type_id' => Yii::t('app', 'Ticket Type'),
            'ticket_priority_id' => Yii::t('app', 'Ticket Priority'),
			'ticket_category_id_1' => Yii::t('app', 'Ticket Category 1'),
            'ticket_category_id_2' => Yii::t('app', 'Ticket Category 2'),            
            'ticket_impact_id' => Yii::t('app', 'Ticket Impact'),
            'queue_id' => Yii::t('app', 'Queue'),
            'user_assigned_id' => Yii::t('app', 'Assigned User'),
            'referenced_ticket_id' => Yii::t('app', 'Referenced Ticket'),
            'ticket_status_id' => Yii::t('app', 'Ticket Status'),
            'escalated_flag' => Yii::t('app', 'Escalated Flag'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

	public function beforeSave($insert) {
		if($this->due_date == NULL) 
			$this->due_date = 0;
		if($this->id == NULL){
			if($this->added_by_user_id == NULL || $this->added_by_user_id == '')
				$this->added_by_user_id = Yii::$app->user->identity->id;
		}
		
		$this->ticket_title = Html::encode($this->ticket_title);
		//$this->ticket_description = Html::encode($this->ticket_description);

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

	public function getTicketPriority()
    {

    	return $this->hasOne(TicketPriority::className(), ['id' => 'ticket_priority_id']);

    }

	public function getTicketStatus()
    {

    	return $this->hasOne(TicketStatus::className(), ['id' => 'ticket_status_id']);

    }

	public function getTicketImpact()
    {

    	return $this->hasOne(TicketImpact::className(), ['id' => 'ticket_impact_id']);

    }

	public function getTicketType()
    {

    	return $this->hasOne(TicketType::className(), ['id' => 'ticket_type_id']);

    }

	public function getTicketCategory()
    {

    	return $this->hasOne(TicketCategory::className(), ['id' => 'ticket_category_id_1']);

    }
	
	public function getQueueName()
    {

    	return $this->hasOne(Queue::className(), ['id' => 'queue_id']);

    }

	public function getCustomer(){
		return $this->hasOne(Customer::ClassName(),['id'=>'ticket_customer_id']);
	}
	
	/* previous / next button addded by deepak on 17 jun 2017 */
	 public function getNext()
    {
        if(Yii::$app->params['user_role'] =='admin'){
		return Ticket::find()
             ->andwhere(['>', 'id', $this->id])
			 ->orderBy(['id' => SORT_ASC])
             ->one();	
		}else{
			if(Yii::$app->user->identity->userType->type=="Customer")
			{
				return Ticket::find()
				 ->andwhere(['>', 'id', $this->id])
				 ->andwhere(['ticket_customer_id' => Yii::$app->user->identity->entity_id])
				 ->orderBy(['id' => SORT_ASC])
				 ->one();			
			}
			else
			{
				return Ticket::find()
				 ->andwhere(['>', 'id', $this->id])
				 ->andwhere(['user_assigned_id' => Yii::$app->user->identity->id])
				 ->orderBy(['id' => SORT_ASC])
				 ->one();
			}
		}
    }
	
	public function getPrev()
    {
		if(Yii::$app->params['user_role'] =='admin'){
        return Ticket::find()
             ->andwhere(['<', 'id', $this->id])
			 ->orderBy(['id' => SORT_DESC])
             ->one();
		}else{
			if(Yii::$app->user->identity->userType->type=="Customer")
			{
				return Ticket::find()
				 ->andwhere(['<', 'id', $this->id])
				 ->andwhere(['ticket_customer_id' => Yii::$app->user->identity->entity_id])
				 ->orderBy(['id' => SORT_DESC])
				 ->one();
			}
			else
			{
				return Ticket::find()
				 ->andwhere(['<', 'id', $this->id])
				 ->andwhere(['user_assigned_id' => Yii::$app->user->identity->id])
				 ->orderBy(['id' => SORT_DESC])
				 ->one();
			}
		}
    }
	
	/* previous / next button addded by deepak on 17 jun 2017 */

	public function afterDelete()
	{
		$file1 = Yii::$app->getBasePath()."\\attachments\\ticket_".$this->id.".zip";
		if(file_exists($file1))
		{
			unlink($file1);
		}

		/*Delete Attachments */
		foreach (File::find()->where(['entity_id'=> $this->id, 'entity_type' => 'ticket'])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Notes */
		foreach (Note::find()->where(['entity_id'=> $this->id, 'entity_type' => 'ticket'])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Timesheet */
		foreach (TimeEntry::find()->where(['entity_id'=> $this->id, 'entity_type' => 'ticket'])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Assignment History */
		foreach (AssignmentHistory::find()->where(['entity_id'=> $this->id, 'entity_type' => 'ticket'])->all() as $record) 
		{
			$record->delete();
		}

		/*Delete Activities */
		foreach (History::find()->where(['entity_id'=> $this->id, 'entity_type' => 'ticket'])->all() as $record) 
		{
			$record->delete();
		}
		
		/*Delete Resolutions */
		foreach (ResolutionReference::find()->where(['ticket_id'=> $this->id])->all() as $record) 
		{
			$record->delete();
		}

		return parent::afterDelete();
	}
}

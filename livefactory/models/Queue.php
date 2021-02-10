<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_queue".
 *
 * @property integer $id
 * @property string $queue_title
 * @property integer $queue_supervisor_user_id
 * @property integer $queue_owner_user_id
 * @property string $active
 */
class Queue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_queue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['queue_title','queue_supervisor_user_id','active', 'department_id'], 'required'],
            [['queue_supervisor_user_id','department_id'], 'integer'],
            [['queue_title', 'active'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'queue_title' => Yii::t('app', 'Queue Title'),
			'department_id' => Yii::t('app', 'Department'),
            'queue_supervisor_user_id' => Yii::t('app', 'Queue Supervisor'),
            'active' => Yii::t('app', 'Active'),
        ];
    }
	public function getQueueSupervisorUser()
    {
    	return $this->hasOne(User::className(), ['id' => 'queue_supervisor_user_id']);
    }
	public function getDepartment(){
		return $this->hasOne(Department::className(),['id'=>'department_id']);
	}

	public function beforeSave($insert) {
		$this->queue_title = Html::encode($this->queue_title);
		return parent::beforeSave($insert);
	}

	public function afterDelete()
	{
		/*Delete Tickets */
		foreach (Ticket::find()->where(['queue_id'=> $this->id])->all() as $record) 
		{
			$record->delete();
		}

		return parent::afterDelete();
	}
}
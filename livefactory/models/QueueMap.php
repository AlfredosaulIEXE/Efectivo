<?php

namespace livefactory\models;

use Yii;

/**
 * This is the model class for table "tbl_queue_map".
 *
 * @property integer $id
 * @property integer $department_id
 * @property integer $ticket_category_id_2
 * @property integer $ticket_category_id_2_id
 * @property integer $queue_id
 */
class QueueMap extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_queue_map';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['department_id', 'queue_id'], 'required'],
            [['department_id', 'ticket_category_id_2', 'ticket_category_id_2_id', 'queue_id'], 'integer'],
			[['department_id', 'ticket_category_id_2','ticket_category_id_2_id', 'queue_id'], 'unique', 'targetAttribute' => ['department_id', 'ticket_category_id_2','ticket_category_id_2_id', 'queue_id']],
			[['department_id', 'queue_id'], 'unique', 'targetAttribute' => ['department_id','queue_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'department_id' => Yii::t('app', 'Department'),
            'ticket_category_id_2' => Yii::t('app', 'Category'),
            'ticket_category_id_2_id' => Yii::t('app', 'Sub Category'),
            'queue_id' => Yii::t('app', 'Queue'),
        ];
    }
	public function getDepartment(){
		return $this->hasOne(Department::className(),['id'=>'department_id']);	
	}
	public function getTicketCategory1(){
		return $this->hasOne(TicketCategory::className(),['id'=>'ticket_category_id_2']);	
	}
	public function getTicketCategory2(){
		return $this->hasOne(TicketCategory::className(),['id'=>'ticket_category_id_2_id']);	
	}
	public function getQueue(){
		return $this->hasOne(Queue::className(),['id'=>'queue_id']);	
	}
}

<?php

namespace livefactory\models;
use livefactory\models\TimeEntry;
use Yii;
use yii\filters\VerbFilter;
use yii\db\Query;
class TimesheetModel extends \yii\db\ActiveRecord
{
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '';
    }
	public static function  timeEntryAdd($notes,$entry_type,$start,$end,$entity_type){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$addTime= new TimeEntry();
		$addTime->entity_id=$_REQUEST['id'] ;
		$addTime->notes=$notes;
		$addTime->entity_type=$entity_type;
		$addTime->entry_type=$entry_type;
		$addTime->approved=$entry_type=='MANUAL'?'0':'1';
		$addTime->start_time=strtotime($start);
		$addTime->end_time=strtotime($end);
		$addTime->user_id=Yii::$app->user->identity->id;
		$addTime->added_at=time();
		$addTime->save();	
	}
	public static  function timeEntryEdit($notes,$id,$start,$end){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$editTime= TimeEntry::find()->where(['id' => $id])->one();
		$editTime->notes=$notes;
		$editTime->start_time=strtotime($start);
		$editTime->end_time=strtotime($end);
		$editTime->modified_at=time();
		$editTime->update();	
	}
	public static  function timeEntryApproved($id){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		if(!empty($_REQUEST['approved']) && $_REQUEST['approved']=='Yes'){
			$app='1';
		}else if(!empty($_REQUEST['approved']) && $_REQUEST['approved']=='No'){
			$app='0';
		}else{
			$app='-1';
		}
		$editTime= TimeEntry::find()->where(['id' => $id])->one();
		$editTime->approved=$app;
		$editTime->modified_at=time();
		$editTime->update();	
	}
}

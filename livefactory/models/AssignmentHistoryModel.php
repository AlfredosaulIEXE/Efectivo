<?php

namespace livefactory\models;
use livefactory\models\AssignmentHistory;
use Yii;
use yii\filters\VerbFilter;
use yii\db\Query;
class AssignmentHistoryModel extends \yii\db\ActiveRecord
{
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '';
    }
    public static function assignHistoryInsert($entity_type,$entity_id,$to_user,$notes, $user_id = 0){
		$addAssHistory = new AssignmentHistory;
		$addAssHistory->entity_id=$entity_id;
		$addAssHistory->entity_type=$entity_type;
		$addAssHistory->notes=$notes;
		$addAssHistory->to_user_id=$to_user;//$model->assigned_user_id;
		if($user_id == 0)
			$addAssHistory->user_id=Yii::$app->user->identity->id;
		else
			$addAssHistory->user_id=$user_id;
		$addAssHistory->added_at=strtotime(date('Y-m-d H:i:s'));
		$addAssHistory->save();	
		
	}
	public static function assignHistoryChange($entity_type,$entity_id,$to_user,$from_user,$notes,$to_date){
		$addAssHistory = new AssignmentHistory;
		$addAssHistory->entity_id=$entity_id;
		$addAssHistory->entity_type=$entity_type;
		$addAssHistory->from_user_id=$from_user;
		$addAssHistory->to_user_id=$to_user;
		$addAssHistory->notes=$notes;
		//$addAssHistory->from=date('Y-m-d H:i:s');
		//$addAssHistory->to=date('Y-m-d H:i:s',strtotime($to_date));
		$addAssHistory->user_id=Yii::$app->user->identity->id;
		$addAssHistory->added_at=strtotime(date('Y-m-d H:i:s'));
		$addAssHistory->save();	
	}
}

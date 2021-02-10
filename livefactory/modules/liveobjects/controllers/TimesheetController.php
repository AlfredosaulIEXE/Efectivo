<?php
namespace livefactory\modules\liveobjects\controllers;
use livefactory\models\TimeEntry;
use livefactory\models\TimeDiffModel;
use Yii;

use livefactory\controllers\Controller;

use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;
class TimesheetController extends Controller {
	
	public function getTimeDiff($start,$end){
		$hours=$day=$minutes=0;
		$timing=explode(',',TimeDiffModel::dateDiff($end,$start));
		foreach($timing as $value){
			if(strpos($value,'day') !== false){
				$day=trim(str_replace('day','',$value));
			}
			if(strpos($value,'hour') !== false){
				$hours=trim(str_replace('hours','',$value));
			}
			if(strpos($value,'hour') !== false){
				$hours=trim(str_replace('hours','',$value));
			}
			if(strpos($value,'minutes') !== false){
				$minutes=trim(str_replace('minutes','',$value));
			}
			if(strpos($value,'minute') !== false){
				$minutes=trim(str_replace('minute','',$value));
			}
			if(strpos($value,'minute') !== false){
				$minutes=trim(str_replace('minutes','',$value));
			}
		}
		$hours = ($day*24)+$hours;
		return $hours.".".$minutes;
	}
	public function actionAjax($id,$type){
		$start_time=!empty($_REQUEST['start_time'])?$_REQUEST['start_time']:'';
		$eid=!empty($_REQUEST['eid'])?$_REQUEST['eid']:'';
		if($eid){
			$Model = TimeEntry::find()->where("id != $eid and entity_id=$id and entity_type='$type' and start_time<='$start_time' and end_time >='$start_time'")->one();
		}else{
		$Model = TimeEntry::find()->where("entity_id=$id and entity_type='$type' and start_time<='$start_time' and end_time >='$start_time'")->one();	
		}
		echo  $Model->id;
	}
	public function actionAjaxTimeDateValidation(){
		/*
		$start_time=$_REQUEST['start_time'];
		$end_time=$_REQUEST['end_time'];
		if($end_time){
			list($hours,$min)=explode('.',$this->getTimeDiff($start_time,$end_time));
			$error='';
			if(intval($hours) > 23){
				$error='yes';	
			}else{
				$error='no';	
			}
		}
		echo $error;
		*/
		echo 'no';
	}
}
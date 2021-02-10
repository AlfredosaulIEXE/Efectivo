<?php

namespace livefactory\models;
use livefactory\models\DefaultValue;
use Yii;
use yii\filters\VerbFilter;
use yii\db\Query;
class DefaultValueModule extends \yii\db\ActiveRecord
{
	public static function upsertDefault($entity_type){
		if(!empty($_GET['del_id'])){
			$defaultValueObj = DefaultValue::find()->where("entity_type='$entity_type' and entity_id=".$_GET['del_id'])->one();
			if(!is_null($defaultValueObj)){
				$defaultValueObj->delete();
			}
		 header("location:index.php?r=".$_GET['r']);
		}
		if(!empty($_GET['id'])){
			$defaultValueObj = DefaultValue::find()->where("entity_type='$entity_type'")->one();
			if(!is_null($defaultValueObj)){
				$defaultValueObj->entity_id = $_GET['id'];
				$defaultValueObj->save();
			}else{
				$defaultValueObj = new DefaultValue();
				$defaultValueObj->entity_type = $entity_type;
				$defaultValueObj->entity_id = $_GET['id'];
				$defaultValueObj->save();
			}
			 header("location:index.php?r=".$_GET['r']);
		}
	}
	public static function checkDefaultValue($entity_type,$entity_id){
		$connection = \Yii::$app->db;
		$sql="select id from tbl_default_value where entity_type='$entity_type' and entity_id='$entity_id'";
		$command=$connection->createCommand($sql);
		$dataReader=$command->queryAll();	
		return count($dataReader);
	}
	public static function getDefaultValueId($entity_type){
		$connection = \Yii::$app->db;
		$sql="select entity_id from tbl_default_value where entity_type='$entity_type'";
		$command=$connection->createCommand($sql);
		$Obj = $command->queryOne();
		return $Obj['entity_id'];
	}
	
}

<?php

namespace livefactory\models;
use livefactory\models\File;
use Yii;
use yii\filters\VerbFilter;
use yii\db\Query;
class FileModel extends \yii\db\ActiveRecord
{
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '';
    }
    public static function bulkFileInsert($entity_id, $entity_type) {
		for($i=0;$i<count($_FILES['attach']);$i++){
			$type=explode('/',$_FILES['attach']['type'][$i]);
			//&& in_array($type[0], $attachType)
			if($_FILES['attach']['name'][$i] ){
				$filetitle=$_REQUEST['filetitle'][$i]?$_REQUEST['filetitle'][$i]:$_FILES['attach']['name'][$i];
				$filename= $_FILES['attach']['name'][$i];
				$filetype= $_FILES['attach']['type'][$i];
				$addFile= new File();
				$addFile->entity_id=$entity_id;
				$addFile->entity_type=$entity_type;
				$addFile->file_title=$filetitle;
				$addFile->file_name=$filename;
				$addFile->file_path='livecrm/attachments';
				$addFile->file_type=$filetype;
				$addFile->added_by_user_id =Yii::$app->user->identity->id;
				$addFile->added_at=time();
				$addFile->save();
				$aid=$addFile->id;
					move_uploaded_file($_FILES['attach']['tmp_name'][$i],"../attachments/$aid".strrchr($_FILES['attach']['name'][$i], "."));
				}
		}
	}
	public static function bulkFileInsertProject($entity_id, $entity_type) {
		for($i=0;$i<count($_FILES['attach']);$i++){
			$type=explode('/',$_FILES['attach']['type'][$i]);
			//&& in_array($type[0], $attachType)
			if($_FILES['attach']['name'][$i] ){
				$filetitle=$_REQUEST['filetitle'][$i]?$_REQUEST['filetitle'][$i]:$_FILES['attach']['name'][$i];
				$filename= $_FILES['attach']['name'][$i];
				$filetype= $_FILES['attach']['type'][$i];
				$addFile= new File();
				$addFile->entity_id=$entity_id;
				$addFile->entity_type=$entity_type;
				$addFile->file_title=$filetitle;
				$addFile->file_name=$filename;
				$addFile->file_path='../../attachments/project'.$entity_id;
				$addFile->file_type=$filetype;
				$addFile->added_by_user_id =Yii::$app->user->identity->id;
				$addFile->added_at=time();
				$addFile->save();
				$aid=$addFile->id;
					move_uploaded_file($_FILES['attach']['tmp_name'][$i],"../attachments/project".$entity_id."/$aid".strrchr($_FILES['attach']['name'][$i], "."));
				}
		}
	}
	public static function fileInsert($entity_id, $entity_type) {
		/* Check for invalid file extensions - if found than dont save the information */
		$file_extension = pathinfo($_FILES['attach']['name'], PATHINFO_EXTENSION);
		/*if (in_array(strtoupper($file_extension), Yii::$app->params['invalid_ext']))
		{
			unlink($_FILES['attach']['tmp_name']);
			return 0;
		}*/

		foreach(Yii::$app->params['invalid_ext'] as $blocked)
		{
			if(strstr(strtoupper($file_extension), $blocked))
			{
				unlink($_FILES['attach']['tmp_name']);
				return 0;
			}
		}

		if (intval(Yii::$app->params['FILE_SIZE']) > 0 && filesize($_FILES['attach']['tmp_name']) > intval(Yii::$app->params['FILE_SIZE'])*1024*1024)
		{
			unlink($_FILES['attach']['tmp_name']);
			return -1;
		}

		$filetitle=$_REQUEST['filetitle']?$_REQUEST['filetitle']:$_FILES['attach']['name'];
		if($_FILES['attach']['tmp_name']){
		    var_dump('muero aqui');exit;
		$addFile= new File();
		$addFile->entity_id=$entity_id;
		$addFile->entity_type=$entity_type;
		$addFile->file_title=$filetitle;
		$addFile->file_name=$_FILES['attach']['name'];
		$addFile->file_path='livecrm/attachments';
		$addFile->file_type=$_FILES['attach']['type'];
		$addFile->added_by_user_id =Yii::$app->user->identity->id;
		$addFile->added_at=time();
		$addFile->save();
		$aid=$addFile->id;
		move_uploaded_file($_FILES['attach']['tmp_name'],"../attachments/$aid.".$file_extension);
		}
		return $aid;
	}
	public static function fileInsertProject($entity_id, $entity_type) {
		$filetitle=$_REQUEST['filetitle']?$_REQUEST['filetitle']:$_FILES['attach']['name'];
		if($_FILES['attach']['tmp_name']){
		$addFile= new File();
		$addFile->entity_id=$entity_id;
		$addFile->entity_type=$entity_type;
		$addFile->file_title=$filetitle;
		$addFile->file_name=$_FILES['attach']['name'];
		$addFile->file_path='../../attachments/project'.$entity_id;
		$addFile->file_type=$_FILES['attach']['type'];
		$addFile->added_by_user_id =Yii::$app->user->identity->id;
		$addFile->added_at=time();
		$addFile->save();
		$aid=$addFile->id;
		move_uploaded_file($_FILES['attach']['tmp_name'],"../attachments/project".$entity_id."/$aid".strrchr($_FILES['attach']['name'], "."));
		}
		return $aid;
	}
	public static function fileEdit(){
		$filetitle=$_REQUEST['filetitle']?$_REQUEST['filetitle']:$_FILES['attach']['name'];
			$addFile=  File::find()->where(['id' =>$_REQUEST['att_id']])->one();
			$addFile->file_title=$filetitle;
			
			if($_FILES['attach']['name']){
				$addFile->file_name=$_FILES['attach']['name'];
				$addFile->file_type=$_FILES['attach']['type'];
			}
			$addFile->updated_at=time();
			$addFile->update();
			if($_FILES['attach']['name']){
			$aid=$_REQUEST['att_id'];
				move_uploaded_file($_FILES['attach']['tmp_name'],"../attachments/$aid".strrchr($_FILES['attach']['name'], "."));
			}
			return $aid;
	}
	public static function fileEditProject(){
		$filetitle=$_REQUEST['filetitle']?$_REQUEST['filetitle']:$_FILES['attach']['name'];
			$addFile=  File::find()->where(['id' =>$_REQUEST['att_id']])->one();
			$addFile->file_title=$filetitle;
			
			if($_FILES['attach']['name']){
				$addFile->file_name=$_FILES['attach']['name'];
				$addFile->file_type=$_FILES['attach']['type'];
			}
			$addFile->updated_at=time();
			$addFile->update();
			if($_FILES['attach']['name']){
			$aid=$_REQUEST['att_id'];
				move_uploaded_file($_FILES['attach']['tmp_name'],"../attachments/project".$_GET['id']."/$aid".strrchr($_FILES['attach']['name'], "."));
			}
			return $aid;
	}
	public static function getAttachmentCount($entity_type,$entity_id){
        $entity_type = '%'.$entity_type.'%';
		return 	File::find()->where("entity_type LIKE '$entity_type' and entity_id=$entity_id")->count();
	}
	public static  function create_zip($files = array(),$destination = '',$overwrite = false) {
        return false;
			//if the zip file already exists and overwrite is false, return false
			if(file_exists($destination) && !$overwrite) { return false; }
			//vars
			$valid_files = array();
			//if files were passed in...
			if(is_array($files)) {
				//cycle through each file
				foreach($files as $file) {
					//make sure the file exists
					if(file_exists($file)) {
						$valid_files[] = $file;
					}
				}
			}
			//if we have good files...
			if(count($valid_files)) {
				//create the archive
				$zip = new \ZipArchive();
				if($zip->open($destination,$overwrite ? \ZipArchive::OVERWRITE : \ZipArchive::CREATE) !== true) {
					return false;
				}
				//add the files
				foreach($valid_files as $file) {
					$zip->addFile($file,$file);
				}
				//debug
				//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
				
				//close the zip -- done!
				$zip->close();
				
				//check to make sure the file exists
				return file_exists($destination);
			}
			else
			{
				return false;
			}
		}
	public static function getAttachmentFiles($entity_type,$entity_id){
		return 	File::find()->where("entity_type='$entity_type' and entity_id='$entity_id'")->asArray()->all();
	}
}
